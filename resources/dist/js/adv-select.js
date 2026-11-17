// <x-ui::adv-select>: searchable single/multi select bound to a Livewire property.
// Ported from vm-engine/webms, plus option keyboard navigation (arrows/Enter/Backspace),
// ARIA active-descendant support and following server-side changes of the property.
{
    const register = () => {
        Alpine.data('uiAdvSelect', (preload, multiple, searchMethod, loadMethod, wireModel, labelField,
            valueField, staticOptions, minSearchLength, isLive, allowCreate, autoLoad, disabled) => ({
            search: '',
            results: [],
            selected: preload,
            active: -1,
            multiple,
            open: false,
            searchMethod,
            loadMethod,
            wireModel,
            labelField,
            valueField,
            staticOptions,
            minSearchLength,
            isLive,
            allowCreate,
            autoLoad,
            disabled,

            async init() {
                if (this.autoLoad && this.selected.length === 0) {
                    await this.applyValue(this.$wire.get(this.wireModel));
                }

                if (this.selected.length > 0) {
                    this.syncWithWire();
                }

                // Follow server-side changes (a reset after save, a value set by an action).
                this.$wire.$watch(this.wireModel, (value) => {
                    if (!this.sameAsSelection(value)) {
                        this.applyValue(value);
                    }
                });

                // Keep the dropdown open while typing across Livewire round-trips.
                Livewire.interceptMessage(({ onSuccess }) => {
                    onSuccess(() => {
                        if (document.activeElement === this.$refs.search && this.search.length > 0) {
                            this.open = true;
                        }
                    });
                });
            },

            valuesOf(value) {
                if (value === null || value === undefined || value === '') return [];
                return Array.isArray(value) ? value : [value];
            },

            sameAsSelection(value) {
                const wanted = this.valuesOf(value).map(String);
                const current = this.selected.map((item) => String(item[this.valueField]));
                return wanted.length === current.length && wanted.every((v, i) => v === current[i]);
            },

            /** Turn a bound value (id or ids) into option rows: static options, load-method, or the bare id. */
            async applyValue(value) {
                const values = this.valuesOf(value);

                if (values.length === 0) {
                    this.selected = [];
                    return;
                }

                const known = [...this.staticOptions, ...this.selected, ...this.results];
                const found = values.map((val) => known.find((o) => o[this.valueField] == val)).filter(Boolean);

                if (found.length === values.length) {
                    this.selected = found;
                    return;
                }

                if (this.loadMethod) {
                    try {
                        const loaded = await this.$wire.call(this.loadMethod, this.multiple ? values : values[0]);
                        if (loaded) {
                            this.selected = Array.isArray(loaded) ? loaded : [loaded];
                            return;
                        }
                    } catch (e) {
                        console.error('[ui-kit] adv-select load error', e);
                    }
                }

                this.selected = values.map((val) => ({ [this.valueField]: val, [this.labelField]: String(val) }));
            },

            focusInput() {
                if (this.disabled) return;
                this.open = true;
                this.$nextTick(() => this.$refs.search && this.$refs.search.focus());
            },

            toggleOpen() {
                if (this.disabled) return;
                if (this.open) {
                    this.open = false;
                    this.$refs.search && this.$refs.search.blur();
                } else {
                    this.focusInput();
                }
            },

            async fetch() {
                const query = this.search.toLowerCase();
                const staticResults = this.staticOptions.filter((o) =>
                    String(o[this.labelField]).toLowerCase().includes(query)
                );

                if (this.searchMethod && query.length >= this.minSearchLength) {
                    try {
                        const res = await this.$wire.call(this.searchMethod, this.search);
                        const merged = [...staticResults];
                        for (const r of (Array.isArray(res) ? res : [])) {
                            if (!merged.find((o) => o[this.valueField] == r[this.valueField])) {
                                merged.push(r);
                            }
                        }
                        this.results = merged;
                    } catch (e) {
                        console.error('[ui-kit] adv-select search error', e);
                        this.results = staticResults;
                    }
                } else {
                    this.results = staticResults;
                }

                this.active = this.results.length > 0 ? 0 : -1;
            },

            /** Indents a tree option by its `depth` (0 = top level). */
            optionIndent(item) {
                const depth = Number(item.depth) || 0;
                return depth > 0 ? { paddingInlineStart: `${0.5 + depth}rem` } : {};
            },

            isSelected(item) {
                return this.selected.some((i) => i[this.valueField] == item[this.valueField]);
            },

            activeId() {
                return this.active >= 0 && this.active < this.results.length
                    ? this.$id('ui-adv-list') + '-' + this.active
                    : null;
            },

            canCreate() {
                return this.allowCreate && this.search.trim() !== '' && this.results.length === 0;
            },

            select(item) {
                if (this.multiple) {
                    if (!this.isSelected(item)) {
                        this.selected.push(item);
                    }
                } else {
                    this.selected = [item];
                    this.$refs.search.blur();
                }
                this.search = '';
                this.results = [];
                this.active = -1;
                this.open = false;
                this.syncWithWire();
            },

            createNew() {
                if (!this.canCreate()) return;
                const text = this.search.trim();
                const newItem = { [this.valueField]: text, [this.labelField]: text };
                this.staticOptions.push(newItem);
                this.select(newItem);
            },

            moveActive(step) {
                if (this.results.length === 0) return;
                this.active = (this.active + step + this.results.length) % this.results.length;
                this.$nextTick(() => {
                    const el = document.getElementById(this.activeId());
                    el && el.scrollIntoView({ block: 'nearest' });
                });
            },

            handleKeydown(e) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.moveActive(1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.moveActive(-1);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (this.active >= 0 && this.active < this.results.length) {
                        this.select(this.results[this.active]);
                    } else if (this.canCreate()) {
                        this.createNew();
                    }
                } else if (e.key === 'Backspace' && this.search === '' && this.multiple && this.selected.length > 0) {
                    this.remove(this.selected.length - 1);
                } else if (e.key === 'Escape') {
                    this.open = false;
                    this.$refs.search.blur();
                }
            },

            remove(index) {
                this.selected.splice(index, 1);
                this.syncWithWire();
            },

            clearSelection() {
                this.selected = [];
                this.syncWithWire();
                this.focusInput();
            },

            syncWithWire() {
                const ids = this.selected.map((i) => i[this.valueField]);

                if (!this._initialized) {
                    this._initialized = true;
                    if (ids.length === 0) return;
                }

                this.$wire.set(this.wireModel, this.multiple ? ids : (ids.length > 0 ? ids[0] : null), this.isLive);
            },
        }));
    };

    window.Alpine ? register() : document.addEventListener('alpine:init', register);
}
