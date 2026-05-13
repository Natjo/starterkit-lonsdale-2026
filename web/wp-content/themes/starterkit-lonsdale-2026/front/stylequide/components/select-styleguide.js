import Select from "../../../assets/components/select/select.js";

export default (root) => {
    if (!(root instanceof Element)) return null;

    const instances = [];

    root.querySelectorAll(".select").forEach((el) => {
        try {
            const inst = new Select(el);
            if (inst) instances.push(inst);
        } catch (_) { /* noop */ }
    });

    return () => {
        instances.forEach((inst) => {
            try { inst?.destroy?.(); } catch (_) { /* noop */ }
        });
    };
};
