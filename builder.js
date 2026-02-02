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
const watch = require('node-watch');
const { optimize } = require('svgo');

const src = 'assets/';
const dist = `web/wp-content/themes/${process.env.WP_THEME_NAME}/`;
const fromCss = "app.css";
const toCss = "styles.css";

let date = new Date();
let version = `${date.getMonth()}${date.getDay()}${date.getHours()}${date.getMinutes()}`;
let hasError = false;
let array = [];

const core = {
    compile(file, dist_name, ext) {
        if (ext == '.js') {
            try {
                this.babel(fs.readFileSync(file, 'utf8'), dist_name);
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
            str +=  fs.readFileSync(`${src}${file}`, 'utf8');
        }

        str = imports + str;

        core.postcss(str, (css, map) => {
            fs.writeFileSync(`${dist}assets/${toCss}`, css, () => true);
            fs.writeFile(`${dist}assets/${toCss}.map`, map.toString(), () => true)
        }, toCss);
    },
    dirScan(dir) {
        const recursive = dir => {
            fs.readdirSync(dir).forEach(res => {
                const file = path.resolve(dir, res);
                const stat = fs.statSync(file);
                if (stat && stat.isDirectory()) recursive(file);
                else if (!/.DS_Store$/.test(file)) {
                    const name = file.replace(`${__dirname}/`, '');
                    const filename = path.parse(name).base;
                    const ext = path.extname(filename);
                    if (filename != fromCss) {
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
    babel(result, dest) {
        let res = "";

        result = result.replace(".js", '');
       
        res = result.replace(/(import[ {}'".\/a-zA-Z0-9_,@-]+)(['"])/igm, `$1.js?v=${version}$2`);

        result = babel.transform(res, {
            minified: true,
            comments: false,
        }).code;

        fs.ensureDirSync(path.dirname(dest));
        fs.writeFileSync(dest, result);
    },
    postcss(str, func, name) {
        postcss(
            [
                cssnano,
                postcssExtendRule,
                postcssGlobalData({ files: [`${src}styles/customMedias.css`] }),
                cssCustomMedia(),
                autoprefixer({ add: true })
            ])
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

core.dirScan(src);

core.app_styles();

if (hasError) {
    core.display('', 'error');
    return;
}

watch(src, { recursive: true }, (evt, file) => {
    if (/.DS_Store$/.test(file)) return
 
    const isFile = file.indexOf('.') > 0 ? true : false;
    const filename = path.basename(file);
    const ext = path.extname(filename);
    const dist_file = dist + file;
    const exist = fs.existsSync(dist_file) ? true : false;

    if (filename !== fromCss) {
        if (!fs.existsSync(dist_file)) evt = 'add';
        if (evt == 'update' || evt == 'add') core.compile(file, dist_file, ext);
    } else {
        core.app_styles(true);
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

    core.display(filename, evt);

    hasError = false;
});

console.log(`I'm Watching you...`);