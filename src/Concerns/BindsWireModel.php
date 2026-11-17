<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Concerns;

use Illuminate\Support\Js;

/**
 * Reads the wire:model attribute of a class component. Laravel sets
 * $this->attributes before the view renders, so these are called from
 * the view as {{ $entangle() }}.
 */
trait BindsWireModel
{
    public function wireModelName(): ?string
    {
        $name = (string) $this->attributes->wire('model')->value();

        return $name === '' ? null : $name;
    }

    public function isLive(): bool
    {
        return (bool) $this->attributes->wire('model')->hasModifier('live');
    }

    /**
     * Alpine expression for the bound value: $wire.entangle('prop')[.live], or null when unbound.
     * Pass live: false to always entangle deferred (currency syncs itself with $commit).
     */
    public function entangle(bool $live = true): string
    {
        $name = $this->wireModelName();

        if ($name === null) {
            return 'null';
        }

        return '$wire.entangle('.Js::from($name)->toHtml().')'.($live && $this->isLive() ? '.live' : '');
    }
}
