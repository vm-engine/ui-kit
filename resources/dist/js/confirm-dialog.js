// <x-ui::confirm-dialog>: one Flux modal per page, opened by <x-ui::confirm-button>
// through the `ui-confirm` window event. Confirm calls the method on the Livewire
// component the button belongs to.
{
    const register = () => {
        Alpine.data('uiConfirmDialog', () => ({
            title: '',
            message: '',
            confirmText: '',
            color: 'red',
            method: null,
            params: [],
            component: null,

            open(detail) {
                this.title = detail.title || '';
                this.message = detail.message || '';
                this.confirmText = detail.confirmText || '';
                this.color = detail.color || 'red';
                this.method = detail.method || null;
                this.params = Array.isArray(detail.params) ? detail.params : [];
                this.component = detail.component || null;
                this.$flux.modal('ui-confirm-dialog').show();
            },

            isDanger() {
                return this.color === 'red';
            },

            confirm() {
                const component = this.component ? Livewire.find(this.component) : null;
                if (component && this.method) {
                    component[this.method](...this.params);
                }
                this.$flux.modal('ui-confirm-dialog').close();
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
