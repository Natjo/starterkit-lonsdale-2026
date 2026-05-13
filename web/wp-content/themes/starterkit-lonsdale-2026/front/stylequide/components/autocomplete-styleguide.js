import Autocomplete from "../../../assets/components/autocomplete/autocomplete.js";

export default (root) => {
    const fields = root.querySelectorAll(".autocomplete-field");
    const instances = Array.from(fields).map((el) => new Autocomplete(el));

    return () => {
        instances.forEach((inst) => {
            try { inst?.destroy?.(); } catch (_) { /* noop */ }
        });
    };
};
