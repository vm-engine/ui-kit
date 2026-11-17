// <x-ui::tabs>: selected tab state (optionally entangled with wire:model) and
// WAI-ARIA tab keyboard support (arrows, Home, End; automatic activation).
{
    const register = () => {
        Alpine.data('uiTabs', (selected) => ({
            selected,

            init() {
                if (!this.selected) {
                    const first = this.tabs()[0];
                    this.selected = first ? first.dataset.uiTab : null;
                }
            },

            tabs() {
                return Array.from(this.$refs.tablist.querySelectorAll('[role="tab"]'));
            },

            isSelected(name) {
                return this.selected === name;
            },

            select(name) {
                this.selected = name;
            },

            current() {
                return this.tabs().findIndex((tab) => tab.dataset.uiTab === this.selected);
            },

            focusAt(index) {
                const tabs = this.tabs();
                if (tabs.length === 0) return;
                const tab = tabs[(index + tabs.length) % tabs.length];
                this.select(tab.dataset.uiTab);
                tab.focus();
            },

            focusNext() {
                this.focusAt(this.current() + 1);
            },

            focusPrev() {
                this.focusAt(this.current() - 1);
            },

            focusFirst() {
                this.focusAt(0);
            },

            focusLast() {
                this.focusAt(-1);
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
