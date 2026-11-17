<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * Horizontal kanban board on Livewire 4's native wire:sort. Dropping a card calls
 * the host's sort-method($itemId, $position, $columnId). Columns read sortMethod
 * and group with @aware.
 */
final class Kanban extends Component
{
    use SanitizesWireArguments;

    public ?string $sortMethod;

    public string $group;

    public function __construct(?string $sortMethod = null, string $group = 'kanban')
    {
        $this->sortMethod = $this->wireMethod($sortMethod);
        $this->group = $this->safeKey($group) ?? 'kanban';
    }

    public function render(): View
    {
        return view('ui-kit::components.kanban');
    }
}
