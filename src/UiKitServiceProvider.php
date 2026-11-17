<?php

declare(strict_types=1);

namespace VmEngine\UiKit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use VmEngine\UiKit\Demo\DemoSearchProvider;
use VmEngine\UiKit\Support\NotificationGate;

final class UiKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ui-kit.php', 'ui-kit');

        // Reset per request/job, so Octane and queue workers re-check the table and flag.
        $this->app->scoped(NotificationGate::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ui-kit');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ui-kit');
        $this->loadRoutesFrom(__DIR__.'/../routes/assets.php');

        // <x-ui::name>: a class in View\Components wins, otherwise an anonymous view.
        Blade::componentNamespace('VmEngine\\UiKit\\View\\Components', 'ui');
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'ui');

        // <livewire:ui::notifications.bell>, <livewire:ui::command-palette>
        Livewire::addNamespace('ui', viewPath: __DIR__.'/../resources/views/livewire');

        if (config('ui-kit.demo.enabled')) {
            $this->registerDemo();
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/ui-kit.php' => config_path('ui-kit.php')], 'ui-kit-config');
            $this->publishes([__DIR__.'/../lang' => $this->app->langPath('vendor/ui-kit')], 'ui-kit-lang');
            $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/ui-kit')], 'ui-kit-views');
        }
    }

    /**
     * The /ui-kit-demo page and its command-palette provider (opt-in, ui-kit.demo.enabled).
     */
    private function registerDemo(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/demo.php');

        $providers = (array) config('ui-kit.command_palette.providers', []);

        if (! in_array(DemoSearchProvider::class, $providers, true)) {
            config(['ui-kit.command_palette.providers' => [...$providers, DemoSearchProvider::class]]);
        }
    }
}
