<?php

declare(strict_types=1);

use VmEngine\UiKit\Concerns\SanitizesWireArguments;

function sanitizer(): object
{
    return new class
    {
        use SanitizesWireArguments;

        public function method(?string $method): ?string
        {
            return $this->wireMethod($method);
        }

        public function key(int|string|null $key): ?string
        {
            return $this->safeKey($key);
        }
    };
}

it('passes bare identifiers and null through', function (): void {
    expect(sanitizer()->method('deleteUser'))->toBe('deleteUser')
        ->and(sanitizer()->method('_go2'))->toBe('_go2')
        ->and(sanitizer()->method(null))->toBeNull()
        ->and(sanitizer()->method(''))->toBeNull();
});

it('throws on a method name that is not a bare identifier', function (string $bad): void {
    sanitizer()->method($bad);
})->with(['delete(); alert(1)', 'a.b', '1abc', "x'"])->throws(InvalidArgumentException::class);

it('keeps safe keys and drops unsafe ones', function (): void {
    expect(sanitizer()->key(42))->toBe('42')
        ->and(sanitizer()->key('card-7_a'))->toBe('card-7_a')
        ->and(sanitizer()->key("7');alert(1)//"))->toBeNull()
        ->and(sanitizer()->key(null))->toBeNull();
});
