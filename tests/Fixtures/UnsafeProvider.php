<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Tests\Fixtures;

use Illuminate\Contracts\Auth\Authenticatable;
use VmEngine\UiKit\CommandPalette\SearchProvider;
use VmEngine\UiKit\CommandPalette\SearchResult;

class UnsafeProvider implements SearchProvider
{
    public function search(string $term, Authenticatable $user, int $limit): iterable
    {
        yield new SearchResult('Evil', 'javascript:alert(1)');
        yield new SearchResult('Fine', '/fine');
    }
}
