<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * Accessible tabs (free Flux has none). Client-side only, or synced to a
 * Livewire property with wire:model (add #[Url] to keep the tab in the URL).
 * Without wire:model the first tab is selected.
 */
final class Tabs extends Component
{
    use BindsWireModel;

    public function render(): View
    {
        return view('ui-kit::components.tabs');
    }
}
