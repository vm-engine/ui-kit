<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Registered only when ui-kit.demo.enabled is true (see UiKitServiceProvider::registerDemo()).
Route::livewire('ui-kit-demo', 'ui::demo')
    ->middleware((array) config('ui-kit.demo.middleware', ['web', 'auth']))
    ->name('ui-kit.demo');
