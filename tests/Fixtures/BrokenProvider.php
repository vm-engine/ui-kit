<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Tests\Fixtures;

use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;
use VmEngine\UiKit\CommandPalette\SearchProvider;

class BrokenProvider implements SearchProvider
{
    public function search(string $term, Authenticatable $user, int $limit): iterable
    {
        throw new RuntimeException('boom');
    }
}
