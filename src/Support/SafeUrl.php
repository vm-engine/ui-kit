<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Support;

/**
 * Whether a URL taken from stored data (notification `url`, search result `url`)
 * may go into an href: a same-origin path or an http(s) URL. Blocks javascript:,
 * data:, protocol-relative //host and /\host, and any control character or
 * whitespace (browsers strip tab/CR/LF, so "/\t/evil.test" would become //evil.test).
 */
final class SafeUrl
{
    public static function isSafe(?string $url): bool
    {
        if ($url === null || $url === '' || preg_match('/[\x00-\x20\x7f]/', $url) === 1) {
            return false;
        }

        if (preg_match('#^/(?![/\\\\])#', $url) === 1) {
            return true;
        }

        return in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true);
    }
}
