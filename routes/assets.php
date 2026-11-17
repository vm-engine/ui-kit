<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VmEngine\UiKit\Http\Controllers\AssetController;

// Stateless: no session or CSRF middleware. Always registered, because every component may need it.
Route::get(config('ui-kit.assets_prefix').'/{file}', AssetController::class)
    ->where('file', '[A-Za-z0-9/._-]+')
    ->name('ui-kit.assets');
