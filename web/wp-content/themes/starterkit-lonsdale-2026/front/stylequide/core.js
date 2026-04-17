const _coreScriptUrl = document.currentScript?.src || '';
const _coreBaseUrl = _coreScriptUrl.replace(/core\.js(?:\?.*)?$/, '');
const _iconsUrl = `${_coreBaseUrl}img/icons.svg`;

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
        const content = this.innerHTML;
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
        details.innerHTML = `
            <ui-summary level="h3">${label}${summaryName}${summaryTags}</ui-summary>
            ${optionsHtml}
            ${full ? content : `<div class="${wrapperClass}">${content}</div>`}
        `;
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
        const rawCode = (this.textContent || "").trim();
        if (!rawCode) {
            this.replaceWith(document.createTextNode(""));
            return;
        }

        const syntax = (this.getAttribute("syntax") || "php").trim().toLowerCase();
        const copyMode = (this.getAttribute("copy") || "php-short-tag").trim().toLowerCase();
        const copyValue = copyMode === "raw" ? rawCode : `<?= ${rawCode} ?>`;

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
            const response = await fetch(`${_coreBaseUrl}strate-options.json`);
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
        const builderTarget = builderRoot.querySelector("[data-btn-builder]") || builderRoot;
        const outputWrap =
            builderTarget.querySelector?.(".sg-code-wrap") ||
            builderTarget.closest?.(".sg-code-wrap") ||
            builderRoot.querySelector(".sg-code-wrap");
        const outputCode = outputWrap?.querySelector("code.sg-code-inline");
        const outputCopyBtn = outputWrap?.querySelector(".sg-copy-btn");
        if (!outputCode || !outputCopyBtn) return;
        const match = (outputCode.textContent || "").match(/component:([a-z_]\w*)\s*\(/i);
        const componentName = (match?.[1] || "").toLowerCase();
        if (!componentName) return;

        const preview = builderRoot.querySelector(".sg-render");
        const ajaxUrl = preview?.dataset.ajaxUrl || "";
        const getInput = (name) => builderRoot.querySelector(`[data-param="${name}"]`);
        const argsInput = getInput("args");
        const classesSelectInput = builderRoot.querySelector('select[data-param="classes"]');
        const classesTextInput = builderRoot.querySelector('input[data-param="classes"]');
        const iconInput = getInput("icon");
        const attributesInput = getInput("attributes");
        const hxInput = getInput("hx");
        const itemsInput = getInput("items");
        const cardInput = getInput("card");
        const toPhpString = (value = "") => `'${value.replaceAll("\\", "\\\\").replaceAll("'", "\\'")}'`;
        const normalizeParam = (value = "", fallback = "") => {
            const v = value.trim();
            if (!v) return fallback;
            // Keep advanced/php expressions as-is.
            if (/^[\[$]/.test(v) || /^\$/.test(v) || /=>/.test(v) || /::/.test(v) || /\w+\s*\(/.test(v)) return v;
            if (/^".*"$/.test(v) || /^'.*'$/.test(v)) return toPhpString(v.slice(1, -1));
            return toPhpString(v);
        };
        let previewTimer = null;
        let previewRequestId = 0;

        const getMergedClasses = () => {
            return [classesSelectInput?.value || "", classesTextInput?.value || ""]
                .map((v) => v.trim())
                .filter(Boolean)
                .join(" ");
        };

        const updatePreview = () => {
            if (!preview || !ajaxUrl) return;
            if (previewTimer) clearTimeout(previewTimer);
            previewTimer = setTimeout(async () => {
                const requestId = ++previewRequestId;
                const body = new URLSearchParams();
                body.set("classes", getMergedClasses());

                if (componentName === "btn") {
                    body.set("action", "sg_preview_btn");
                    body.set("args", argsInput?.value || "");
                    body.set("icon", iconInput?.value || "");
                    body.set("attributes", attributesInput?.value || "");
                } else if (componentName === "title") {
                    body.set("action", "sg_preview_title");
                    body.set("args", argsInput?.value || "");
                    body.set("hx", hxInput?.value || "");
                } else if (componentName === "list") {
                    body.set("action", "sg_preview_list");
                    body.set("items", itemsInput?.value || "");
                    body.set("card", cardInput?.value || "");
                } else {
                    return;
                }

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
                        preview.innerHTML = json.data.html;
                    }
                } catch (_) {
                    // Keep previous preview output on request failure.
                }
            }, 150);
        };

        const buildCall = () => {
            const argsValue = normalizeParam((argsInput?.value || "").trim(), "$args");
            const classesValue = normalizeParam(getMergedClasses());
            let signature = "";

            if (componentName === "btn") {
                const iconValue = normalizeParam((iconInput?.value || "").trim());
                const attributesValue = normalizeParam((attributesInput?.value || "").trim());
                const params = [argsValue];
                if (classesValue) params.push(classesValue);
                if (iconValue) params.push(iconValue);
                if (attributesValue) params.push(attributesValue);
                signature = `component:btn(${params.join(", ")})`;
            } else if (componentName === "title") {
                const hxRaw = (hxInput?.value || "").trim();
                const hxValue = /^\d+$/.test(hxRaw) ? hxRaw : "";
                const params = [argsValue];
                if (hxValue) params.push(hxValue);
                if (classesValue) {
                    if (!hxValue) params.push("2");
                    params.push(classesValue);
                }
                signature = `component:title(${params.join(", ")})`;
            } else if (componentName === "list") {
                const itemsValue = normalizeParam((itemsInput?.value || "").trim(), "$items");
                const cardValue = normalizeParam((cardInput?.value || "").trim());
                const params = [itemsValue];
                if (cardValue) params.push(cardValue);
                if (classesValue) {
                    if (!cardValue) params.push("'news'");
                    params.push(classesValue);
                }
                signature = `component:list(${params.join(", ")})`;
            } else {
                return;
            }

            outputCode.textContent = signature;
            outputCode.dataset.highlighted = "0";
            highlightPhpInlineCode(outputCode);
            outputCopyBtn.dataset.copy = `<?= ${signature} ?>`;
            updatePreview();
        };

        [argsInput, classesSelectInput, classesTextInput, iconInput, attributesInput, hxInput, itemsInput, cardInput].forEach((input) => {
            input?.addEventListener("input", buildCall);
            input?.addEventListener("change", buildCall);
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