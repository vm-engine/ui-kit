<?php

declare(strict_types=1);

use Illuminate\View\ComponentAttributeBag;
use VmEngine\UiKit\Concerns\BindsWireModel;

/**
 * @param  array<string, string>  $attributes
 */
function binder(array $attributes): object
{
    $component = new class
    {
        use BindsWireModel;

        public ?ComponentAttributeBag $attributes = null;
    };
    $component->attributes = new ComponentAttributeBag($attributes);

    return $component;
}

it('reads the wire:model property name and live modifier', function (): void {
    expect(binder(['wire:model.live' => 'form.color'])->wireModelName())->toBe('form.color')
        ->and(binder(['wire:model.live' => 'form.color'])->isLive())->toBeTrue()
        ->and(binder(['wire:model' => 'color'])->isLive())->toBeFalse()
        ->and(binder([])->wireModelName())->toBeNull();
});

it('builds an entangle expression, or null when unbound', function (): void {
    expect(binder(['wire:model.live' => 'color'])->entangle())->toBe("\$wire.entangle('color').live")
        ->and(binder(['wire:model.live' => 'color'])->entangle(live: false))->toBe("\$wire.entangle('color')")
        ->and(binder(['wire:model' => "a'b"])->entangle())->toBe("\$wire.entangle('a\\u0027b')")
        ->and(binder([])->entangle())->toBe('null');
});
