<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serves ui-kit's package-owned JS and vendored libraries (resources/dist)
 * without a host build step. Only whitelisted files are served (lookup,
 * never path joining), with a one-year immutable cache; url() appends
 * ?v={filemtime} for cache-busting.
 */
final class AssetController
{
    private const JS = 'text/javascript; charset=UTF-8';

    private const CSS = 'text/css; charset=UTF-8';

    /** @var array<string, string> published path => content type */
    private const FILES = [
        // Vendored flatpickr 4.6.13 + monthSelect plugin (MIT, vendor/flatpickr/LICENSE).
        'vendor/flatpickr/flatpickr.min.js' => self::JS,
        'vendor/flatpickr/flatpickr.min.css' => self::CSS,
        'vendor/flatpickr/month-select.js' => self::JS,
        'vendor/flatpickr/month-select.css' => self::CSS,
        // Vendored Jodit 4.18.4 full build (MIT, vendor/jodit/LICENSE).
        'vendor/jodit/jodit.min.js' => self::JS,
        'vendor/jodit/jodit.min.css' => self::CSS,
        // Component scripts.
        'js/clipboard.js' => self::JS,
        'js/copy-button.js' => self::JS,
        'js/password-generator.js' => self::JS,
        'js/confirm-dialog.js' => self::JS,
        'js/tabs.js' => self::JS,
        'js/split-pane.js' => self::JS,
        'js/lightbox.js' => self::JS,
        'js/currency.js' => self::JS,
        'js/color-picker.js' => self::JS,
        'js/datepicker.js' => self::JS,
        'js/adv-select.js' => self::JS,
        'js/drag-n-drop.js' => self::JS,
        'js/file-drop.js' => self::JS,
        'js/rte.js' => self::JS,
        'js/command-palette.js' => self::JS,
        // Sample image for the opt-in demo page (/ui-kit-demo).
        'demo/sample.svg' => 'image/svg+xml',
    ];

    public function __invoke(string $file): BinaryFileResponse
    {
        abort_unless(array_key_exists($file, self::FILES), 404);

        return response()->file(self::path($file), [
            'Content-Type' => self::FILES[$file],
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public static function url(string $file): string
    {
        $version = (string) (@filemtime(self::path($file)) ?: '0');

        return route('ui-kit.assets', ['file' => $file, 'v' => $version]);
    }

    /**
     * @return list<string>
     */
    public static function files(): array
    {
        return array_keys(self::FILES);
    }

    public static function path(string $file): string
    {
        return dirname(__DIR__, 3).'/resources/dist/'.$file;
    }
}
