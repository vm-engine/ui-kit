<?php

declare(strict_types=1);

namespace VmEngine\UiKit\CommandPalette;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Log;
use Throwable;
use UnexpectedValueException;
use VmEngine\UiKit\Support\SafeUrl;

/**
 * Runs every configured SearchProvider for a term and merges the results.
 * A provider that throws is logged and skipped, so the palette never breaks.
 */
final class CommandPaletteSearch
{
    public const MIN_TERM_LENGTH = 2;

    public function __construct(private readonly Container $container) {}

    public function enabled(?Authenticatable $user): bool
    {
        return $user !== null && $this->providerClasses() !== [];
    }

    /**
     * @return list<SearchResult>
     */
    public function search(string $term, Authenticatable $user): array
    {
        $term = trim($term);

        if (mb_strlen($term) < self::MIN_TERM_LENGTH) {
            return [];
        }

        $limit = max(1, (int) config('ui-kit.command_palette.limit_per_provider', 5));
        $results = [];

        foreach ($this->providerClasses() as $class) {
            try {
                $provider = $this->container->make($class);

                if (! $provider instanceof SearchProvider) {
                    throw new UnexpectedValueException("{$class} does not implement ".SearchProvider::class);
                }

                $taken = 0;

                foreach ($provider->search($term, $user, $limit) as $result) {
                    // Providers come from other packages; guard the contract at runtime.
                    // @phpstan-ignore instanceof.alwaysTrue
                    if (! $result instanceof SearchResult || ! SafeUrl::isSafe($result->url)) {
                        continue;
                    }

                    $results[] = $result;

                    if (++$taken >= $limit) {
                        break;
                    }
                }
            } catch (Throwable $exception) {
                Log::warning('ui-kit: command palette provider failed', ['provider' => $class, 'exception' => $exception]);
            }
        }

        return $results;
    }

    /**
     * @return list<string>
     */
    private function providerClasses(): array
    {
        return array_values(array_filter((array) config('ui-kit.command_palette.providers', []), 'is_string'));
    }
}
