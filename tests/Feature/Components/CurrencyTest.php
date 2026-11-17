<?php

declare(strict_types=1);

it('entangles the raw value deferred and passes decimals and the sync delay', function (string $model, string $delay): void {
    $html = decoded('<x-ui::currency currency="Rp" raw :decimals="2" '.$model.'="amount" />');

    // Always deferred (no .live): the component commits once typing pauses.
    expect($html)->toContain("uiCurrency(\$wire.entangle('amount'), 2, ".$delay.')')
        ->toContain('Rp')
        ->not->toContain($model.'="amount"');
})->with([
    ['wire:model', 'null'],
    ['wire:model.live', '150'],
    ['wire:model.live.debounce', '250'],
    ['wire:model.live.debounce.500ms', '500'],
]);

it('passes wire:model straight through when not raw', function (): void {
    expect(decoded('<x-ui::currency wire:model="amount" />'))
        ->toContain('wire:model="amount"')
        ->toContain("\$money(\$input, ',', '.', 0)")
        ->not->toContain('uiCurrency(');
});

it('omits the prefix when currency is empty', function (): void {
    expect(render('<x-ui::currency currency="" wire:model="amount" />'))->not->toContain('data-ui-currency-prefix');
});
