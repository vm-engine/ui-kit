<?php

declare(strict_types=1);

use Illuminate\View\ViewException;

it('wires native wire:sort with a shared group and column ids', function (): void {
    $html = render(<<<'BLADE'
        <x-ui::kanban sort-method="moveItem" group="tasks">
            <x-ui::kanban.column id="draft" label="Draft" color="blue" :count="1">
                <x-ui::kanban.card id="7">Write spec</x-ui::kanban.card>
            </x-ui::kanban.column>
        </x-ui::kanban>
        BLADE);

    expect($html)->toContain('wire:sort="moveItem"')
        ->toContain('wire:sort:group="tasks"')
        ->toContain('wire:sort:group-id="draft"')
        ->toContain('wire:sort:item="7"')
        ->toContain('wire:sort:handle')
        ->toContain('bg-blue-500')
        ->toContain('Write spec');
});

it('drops an unsafe card id', function (): void {
    expect(render('<x-ui::kanban.card id="7\');alert(1)//">x</x-ui::kanban.card>'))->not->toContain('wire:sort:item');
});

it('throws on an unsafe sort method', function (): void {
    render('<x-ui::kanban sort-method="move(); x()"> </x-ui::kanban>');
})->throws(ViewException::class);

it('shows the translated empty text in an empty column', function (): void {
    expect(render('<x-ui::kanban.column id="done" label="Done"></x-ui::kanban.column>'))->toContain(__('ui-kit::ui.no_items'));
});
