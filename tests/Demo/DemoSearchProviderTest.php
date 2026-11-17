<?php

declare(strict_types=1);

use VmEngine\UiKit\CommandPalette\CommandPaletteSearch;
use VmEngine\UiKit\CommandPalette\SearchResult;
use VmEngine\UiKit\Demo\DemoSearchProvider;

it('is added to the palette providers while the demo is enabled', function (): void {
    expect(config('ui-kit.command_palette.providers'))->toContain(DemoSearchProvider::class);
});

it('finds demo sections as anchors on the demo page', function (): void {
    $results = app(CommandPaletteSearch::class)->search('date', makeUser());

    expect(array_map(fn (SearchResult $r): string => $r->url, $results))->toContain('/ui-kit-demo#datepicker')
        ->and($results[0]->group)->toBe('UI kit demo');
});
