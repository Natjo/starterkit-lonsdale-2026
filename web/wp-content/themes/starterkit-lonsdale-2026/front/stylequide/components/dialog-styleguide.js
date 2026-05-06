import Dialog from "../../../assets/components/dialog/dialog.js";

export default (root) => {
    if (!(root instanceof Element)) return null;

    const instances = [];

    root.querySelectorAll("dialog.dialog").forEach((dialogEl) => {
        try {
            const inst = new Dialog(dialogEl);
            if (inst) instances.push(inst);
        } catch (_) { /* noop */ }
    });

    return () => {
        instances.forEach((inst) => {
            try { inst?.destroy?.(); } catch (_) { /* noop */ }
        });
    };
};

