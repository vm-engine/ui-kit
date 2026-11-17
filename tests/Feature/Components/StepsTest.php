<?php

declare(strict_types=1);

use Illuminate\View\ViewException;

it('makes completed steps buttons calling go-method and marks the current step', function (): void {
    $html = render('<x-ui::steps :steps="[\'Details\', \'Media\', \'Review\']" :current="2" go-method="goToStep" />');

    expect($html)->toContain('wire:click="goToStep(1)"')
        ->not->toContain('wire:click="goToStep(2)"')
        ->not->toContain('wire:click="goToStep(3)"')
        ->toContain('aria-current="step"')
        ->toContain('aria-label="'.__('ui-kit::ui.progress').'"');
});

it('renders plain steps without a go-method', function (): void {
    expect(render('<x-ui::steps :steps="[\'A\', \'B\']" :current="2" />'))->not->toContain('wire:click');
});

it('throws on an unsafe go-method', function (): void {
    render('<x-ui::steps :steps="[\'A\']" go-method="go(); x()" />');
})->throws(ViewException::class);
