<?php

use ReeStyleIt\NumberClass\Mathmatical\Pythagoras;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\number;

it('calculates right triangle lengths in a new instance', function (int|float $value, string $method, int|float $otherLeg, float $expected) {
    $pythagoras = new Pythagoras($value);
    $result = $pythagoras->$method($otherLeg);

    expect($result)->toBeInstanceOf(Pythagoras::class)
        ->not->toBe($pythagoras)
        ->and($result->get())->toEqualWithDelta($expected, 1e-12)
        ->and($pythagoras->get())->toBe($value);
})->with([
    'hypotenuse' => [3, 'hypotenuse', 4, 5.0],
    'reversed sides' => [4, 'hypotenuse', 3, 5.0],
    'fractional sides' => [1.5, 'hypotenuse', 2, 2.5],
    'missing leg' => [5, 'leg', 3, 4.0],
    'other missing leg' => [5, 'leg', 4, 3.0],
    'zero hypotenuse' => [0, 'hypotenuse', 0, 0.0],
    'zero leg' => [5, 'leg', 0, 5.0],
    'equal lengths' => [5, 'leg', 5, 0.0],
    'all zero' => [0, 'leg', 0, 0.0],
]);

it('calculates lengths from Number and helper functions', function () {
    $number = number(3);

    expect($number->pythagoras()->hypotenuse(4)->get())->toBe(5.0)
        ->and(number(5)->pythagoras()->leg(3)->get())->toBe(4.0)
        ->and($number->get())->toBe(3);
});

it('chains hypotenuse and missing leg calculations', function () {
    expect(new Pythagoras(3)->hypotenuse(4)->leg(3)->get())->toBe(4.0);
});

it('handles large and small lengths without squaring overflow or underflow', function (float $scale) {
    expect(new Pythagoras(3 * $scale)->hypotenuse(4 * $scale)->get() / $scale)
        ->toEqualWithDelta(5.0, 1e-12)
        ->and(new Pythagoras(5 * $scale)->leg(3 * $scale)->get() / $scale)
        ->toEqualWithDelta(4.0, 1e-12);
})->with([1e200, 1e-200]);

it('rejects a leg longer than the hypotenuse', function () {
    expect(fn () => new Pythagoras(3)->leg(4))
        ->toThrow(InvalidArgumentException::class, 'A leg cannot be longer than the hypotenuse.');
});

it('rejects invalid lengths on either side', function (string $method, int|float $length) {
    expect(fn () => new Pythagoras($length)->$method(3))
        ->toThrow(InvalidArgumentException::class, 'Length must be a non-negative finite number.')
        ->and(fn () => new Pythagoras(5)->$method($length))
        ->toThrow(InvalidArgumentException::class, 'Length must be a non-negative finite number.');
})->with(['hypotenuse', 'leg'])->with([-1, INF, -INF, NAN]);

it('validates stored values before calculating lengths', function (string $method) {
    expect(fn () => new Pythagoras('3')->$method(4))
        ->toThrow(UnexpectedValueException::class);
})->with(['hypotenuse', 'leg']);

it('validates a Number before creating a Pythagoras object', function () {
    expect(fn () => number('3')->pythagoras())->toThrow(UnexpectedValueException::class);
});
