// <x-ui::file-drop>: dropped files go onto the native input, whose wire:model
// (or form submit) takes it from there.
{
    const register = () => {
        Alpine.data('uiFileDrop', () => ({
            isDragging: false,

            handleDrop(event) {
                this.isDragging = false;
                const input = this.$refs.input;
                const files = event.dataTransfer.files;
                if (!input || !files || files.length === 0) return;
                input.files = files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
