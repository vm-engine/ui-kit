// <x-ui::drag-n-drop>: checks type, size and image dimensions in the browser, then
// uploads with $wire.upload() (bound) or keeps the file on the form input (unbound).
{
    const register = () => {
        Alpine.data('uiDragNDrop', (config) => ({
            fileName: '',
            fileSize: '',
            previewUrl: null,
            isDragging: false,
            error: null,
            uploading: false,
            progress: 0,

            init() {
                if (config.wireModel) {
                    // A server-side reset of the property clears the preview.
                    this.$wire.$watch(config.wireModel, (value) => {
                        if ((value === null || value === '') && !this.uploading) {
                            this.resetInfo();
                        }
                    });
                }
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 B';
                const units = ['B', 'KB', 'MB', 'GB'];
                const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
                return Math.round((bytes / Math.pow(1024, i)) * 100) / 100 + ' ' + units[i];
            },

            resetInfo() {
                this.fileName = '';
                this.fileSize = '';
                this.previewUrl = null;
                this.progress = 0;
            },

            typeAllowed(file) {
                if (!config.accept) return true;
                return config.accept.split(',').map((t) => t.trim()).some((type) =>
                    type.endsWith('/*') ? file.type.startsWith(type.slice(0, -1)) : file.type === type
                );
            },

            imageSize(file) {
                return new Promise((resolve) => {
                    const url = URL.createObjectURL(file);
                    const img = new Image();
                    img.onload = () => {
                        resolve({ width: img.naturalWidth, height: img.naturalHeight });
                        URL.revokeObjectURL(url);
                    };
                    img.onerror = () => {
                        resolve(null);
                        URL.revokeObjectURL(url);
                    };
                    img.src = url;
                });
            },

            async validate(file) {
                if (!this.typeAllowed(file)) return config.messages.invalidType;
                if (config.maxSize && file.size / 1024 > config.maxSize) return config.messages.fileTooLarge;
                if ((config.maxWidth || config.maxHeight) && file.type.startsWith('image/')) {
                    const size = await this.imageSize(file);
                    if (size && ((config.maxWidth && size.width > config.maxWidth) || (config.maxHeight && size.height > config.maxHeight))) {
                        return config.messages.imageTooLarge;
                    }
                }
                return null;
            },

            onDrop(event) {
                this.isDragging = false;
                this.pick(event.dataTransfer.files, true);
            },

            async pick(files, dropped = false) {
                if (!files || files.length === 0) return;
                const file = files[0];

                this.error = await this.validate(file);
                if (this.error) {
                    this.$refs.fileInput.value = '';
                    return;
                }

                this.fileName = file.name;
                this.fileSize = this.formatFileSize(file.size);
                this.previewUrl = null;

                if (config.preview && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }

                if (config.wireModel) {
                    this.uploading = true;
                    this.progress = 0;
                    this.$wire.upload(
                        config.wireModel,
                        file,
                        () => {
                            this.uploading = false;
                            this.progress = 100;
                        },
                        () => {
                            this.uploading = false;
                            this.error = config.messages.uploadFailed;
                            this.resetInfo();
                        },
                        (event) => {
                            this.progress = event.detail.progress;
                        },
                    );
                } else if (dropped) {
                    // Keep the dropped file on the input so a normal form submit sends it.
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    this.$refs.fileInput.files = transfer.files;
                }
            },

            removeFile() {
                this.resetInfo();
                this.error = null;
                this.$refs.fileInput.value = '';
                if (config.wireModel) {
                    this.$wire.set(config.wireModel, null);
                }
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
