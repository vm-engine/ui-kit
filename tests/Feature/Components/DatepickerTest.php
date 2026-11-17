<?php

declare(strict_types=1);

use Illuminate\View\ViewException;

it('entangles the value and passes the mode and options', function (): void {
    $html = decoded('<x-ui::datepicker wire:model.live="expiresAt" :options="[\'minDate\' => \'today\']" />');

    expect($html)->toContain("uiDatepicker(\$wire.entangle('expiresAt').live")
        ->toContain(js(['allowInput' => true, 'disableMobile' => true, 'minDate' => 'today']))
        ->toContain(", 'date')")
        ->toContain('wire:ignore')
        ->not->toContain('type="date"')
        ->not->toContain('wire:model');
});

it('works unbound in a plain form via name', function (): void {
    expect(decoded('<x-ui::datepicker name="starts_on" />'))->toContain('uiDatepicker(null')->toContain('name="starts_on"');
});

it('supports month mode', function (): void {
    expect(decoded('<x-ui::datepicker mode="month" wire:model="period" />'))->toContain(", 'month')");
});

it('rejects other modes', function (): void {
    render('<x-ui::datepicker mode="week" />');
})->throws(ViewException::class, 'mode');

it('leaks no Blade comment text into the page', function (): void {
    expect(render('<x-ui::datepicker mode="month" wire:model="period" />'))->not->toContain('--}}');
});
