<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use VmEngine\UiKit\CommandPalette\CommandPaletteSearch;
use VmEngine\UiKit\CommandPalette\SearchResult;
use VmEngine\UiKit\Tests\Fixtures\BrokenProvider;
use VmEngine\UiKit\Tests\Fixtures\PagesProvider;
use VmEngine\UiKit\Tests\Fixtures\UnsafeProvider;

it('is disabled for guests and without providers', function (): void {
    config(['ui-kit.command_palette.providers' => [PagesProvider::class]]);
    expect(app(CommandPaletteSearch::class)->enabled(null))->toBeFalse();

    config(['ui-kit.command_palette.providers' => []]);
    expect(app(CommandPaletteSearch::class)->enabled(makeUser()))->toBeFalse();
});

it('merges provider results and passes the user and limit', function (): void {
    config(['ui-kit.command_palette.providers' => [PagesProvider::class, UnsafeProvider::class], 'ui-kit.command_palette.limit_per_provider' => 3]);
    $user = makeUser();

    $titles = array_map(fn (SearchResult $r): string => $r->title, app(CommandPaletteSearch::class)->search('us', $user));

    expect($titles)->toContain('Users')->toContain('Fine')
        ->and(PagesProvider::$lastUser?->getAuthIdentifier())->toBe($user->getAuthIdentifier())
        ->and(PagesProvider::$lastLimit)->toBe(3);
});

it('needs two characters and caps each provider at the limit', function (): void {
    config(['ui-kit.command_palette.providers' => [PagesProvider::class], 'ui-kit.command_palette.limit_per_provider' => 1]);
    $user = makeUser();

    expect(app(CommandPaletteSearch::class)->search('r', $user))->toBe([])
        ->and(app(CommandPaletteSearch::class)->search('ro', $user))->toHaveCount(1);
});

it('skips and logs a provider that throws', function (): void {
    config(['ui-kit.command_palette.providers' => [BrokenProvider::class, PagesProvider::class]]);
    Log::spy();

    $results = app(CommandPaletteSearch::class)->search('users', makeUser());

    expect($results)->toHaveCount(1);
    Log::shouldHaveReceived('warning')->once();
});

it('skips a configured class that is not a provider', function (): void {
    config(['ui-kit.command_palette.providers' => [stdClass::class, PagesProvider::class]]);
    Log::spy();

    expect(app(CommandPaletteSearch::class)->search('users', makeUser()))->toHaveCount(1);
    Log::shouldHaveReceived('warning')->once();
});

it('drops results with unsafe urls', function (): void {
    config(['ui-kit.command_palette.providers' => [UnsafeProvider::class]]);

    $urls = array_map(fn (SearchResult $r): string => $r->url, app(CommandPaletteSearch::class)->search('anything', makeUser()));

    expect($urls)->toBe(['/fine']);
});
