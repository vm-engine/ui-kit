// <x-ui::color-picker>: "#rrggbb" value shared by the swatches, the native picker
// and the hex field (entangled with wire:model when bound).
{
    const HEX = /^#[0-9a-fA-F]{6}$/;

    const register = () => {
        Alpine.data('uiColorPicker', (initialValue) => ({
            value: initialValue,

            isValidHex() {
                return HEX.test(this.value || '');
            },

            nativeValue() {
                return this.isValidHex() ? this.value.toLowerCase() : '#000000';
            },

            fromNative(event) {
                this.value = event.target.value;
            },

            pick(color) {
                this.value = color;
            },

            isSelected(color) {
                return (this.value || '').toLowerCase() === color.toLowerCase();
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
