// <x-ui::datepicker>: flatpickr bound to a Livewire property.
// mode 'month' uses the monthSelect plugin and submits Y-m.
{
    const register = () => {
        Alpine.data('uiDatepicker', (initialValue, flatpickrOptions, mode) => ({
            flatpickrInstance: null,
            value: initialValue,

            init() {
                // Month defaults go first so a call-site dateFormat still wins.
                const config = Object.assign({}, mode === 'month' ? { dateFormat: 'Y-m' } : {}, flatpickrOptions, {
                    defaultDate: this.value || null,
                    onChange: (selectedDates, dateStr) => {
                        this.value = dateStr;
                    },
                });

                if (mode === 'month') {
                    // The plugin's format drives the input text, flatpickr's drives parsing.
                    config.plugins = [window.monthSelectPlugin({
                        shorthand: false,
                        dateFormat: config.dateFormat,
                        altFormat: config.dateFormat,
                    })];
                }

                this.flatpickrInstance = window.flatpickr(this.$el, config);

                this.$watch('value', (val) => {
                    if (this.flatpickrInstance && val !== this.flatpickrInstance.input.value) {
                        this.flatpickrInstance.setDate(val || null, false);
                    }
                });
            },

            destroy() {
                if (this.flatpickrInstance) {
                    this.flatpickrInstance.destroy();
                    this.flatpickrInstance = null;
                }
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
