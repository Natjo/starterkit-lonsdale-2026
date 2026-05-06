
import Lenis from "./modules/lenis/lenis.mjs";

const appjsEl = document.getElementById("appjs");

const lenis = new Lenis({ smoothWheel: true });

let rafId = null;
const runRaf = (time) => {
    lenis.raf(time);
    rafId = requestAnimationFrame(runRaf);
};
rafId = requestAnimationFrame(runRaf);

//history.scrollRestoration = 'manual';

// Observe modules and set version if not local/preprod
const versionQ = appjsEl?.dataset.version ? `?v=${appjsEl.dataset.version}` : "";

const loadModule = (name) => {
    const rel = moduleMap[name];
    if (!rel) {
        console.warn("[app] Unknown module:", name);
        return Promise.resolve(null);
    }
    return import(rel + versionQ);
};

const visibleModules = [];
const eagerModules = [];

document.querySelectorAll('[data-module]').forEach(el => {
    const name = el.dataset.module.replace(/^\/+|\/+$/g, "");
    if (el.dataset.context === "@visible true") {
        visibleModules.push({ el, name });
    } else {
        eagerModules.push({ el, name });
    }
});

// Lazy-load visible modules via a single shared IntersectionObserver
if (visibleModules.length) {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const { el, name } = entry.target._moduleInfo;  
            loadModule(name).then(e => e?.default?.(el));
            io.unobserve(entry.target);
        });
    });
    visibleModules.forEach(({ el, name }) => {
        el._moduleInfo = { el, name };
        io.observe(el);
    });
}

// Defer eager modules to avoid blocking the main thread at parse time
const scheduleEager = (list) => {
    const next = list.shift();
    if (!next) return;
    loadModule(next.name).then(e => e?.default?.(next.el));
    if (list.length) requestIdleCallback(() => scheduleEager(list), { timeout: 300 });
};
if (eagerModules.length) {
    requestIdleCallback(() => scheduleEager(eagerModules), { timeout: 300 });
}
