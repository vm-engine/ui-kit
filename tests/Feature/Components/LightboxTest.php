<?php

declare(strict_types=1);

it('listens for its named open event and is a labelled modal dialog', function (): void {
    $html = render('<x-ui::lightbox name="preview" />');

    expect($html)->toContain('x-on:open-lightbox-preview.window="open($event.detail)"')
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('aria-label="'.__('ui-kit::ui.close').'"');
});

it('falls back to the default name for an unsafe one', function (): void {
    expect(render('<x-ui::lightbox name="a b" />'))->toContain('x-on:open-lightbox-lightbox.window');
});

it('lowercases the name so the event listener attribute matches', function (): void {
    expect(render('<x-ui::lightbox name="Preview" />'))->toContain('x-on:open-lightbox-preview.window');
});
