<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Concerns;

use InvalidArgumentException;

/**
 * Whitelists values a component interpolates into Livewire expressions
 * (wire:click="method('key')", wire:sort="method"), so a prop can never
 * inject JavaScript into the host component's markup.
 */
trait SanitizesWireArguments
{
    /**
     * A developer-supplied Livewire method name. Null/empty means "none";
     * anything that is not a bare identifier is a programming error.
     *
     * @throws InvalidArgumentException
     */
    protected function wireMethod(?string $method): ?string
    {
        if ($method === null || $method === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $method) !== 1) {
            throw new InvalidArgumentException("ui-kit: [{$method}] is not a valid Livewire method name.");
        }

        return $method;
    }

    /**
     * A data key (record id, date, column id): kept only if it is [A-Za-z0-9_-]+.
     */
    protected function safeKey(int|string|null $key): ?string
    {
        if ($key === null) {
            return null;
        }

        $key = (string) $key;

        return preg_match('/^[A-Za-z0-9_-]+$/', $key) === 1 ? $key : null;
    }
}
