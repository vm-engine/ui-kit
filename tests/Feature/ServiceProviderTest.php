<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;

it('merges the package config', function (): void {
    expect(config('ui-kit.assets_prefix'))->toBe('ui-kit-assets')
        ->and(config('ui-kit.notifications.enabled'))->toBeFalse()
        ->and(config('ui-kit.command_palette.providers'))->toBe([]);
});

it('loads the package translations', function (): void {
    expect(__('ui-kit::ui.cancel'))->toBe('Cancel');
    app()->setLocale('id');
    expect(__('ui-kit::ui.cancel'))->toBe('Batal');
});

it('resolves anonymous and class components under the ui prefix', function (): void {
    expect(render('<x-ui::empty-state title="Anonymous A" />'))->toContain('Anonymous A')
        ->and(render('<x-ui::stat-tile label="Class B" value="1" />'))->toContain('Class B');
});

it('does not register the demo route by default', function (): void {
    expect(config('ui-kit.demo.enabled'))->toBeFalse()
        ->and(Route::has('ui-kit.demo'))->toBeFalse();
});
