<?php

declare(strict_types=1);

namespace VmEngine\UiKit\View\Components\Kanban;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use VmEngine\UiKit\Concerns\SanitizesWireArguments;

/**
 * A kanban column (<x-ui::kanban.column>): header with colour dot, label and count,
 * and a wire:sort list whose group-id is the column id.
 */
final class Column extends Component
{
    use SanitizesWireArguments;

    /** @var array<string, string> colour => dot class (Synapse names are aliases) */
    private const DOTS = [
        'zinc' => 'bg-zinc-400',
        'accent' => 'bg-accent',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'amber' => 'bg-amber-500',
        'red' => 'bg-red-500',
        'purple' => 'bg-purple-500',
    ];

    private const ALIASES = ['gray' => 'zinc', 'brand' => 'accent', 'success' => 'green', 'warning' => 'amber', 'danger' => 'red', 'info' => 'blue'];

    public ?string $id;

    public string $emptyText;

    public function __construct(
        int|string $id,
        public string $label = '',
        public string $color = 'zinc',
        public ?int $count = null,
        ?string $emptyText = null,
    ) {
        $this->id = $this->safeKey($id);
        $color = self::ALIASES[$color] ?? $color;
        $this->color = array_key_exists($color, self::DOTS) ? $color : 'zinc';
        $this->emptyText = $emptyText ?? (string) __('ui-kit::ui.no_items');
    }

    public function dotClass(): string
    {
        return self::DOTS[$this->color];
    }

    public function render(): View
    {
        return view('ui-kit::components.kanban.column');
    }
}
