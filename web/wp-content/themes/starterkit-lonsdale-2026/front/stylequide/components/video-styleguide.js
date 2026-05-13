import Video from "../../../assets/components/video/video.js";

export default (root) => {
    if (!(root instanceof Element)) return null;
    const instances = Array.from(root.querySelectorAll(".video")).map((el) => new Video(el));

    return () => {
        instances.forEach((inst) => {
            try { inst?.destroy?.(); } catch (_) { /* noop */ }
        });
    };
};
