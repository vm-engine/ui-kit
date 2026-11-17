<?php

declare(strict_types=1);

use Illuminate\View\ViewException;

it('passes the bound property, options and flags to Alpine', function (): void {
    $options = [['value' => 1, 'label' => 'Admin'], ['value' => 2, 'label' => 'Team', 'depth' => 1]];
    $html = decoded('<x-ui::adv-select wire:model.live="roleIds" multiple :options="$options" search-method="searchRoles" />', ['options' => $options]);

    expect($html)->toContain('uiAdvSelect(')
        ->toContain("'roleIds'")
        ->toContain("'searchRoles'")
        ->toContain(js($options))
        ->not->toContain('wire:model');
});

it('is an accessible combobox with a listbox', function (): void {
    $html = render('<x-ui::adv-select wire:model="status" :options="[]" multiple />');

    expect($html)->toContain('role="combobox"')
        ->toContain('aria-haspopup="listbox"')
        ->toContain('role="listbox"')
        ->toContain('aria-multiselectable="true"')
        ->toContain('aria-label="'.__('ui-kit::ui.search').'"');
});

it('requires wire:model', function (): void {
    render('<x-ui::adv-select :options="[]" />');
})->throws(ViewException::class, 'wire:model');

it('throws on unsafe search or load methods', function (string $attribute): void {
    render('<x-ui::adv-select wire:model="x" '.$attribute.'="go(); alert(1)" />');
})->with(['search-method', 'load-method'])->throws(ViewException::class);
