<?php

declare(strict_types=1);

it('entangles the colour and renders swatches as labelled buttons', function (): void {
    $html = decoded('<x-ui::color-picker wire:model="color" :swatches="[\'#ef4444\', \'#3b82f6\']" />');

    expect($html)->toContain("uiColorPicker(\$wire.entangle('color'))")
        ->toContain('aria-label="#ef4444"')
        ->toContain('aria-label="#3b82f6"')
        ->toContain('wire:ignore')
        ->not->toContain('wire:model');
});

it('drops swatches that are not #rrggbb', function (): void {
    expect(render('<x-ui::color-picker :swatches="[\'red\', \'#12345g\', \'#00FF00\']" />'))
        ->toContain('#00ff00')
        ->not->toContain('#12345g')
        ->not->toContain('aria-label="red"');
});

it('always offers the custom picker when no swatches are valid', function (): void {
    expect(render('<x-ui::color-picker :allow-custom="false" />'))->toContain('type="color"');
});
