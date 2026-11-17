<?php

declare(strict_types=1);

namespace VmEngine\UiKit\CommandPalette;

/**
 * One command-palette row. `url` must be a same-origin path or an http(s) URL;
 * anything else is dropped.
 */
final readonly class SearchResult
{
    /**
     * @param  string|null  $icon  Heroicon name
     * @param  string|null  $group  heading the result is listed under
     */
    public function __construct(
        public string $title,
        public string $url,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $group = null,
    ) {}
}
