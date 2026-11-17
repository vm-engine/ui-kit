<?php

declare(strict_types=1);

it('renders one named flux modal that listens for ui-confirm', function (): void {
    $html = decoded('<x-ui::confirm-dialog />');

    expect($html)->toContain('x-data="uiConfirmDialog"')
        ->toContain('x-on:ui-confirm.window="open($event.detail)"')
        ->toContain('ui-confirm-dialog')
        ->toContain(__('ui-kit::ui.cancel'));
});
