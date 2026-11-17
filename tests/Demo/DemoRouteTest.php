<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers the demo route with the configured middleware', function (): void {
    $route = Route::getRoutes()->getByName('ui-kit.demo');

    expect($route)->not->toBeNull()
        ->and($route->uri())->toBe('ui-kit-demo')
        ->and($route->gatherMiddleware())->toContain('web')->toContain('auth');
});

it('redirects guests to login', function (): void {
    $this->get('/ui-kit-demo')->assertRedirect('/login');
});
