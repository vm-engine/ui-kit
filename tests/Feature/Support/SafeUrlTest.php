<?php

declare(strict_types=1);

use VmEngine\UiKit\Support\SafeUrl;

it('accepts relative paths and http(s) urls', function (string $url): void {
    expect(SafeUrl::isSafe($url))->toBeTrue();
})->with(['/invoices/7', '/', 'https://example.test/a?b=1', 'http://example.test', 'HTTPS://EXAMPLE.TEST']);

it('rejects scripts, protocol-relative and empty urls', function (?string $url): void {
    expect(SafeUrl::isSafe($url))->toBeFalse();
})->with(['javascript:alert(1)', ' JaVaScRiPt:alert(1)', 'data:text/html,x', '//evil.test', '/\\evil.test', '', null, 'invoices/7']);

it('rejects urls with control characters or whitespace that browsers strip', function (string $url): void {
    expect(SafeUrl::isSafe($url))->toBeFalse();
})->with([
    'tab' => ['/'.chr(9).'/evil.test'],
    'newline' => ['/'.chr(10).'/evil.test'],
    'carriage return' => ['/'.chr(13).'/evil.test'],
    'space' => ['/a b'],
    'trailing newline on https' => ['https://example.test'.chr(10)],
]);
