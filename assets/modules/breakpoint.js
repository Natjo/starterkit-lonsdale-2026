/**
 * Breakpoint
 * Fires callbacks when viewport crosses a min-width media query.
 *
 * Usage:
 *   const bp = new Breakpoint(800);
 *   bp.above = () => { ... };
 *   bp.under = () => { ... };
 */
function Breakpoint(value) {
    this.above = () => {};
    this.under = () => {};

    const mql = window.matchMedia(`(min-width: ${value}px)`);
    const onChange = (e) => (e.matches ? this.above() : this.under());

    // Safari legacy support
    if (mql.addEventListener) mql.addEventListener("change", onChange);
    else mql.addListener(onChange);

    // Trigger initial state after callbacks are assigned
    const schedule =
        typeof queueMicrotask === "function"
            ? queueMicrotask
            : (fn) => Promise.resolve().then(fn);
    schedule(() => onChange(mql));
}

export default Breakpoint;