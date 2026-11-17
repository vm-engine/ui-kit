@props(['name', 'icon' => null])

<button
    type="button"
    role="tab"
    data-ui-tab="{{ $name }}"
    x-bind:id="$id('ui-tab', @js($name))"
    x-bind:aria-controls="$id('ui-panel', @js($name))"
    x-bind:aria-selected="isSelected(@js($name))"
    x-bind:tabindex="isSelected(@js($name)) ? 0 : -1"
    x-bind:class="isSelected(@js($name)) ? 'border-accent text-zinc-800 dark:text-white' :
        'border-transparent text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white'"
    x-on:click="select(@js($name))"
    {{ $attributes->class('inline-flex items-center gap-2 whitespace-nowrap border-b-2 px-1 py-2.5 text-sm font-medium focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent') }}
>
    @if ($icon)
        <flux:icon
            :name="$icon"
            variant="mini"
            class="size-4"
        />
    @endif
    {{ $slot }}
</button>
