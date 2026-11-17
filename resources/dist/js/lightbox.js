// <x-ui::lightbox>: open/close, focus moved into the dialog and back to the opener.
// The close button is the only focusable element, so Tab keeps focus on it.
{
    const register = () => {
        Alpine.data('uiLightbox', () => ({
            show: false,
            url: '',
            alt: '',
            opener: null,

            open(detail) {
                this.url = (detail && detail.url) || '';
                this.alt = (detail && detail.alt) || '';
                this.opener = document.activeElement;
                this.show = true;
                this.$nextTick(() => this.$refs.close.focus());
            },

            close() {
                if (!this.show) return;
                this.show = false;
                if (this.opener && typeof this.opener.focus === 'function') {
                    this.opener.focus();
                }
                this.opener = null;
            },

            trap(event) {
                event.preventDefault();
                this.$refs.close.focus();
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
