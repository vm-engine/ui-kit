<?php

declare(strict_types=1);

namespace VmEngine\UiKit\CommandPalette;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * A source of command-palette results. Register the class in
 * config('ui-kit.command_palette.providers'); it is resolved from the container.
 * Implementations MUST only return results the user may open.
 */
interface SearchProvider
{
    /**
     * @return iterable<SearchResult>
     */
    public function search(string $term, Authenticatable $user, int $limit): iterable;
}
