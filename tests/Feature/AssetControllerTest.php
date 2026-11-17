<?php

declare(strict_types=1);

use VmEngine\UiKit\Http\Controllers\AssetController;

it('serves a whitelisted file with its type and an immutable cache header', function (): void {
    $response = $this->get('/ui-kit-assets/vendor/flatpickr/flatpickr.min.js')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/javascript; charset=UTF-8');

    expect($response->headers->get('Cache-Control'))
        ->toContain('immutable')
        ->toContain('max-age=31536000')
        ->toContain('public');
});

it('returns 404 for files outside the whitelist', function (string $path): void {
    $this->get('/ui-kit-assets/'.$path)->assertNotFound();
})->with(['nope.js', '../composer.json', 'vendor/../../composer.json', 'js/../../src/UiKitServiceProvider.php']);

it('appends a cache-busting version to asset urls', function (): void {
    expect(AssetController::url('vendor/jodit/jodit.min.js'))
        ->toMatch('#/ui-kit-assets/vendor/jodit/jodit\.min\.js\?v=\d+$#');
});

it('whitelists only files that exist', function (): void {
    foreach (AssetController::files() as $file) {
        expect(file_exists(AssetController::path($file)))->toBeTrue($file);
    }
});
