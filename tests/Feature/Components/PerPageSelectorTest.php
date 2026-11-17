<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;

it('binds perPage live, offers 10/15/25/50 and labels the select', function (): void {
    $html = render('<x-ui::per-page-selector :paginator="$p" />', ['p' => new LengthAwarePaginator([], 120, 15)]);

    expect($html)->toContain('wire:model.live="perPage"')
        ->toContain('aria-label="'.__('ui-kit::ui.per_page').'"');

    foreach (['10', '15', '25', '50'] as $size) {
        expect($html)->toContain('value="'.$size.'"');
    }
});

it('does not leak its label prop into the Flux select as a visible label', function (): void {
    expect(render('<x-ui::per-page-selector :paginator="$p" label="Rows" />', ['p' => new LengthAwarePaginator([], 120, 15)]))
        ->toContain('aria-label="Rows"')
        ->not->toContain('<label');
});
