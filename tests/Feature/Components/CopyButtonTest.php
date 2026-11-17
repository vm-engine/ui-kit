<?php

declare(strict_types=1);

it('passes the text and translated labels to Alpine', function (): void {
    $html = decoded('<x-ui::copy-button text="/storage/a b.pdf" label="Copy path" :icon-only="false" />');

    expect($html)->toContain('uiCopyButton('.js('/storage/a b.pdf').', ')
        ->toContain('Copy path')
        ->toContain('aria-live="polite"');
});

it('labels the icon-only button for screen readers by default', function (): void {
    expect(render('<x-ui::copy-button text="x" />'))->toContain('aria-label="'.__('ui-kit::ui.copy').'"');
});
