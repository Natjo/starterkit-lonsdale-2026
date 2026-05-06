import Slider from "../../../assets/components/slider/slider.js";

export default (root) => {
    if (!(root instanceof Element)) return null;
    let cancelled = false;
    const instances = [];

    const run = () => {
        if (cancelled) return;

        root.querySelectorAll(".slider").forEach((el) => {
            try {
                el.__sgSlider?.remove?.();
            } catch (_) { /* noop */ }

            const inst = new Slider(el);
            if (inst && typeof inst.add === "function") {
                inst.add();
                el.__sgSlider = inst;
                instances.push(inst);
            }
        });

        // CSS/images may still settle; trigger one extra resize pass.
        window.setTimeout(() => {
            try { window.dispatchEvent(new Event("resize")); } catch (_) { /* noop */ }
        }, 50);
    };

    requestAnimationFrame(run);

    return () => {
        cancelled = true;
        instances.forEach((inst) => {
            try { inst?.remove?.(); } catch (_) { /* noop */ }
        });
    };
};

