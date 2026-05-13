const fs = require('fs-extra');
const path = require('path');
const postcss = require('postcss');
const cssCustomMedia = require('postcss-custom-media');
const postcssGlobalData = require('@csstools/postcss-global-data');
const parser = require('postcss-comment');
const postcssExtendRule = require('postcss-extend-rule');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const babel = require('@babel/core');
const esbuild = require('esbuild');
const watch = require('node-watch');
const { optimize } = require('svgo');

const src = 'assets/';
const themeName = process.env.WP_THEME_NAME || 'starterkit-lonsdale-2026';
const dist = `web/wp-content/themes/${themeName}/`;
const fromCss = "app.css";
const toCss = "styles.css";
const bundlesDir = "bundles";
const bundleSourceFile = "bundles.css";
const bundleEntries = ["components", "cards", "strates"];
// modules/ CSS is merged into components — no separate modules.css output
const bundleAliases = { "modules": "components" };
let date = new Date();
let version = `${date.getMonth()}${date.getDay()}${date.getHours()}${date.getMinutes()}`;
let hasError = false;
let array = [];
let bundleImports = bundleEntries.reduce((acc, entry) => {
    acc[entry] = [];
    return acc;
}, {});
let moduleMapJSON = "{}";
const isBundleSourceFile = (relPosixPath = "") => relPosixPath === `assets/${bundleSourceFile}`;
const createEmptyBundleImports = () => bundleEntries.reduce((acc, entry) => {
    acc[entry] = [];
    return acc;
}, {});
const hasBundleChanged = (prev = [], next = []) =>
    prev.length !== next.length || prev.some((value, index) => value !== next[index]);
const stripCssComments = (cssSource = "") => cssSource
    .replace(/\/\*[\s\S]*?\*\//g, "")
    .replace(/^\s*\/\/.*$/gm, "");
const extractImports = (cssSource = "") => {
    const imports = [];
    const cleanSource = stripCssComments(cssSource);
    // Only accept explicit @import directives at line start.
    cleanSource.replace(/^\s*@import\s+["']([0-9a-z._\/-]+)["']\s*;?/igm, (match, file) => imports.push(file));
    return [...new Set(imports)];
};
const postcssProcessor = postcss(
    [
        cssnano,
        postcssExtendRule,
        postcssGlobalData({ files: [`${src}styles/customMedias.css`] }),
        cssCustomMedia(),
        autoprefixer({ add: true })
    ]);
const core = {
    generateModuleMap() {
        const loadableRoots = ["modules", "strates", "components"];
        const map = {};

        for (const root of loadableRoots) {
            const rootDir = path.join(__dirname, "assets", root);
            if (!fs.existsSync(rootDir)) continue;

            const entries = fs.readdirSync(rootDir);
            for (const entry of entries) {
                const entryDir = path.join(rootDir, entry);
                if (!fs.statSync(entryDir).isDirectory()) continue;

                const jsFile = path.join(entryDir, `${entry}.js`);
                if (!fs.existsSync(jsFile)) continue;

                const key = `${root}/${entry}`;
                const rel = `./${root}/${entry}/${entry}.js`;
                map[key] = rel;
            }
        }

        const keys = Object.keys(map).sort();
        const obj = keys.reduce((acc, k) => { acc[k] = map[k]; return acc; }, {});
        const nextJSON = JSON.stringify(obj);
        const changed = nextJSON !== moduleMapJSON;
        moduleMapJSON = nextJSON;
        return changed;
    },
    compile(file, dist_name, ext) {
        if (ext == '.js') {
            try {
                this.babel(fs.readFileSync(file, 'utf8'), dist_name, file);
            } catch (error) {
                hasError = true;
                console.log(error);
            }
        }
        else if (ext == '.css') {
            const str = fs.readFileSync(file, 'utf8');
            core.postcss(str, (css, map) => {
                fs.ensureDirSync(path.dirname(dist_name));
                fs.writeFileSync(dist_name, css, () => true);
                fs.writeFile(`${dist_name}.map`, map.toString(), () => true)
            }, dist_name);
        }
        else if (ext == '.svg') {
            const svgString = fs.readFileSync(file, 'utf8');
            const result = optimize(svgString, {
                path: dist_name,
                multipass: true,
                plugins: ["removeUselessDefs"]
            });
            const optimizedSvgString = result.data;
            fs.ensureDirSync(path.dirname(dist_name));
            fs.writeFileSync(dist_name, optimizedSvgString);
        }
        else fs.copySync(file, dist_name);
    },
    app_styles() {
        const appcss = fs.readFileSync(`${__dirname}/assets/${fromCss}`, 'utf8');
        array = [];
        let imports = "";
        let str = "";

        /**
         * compile @imports from app.css to styles.css
         * @imports in files will not be compilled and placed on top
         * 
         */

        appcss.replace(/(?<!\/\/)@import[ "']{1,2}([1-9a-z.\/-]+)["']/igm, (match, file) => array.push(file));
        for (let file of [...new Set(array)]) {
            str += fs.readFileSync(`${src}${file}`, 'utf8');
        }

        str = imports + str;

        core.postcss(str, (css, map) => {
            fs.writeFileSync(`${dist}assets/${toCss}`, css, () => true);
            fs.writeFile(`${dist}assets/${toCss}.map`, map.toString(), () => true)
        }, toCss);
    },
    parse_bundle_source() {
        const sourcePath = `${__dirname}/assets/${bundleSourceFile}`;
        const nextBundleImports = createEmptyBundleImports();
        if (!fs.existsSync(sourcePath)) {
            const changedEntries = bundleEntries.filter((entryName) =>
                hasBundleChanged(bundleImports[entryName], nextBundleImports[entryName]));
            bundleImports = nextBundleImports;
            return changedEntries;
        }

        const sourceCss = fs.readFileSync(sourcePath, "utf8");
        const imports = extractImports(sourceCss);
        for (const file of imports) {
            const matchedEntry = bundleEntries.find(e => file.startsWith(`${e}/`));
            const aliasedEntry = !matchedEntry
                ? Object.entries(bundleAliases).find(([prefix]) => file.startsWith(`${prefix}/`))?.[1]
                : null;
            const target = matchedEntry ?? aliasedEntry;
            if (target) nextBundleImports[target].push(file);
        }
        const changedEntries = bundleEntries.filter((entryName) =>
            hasBundleChanged(bundleImports[entryName], nextBundleImports[entryName]));
        bundleImports = nextBundleImports;
        return changedEntries;
    },
    write_bundle_manifest() {
        const manifest = {
            components: bundleImports.components,
            cards: bundleImports.cards,
            strates: bundleImports.strates,
        };
        fs.ensureDirSync(`${dist}assets/${bundlesDir}`);
        fs.writeFileSync(`${dist}assets/${bundlesDir}/css-bundles.json`, JSON.stringify(manifest, null, 2));
    },
    bundle_entry(entryName) {
        let str = "";
        for (let file of bundleImports[entryName]) {
            str += fs.readFileSync(`${src}${file}`, "utf8") + "\n";
        }

        core.postcss(str, (css, map) => {
            const outPath = `${dist}assets/${bundlesDir}/${entryName}.css`;
            fs.ensureDirSync(path.dirname(outPath));
            fs.writeFileSync(outPath, css, () => true);
            fs.writeFile(`${outPath}.map`, map.toString(), () => true);
        }, `${entryName}.css`);
    },
    bundle_entries_from_source(entries = bundleEntries) {
        entries.forEach((entry) => core.bundle_entry(entry));
        core.write_bundle_manifest();
    },
    dirScan(dir) {
        const recursive = dir => {
            fs.readdirSync(dir).forEach(res => {
                const file = path.resolve(dir, res);
                const stat = fs.statSync(file);
                if (stat && stat.isDirectory()) recursive(file);
                else if (!/.DS_Store$/.test(file)) {
                    const name = file.replace(`${__dirname}/`, '');
                    const relPosix = name.replaceAll(path.sep, "/");
                    const filename = path.parse(name).base;
                    const ext = path.extname(filename);
                    if (filename != fromCss && !isBundleSourceFile(relPosix)) {
                        core.compile(file, dist + name, ext);
                    }
                }
            });
        }
        recursive(dir);
    },
    rmDir(dirPath, removeSelf) {
        if (removeSelf === undefined) removeSelf = true;
        try { var files = fs.readdirSync(dirPath); }
        catch (e) { return; }
        for (let file of files) {
            const filePath = `${dirPath}/${file}`;
            fs.statSync(filePath).isFile() ? fs.unlinkSync(filePath) : core.rmDir(filePath);
        }
        removeSelf && fs.rmdirSync(dirPath);
    },
    babel(result, dest, srcFile = "") {
        let res = "";

        // Bundle assets/app.js into a single common file (static imports only).
        // Dynamic imports are wrapped in importModule() so esbuild won't glob/bundle them.
        const normalizedSrcFile = srcFile ? path.normalize(srcFile) : "";
        const isAppJs =
            normalizedSrcFile === `assets${path.sep}app.js` ||
            normalizedSrcFile.endsWith(`${path.sep}assets${path.sep}app.js`);

        if (isAppJs) {
            const entry = path.isAbsolute(srcFile) ? srcFile : path.resolve(__dirname, srcFile);
            const moduleMapBanner = `const moduleMap=${moduleMapJSON};`;

            const bundled = esbuild.buildSync({
                entryPoints: [entry],
                bundle: true,
                write: false,
                platform: "browser",
                format: "esm",
                target: ["es2020"],
                minify: true,
                legalComments: "none",
                banner: { js: `${moduleMapBanner}\n` },
            });

            const code = bundled.outputFiles?.[0]?.text ?? "";
            fs.ensureDirSync(path.dirname(dest));
            fs.writeFileSync(dest, code);
            return;
        }

        // Append a cache-busting query to ESM import specifiers.
        // - If the specifier already ends with `.js`, only append `?v=...`
        // - Otherwise append `.js?v=...`
        // Avoids the previous behavior where `.js` could be appended twice.
        res = result.replace(
            /(import\s+[^'"]*?\sfrom\s*)(['"])([^'"]+)(\2)/g,
            (match, before, quote, spec, endQuote) => {
                // Ignore remote URLs / data URLs.
                if (/^(https?:|data:)/i.test(spec)) return match;
                // Already has a query string: leave it as-is.
                if (spec.includes("?")) return match;
                if (spec.endsWith(".js")) {
                    return `${before}${quote}${spec}?v=${version}${endQuote}`;
                }
                return `${before}${quote}${spec}.js?v=${version}${endQuote}`;
            }
        );

        result = babel.transform(res, {
            minified: true,
            comments: false,
        }).code;

        fs.ensureDirSync(path.dirname(dest));
        fs.writeFileSync(dest, result);
    },
    postcss(str, func, name) {
        postcssProcessor
            .process(str, {
                from: `assets/${fromCss}`,
                parser: parser,
                map: { inline: false, annotation: `${toCss}.map` }
            })
            .catch(error => {
                console.log(`\x1b[90m${error}\x1b[39m\x1b[23m`);
                console.log(error.reason, 'line:', error.line, 'col', error.column);
                core.display(name, "error");
            })
            .then(result => {
                if (result) {
                    func(result.css, result.map);
                }
            })
    },
    display(filename, evt) {
        let status;
        if (evt == 'remove') status = `31mremoved`;
        if (evt == 'error') status = `31merror`;
        if (evt == 'update') status = `32mupdated`;
        if (evt == 'add') status = `36madded`;
        console.log(`\x1b[1m${filename}\x1b[22m`, `\x1b[${status}\x1b[39m`);
    }
}

core.rmDir(`${dist}${src}`);

core.generateModuleMap();
// Ensure app.js is bundled against the latest module map.
core.compile(path.join(__dirname, "assets", "app.js"), path.join(__dirname, dist, "assets", "app.js"), ".js");
core.dirScan(src);

core.app_styles();

// Build CSS bundles from single source file
core.parse_bundle_source();
core.bundle_entries_from_source();

if (hasError) {
    core.display('', 'error');
    return;
}

watch(src, { recursive: true }, (evt, file) => {
    const relFile = path.isAbsolute(file) ? path.relative(__dirname, file) : file;
    const relFilePosix = relFile.replaceAll(path.sep, "/");

    if (/.DS_Store$/.test(relFile)) return

    const isFile = relFile.indexOf('.') > 0 ? true : false;
    const filename = path.basename(relFile);
    const ext = path.extname(filename);
    const dist_file = path.join(dist, relFile);
    const exist = fs.existsSync(dist_file) ? true : false;
    const bundleImportPath = relFilePosix.replace(/^assets\//, "");

    if (filename !== fromCss && !isBundleSourceFile(relFilePosix)) {
        if (!exist) evt = 'add';
        if (evt == 'update' || evt == 'add') core.compile(relFile, dist_file, ext);
    } else {
        if (filename === fromCss) core.app_styles(true);
    }

    // Regenerate module map when loadable modules/strates/components change
    if (ext === ".js" && (/^assets\/modules\//.test(relFilePosix) || /^assets\/strates\//.test(relFilePosix) || /^assets\/components\//.test(relFilePosix))) {
        const changed = core.generateModuleMap();
        if (changed) {
            core.compile(path.join(__dirname, "assets", "app.js"), path.join(__dirname, dist, "assets", "app.js"), ".js");
        }
    }

    if (exist && evt == 'remove') {
        isFile ? fs.unlinkSync(dist_file) : core.rmDir(dist_file);
    }

    if (hasError) evt = 'error';

    if (ext === '.css') {
        if (filename != fromCss) {
            const matcher = new RegExp(`\\b${filename}\\b`);
            const found = array.find((element) => matcher.test(element));
            if (found) {
                core.app_styles(true);
            }
        }
    }

    // Rebuild bundles when relevant CSS changes
    if (ext === '.css') {
        if (relFilePosix === `assets/${bundleSourceFile}`) {
            const changedEntries = core.parse_bundle_source();
            if (changedEntries.length) core.bundle_entries_from_source(changedEntries);
            else core.write_bundle_manifest();
        }
        if (relFilePosix.startsWith("assets/modules/") && bundleImports.components.includes(bundleImportPath)) {
            core.bundle_entry("components");
        }
        if (relFilePosix.startsWith("assets/components/") && bundleImports.components.includes(bundleImportPath)) {
            core.bundle_entry("components");
        }
        if (relFilePosix.startsWith("assets/cards/") && bundleImports.cards.includes(bundleImportPath)) {
            core.bundle_entry("cards");
        }
        if (relFilePosix.startsWith("assets/strates/") && bundleImports.strates.includes(bundleImportPath)) {
            core.bundle_entry("strates");
        }
    }

    core.display(filename, evt);

    hasError = false;
});

console.log(`I'm Watching you...`);