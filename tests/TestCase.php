<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Tests;

use Flux\FluxServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Concerns\WithLaravelMigrations;
use Orchestra\Testbench\TestCase as Orchestra;
use VmEngine\UiKit\Tests\Fixtures\User;
use VmEngine\UiKit\UiKitServiceProvider;

abstract class TestCase extends Orchestra
{
    use WithLaravelMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Real web requests get $errors from ShareErrorsFromSession; Flux fields read it.
        View::share('errors', new ViewErrorBag);
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FluxServiceProvider::class,
            UiKitServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));
        $app['config']->set('auth.providers.users.model', User::class);

        // defineDatabaseMigrations() isn't called under Pest; register the path on the migrator.
        $app->afterResolving('migrator', fn ($migrator) => $migrator->path(__DIR__.'/migrations'));
    }
}
