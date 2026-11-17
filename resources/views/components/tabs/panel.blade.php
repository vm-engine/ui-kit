@props(['name'])

<div
    role="tabpanel"
    tabindex="0"
    x-bind:id="$id('ui-panel', @js($name))"
    x-bind:aria-labelledby="$id('ui-tab', @js($name))"
    x-show="isSelected(@js($name))"
    x-cloak
    {{ $attributes->class('pt-6 focus-visible:outline-none') }}
>
    {{ $slot }}
</div>
