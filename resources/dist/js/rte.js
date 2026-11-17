// <x-ui::rte>: Jodit bound to a Livewire property with a deferred $wire.set, like
// wire:model. Jodit is the vendored global (window.Jodit); if it is missing the
// plain textarea stays editable and bound.
//
// Extensions add toolbar buttons and Jodit options:
//   window.uiRte.extend('name', (rte, config) => ({ buttons: [...], options: {...}, destroy() {} }))
// and the tag names one: <x-ui::rte wire:model="body" extension="name" :extension-config="[...]">.
{
    window.uiRte = window.uiRte || {
        extensions: {},
        extend(name, setup) {
            this.extensions[name] = setup;
        },
    };

    const DEFAULT_BUTTONS = [
        'undo', 'redo', '|',
        'paragraph', '|',
        'bold', 'italic', 'underline', 'strikethrough', '|',
        'left', 'center', 'right', '|',
        'ul', 'ol', '|',
        'link',
    ];

    const register = () => {
        Alpine.data('uiRte', (config) => ({
            editor: null,
            extension: null,

            init() {
                const textarea = this.$refs.textarea;
                textarea.value = this.$wire.get(config.field) || '';

                if (!window.Jodit) {
                    console.error('[ui-kit] Jodit failed to load, using plain textarea.');
                    textarea.addEventListener('input', (event) => this.$wire.set(config.field, event.target.value, false));
                    return;
                }

                const setup = config.extension ? window.uiRte.extensions[config.extension] : null;
                if (config.extension && !setup) {
                    console.warn(`[ui-kit] rte extension "${config.extension}" is not registered.`);
                }
                this.extension = setup ? setup(this, config.extensionConfig || {}) : null;
                const extra = this.extension && this.extension.buttons ? this.extension.buttons : [];

                this.editor = window.Jodit.make(textarea, Object.assign({
                    height: config.height,
                    toolbarAdaptive: false,
                    askBeforePasteHTML: false,
                    askBeforePasteFromWord: false,
                    defaultActionOnPaste: 'insert_clear_html',
                    // Follows the appearance at load; a later switch applies on the next page load.
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
                    buttons: [...(config.buttons || DEFAULT_BUTTONS), ...extra, '|', 'source'],
                }, (this.extension && this.extension.options) || {}));

                this.editor.events.on('change', (value) => this.$wire.set(config.field, value, false));

                // Follow server-side changes (a reset after save).
                this.$wire.$watch(config.field, (value) => {
                    if (this.editor && (value || '') !== this.editor.value) {
                        this.editor.value = value || '';
                    }
                });
            },

            destroy() {
                if (this.extension && this.extension.destroy) {
                    this.extension.destroy();
                }
                if (this.editor) {
                    this.editor.destruct();
                    this.editor = null;
                }
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
