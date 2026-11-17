<?php

declare(strict_types=1);

use Illuminate\View\ViewException;

it('renders a sticky bar with the save action, cancel link and hint', function (): void {
    $html = render('<x-ui::form-action-bar action="save" cancel-url="/back" hint="Unsaved changes" />');

    expect($html)->toContain('wire:click="save"')
        ->toContain('href="/back"')
        ->toContain('wire:navigate')
        ->toContain('sticky')
        ->toContain('Unsaved changes')
        ->toContain(__('ui-kit::ui.save'));
});

it('shows the required-field hint by default and none when hint is false', function (): void {
    expect(render('<x-ui::form-action-bar action="save" />'))->toContain(__('ui-kit::ui.required_field'))
        ->and(render('<x-ui::form-action-bar action="save" :hint="false" />'))->not->toContain(__('ui-kit::ui.required_field'));
});

it('rejects an unsafe or missing action name', function (string $action): void {
    render('<x-ui::form-action-bar action="'.$action.'" />');
})->with(['save(); alert(1)', ''])->throws(ViewException::class);

it('drops an unsafe cancel url', function (): void {
    expect(render('<x-ui::form-action-bar action="save" cancel-url="javascript:alert(1)" />'))
        ->not->toContain('javascript:')
        ->not->toContain(__('ui-kit::ui.cancel'));
});
