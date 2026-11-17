// <livewire:ui::command-palette>: keyboard navigation over the result links
// (↑/↓ move, Enter opens via wire:navigate) and focus on open.
{
    const register = () => {
        Alpine.data('uiCommandPalette', () => ({
            active: 0,

            init() {
                // Focus the search field whenever the palette modal opens.
                document.addEventListener('modal-show', (event) => {
                    if (event.detail && event.detail.name === 'ui-command-palette') {
                        this.$nextTick(() => {
                            const input = this.$root.querySelector('input');
                            input && input.focus();
                        });
                    }
                });
            },

            options() {
                return Array.from(this.$root.querySelectorAll('[data-ui-palette-option]'));
            },

            reset() {
                this.active = 0;
            },

            next() {
                const count = this.options().length;
                if (count > 0) this.active = (this.active + 1) % count;
                this.scrollActive();
            },

            prev() {
                const count = this.options().length;
                if (count > 0) this.active = (this.active - 1 + count) % count;
                this.scrollActive();
            },

            activate(el) {
                this.active = this.options().indexOf(el);
            },

            isActive(el) {
                return this.options().indexOf(el) === this.active;
            },

            activeId() {
                const el = this.options()[this.active];
                if (!el) return null;
                if (!el.id) el.id = 'ui-palette-opt-' + this.active;
                return el.id;
            },

            scrollActive() {
                const el = this.options()[this.active];
                el && el.scrollIntoView({ block: 'nearest' });
            },

            openActive() {
                const el = this.options()[this.active];
                el && el.click();
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
