{{--
    Kanban: native wire:sort board. sort-method($itemId, $position, $columnId).

    <x-ui::kanban sort-method="moveItem">
        <x-ui::kanban.column id="draft" label="Draft" color="zinc" :count="count($draft)">
            @foreach ($draft as $item)
                <x-ui::kanban.card :id="$item->id" wire:key="card-{{ $item->id }}">{{ $item->name }}</x-ui::kanban.card>
            @endforeach
        </x-ui::kanban.column>
    </x-ui::kanban>
--}}
<div {{ $attributes->class('flex w-full items-start gap-4 overflow-x-auto pb-2 [contain:inline-size]') }}>
    {{ $slot }}
</div>
