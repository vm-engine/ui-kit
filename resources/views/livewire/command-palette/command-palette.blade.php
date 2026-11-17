<div>
    @if ($this->enabled)
        @assets
            <script
                src="{{ \VmEngine\UiKit\Http\Controllers\AssetController::url('js/command-palette.js') }}"
                nonce="{{ \Illuminate\Support\Facades\Vite::cspNonce() }}"
            ></script>
        @endassets

        <flux:button
            variant="ghost"
            size="sm"
            icon="magnifying-glass"
            aria-label="{{ __('ui-kit::ui.search') }}"
            aria-keyshortcuts="Control+K Meta+K"
            x-data
            x-on:click="$flux.modal('ui-command-palette').show()"
            x-on:keydown.ctrl.k.window.prevent="$flux.modal('ui-command-palette').show()"
            x-on:keydown.meta.k.window.prevent="$flux.modal('ui-command-palette').show()"
        />
                {{-- Not @teleport-ed: Livewire swaps teleported content on every re-render, which
             replaces the focused search input and loses the keyboard focus. Place the palette
             in a container that is always rendered (a <dialog> can't show inside display:none). --}}
        <flux:modal name="ui-command-palette" class="w-full md:w-[36rem]">
                    <div
                        x-data="uiCommandPalette"
                        x-on:keydown.arrow-down.prevent="next()"
                        x-on:keydown.arrow-up.prevent="prev()"
                        x-on:keydown.enter.prevent="openActive()"
                        class="space-y-3"
                    >
                        <flux:input
                            icon="magnifying-glass"
                            wire:model.live.debounce.250ms="term"
                            x-ref="input"
                            x-on:input="reset()"
                            placeholder="{{ __('ui-kit::ui.search_placeholder') }}"
                            autocomplete="off"
                            role="combobox"
                            aria-expanded="true"
                            aria-controls="ui-command-palette-results"
                            aria-autocomplete="list"
                            aria-label="{{ __('ui-kit::ui.search') }}"
                            x-bind:aria-activedescendant="activeId()"
                        />

                        <div wire:loading.delay wire:target="term" class="{{ \VmEngine\UiKit\Support\Styles::MUTED }}">{{ __('ui-kit::ui.searching') }}</div>

                        <div wire:loading.remove wire:target="term">
                            @if (! $this->hasTerm())
                                <p class="{{ \VmEngine\UiKit\Support\Styles::MUTED }}">{{ __('ui-kit::ui.search_hint') }}</p>
                            @elseif ($this->groups === [])
                                <p class="{{ \VmEngine\UiKit\Support\Styles::MUTED }}">{{ __('ui-kit::ui.no_results') }}</p>
                            @else
                                <div id="ui-command-palette-results" role="listbox" class="max-h-96 space-y-3 overflow-y-auto">
                                    @foreach ($this->groups as $group => $results)
                                        <div role="group" aria-label="{{ $group }}">
                                            <div class="px-2 pb-1 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $group }}</div>
                                            @foreach ($results as $result)
                                                <a
                                                    href="{{ $result->url }}"
                                                    wire:navigate
                                                    role="option"
                                                    data-ui-palette-option
                                                    x-on:click="$flux.modal('ui-command-palette').close()"
                                                    x-bind:aria-selected="isActive($el) ? 'true' : 'false'"
                                                    x-bind:data-active="isActive($el) ? 'true' : null"
                                                    x-on:mouseenter="activate($el)"
                                                    class="{{ \VmEngine\UiKit\Support\Styles::OPTION }}"
                                                >
                                                    <flux:icon :name="$result->icon ?? 'arrow-right'" variant="mini" class="size-4 shrink-0 text-zinc-400" />
                                                    <span class="min-w-0 flex-1">
                                                        <span class="block truncate">{{ $result->title }}</span>
                                                        @if ($result->description)
                                                            <span class="block truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $result->description }}</span>
                                                        @endif
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </flux:modal>
    @endif
</div>
