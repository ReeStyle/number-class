<?php

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Format\Rounded;
use ReeStyleIt\NumberClass\Number;

it('converts a Number into a Rounded formatter without changing its value', function () {
    $number = new Number(1234.56);
    $rounded = $number->round();

    expect($rounded)->toBeInstanceOf(Rounded::class)
        ->and($rounded)->toBeInstanceOf(Base::class)
        ->and($rounded->get())->toBe(1234.56)
        ->and($rounded->with(2)->get())->toBe(1234.56)
        ->and($number->get())->toBe(1234.56);
});

it('rejects non-number values when rounding', function (mixed $value) {
    expect(fn () => (new Rounded($value))->with())
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
})->with([
    'numeric string' => ['7'],
    'null' => [null],
    'boolean' => [true],
    'array' => [[]],
    'object' => [new stdClass()],
]);

it('rounds without changing its original value', function (int|float $value, array $arguments, float $expected) {
    $rounded = new Rounded($value);
    $result = $rounded->with(...$arguments);

    expect($result)->toBeInstanceOf(Rounded::class)
        ->not->toBe($rounded)
        ->and($result->get())->toBe($expected)
        ->and($rounded->get())->toBe($value);
})->with([
    'defaults' => [1234.56, [], 1235.0],
    'decimal precision' => [1.236, [2], 1.24],
    'negative precision' => [1234.56, [-2], 1200.0],
    'negative tie' => [-1.5, [], -2.0],
    'zero' => [0, [], 0.0],
    'legacy mode' => [2.5, [0, PHP_ROUND_HALF_DOWN], 2.0],
    'half even' => [2.5, [0, RoundingMode::HalfEven], 2.0],
    'half odd' => [2.5, [0, RoundingMode::HalfOdd], 3.0],
    'towards zero' => [-2.9, [0, RoundingMode::TowardsZero], -2.0],
    'away from zero' => [2.1, [0, RoundingMode::AwayFromZero], 3.0],
    'negative infinity' => [-2.1, [0, RoundingMode::NegativeInfinity], -3.0],
    'positive infinity' => [2.1, [0, RoundingMode::PositiveInfinity], 3.0],
    'named arguments' => [2.5, ['mode' => RoundingMode::HalfTowardsZero, 'precision' => 0], 2.0],
]);

it('rejects invalid integer rounding modes', function (int $mode) {
    expect(fn () => (new Rounded(2.5))->with(mode: $mode))
        ->toThrow(ValueError::class, 'Rounding mode must be a valid RoundingMode case or its integer equivalent.');
})->with([-1, 0, 9, 999]);

it('accepts all supported integer rounding modes', function (int $mode, float $expected) {
    expect((new Rounded(2.1))->with(mode: $mode)->get())->toBe($expected);
})->with([
    [1, 2.0],
    [2, 2.0],
    [3, 2.0],
    [4, 2.0],
    [5, 3.0],
    [6, 2.0],
    [7, 2.0],
    [8, 3.0],
]);

it('allows rounding the same value with different precisions', function () {
    $rounded = new Rounded(1234.56);

    expect($rounded->with(1)->get())->toBe(1234.6)
        ->and($rounded->with(-1)->get())->toBe(1230.0)
        ->and($rounded->get())->toBe(1234.56);
});

it('rounds to the nearest multiple without changing its value', function (int|float $value, int|float $multiple, float $expected) {
    $rounded = new Rounded($value);

    $result = $rounded->nearest($multiple);

    expect($result)->toBeInstanceOf(Rounded::class)
        ->not->toBe($rounded)
        ->and($result->get())->toBe($expected)
        ->and($rounded->get())->toBe($value);
})->with([
    'down' => [12, 5, 10.0],
    'up' => [13, 5, 15.0],
    'positive tie' => [12.5, 5, 15.0],
    'negative tie' => [-12.5, 5, -15.0],
    'negative value' => [-12, 5, -10.0],
    'fractional multiple' => [1.03, 0.05, 1.05],
    'exact multiple' => [15, 5, 15.0],
    'zero' => [0, 5, 0.0],
]);

it('rejects invalid multiples', function (int|float $multiple) {
    expect(fn () => (new Rounded(12))->nearest($multiple))
        ->toThrow(InvalidArgumentException::class, 'Multiple must be a positive finite number.');
})->with([0, 0.0, -5, INF, -INF, NAN]);

it('rejects non-number values before rounding to a multiple', function () {
    expect(fn () => (new Rounded('12'))->nearest(5))
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
});

it('allows chaining rounding methods without changing earlier instances', function () {
    $original = new Rounded(12.54);
    $rounded = $original->with(1);
    $nearest = $rounded->nearest(5);
    $result = $nearest->with(-1);

    expect($original->get())->toBe(12.54)
        ->and($rounded->get())->toBe(12.5)
        ->and($nearest->get())->toBe(15.0)
        ->and($result->get())->toBe(20.0);
});
