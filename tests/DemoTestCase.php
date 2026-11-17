<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Tests;

/**
 * Boots the package with the demo enabled and a bare fixture layout.
 */
abstract class DemoTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('ui-kit.demo.enabled', true);
        $app['config']->set('ui-kit.demo.layout', 'demo-fixtures::layout');
        $app['view']->addNamespace('demo-fixtures', __DIR__.'/Demo/views');
    }

    protected function defineRoutes($router): void
    {
        // Hosts provide a login route; the auth middleware redirects guests to it.
        $router->get('/login', fn (): string => 'login')->name('login');
    }
}
