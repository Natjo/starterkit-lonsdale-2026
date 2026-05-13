const _coreScriptUrl = document.currentScript?.src || '';
const _coreBaseUrl = _coreScriptUrl.replace(/core\.js(?:\?.*)?$/, '');
const _iconsUrl = (() => {
    // `icons.svg` lives in theme `/assets/img/`, not in `/front/stylequide/img/`.
    // Resolve relative to this script URL for portability (dev/prod domains).
    try {
        if (_coreScriptUrl) return new URL('../../assets/img/icons.svg', _coreScriptUrl).toString();
    } catch (_) { /* noop */ }
    return `${_coreBaseUrl}../../assets/img/icons.svg`;
})();
const _sgAjaxUrl = (() => {
    try {
        const value = globalThis?.SG_AJAX_URL;
        return typeof value === 'string' ? value.trim() : '';
    } catch (_) {
        return '';
    }
})();

const _sgHydrateModuleCache = new Map();
const _sgHydrateCssCache = new Map();
const _sgHydrateCleanupByPreview = new WeakMap();

const sgResolveHydrateUrl = (raw = "") => {
    const value = String(raw || "").trim();
    if (!value) return "";
    try {
        return new URL(value, _coreScriptUrl || window.location.href).toString();
    } catch (_) {
        return value;
    }
};

const sgCleanupFromHydratorReturn = (value) => {
    if (typeof value === "function") return value;
    if (value && typeof value === "object") {
        if (typeof value.cleanup === "function") return () => value.cleanup();
        if (typeof value.destroy === "function") return () => value.destroy();
        if (typeof value.remove === "function") return () => value.remove();
    }
    return null;
};

const sgEnsureStylesheet = (url) => {
    const href = sgResolveHydrateUrl(url);
    if (!href) return Promise.resolve();
    if (_sgHydrateCssCache.has(href)) return _sgHydrateCssCache.get(href);

    const promise = new Promise((resolve) => {
        let timer = null;
        const done = () => {
            if (timer) window.clearTimeout(timer);
            resolve();
        };
        // Never block hydration forever if the CSS request hangs.
        timer = window.setTimeout(done, 2000);

        const existing = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
            .find((l) => (l instanceof HTMLLinkElement) && l.href === href);
        if (existing && existing instanceof HTMLLinkElement) {
            // Already loaded?
            if (existing.dataset.sgCssLoaded === "1" || existing.sheet) return done();
            existing.addEventListener("load", done, { once: true });
            existing.addEventListener("error", done, { once: true });
            return;
        }

        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = href;
        link.addEventListener("load", () => {
            link.dataset.sgCssLoaded = "1";
            done();
        }, { once: true });
        link.addEventListener("error", done, { once: true });
        document.head.appendChild(link);
    });

    _sgHydrateCssCache.set(href, promise);
    return promise;
};

const sgDefaultComponentCssUrl = (componentName = "") => {
    const name = String(componentName || "").trim().toLowerCase();
    if (!name) return "";
    try {
        return new URL(`../../assets/components/${name}/${name}.css`, _coreScriptUrl || window.location.href).toString();
    } catch (_) {
        return "";
    }
};

const sgHydrateLoadCss = async (resultRoot, componentName = "") => {
    if (!resultRoot) return;
    const raw = (resultRoot.getAttribute("data-sg-hydrate-css") || "").trim();
    const urls = raw
        ? raw
            .split(",")
            .map((v) => v.trim())
            .filter(Boolean)
        : [];

    const inferredName =
        String(componentName || "").trim().toLowerCase() ||
        String(resultRoot.getAttribute("name") || "").trim().toLowerCase() ||
        ((resultRoot.getAttribute("data-sg-render-fn") || "").trim().match(/^component::([a-z_]\w*)$/i)?.[1] || "").toLowerCase();
    const autoUrl = sgDefaultComponentCssUrl(inferredName);
    if (autoUrl) urls.push(autoUrl);

    if (!urls.length) return;
    await Promise.all(urls.map((u) => sgEnsureStylesheet(u)));
};

const sgHydratePreview = async (resultRoot, preview, requestId) => {
    if (!resultRoot || !preview) return;
    const raw = (
        resultRoot.getAttribute("data-sg-module") ||
        resultRoot.getAttribute("data-sg-hydrate-module") ||
        ""
    ).trim();
    if (!raw) return;

    const url = sgResolveHydrateUrl(raw);
    if (!url) return;

    const currentId = preview.__sgHydrateRequestId;
    if (requestId != null && currentId != null && currentId !== requestId) return;

    const promise = _sgHydrateModuleCache.get(url) || import(url);
    _sgHydrateModuleCache.set(url, promise);

    try {
        const inferredName =
            String(resultRoot.getAttribute("name") || "").trim().toLowerCase() ||
            ((resultRoot.getAttribute("data-sg-render-fn") || "").trim().match(/^component::([a-z_]\w*)$/i)?.[1] || "").toLowerCase();
        await sgHydrateLoadCss(resultRoot, inferredName);
        const mod = await promise;
        const stillCurrent = preview.__sgHydrateRequestId;
        if (requestId != null && stillCurrent != null && stillCurrent !== requestId) return;

        const run = mod?.default;
        if (typeof run !== "function") return;
        const maybeCleanup = run(preview, { resultRoot, requestId });
        const cleanup = sgCleanupFromHydratorReturn(maybeCleanup);
        if (cleanup) _sgHydrateCleanupByPreview.set(preview, cleanup);
    } catch (_) {
        // Ignore hydrate errors (preview HTML remains visible).
    }
};

class UiSummary extends HTMLElement {
    connectedCallback() {
        const label = this.innerHTML.trim();
        const level = this.getAttribute('level') || 'h2';
        const summary = document.createElement('summary');
        summary.innerHTML = `
            <${level} class="sg-${level}">${label}</${level}>
            <svg class="icon" width="20" height="20" aria-hidden="true" viewBox="0 0 20 20">
                <use href="${_iconsUrl}#caret"></use>
            </svg>
        `;
        this.replaceWith(summary);
    }
}
customElements.define('ui-summary', UiSummary);

class SgSection extends HTMLElement {
    connectedCallback() {
        const label   = this.getAttribute('label') || '';
        const slug    = this.getAttribute('slug')  || '';
        const open    = this.hasAttribute('open');
        const content = this.innerHTML;

        const section = document.createElement('section');
        section.className = 'sg-section';
        section.id = `sg-${slug}`;
        section.innerHTML = `
            <details class="sg-details sg-stratestrate-header"
                     id="sg-section-${slug}"${open ? ' open' : ''}>
                <ui-summary>${label}</ui-summary>
                ${content}
            </details>
        `;
        this.replaceWith(section);
    }
}
customElements.define('sg-section', SgSection);

class SgPart extends HTMLElement {
    connectedCallback() {
        const label   = this.getAttribute('label')   || '';
        const name    = this.getAttribute('name')    || '';
        const type    = (this.getAttribute('type') || '').trim().toLowerCase();
        const actionAttr = (this.getAttribute('action') || '').trim();
        const ajaxUrlAttr = (this.getAttribute('data-ajax-url') || '').trim();
        const tagAttr = this.getAttribute('tag') || '';
        const tags = tagAttr
            .split(',')
            .map((tag) => tag.trim())
            .filter(Boolean);
        const full    = this.hasAttribute('full');
        const colsRaw = this.getAttribute('cols')    || '';
        const cols    = colsRaw ? `sg-grid cols-${colsRaw}` : '';
        const wrapperClass = cols ? `sg-part ${cols}` : 'sg-part';
        const classes = JSON.parse(this.getAttribute('classes') || '[]');
        const selects = JSON.parse(this.getAttribute('selects') || '[]');
        let content = this.innerHTML;
        let actionHtml = '';
        const actionKey = actionAttr && /^[a-z0-9_-]+$/i.test(actionAttr) ? actionAttr : '';
        if (actionKey) {
            const tmp = document.createElement('div');
            tmp.innerHTML = content;
            const actionEl = tmp.querySelector(`[data-sg-action="${actionKey}"]`);
            if (actionEl) {
                actionHtml = actionEl.outerHTML;
                actionEl.remove();
                content = tmp.innerHTML;
            }
        }
        const summaryName = (type === "style" || type === "component") ? "" : ` <small>${name}</small>`;
        const summaryTags = type === "component" && tags.length
            ? ` <span class="sg-summary-tags">${tags.map((tag) => `<small class="sg-summary-tag">${tag}</small>`).join('')}</span>`
            : "";

        const hasStrateOptions = type === "strate";
        const optionsHtml = (classes.length || selects.length || hasStrateOptions) ? `
            <div class="sg-options">
                ${classes.length ? `
                <div class="sg-classes">
                    ${classes.map(opt => `
                    <div><label>
                        <input type="checkbox" class="sg-checkbox"
                            id="sg-checkbox-${name}-${opt.value}"
                            value="${opt.value}">
                        ${opt.title}
                    </label></div>`).join('')}
                </div>` : ''}
                ${selects.length ? `
                <div class="sg-classes">
                    ${selects.map(sel => `
                    <div>
                        <label>${sel.title} : </label>
                        <select class="sg-select" id="sg-select-${name}-${sel.name}">
                            ${Object.entries(sel.choices).map(([key, val], i) =>
                                `<option value="${val}"${i === 0 ? ' selected' : ''}>${key}</option>`
                            ).join('')}
                        </select>
                    </div>`).join('')}
                </div>` : ''}
                ${hasStrateOptions ? `<sg-strate-options></sg-strate-options>` : ""}
            </div>` : '';

        const details = document.createElement('details');
        details.className = 'sg-details sg-strate-header';
        details.id = `sg-${name}`;
        const effectiveAjaxUrl = ajaxUrlAttr || _sgAjaxUrl;
        if (effectiveAjaxUrl) details.setAttribute('data-ajax-url', effectiveAjaxUrl);
        if (actionHtml) {
            details.innerHTML = `
                <summary>
                    <h3 class="sg-h3">${label}${summaryName}${summaryTags}</h3>
                    <div class="sg-summary-right">
                        <div class="sg-summary-actions">${actionHtml}</div>
                        <svg class="icon" width="20" height="20" aria-hidden="true" viewBox="0 0 20 20">
                            <use href="${_iconsUrl}#caret"></use>
                        </svg>
                    </div>
                </summary>
                ${optionsHtml}
                ${full ? content : `<div class="${wrapperClass}">${content}</div>`}
            `;

            // Prevent a click on actions from toggling <details>. For forms, submit programmatically.
            const summaryEl = details.querySelector('summary');
            const actionsEl = summaryEl?.querySelector('.sg-summary-actions');
            if (actionsEl) {
                actionsEl.addEventListener('click', (event) => {
                    const target = event.target;
                    if (!(target instanceof Element)) return;
                    if (!target.closest('form')) return;

                    // Stop summary toggle.
                    event.preventDefault();
                    event.stopPropagation();

                    const form = target.closest('form');
                    if (!form) return;
                    const submitter = target.closest('button, input[type="submit"]');

                    // Submit only when a submit control was clicked.
                    if (submitter instanceof HTMLElement) {
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit(submitter);
                            return;
                        }

                        const name = submitter.getAttribute('name');
                        const value = submitter.getAttribute('value') || '';
                        if (name) {
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = name;
                            hidden.value = value;
                            form.appendChild(hidden);
                        }
                        form.submit();
                    }
                }, { capture: true });
            }
        } else {
            details.innerHTML = `
                <ui-summary level="h3">${label}${summaryName}${summaryTags}</ui-summary>
                ${optionsHtml}
                ${full ? content : `<div class="${wrapperClass}">${content}</div>`}
            `;
        }
        this.replaceWith(details);
    }
}
customElements.define('sg-part', SgPart);

class SgColor extends HTMLElement {
    connectedCallback() {
        const name = (this.getAttribute("name") || "").trim();
        const variable = (this.getAttribute("variable") || "").trim();
        if (!variable) return;

        const block = document.createElement("div");
        block.className = "sg-color";
        block.style.setProperty("--color", `var(${variable})`);

        const content = document.createElement("div");
        const title = document.createElement("div");
        title.className = "sg-color-name";
        title.textContent = name || variable;

        const small = document.createElement("small");
        small.textContent = variable;

        content.appendChild(title);
        content.appendChild(small);
        block.appendChild(content);
        this.replaceWith(block);
    }
}
customElements.define("sg-color", SgColor);

class SgBgColor extends HTMLElement {
    connectedCallback() {
        const name = (this.getAttribute("name") || "").trim();
        const classInput = (this.getAttribute("class") || "").trim();
        if (!classInput) return;
        const normalizedClasses = classInput
            .split(/\s+/)
            .map((value) => value.replace(/^\./, "").trim())
            .filter(Boolean);
        if (!normalizedClasses.length) return;

        const label = name || normalizedClasses[0];
        const previewClasses = normalizedClasses.join(" ");
        const block = document.createElement("div");
        block.className = "sg-bg-color";
        block.innerHTML = `
            <div class="sg-bg-color-preview ${previewClasses}">${label}</div>
            <div class="sg-bg-color-meta">
                <div class="sg-color-name"></div>
                <small>${classInput}</small>
            </div>
        `;



        this.replaceWith(block);
    }
}
customElements.define("sg-bg-color", SgBgColor);

class SgCode extends HTMLElement {
    connectedCallback() {
        let rawCode = (this.textContent || "").trim();
        if (!rawCode && this.hasAttribute("data-btn-builder")) {
            const explicit = (this.getAttribute("data-component") || "").trim();
            const fromResult = (this.closest(".sg-components-builder-result")?.getAttribute("name") || "").trim();
            const componentName = explicit || fromResult;
            if (componentName) {
                rawCode = `component::${componentName}($args)`;
            }
        }
        if (!rawCode) {
            this.replaceWith(document.createTextNode(""));
            return;
        }

        const syntax = (this.getAttribute("syntax") || "php").trim().toLowerCase();
        const copyMode = (this.getAttribute("copy") || "php-tag").trim().toLowerCase();
        const ensureStatement = (code = "") => {
            const v = String(code || "").trim();
            if (!v) return "";
            // Avoid double `;` when user already wrote one.
            return /;\s*$/.test(v) ? v : `${v};`;
        };
        const copyValue = (() => {
            if (copyMode === "raw") return rawCode;
            if (copyMode === "php-short-tag") return `<?= ${rawCode} ?>`;
            // default: php-tag
            return `<?php ${ensureStatement(rawCode)} ?>`;
        })();

        const wrapper = document.createElement("div");
        wrapper.className = "sg-code-wrap";
        if (this.hasAttribute("data-btn-builder")) {
            wrapper.setAttribute("data-btn-builder", "");
        }

        const code = document.createElement("code");
        code.className = "sg-code-inline";
        code.dataset.syntax = syntax;
        code.textContent = rawCode;

        const button = document.createElement("button");
        button.type = "button";
        button.className = "sg-copy-btn";
        button.dataset.copy = copyValue;
        button.textContent = "Copier";

        wrapper.appendChild(code);
        wrapper.appendChild(button);
        this.replaceWith(wrapper);
    }
}
customElements.define("sg-code", SgCode);

class SgBuilderResult extends HTMLElement {
    connectedCallback() {
        const name = (this.getAttribute("name") || "").trim();
        const codeAttr = (this.getAttribute("code") || "").trim();
        const tmp = document.createElement("div");
        tmp.innerHTML = this.innerHTML;

        // Allow overriding the defaults by providing your own sg-code / .sg-render.
        const existingCode = tmp.querySelector("sg-code");
        const existingRender = tmp.querySelector(".sg-render");

        const inferComponentName = (raw = "") => {
            const value = String(raw || "").trim();
            const match = value.match(/component:{1,2}([a-z_]\w*)\s*\(/i);
            return (match?.[1] || "").trim();
        };
        const derivedName =
            name ||
            inferComponentName(codeAttr) ||
            inferComponentName(existingCode?.textContent || "");

        // Optional: provide initial preview without writing `.sg-render`.
        // Usage: <div data-sg-render>...html...</div>
        let renderHtml = '';
        const renderSlot = tmp.querySelector("[data-sg-render]");
        if (!existingRender && renderSlot) {
            renderHtml = `<div class="sg-render">${renderSlot.innerHTML}</div>`;
            renderSlot.remove();
        } else {
            renderHtml = existingRender ? existingRender.outerHTML : '<div class="sg-render"></div>';
        }

        // Optional: provide initial snippet without typing inside <sg-code>.
        // Usage: <sg-builder-result code="component:link($link)">...</sg-builder-result>
        const codeHtml = existingCode
            ? existingCode.outerHTML
            : (codeAttr ? `<sg-code data-btn-builder>${codeAttr}</sg-code>` : '<sg-code data-btn-builder></sg-code>');

        existingCode?.remove();
        existingRender?.remove();

        const wrapper = document.createElement("div");
        wrapper.className = "sg-components-builder-result";
        if (derivedName) wrapper.setAttribute("name", derivedName);
        // Forward `data-sg-*` attributes (used by the generic builder/preview system).
        Array.from(this.attributes).forEach((attr) => {
            if (attr?.name && attr.name.startsWith("data-sg-")) {
                wrapper.setAttribute(attr.name, attr.value || "");
            }
        });
        wrapper.innerHTML = `${codeHtml}${tmp.innerHTML}${renderHtml}`;
        this.replaceWith(wrapper);
    }
}
customElements.define("sg-builder-result", SgBuilderResult);

const dedentSnippet = (raw = "") => {
    const lines = raw.replace(/\t/g, "    ").split("\n");
    while (lines.length && lines[0].trim() === "") lines.shift();
    while (lines.length && lines[lines.length - 1].trim() === "") lines.pop();
    const indents = lines
        .filter((line) => line.trim() !== "")
        .map((line) => line.match(/^ */)[0].length);
    const minIndent = indents.length ? Math.min(...indents) : 0;
    return lines.map((line) => line.slice(minIndent)).join("\n");
};

const phpValueFromJson = (value, indent = 0) => {
    const pad = "    ".repeat(indent);
    const innerPad = "    ".repeat(indent + 1);
    if (value === null) return "null";
    if (typeof value === "boolean") return value ? "true" : "false";
    if (typeof value === "number") return String(value);
    if (typeof value === "string") return `"${value.replaceAll('\\', '\\\\').replaceAll('"', '\\"')}"`;
    if (Array.isArray(value)) {
        if (!value.length) return "[]";
        const items = value.map((v) => `${innerPad}${phpValueFromJson(v, indent + 1)}`);
        return `[\n${items.join(",\n")}\n${pad}]`;
    }
    if (typeof value === "object") {
        const keys = Object.keys(value);
        if (!keys.length) return "[]";
        const entries = keys.map(
            (k) => `${innerPad}"${k}" => ${phpValueFromJson(value[k], indent + 1)}`
        );
        return `[\n${entries.join(",\n")}\n${pad}]`;
    }
    return String(value);
};

class SgSnippet extends HTMLElement {
    connectedCallback() {
        let rawCode = dedentSnippet(this.textContent || "");

        // Some templates may HTML-escape `=>` as `=&gt;` (sometimes with an extra `;`).
        // Normalize here so snippets always display valid JS/PHP syntax.
        rawCode = rawCode
            .replaceAll("=&gt;;", "=>")
            .replaceAll("=&gt;", "=>");

        if (!rawCode) {
            // Auto-generate from a parent carrying data-args-json (e.g. .sg-args-var).
            const source = this.closest("[data-args-json]");
            if (source) {
                const jsonAttr = source.getAttribute("data-args-json") || "";
                try {
                    const parsed = JSON.parse(jsonAttr);
                    const varName = (
                        this.getAttribute("var-name") ||
                        source.getAttribute("data-args-value") ||
                        "$args"
                    ).trim();
                    rawCode = `${varName} = ${phpValueFromJson(parsed)}`;
                } catch (_) {
                    // Invalid JSON: fall through to empty handling below.
                }
            }
        }

        if (!rawCode) {
            this.replaceWith(document.createTextNode(""));
            return;
        }

        const syntax = (this.getAttribute("syntax") || "php").trim().toLowerCase();
        const showCopy = !this.hasAttribute("no-copy");

        const wrapper = document.createElement("div");
        wrapper.className = "sg-code-wrap sg-code-wrap--block";
        // Allow styling a snippet by adding classes on `<sg-snippet class="...">`.
        // (The custom element is replaced by `wrapper`, so we forward the class list.)
        try {
            this.classList.forEach((c) => wrapper.classList.add(c));
        } catch (_) { /* noop */ }

        const pre = document.createElement("pre");
        pre.className = "sg-code-block";

        const code = document.createElement("code");
        code.className = "sg-code-inline";
        code.dataset.syntax = syntax;
        code.textContent = rawCode;
        pre.appendChild(code);
        wrapper.appendChild(pre);

        if (showCopy) {
            const button = document.createElement("button");
            button.type = "button";
            button.className = "sg-copy-btn";
            button.dataset.copy = rawCode;
            button.textContent = "Copier";
            wrapper.appendChild(button);
        }

        this.replaceWith(wrapper);
    }
}
customElements.define("sg-snippet", SgSnippet);

const DEFAULT_SG_STRATE_OPTIONS_CONFIG = {
    container: {
        default: "",
        choices: { Full: "ctr-full", Fluid: "ctr-fluid", Normal: "", Petit: "ctr-sm" },
    },
    marginTop: {
        default: "",
        choices: { Petit: "mt-sm", Moyen: "", Large: "mt-lg" },
    },
    marginBottom: {
        default: "",
        choices: { Petit: "mb-sm", Moyen: "", Large: "mb-lg" },
    },
    bgColor: {
        default: "",
        choices: { Aucune: "", Vert: "bg-color-1", Jaune: "bg-color-2", Orange: "bg-color-3" },
    },
    bgTop: {
        default: "",
        choices: { Petit: "pt-sm", Moyen: "", Large: "pt-lg" },
    },
    bgBottom: {
        default: "",
        choices: { Petit: "pb-sm", Moyen: "", Large: "pb-lg" },
    },
};

let sgStrateOptionsConfigPromise = null;
const loadSgStrateOptionsConfig = async () => {
    if (sgStrateOptionsConfigPromise) return sgStrateOptionsConfigPromise;
    sgStrateOptionsConfigPromise = (async () => {
        try {
            const response = await fetch(`${_coreBaseUrl}strates/strate-options.json`);
            if (!response.ok) return {};
            const json = await response.json();
            return json && typeof json === "object" ? json : {};
        } catch (error) {
            return {};
        }
    })();
    return sgStrateOptionsConfigPromise;
};

const getSgStrateOptionsConfig = async () => {
    const source = await loadSgStrateOptionsConfig();
    const normalized = {};
    for (const key of Object.keys(DEFAULT_SG_STRATE_OPTIONS_CONFIG)) {
        const base = DEFAULT_SG_STRATE_OPTIONS_CONFIG[key];
        const next = source[key] || {};
        normalized[key] = {
            default: typeof next.default === "string" ? next.default : base.default,
            choices: next.choices && typeof next.choices === "object" ? next.choices : base.choices,
        };
    }
    return normalized;
};

const toOptionsHtml = (choices = {}, selectedValue = "") =>
    Object.entries(choices)
        .map(([label, value]) => `<option value="${value}"${value === selectedValue ? " selected" : ""}>${label}</option>`)
        .join("");

class SgStrateOptions extends HTMLElement {
    async connectedCallback() {
        const scopeId = this.closest(".sg-details")?.id || "strate-options";
        const uid = scopeId.replace(/[^a-z0-9_-]/gi, '-');
        const config = await getSgStrateOptionsConfig();
        this.innerHTML = `
            <div class="sg-strates-options" data-strate-options-panel>
                <div>
                    <h4>Conteneur</h4>
                    <select id="sg-strate-${uid}-container"
                            class="sg-strate-select"
                            data-class-group="container">
                        ${toOptionsHtml(config.container.choices, config.container.default)}
                    </select>
                </div>

                <div>
                    <h4>Marges</h4>
                    <div class="sg-group">
                        <div class="sg-field">
                            <label for="sg-strate-${uid}-mt">Top</label>
                            <select id="sg-strate-${uid}-mt"
                                    class="sg-strate-select"
                                    data-class-group="margin-top">
                                ${toOptionsHtml(config.marginTop.choices, config.marginTop.default)}
                            </select>
                        </div>
                        <div class="sg-field">
                            <label for="sg-strate-${uid}-mb">Bottom</label>
                            <select id="sg-strate-${uid}-mb"
                                    class="sg-strate-select"
                                    data-class-group="margin-bottom">
                                ${toOptionsHtml(config.marginBottom.choices, config.marginBottom.default)}
                            </select>
                        </div>
                    </div>
                </div>

                <div class="sg-strates-options-background">
                    <h4>Background</h4>

                    <div class="sg-group">
                        <div class="sg-field">
                            <label for="sg-strate-${uid}-bg-color">Color</label>
                            <select id="sg-strate-${uid}-bg-color"
                                    class="sg-strate-select"
                                    data-class-group="bg-color"
                                    data-bg-color-control>
                                ${toOptionsHtml(config.bgColor.choices, config.bgColor.default)}
                            </select>
                        </div>
                        <div class="sg-field">
                            <label for="sg-strate-${uid}-pt">Top</label>
                            <select id="sg-strate-${uid}-pt"
                                    class="sg-strate-select"
                                    data-class-group="padding-top"
                                    data-bg-margin-control>
                                ${toOptionsHtml(config.bgTop.choices, config.bgTop.default)}
                            </select>
                        </div>
                        <div class="sg-field">
                            <label for="sg-strate-${uid}-pb">Bottom</label>
                            <select id="sg-strate-${uid}-pb"
                                    class="sg-strate-select"
                                    data-class-group="padding-bottom"
                                    data-bg-margin-control>
                                ${toOptionsHtml(config.bgBottom.choices, config.bgBottom.default)}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `;
        const panel = this.querySelector("[data-strate-options-panel]");
        if (panel) {
            document.dispatchEvent(new CustomEvent("sg-strate-options:ready", { detail: { panel } }));
        }
    }
}
customElements.define('sg-strate-options', SgStrateOptions);

document.querySelector("title").innerText = "Styleguide";
const test = document.querySelectorAll("[class*=card-]");

// selects
let select_status = [];
var select_storage = localStorage.getItem("sg-select");

if (!select_storage) {
    localStorage.setItem("sg-checkboxes", JSON.stringify(select_status));
} else {
    select_status = JSON.parse(select_storage);
}
select_status.forEach(status => {
    const entry = Object.entries(status ?? {})[0];
    if (!entry) return;
    const [id, value] = entry;
    const el = document.getElementById(id);
    if (el) el.value = value;
})


const applySelectClass = (els, classes, value) => {
    classes.forEach(cls => cls && els.forEach(el => el.classList.remove(cls)));
    if (value) els.forEach(el => el.classList.add(value));
};

const selects = document.querySelectorAll("select.sg-select");
selects.forEach(select => {
    const nextElement = select.closest(".sg-options").nextElementSibling;
    let els = [nextElement];
    if (nextElement.classList.contains("sg-part")) {
        els = Array.from(nextElement.querySelectorAll("[class*=card-],[class*=nj-btn],[class*=nj-icon-material],[class*=nj-tag]"));
    }

    const classes = Array.from(select.querySelectorAll("option"))
        .map(o => o.value)
        .filter(Boolean);

    // Appliquer la classe au chargement (valeur déjà restaurée par select_status.forEach)
    applySelectClass(els, classes, select.value);

    select.onchange = () => {
        applySelectClass(els, classes, select.value);

        const existingIndex = select_status.findIndex(item => select.id in item);
        if (select.value === "") {
            // Supprimer si existe
            if (existingIndex !== -1) select_status.splice(existingIndex, 1);
        } else {
            const obj = { [select.id]: select.value };
            if (existingIndex !== -1) {
                // Remplacer
                select_status[existingIndex] = obj;
            } else {
                // Ajouter
                select_status.push(obj);
            }
        }

        localStorage.setItem("sg-select", JSON.stringify(select_status));
    }
})





// checkbox
let checkbox_status = [];
var checkbox_storage = localStorage.getItem("sg-checkboxes");

if (!checkbox_storage) {
    localStorage.setItem("sg-checkboxes", JSON.stringify(checkbox_status));
} else {
    checkbox_status = JSON.parse(checkbox_storage);
}
checkbox_status.forEach(status => {
    if (status) document.getElementById(status).checked = true;
})

const applyCheckboxClass = (els, value, checked) => {
    els.forEach(el => el.classList[checked ? "add" : "remove"](value));
};

const checkboxs = document.querySelectorAll(".sg-checkbox");
checkboxs.forEach(checkbox => {
    const nextElement = checkbox.closest(".sg-options").nextElementSibling;
    let els = [nextElement];
    if (nextElement.classList.contains("sg-part")) {
        els = Array.from(nextElement.querySelectorAll("[class*=card-],[class*=nj-btn],[class*=nj-icon-material],[class*=nj-tag]"));
    }

    applyCheckboxClass(els, checkbox.value, checkbox.checked);

    checkbox.onchange = () => {
        applyCheckboxClass(els, checkbox.value, checkbox.checked);

        if (checkbox.checked) {
            !checkbox_status.includes(checkbox.id) && checkbox_status.push(checkbox.id);
        } else {
            checkbox_status.splice(checkbox_status.indexOf(checkbox.id), 1);
        }
        localStorage.setItem("sg-checkboxes", JSON.stringify(checkbox_status));
    }
})





// detail with local storage
const details = document.querySelectorAll("details");
let details_status = [];
var cat = localStorage.getItem("sg-details");
if (!cat) {
    localStorage.setItem("sg-details", JSON.stringify(details_status));
} else {
    details_status = JSON.parse(cat);
}

details.forEach(detail => {
    detail.removeAttribute('open');
})

details_status = details_status.filter((status) => {
    if (!status) return false;
    const detailEl = document.getElementById(status);
    if (!detailEl) return false;
    detailEl.setAttribute('open', true);
    return true;
});
localStorage.setItem("sg-details", JSON.stringify(details_status));

details.forEach(detail => {
    detail.addEventListener("toggle", (evt) => {
        if (detail.open) {
            !details_status.includes(detail.id) && details_status.push(detail.id);
        } else {
            details_status.splice(details_status.indexOf(detail.id), 1);
        }
        localStorage.setItem("sg-details", JSON.stringify(details_status));
    })
})

// strate options component
const resolveStrateTarget = (panel) => {
    const details = panel.closest(".sg-details");
    if (!details) return null;

    let current = panel.nextElementSibling;
    while (current) {
        if (current.classList.contains("strate")) return current;
        const nested = current.querySelector?.(".strate");
        if (nested) return nested;
        current = current.nextElementSibling;
    }

    return details.querySelector(".strate");
};

const applyStrateSelectClass = (target, select, forcedValue = null) => {
    const classValues = Array.from(select.options).map((opt) => opt.value).filter(Boolean);
    classValues.forEach((cls) => target.classList.remove(cls));
    const nextValue = forcedValue === null ? select.value : forcedValue;
    if (nextValue) target.classList.add(nextValue);
};

let strateSelectStatus = {};
try {
    strateSelectStatus = JSON.parse(localStorage.getItem("sg-strate-selects") || "{}") || {};
} catch (error) {
    strateSelectStatus = {};
}

const initStratePanel = (panel) => {
    if (!panel || panel.dataset.sgStrateBound === "1") return;
    const target = resolveStrateTarget(panel);
    if (!target) return;
    panel.dataset.sgStrateBound = "1";

    const selects = Array.from(panel.querySelectorAll(".sg-strate-select"));
    selects.forEach((select) => {
        const saved = strateSelectStatus[select.id];
        if (saved != null) select.value = saved;
        if (select.hasAttribute("data-bg-color-control") || select.hasAttribute("data-bg-margin-control")) {
            return;
        }
        applyStrateSelectClass(target, select);
        select.addEventListener("change", () => {
            if (select.hasAttribute("data-bg-color-control") || select.hasAttribute("data-bg-margin-control")) {
                return;
            }
            applyStrateSelectClass(target, select);
            strateSelectStatus[select.id] = select.value;
            localStorage.setItem("sg-strate-selects", JSON.stringify(strateSelectStatus));
        });
    });

    const bgColorControl = panel.querySelector("[data-bg-color-control]");
    const bgMarginControls = Array.from(panel.querySelectorAll("[data-bg-margin-control]"));
    if (!bgColorControl) return;

    const syncBackgroundState = () => {
        const hasBackgroundColor = !!bgColorControl.value;
        applyStrateSelectClass(target, bgColorControl);
        strateSelectStatus[bgColorControl.id] = bgColorControl.value;

        bgMarginControls.forEach((control) => {
            control.disabled = !hasBackgroundColor;
            if (!hasBackgroundColor) {
                applyStrateSelectClass(target, control, "");
            } else {
                applyStrateSelectClass(target, control);
            }
            strateSelectStatus[control.id] = control.value;
        });

        localStorage.setItem("sg-strate-selects", JSON.stringify(strateSelectStatus));
    };

    bgColorControl.addEventListener("change", syncBackgroundState);
    bgMarginControls.forEach((control) => {
        control.addEventListener("change", syncBackgroundState);
    });
    syncBackgroundState();
};

const initExistingStratePanels = () => {
    document.querySelectorAll("[data-strate-options-panel]").forEach(initStratePanel);
};

const escapeHtml = (value = "") =>
    value
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;");

const highlightPhpInlineCode = (codeEl) => {
    if (!codeEl) return;
    // Skip only if the tokenized markup is already present.
    if (codeEl.dataset.highlighted === "1" && codeEl.querySelector("[class^='tok-']")) return;

    let html = escapeHtml(codeEl.textContent || "");
    const tokens = [];
    const stash = (markup) => {
        const key = `@@TOK${tokens.length}@@`;
        tokens.push(markup);
        return key;
    };

    html = html.replace(/"([^"\\]|\\.)*"|'([^'\\]|\\.)*'/g, (match) => stash(`<span class="tok-string">${match}</span>`));
    html = html.replace(/\$[A-Za-z_]\w*/g, (match) => stash(`<span class="tok-var">${match}</span>`));
    html = html.replace(/\b\d+(?:\.\d+)?\b/g, (match) => stash(`<span class="tok-number">${match}</span>`));
    html = html.replace(/\b([A-Za-z_]\w*)(:{1,2})([A-Za-z_]\w*)\b/g, (_, owner, separator, method) =>
        stash(`<span class="tok-class">${owner}</span><span class="tok-operator">${separator}</span><span class="tok-fn">${method}</span>`)
    );
    html = html.replace(/\b(null|true|false)\b/gi, (match) => stash(`<span class="tok-keyword">${match}</span>`));
    html = html.replace(/\b([A-Za-z_]\w*)(?=\s*\()/g, (match) => stash(`<span class="tok-fn">${match}</span>`));
    html = html.replace(/[(),;]/g, (match) => stash(`<span class="tok-punc">${match}</span>`));
    html = html.replace(/@@TOK(\d+)@@/g, (_, index) => tokens[Number(index)] || "");

    codeEl.innerHTML = html;
    codeEl.dataset.highlighted = "1";
};

const highlightInlineCodeBlocks = () => {
    document.querySelectorAll('code[data-syntax="php"]').forEach(highlightPhpInlineCode);
};

const initBtnCodeBuilder = () => {
    const builders = document.querySelectorAll(".sg-components-builder");
    if (!builders.length) return;

    builders.forEach((builderRoot) => {
        // Sugar: allow `<div class="sg-components-builder" full>` to toggle the `.full` class.
        // (Used in styleguide CSS: `.sg-components-builder.full { ... }`)
        if (builderRoot.hasAttribute("full")) builderRoot.classList.add("full");
        else builderRoot.classList.remove("full");

        const builderTarget = builderRoot.querySelector("[data-btn-builder]") || builderRoot;
        const outputWrap =
            builderTarget.querySelector?.(".sg-code-wrap") ||
            builderTarget.closest?.(".sg-code-wrap") ||
            builderRoot.querySelector(".sg-code-wrap");
        const outputCode = outputWrap?.querySelector("code.sg-code-inline");
        const outputCopyBtn = outputWrap?.querySelector(".sg-copy-btn");
        if (!outputCode || !outputCopyBtn) return;

        const resultRoot =
            outputWrap?.closest?.(".sg-components-builder-result") ||
            builderRoot.querySelector(".sg-components-builder-result");
        const renderFnAttr = (resultRoot?.getAttribute("data-sg-render-fn") || "").trim();
        const renderFnFromAttr = renderFnAttr.match(/^component::([a-z_]\w*)$/i);
        const inferFnNameFromCode = (raw = "") => {
            const value = String(raw || "").trim();
            const match = value.match(/component:{1,2}([a-z_]\w*)\s*\(/i);
            return (match?.[1] || "").trim();
        };
        const inferredName =
            (renderFnFromAttr?.[1] || "").trim() ||
            String(resultRoot?.getAttribute("name") || "").trim() ||
            inferFnNameFromCode(outputCode.textContent || "");
        const fnName = inferredName.toLowerCase();
        const renderFn = renderFnFromAttr ? renderFnAttr : (fnName ? `component::${fnName}` : "");
        const renderParamsRaw = (
            resultRoot?.getAttribute("data-sg-params") ||
            resultRoot?.getAttribute("data-sg-render-params") ||
            ""
        ).trim();
        const renderParams = renderParamsRaw
            .split(",")
            .map((value) => value.trim())
            .filter((value) => /^[a-z_]\w*$/i.test(value));
        if (!fnName || !renderParams.length) return;
        // Auto-load component CSS when it exists: `/assets/components/<name>/<name>.css`.
        // (404 is ignored by `sgEnsureStylesheet`.)
        sgHydrateLoadCss(resultRoot, fnName);

        const preview = builderRoot.querySelector(".sg-render");
        const ajaxUrl =
            preview?.dataset.ajaxUrl ||
            builderRoot.dataset.ajaxUrl ||
            builderRoot.closest?.("[data-ajax-url]")?.dataset.ajaxUrl ||
            _sgAjaxUrl ||
            "";

        const getInput = (name) => builderRoot.querySelector(`[data-param="${name}"]`);
        const argsTypeInput = builderRoot.querySelector('select[data-param="args-type"]');
        const argsInputsByType = {};
        builderRoot.querySelectorAll('[data-param="args"][data-args-type]').forEach((input) => {
            const type = input.getAttribute("data-args-type");
            if (type) argsInputsByType[type] = input;
        });
        const typeInput = builderRoot.querySelector('[data-param="type"]');

        const getFallbackArgsTypeFromTypeParam = () => {
            const v = String(typeInput?.value || "").trim().toLowerCase();
            // Special-case: Tag component uses `type=link` to switch `$args` to `$link`.
            if (v === "link") return "var";
            return "label";
        };

        const getEffectiveArgsType = () => {
            if (argsTypeInput) return String(argsTypeInput.value || "").trim();
            if (!Object.keys(argsInputsByType).length) return "";
            const fallback = getFallbackArgsTypeFromTypeParam();
            if (fallback && argsInputsByType[fallback]) return fallback;
            if (argsInputsByType.label) return "label";
            if (argsInputsByType.var) return "var";
            return Object.keys(argsInputsByType)[0] || "";
        };

        const getActiveArgsInput = () => {
            const type = getEffectiveArgsType();
            return argsInputsByType[type] || getInput("args");
        };
        const readArgsEl = (el) => {
            if (!el) return "";
            if (el.getAttribute?.("data-args-value")) {
                return el.getAttribute("data-args-value").trim();
            }
            if ("value" in el && el.tagName !== "SPAN") return (el.value || "").trim();
            return (el.textContent || "").trim();
        };
        const getActiveArgsValue = () => readArgsEl(getActiveArgsInput());
        const getActiveArgsType = () => getEffectiveArgsType() || "";
        const resolveParamEl = (param) => {
            if (param === "args" && Object.keys(argsInputsByType).length) return getActiveArgsInput();
            return getInput(param);
        };

        // Generic: toggle any element with `data-args-type-show="<type> [<type> ...]"`
        // based on the active args-type. Use space-separated types to allow multi-match
        // (e.g. `data-args-type-show="id src"`).
        const argsTypeShowEls = Array.from(builderRoot.querySelectorAll("[data-args-type-show]"));
        const syncArgsTypeShowVisibility = (type) => {
            argsTypeShowEls.forEach((el) => {
                const allowed = (el.getAttribute("data-args-type-show") || "")
                    .split(/\s+/)
                    .map((t) => t.trim())
                    .filter(Boolean);
                const isActive = allowed.length === 0 || allowed.includes(type);
                el.hidden = !isActive;
                el.style.display = isActive ? "" : "none";
            });
        };

        const syncArgsInputsVisibility = () => {
            const type = getEffectiveArgsType();
            if (argsTypeInput || typeInput) {
                Object.entries(argsInputsByType).forEach(([key, input]) => {
                    const isActive = key === type;
                    input.hidden = !isActive;
                    // Inline style to avoid any CSS overriding `[hidden]`.
                    input.style.display = isActive ? "" : "none";
                });
            }
            syncArgsTypeShowVisibility(type);
        };
        syncArgsInputsVisibility();

        const toPhpString = (value = "") => `'${value.replaceAll("\\", "\\\\").replaceAll("'", "\\'")}'`;
        const normalizeParam = (value = "", fallback = "") => {
            const v = value.trim();
            if (!v) return fallback;
            if (/^(null|true|false)$/i.test(v)) return v.toLowerCase();
            if (/^-?\d+(?:\.\d+)?$/.test(v)) return v;
            // Keep advanced/php expressions as-is.
            if (/^[\[$]/.test(v) || /^\$/.test(v) || /=>/.test(v) || /::/.test(v) || /\w+\s*\(/.test(v)) return v;
            if (/^".*"$/.test(v) || /^'.*'$/.test(v)) return toPhpString(v.slice(1, -1));
            return toPhpString(v);
        };

        let previewTimer = null;
        let previewRequestId = 0;

        // Collect ALL inputs/selects sharing the same `data-param`.
        // This lets a builder expose several `<select data-param="classes">`
        // (e.g. variant + size) without losing values.
        const readControlValues = (controls) => {
            const out = [];
            controls.forEach((el) => {
                if (!el) return;
                if (el instanceof HTMLSelectElement && el.multiple) {
                    Array.from(el.selectedOptions).forEach((opt) => {
                        const v = (opt.value || "").trim();
                        if (v) out.push(v);
                    });
                    return;
                }
                const raw = typeof el.value === "string" ? el.value : "";
                raw.split(/\s+/).forEach((token) => {
                    const v = token.trim();
                    if (v) out.push(v);
                });
            });
            // De-duplicate while preserving order.
            return Array.from(new Set(out));
        };

        const classesControls = Array.from(
            builderRoot.querySelectorAll('select[data-param="classes"], input[data-param="classes"], textarea[data-param="classes"]')
        );
        const getMergedClasses = () => readControlValues(classesControls).join(" ");

        const sizeControls = Array.from(
            builderRoot.querySelectorAll('select[data-param="size"], input[data-param="size"], textarea[data-param="size"]')
        );
        const getMergedSize = () => readControlValues(sizeControls).join(" ");

        const updatePreview = () => {
            if (!preview || !ajaxUrl) return;
            if (previewTimer) clearTimeout(previewTimer);
            previewTimer = setTimeout(async () => {
                const requestId = ++previewRequestId;
                const body = new URLSearchParams();
                body.set("action", "sg_preview_component");
                body.set("render_fn", renderFn);
                body.set("render_params", renderParams.join(","));

                const payload = {};
                if (argsTypeInput) {
                    payload.args_type = (argsTypeInput.value || "").trim();
                }
                renderParams.forEach((param) => {
                    if (param === "classes") {
                        payload[param] = getMergedClasses();
                        return;
                    }
                    if (param === "size") {
                        payload[param] = getMergedSize();
                        return;
                    }
                    if (param === "sizes") {
                        const desktopEl = builderRoot.querySelector('[data-param="sizes_desktop"]');
                        const mobileEl = builderRoot.querySelector('[data-param="sizes_mobile"]');
                        const desk = (desktopEl?.value || "").trim();
                        const mob = (mobileEl?.value || "").trim();
                        const mobDef = (mobileEl?.getAttribute?.("data-default") || "").trim();
                        const type = getActiveArgsType();
                        if (type === "var") {
                            // Si mobile est au défaut, on retombe sur la string desktop seule
                            // (cohérent avec le snippet et avec component::picture()).
                            const mobileAtDefault = !mob || (mobDef && mob === mobDef);
                            if (mobileAtDefault) {
                                payload[param] = desk;
                            } else {
                                const arr = [];
                                if (desk) arr.push(desk);
                                if (mob) arr.push(mob);
                                payload[param] = arr;
                            }
                        } else {
                            // id / src / pas d'args-type → image unique : on n'envoie que le desktop.
                            payload[param] = desk;
                        }
                        return;
                    }

                    const el = resolveParamEl(param);
                    if (el instanceof HTMLInputElement && el.type === "checkbox") {
                        payload[param] = !!el.checked;
                    } else if (param === "args" && argsTypeInput) {
                        payload[param] = getActiveArgsValue();
                    } else {
                        payload[param] = readArgsEl(el);
                    }

                    const argsJson = el?.getAttribute?.("data-args-json") || "";
                    if (argsJson) payload[`${param}_json`] = argsJson;

                    const jsonEl = builderRoot.querySelector(`[data-param="${param}_json"]`);
                    if (jsonEl) {
                        if (jsonEl instanceof HTMLInputElement || jsonEl instanceof HTMLTextAreaElement || jsonEl instanceof HTMLSelectElement) {
                            const v = (jsonEl.value || "").trim();
                            if (v) payload[`${param}_json`] = v;
                        } else {
                            const v = (jsonEl.getAttribute?.("data-args-json") || jsonEl.textContent || "").trim();
                            if (v) payload[`${param}_json`] = v;
                        }
                    }
                });
                body.set("payload_json", JSON.stringify(payload));

                try {
                    const response = await fetch(ajaxUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                        },
                        body: body.toString(),
                    });
                    const json = await response.json();
                    if (requestId !== previewRequestId) return;
                    if (json?.success && typeof json.data?.html === "string") {
                        // Cleanup previous interactive modules before DOM replacement.
                        try {
                            const cleanup = _sgHydrateCleanupByPreview.get(preview);
                            if (typeof cleanup === "function") cleanup();
                        } catch (_) { /* noop */ }
                        _sgHydrateCleanupByPreview.delete(preview);

                        preview.__sgHydrateRequestId = requestId;
                        preview.innerHTML = json.data.html;
                        // (Async) load CSS even when no JS module is declared.
                        sgHydrateLoadCss(resultRoot, fnName);
                        // (Async) hydrate preview if a module is declared on the builder result root.
                        sgHydratePreview(resultRoot, preview, requestId);
                    }
                } catch (_) {
                    // Keep previous preview output on request failure.
                }
            }, 150);
        };

        const getParamDefaultToken = (param) => {
            if (!param) return "";
            if (param === "classes" || param === "size") return "";
            if (param === "args") return "";

            // `sizes` est un param virtuel agrégé depuis 2 selects.
            // Comme `buildCall` collapse vers la string desktop quand mobile est
            // au défaut, le token "par défaut" est toujours la string desktop.
            if (param === "sizes") {
                const desktopEl = builderRoot.querySelector('[data-param="sizes_desktop"]');
                const deskDef = (desktopEl?.getAttribute?.("data-default") || "").trim();
                return deskDef ? toPhpString(deskDef) : "";
            }

            const el = getInput(param);
            const raw = (
                el?.getAttribute?.("data-default") ||
                el?.getAttribute?.("data-sg-default") ||
                ""
            ).trim();
            if (raw) return normalizeParam(raw, "");

            // Auto-détection : pour une checkbox sans `data-default`, on utilise
            // l'état initial (`defaultChecked`) comme valeur par défaut.
            if (el instanceof HTMLInputElement && el.type === "checkbox") {
                return el.defaultChecked ? "true" : "false";
            }

            return "";
        };

        const trimTrailingOptionalParams = (params, defaults = []) => {
            const out = [...params];
            while (out.length) {
                const i = out.length - 1;
                const last = out[i];
                const def = defaults[i] || "";
                if (def) {
                    if (last === def) {
                        out.pop();
                        continue;
                    }
                    // Valeur explicitement différente du défaut → on garde.
                    break;
                }
                // Pas de défaut connu : on retire les valeurs "neutres".
                if (last === "null" || last === "false") {
                    out.pop();
                    continue;
                }
                break;
            }
            return out;
        };

        const buildCall = () => {
            const mergedClassesValue = normalizeParam(getMergedClasses(), "null");
            const defaults = renderParams.map((param) => getParamDefaultToken(param));
            let signature;

            if (fnName === "dialog") {
                const pContent = normalizeParam(readArgsEl(getInput("content")), "null");
                const typeEl = getInput("type");
                const tType = String(typeEl?.value || "btn").trim().toLowerCase();
                const typeToken = tType === "link" ? "'link'" : "'btn'";
                const nameRaw = readArgsEl(getInput("trigger_name"));
                const nameToken = nameRaw.trim() === "" ? "null" : normalizeParam(nameRaw, "null");
                const clsRaw = readArgsEl(getInput("trigger_classes"));
                const clsToken = clsRaw.trim() === "" ? "null" : normalizeParam(clsRaw, "null");
                const triggerArr = `[${typeToken}, ${nameToken}, ${clsToken}]`;
                const pClasses = mergedClassesValue || "null";
                const pAttrs = normalizeParam(readArgsEl(getInput("attributes")), "null");
                const dialogParams = trimTrailingOptionalParams(
                    [pContent, triggerArr, pClasses, pAttrs],
                    ["", "", "null", "null"]
                );
                signature = `component::${fnName}(${dialogParams.join(", ")})`;
            } else {
                const params = renderParams.map((param) => {
                    if (param === "classes") {
                        return mergedClassesValue || "null";
                    }
                    if (param === "size") {
                        const sizeValue = normalizeParam(getMergedSize(), "null");
                        return sizeValue || "null";
                    }
                    if (param === "sizes") {
                        const desktopEl = builderRoot.querySelector('[data-param="sizes_desktop"]');
                        const mobileEl = builderRoot.querySelector('[data-param="sizes_mobile"]');
                        const desk = (desktopEl?.value || "").trim();
                        const mob = (mobileEl?.value || "").trim();
                        const mobDef = (mobileEl?.getAttribute?.("data-default") || "").trim();
                        const type = getActiveArgsType();
                        if (type === "var") {
                            // Si mobile est à sa valeur par défaut (ou vide), on émet
                            // juste la string desktop : `'666_356'` plutôt que `['666_356', 'full']`.
                            const mobileAtDefault = !mob || (mobDef && mob === mobDef);
                            if (mobileAtDefault) {
                                return desk ? toPhpString(desk) : "null";
                            }
                            const items = [];
                            if (desk) items.push(toPhpString(desk));
                            if (mob) items.push(toPhpString(mob));
                            return items.length ? `[${items.join(", ")}]` : "null";
                        }
                        // id / src / pas d'args-type → image unique → string desktop seulement.
                        return desk ? toPhpString(desk) : "null";
                    }
                    if (param === "args" && argsTypeInput) {
                        const type = getActiveArgsType();
                        const raw = getActiveArgsValue();
                        return type === "var" ? (raw || "$args") : normalizeParam(raw, "$args");
                    }
                    const el = resolveParamEl(param);
                    if (el instanceof HTMLInputElement && el.type === "checkbox") {
                        return el.checked ? "true" : "false";
                    }
                    return normalizeParam(readArgsEl(el), "null");
                });

                const normalizedParams = trimTrailingOptionalParams(params, defaults);
                signature = `component::${fnName}(${normalizedParams.join(", ")})`;
            }

            outputCode.textContent = signature;
            outputCode.dataset.highlighted = "0";
            highlightPhpInlineCode(outputCode);
            outputCopyBtn.dataset.copy = `<?php ${signature}; ?>`;
            updatePreview();
        };

        const initArrayCheckboxGroup = (param) => {
            const group = builderRoot.querySelector(`[data-sg-array="${CSS.escape(param)}"]`);
            if (!group) return;

            const phpEl = builderRoot.querySelector(`input[data-param="${CSS.escape(param)}"], textarea[data-param="${CSS.escape(param)}"]`);
            const jsonEl = builderRoot.querySelector(`input[data-param="${CSS.escape(param)}_json"], textarea[data-param="${CSS.escape(param)}_json"]`);
            if (!phpEl && !jsonEl) return;

            const checkboxes = Array.from(group.querySelectorAll('input[type="checkbox"][value]'));
            if (!checkboxes.length) return;

            const sync = () => {
                const values = checkboxes
                    .filter((cb) => cb instanceof HTMLInputElement && cb.checked)
                    .map((cb) => String(cb.value || "").trim())
                    .filter(Boolean);

                const phpLiteral = `[${values.map((v) => toPhpString(v)).join(", ")}]`;
                const jsonLiteral = JSON.stringify(values);

                if (phpEl && "value" in phpEl) phpEl.value = phpLiteral;
                if (jsonEl && "value" in jsonEl) jsonEl.value = jsonLiteral;
            };

            checkboxes.forEach((cb) => {
                cb.addEventListener("change", () => {
                    sync();
                    buildCall();
                });
            });

            sync();
        };
        renderParams.forEach(initArrayCheckboxGroup);

        if (fnName === "icon") {
            const nameInput = getInput("name");
            const widthInput = getInput("width");
            const heightInput = getInput("height");

            let iconState = {
                name: "youtube",
                width: "24",
                height: "24",
                baseWidth: 24,
                baseHeight: 24,
                ratio: 1, // baseHeight / baseWidth
            };
            const raw = (outputCode.textContent || "").trim();
            const m = raw.match(/component:{1,2}icon\(\s*["']([^"']+)["']\s*,\s*([0-9.]+)\s*,\s*([0-9.]+)\s*(?:,|\))/i);
            if (m) {
                const baseWidth = Number.parseFloat(m[2]);
                const baseHeight = Number.parseFloat(m[3]);
                iconState.name = m[1];
                iconState.width = String(m[2]);
                iconState.height = String(m[3]);
                iconState.baseWidth = Number.isFinite(baseWidth) && baseWidth > 0 ? baseWidth : 24;
                iconState.baseHeight = Number.isFinite(baseHeight) && baseHeight > 0 ? baseHeight : 24;
                iconState.ratio = iconState.baseWidth ? (iconState.baseHeight / iconState.baseWidth) : 1;
            }

            let iconSyncing = false;

            const normalizePositiveInt = (value, fallback = 24) => {
                const n = Number.parseFloat(String(value ?? ""));
                if (!Number.isFinite(n) || n <= 0) return fallback;
                return Math.round(n);
            };

            const setIconBaseDims = (width, height) => {
                const bw = Number.parseFloat(String(width ?? ""));
                const bh = Number.parseFloat(String(height ?? ""));
                iconState.baseWidth = Number.isFinite(bw) && bw > 0 ? bw : 24;
                iconState.baseHeight = Number.isFinite(bh) && bh > 0 ? bh : 24;
                iconState.ratio = iconState.baseWidth ? (iconState.baseHeight / iconState.baseWidth) : 1;
            };

            const setIconCurrentDims = (width, height) => {
                const w = normalizePositiveInt(width, 24);
                const h = normalizePositiveInt(height, 24);
                iconState.width = String(w);
                iconState.height = String(h);
                if (widthInput) widthInput.value = String(w);
                if (heightInput) heightInput.value = String(h);
            };

            // Init: show the current dimensions in inputs.
            if (widthInput && !widthInput.value) widthInput.value = iconState.width;
            if (heightInput && !heightInput.value) heightInput.value = iconState.height;

            const syncHeightFromWidth = () => {
                if (!widthInput || !heightInput) return;
                const w = normalizePositiveInt(widthInput.value, normalizePositiveInt(iconState.width, 24));
                const ratio = Number.isFinite(iconState.ratio) && iconState.ratio > 0 ? iconState.ratio : 1;
                const h = Math.max(1, Math.round(w * ratio));

                iconSyncing = true;
                widthInput.value = String(w);
                heightInput.value = String(h);
                iconSyncing = false;
                iconState.width = String(w);
                iconState.height = String(h);
            };

            const syncWidthFromHeight = () => {
                if (!widthInput || !heightInput) return;
                const h = normalizePositiveInt(heightInput.value, normalizePositiveInt(iconState.height, 24));
                const ratio = Number.isFinite(iconState.ratio) && iconState.ratio > 0 ? iconState.ratio : 1;
                const w = Math.max(1, Math.round(h / ratio));

                iconSyncing = true;
                widthInput.value = String(w);
                heightInput.value = String(h);
                iconSyncing = false;
                iconState.width = String(w);
                iconState.height = String(h);
            };

            // Ratio lock: run before the generic buildCall handlers.
            widthInput?.addEventListener("input", () => {
                if (iconSyncing) return;
                syncHeightFromWidth();
            }, { capture: true });
            widthInput?.addEventListener("change", () => {
                if (iconSyncing) return;
                syncHeightFromWidth();
            }, { capture: true });
            heightInput?.addEventListener("input", () => {
                if (iconSyncing) return;
                syncWidthFromHeight();
            }, { capture: true });
            heightInput?.addEventListener("change", () => {
                if (iconSyncing) return;
                syncWidthFromHeight();
            }, { capture: true });

            builderRoot.querySelectorAll("[data-icon-pick][data-icon-id]").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const id = (btn.getAttribute("data-icon-id") || "").trim();
                    const w = (btn.getAttribute("data-icon-w") || "").trim();
                    const h = (btn.getAttribute("data-icon-h") || "").trim();
                    if (id) iconState.name = id;
                    if (id && nameInput) nameInput.value = id;
                    if (w && h) {
                        setIconBaseDims(w, h);
                        setIconCurrentDims(w, h);
                    }
                    buildCall();
                });
            });
        }

        // Watch every `data-param` control (new components should not require JS edits).
        builderRoot.querySelectorAll('input[data-param], select[data-param], textarea[data-param]').forEach((el) => {
            el.addEventListener("input", buildCall);
            el.addEventListener("change", buildCall);
        });

        // args-type affects which args control is active.
        argsTypeInput?.addEventListener("change", () => {
            syncArgsInputsVisibility();
            buildCall();
        });

        // Some components (e.g. tag) switch `$args` based on another control (`type`).
        typeInput?.addEventListener("change", () => {
            syncArgsInputsVisibility();
        });

        buildCall();
    });
};

document.addEventListener("sg-strate-options:ready", (event) => {
    initStratePanel(event.detail?.panel);
});

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        initExistingStratePanels();
        highlightInlineCodeBlocks();
        initBtnCodeBuilder();
    }, { once: true });
} else {
    initExistingStratePanels();
    highlightInlineCodeBlocks();
    initBtnCodeBuilder();
}

document.addEventListener("click", async (event) => {
    const button = event.target.closest(".sg-copy-btn");
    if (!button) return;
    const value = button.getAttribute("data-copy") || "";
    if (!value) return;

    try {
        await navigator.clipboard.writeText(value);
        const previous = button.textContent;
        button.textContent = "Copiee";
        window.setTimeout(() => {
            button.textContent = previous || "Copier";
        }, 1200);
    } catch (error) {
        button.textContent = "Erreur";
        window.setTimeout(() => {
            button.textContent = "Copier";
        }, 1200);
    }
});