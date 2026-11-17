<?php

declare(strict_types=1);

use Illuminate\View\ViewException;
use VmEngine\UiKit\View\Components\Rte;

it('renders an ignored wrapper with a labelled textarea', function (): void {
    $html = decoded('<x-ui::rte wire:model="form.body" />');

    expect($html)->toContain('uiRte(')
        ->toContain('wire:ignore')
        ->toContain('<textarea')
        ->toContain('id="rte-form-body"')
        ->not->toContain('wire:model');
});

it('passes the bound field, height, buttons and extension to Alpine', function (): void {
    $rte = new Rte(height: 400, buttons: ['bold', 'italic'], extension: 'file-manager', extensionConfig: ['disk' => 'public']);
    $rte->withAttributes(['wire:model' => 'form.body']);

    expect($rte->config())->toBe([
        'field' => 'form.body',
        'height' => 400,
        'buttons' => ['bold', 'italic'],
        'extension' => 'file-manager',
        'extensionConfig' => ['disk' => 'public'],
    ]);
});

it('drops an unsafe extension name', function (): void {
    $rte = new Rte(extension: 'a b;c');
    $rte->withAttributes(['wire:model' => 'body']);

    expect($rte->config())->not->toHaveKey('extension');
});

it('requires wire:model', function (): void {
    render('<x-ui::rte />');
})->throws(ViewException::class, 'wire:model');
