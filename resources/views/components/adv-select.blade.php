{{--
    Adv-select: searchable single/multi select bound to wire:model.

    <x-ui::adv-select wire:model.live="roleIds" multiple :options="$roleOptions" />
    <x-ui::adv-select wire:model="userId" search-method="searchUsers" load-method="loadUsers" />

    Options: [['value' => 7, 'label' => 'Our Team', 'depth' => 1], …]. search-method($term)
    returns option rows; load-method($valueOrValues) returns the rows for preset values.
    Keyboard: ↓/Enter/Space open; ↑/↓ move; Enter selects (or creates); Backspace on an
    empty search removes the last chip; Escape closes.
--}}
@assets
    <script
        src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/adv-select.js') }}"
        nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
    ></script>
@endassets

<div
    wire:ignore
    x-data="{{ $alpineData() }}"
    x-id="['ui-adv-list']"
    x-on:click.outside.capture="open = false"
    {{ $attributes->whereDoesntStartWith('wire:model')->class('relative w-full') }}
>
    <div
        role="combobox"
        tabindex="{{ $disabled ? '-1' : '0' }}"
        aria-haspopup="listbox"
        aria-label="{{ $placeholder }}"
        x-bind:aria-expanded="open ? 'true' : 'false'"
        x-bind:aria-controls="$id('ui-adv-list')"
        x-bind:aria-disabled="disabled ? 'true' : 'false'"
        x-on:click="toggleOpen()"
        x-on:keydown.enter.prevent="focusInput()"
        x-on:keydown.space.prevent="focusInput()"
        x-on:keydown.arrow-down.prevent="focusInput()"
        class="{{ \VmEngine\UiKit\Support\Styles::FIELD }} flex min-h-10 cursor-pointer flex-wrap items-center gap-1 py-1.5 pe-16 aria-disabled:cursor-not-allowed aria-disabled:opacity-75"
    >
        <template x-if="selected.length === 0">
            <span class="text-zinc-400 dark:text-zinc-400">{{ $placeholder }}</span>
        </template>

        <template x-if="multiple">
            <template
                x-for="(item, index) in selected"
                :key="item[valueField]"
            >
                <span
                    class="inline-flex items-center gap-1 rounded-md bg-zinc-100 px-1.5 py-0.5 text-xs text-zinc-700 dark:bg-white/10 dark:text-zinc-200"
                >
                    <span x-text="item[labelField]"></span>
                    <button
                        type="button"
                        aria-label="{{ __('ui-kit::ui.remove') }}"
                        class="rounded text-zinc-400 hover:text-zinc-700 dark:hover:text-white"
                        x-bind:disabled="disabled"
                        x-on:click.stop="remove(index)"
                    >
                        <flux:icon
                            name="x-mark"
                            variant="micro"
                            class="size-3.5"
                        />
                    </button>
                </span>
            </template>
        </template>

        <template x-if="!multiple && selected.length">
            <span
                class="truncate text-zinc-800 dark:text-white"
                x-text="selected[0][labelField]"
            ></span>
        </template>

        <div class="absolute inset-y-0 end-2 flex items-center gap-1">
            <template x-if="!multiple && selected.length">
                <button
                    type="button"
                    aria-label="{{ __('ui-kit::ui.remove') }}"
                    class="rounded p-0.5 text-zinc-400 hover:text-zinc-700 dark:hover:text-white"
                    x-bind:disabled="disabled"
                    x-on:click.stop="clearSelection()"
                >
                    <flux:icon
                        name="x-mark"
                        variant="micro"
                        class="size-4"
                    />
                </button>
            </template>
            <flux:icon
                name="chevron-down"
                variant="micro"
                class="size-4 text-zinc-400 transition-transform"
                x-bind:class="open ? 'rotate-180' : ''"
            />
        </div>
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity
        class="{{ \VmEngine\UiKit\Support\Styles::POPOVER }} absolute z-50 mt-1 w-full"
    >
        <input
            type="text"
            x-ref="search"
            x-model="search"
            autocomplete="off"
            aria-label="{{ $searchLabel }}"
            aria-autocomplete="list"
            placeholder="{{ $searchLabel }}"
            x-bind:aria-controls="$id('ui-adv-list')"
            x-bind:aria-activedescendant="activeId()"
            x-bind:disabled="disabled"
            x-on:focus="fetch()"
            x-on:input.debounce.300ms="fetch()"
            x-on:keydown="handleKeydown($event)"
            class="{{ \VmEngine\UiKit\Support\Styles::INPUT }} mb-1 pe-3"
        >

        <ul
            role="listbox"
            x-bind:id="$id('ui-adv-list')"
            @if ($multiple) aria-multiselectable="true" @endif
            class="max-h-60 overflow-auto"
        >
            <template
                x-for="(item, index) in results"
                :key="item[valueField]"
            >
                <li
                    role="option"
                    x-bind:id="$id('ui-adv-list') + '-' + index"
                    x-bind:aria-selected="isSelected(item) ? 'true' : 'false'"
                    x-bind:data-active="index === active ? 'true' : null"
                    x-bind:style="optionIndent(item)"
                    x-on:mouseenter="active = index"
                    x-on:click="select(item)"
                    class="{{ \VmEngine\UiKit\Support\Styles::OPTION }} cursor-pointer"
                >
                    <span
                        class="flex-1"
                        x-text="item[labelField]"
                    ></span>
                    <template x-if="isSelected(item)">
                        <flux:icon
                            name="check"
                            variant="micro"
                            class="size-4 text-zinc-500 dark:text-zinc-300"
                        />
                    </template>
                </li>
            </template>

            <template x-if="canCreate()">
                <li
                    role="option"
                    aria-selected="false"
                    x-on:click="createNew()"
                    class="{{ \VmEngine\UiKit\Support\Styles::OPTION }} cursor-pointer"
                >
                    <flux:icon
                        name="plus"
                        variant="micro"
                        class="size-4"
                    />
                    <span>{{ __('ui-kit::ui.create') }} "<span x-text="search.trim()"></span>"</span>
                </li>
            </template>

            <template x-if="results.length === 0 && !canCreate()">
                <li class="px-2 py-1.5 text-sm text-zinc-500 dark:text-zinc-400">{{ __('ui-kit::ui.no_results') }}</li>
            </template>
        </ul>
    </div>
</div>
