<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Js;
use VmEngine\UiKit\Tests\DemoTestCase;
use VmEngine\UiKit\Tests\Fixtures\User;
use VmEngine\UiKit\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(DemoTestCase::class, RefreshDatabase::class)->in('Demo');

/**
 * Render a Blade string (components included) to raw HTML. Use for escaping assertions.
 *
 * @param  array<string, mixed>  $data
 */
function render(string $blade, array $data = []): string
{
    return Blade::render($blade, $data, deleteCachedView: true);
}

/**
 * Render and decode HTML entities. Attributes passed through Flux components
 * (and {{ }} echoes) are HTML-escaped, so assertions on Alpine/Livewire
 * expressions compare against the decoded markup the browser actually runs.
 *
 * @param  array<string, mixed>  $data
 */
function decoded(string $blade, array $data = []): string
{
    return html_entity_decode(render($blade, $data), ENT_QUOTES | ENT_HTML5);
}

/** The exact JS literal a view prints with @js($value). */
function js(mixed $value): string
{
    return Js::from($value)->toHtml();
}

function makeUser(string $email = 'ada@example.test'): User
{
    return User::query()->create(['name' => 'Ada', 'email' => $email, 'password' => 'secret']);
}
