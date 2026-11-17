<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Tests\Fixtures;

use Illuminate\Contracts\Auth\Authenticatable;
use VmEngine\UiKit\CommandPalette\SearchProvider;
use VmEngine\UiKit\CommandPalette\SearchResult;

class PagesProvider implements SearchProvider
{
    public static ?Authenticatable $lastUser = null;

    public static ?int $lastLimit = null;

    public function search(string $term, Authenticatable $user, int $limit): iterable
    {
        self::$lastUser = $user;
        self::$lastLimit = $limit;

        $pages = [
            new SearchResult('Users', '/admin/users', group: 'Pages'),
            new SearchResult('Roles', '/admin/roles', description: 'Who can do what', group: 'Pages'),
            new SearchResult('Role audit', '/admin/roles/audit', group: 'Pages'),
        ];

        return array_values(array_filter($pages, fn (SearchResult $page): bool => str_contains(strtolower($page->title), strtolower($term))));
    }
}
