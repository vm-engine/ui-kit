<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components\Kanban;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * A kanban card (<x-ui::kanban.card>). Its id is passed to the board's sort-method;
 * wrap buttons/links inside the card in wire:sort:ignore.
 */
final class Card extends Component
{
    use SanitizesWireArguments;

    public ?string $id;

    public function __construct(int|string $id)
    {
        $this->id = $this->safeKey($id);
    }

    public function render(): View
    {
        return view('ui-kit::components.kanban.card');
    }
}
