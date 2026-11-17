// <x-ui::split-pane>: aside width (drag or arrow keys, remembered in localStorage
// per storage-key) and the mobile aside toggle. Measurements use $root: inside a
// handler $el is the separator, not the pane.
{
    const MIN_ASIDE = 160;
    const MIN_MAIN = 240;

    const register = () => {
        Alpine.data('uiSplitPane', (storageKey, initialWidth) => ({
            width: initialWidth,
            asideOpen: false,

            init() {
                if (!storageKey) return;
                try {
                    const saved = window.localStorage.getItem('ui-split:' + storageKey);
                    if (saved) this.width = saved;
                } catch (e) {
                    // Storage blocked (private mode, sandbox): keep the default width.
                }
            },

            gridStyle() {
                return '--ui-split-aside: ' + this.width;
            },

            toggleAside() {
                this.asideOpen = !this.asideOpen;
            },

            clamp(px) {
                const total = this.$root.getBoundingClientRect().width;
                return Math.min(Math.max(px, MIN_ASIDE), total - MIN_MAIN);
            },

            startDrag() {
                const move = (event) => {
                    const rect = this.$root.getBoundingClientRect();
                    this.width = this.clamp(event.clientX - rect.left) + 'px';
                };
                const stop = () => {
                    window.removeEventListener('pointermove', move);
                    window.removeEventListener('pointerup', stop);
                    this.persist();
                };
                window.addEventListener('pointermove', move);
                window.addEventListener('pointerup', stop);
            },

            nudge(delta) {
                const aside = this.$root.querySelector(':scope > aside');
                this.width = this.clamp(aside.getBoundingClientRect().width + delta) + 'px';
                this.persist();
            },

            persist() {
                if (!storageKey) return;
                try {
                    window.localStorage.setItem('ui-split:' + storageKey, this.width);
                } catch (e) {
                    // Storage blocked: the width just isn't remembered.
                }
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
