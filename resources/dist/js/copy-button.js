// <x-ui::copy-button>: copies a fixed text, then shows "Copied"/"Copy failed" for 2s.
{
    const register = () => {
        Alpine.data('uiCopyButton', (text, labels) => ({
            copied: false,
            failed: false,
            timer: null,

            async copy() {
                const ok = await window.uiClipboard.write(text);
                this.copied = ok;
                this.failed = !ok;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => {
                    this.copied = false;
                    this.failed = false;
                }, 2000);
            },

            isIdle() {
                return !this.copied && !this.failed;
            },

            buttonText() {
                if (this.copied) return labels.copied;
                if (this.failed) return labels.failed;
                return labels.label;
            },

            announcement() {
                if (this.copied) return labels.copied;
                if (this.failed) return labels.failed;
                return '';
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
