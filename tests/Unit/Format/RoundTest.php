<?php

namespace Tests\Unit\Format;

use RoundingMode;
use stdClass;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;
use ReeStyleIt\NumberClass\Exceptions\RoundingException;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Number;

it('keeps with as an alias of decimals', function (array $arguments) {
    $round = new Round(12.545);
    $result = $round->with(...$arguments);

    expect($result)->toBeInstanceOf(Round::class)
        ->not->toBe($round)
        ->and($result->get())->toBe($round->decimals(...$arguments)->get())
        ->and($round->get())->toBe(12.545);
})->with([
    'defaults'                 => [[]],
    'precision'                => [[2]],
    'negative precision'       => [[-1]],
    'named arguments and mode' => [['mode' => RoundingMode::HalfEven, 'precision' => 2]],
]);

it('validates rounding modes through the with alias', function () {
    expect(fn () => new Round(7)->with(mode: 0))->toThrow(RoundingException::class);
});

it('converts a Number into a Round formatter without changing its value', function () {
    $number = new Number(1234.56);
    $rounded = $number->round;

    expect($rounded)->toBeInstanceOf(Round::class)
        ->and($rounded)->toBeInstanceOf(Base::class)
        ->and($rounded->get())->toBe(1234.56)
        ->and($rounded->decimals(2)->get())->toBe(1234.56)
        ->and($number->get())->toBe(1234.56);
});

it('rejects non-number values when rounding', function (mixed $value) {
    expect(fn () => (new Round($value))->decimals())
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with([
    'numeric string' => ['7'],
    'null'           => [null],
    'boolean'        => [true],
    'array'          => [[]],
    'object'         => [new stdClass()],
]);

it('rounds without changing its original value', function (int|float $value, array $arguments, float $expected) {
    $rounded = new Round($value);
    $result = $rounded->decimals(...$arguments);

    expect($result)->toBeInstanceOf(Round::class)
        ->not->toBe($rounded)
        ->and($result->get())->toBe($expected)
        ->and($rounded->get())->toBe($value);
})->with([
    'defaults'           => [1234.56, [], 1235.0],
    'decimal precision'  => [1.236, [2], 1.24],
    'negative precision' => [1234.56, [-2], 1200.0],
    'negative tie'       => [-1.5, [], -2.0],
    'zero'               => [0, [], 0.0],
    'legacy mode'        => [2.5, [0, PHP_ROUND_HALF_DOWN], 2.0],
    'half even'          => [2.5, [0, RoundingMode::HalfEven], 2.0],
    'half odd'           => [2.5, [0, RoundingMode::HalfOdd], 3.0],
    'towards zero'       => [-2.9, [0, RoundingMode::TowardsZero], -2.0],
    'away from zero'     => [2.1, [0, RoundingMode::AwayFromZero], 3.0],
    'negative infinity'  => [-2.1, [0, RoundingMode::NegativeInfinity], -3.0],
    'positive infinity'  => [2.1, [0, RoundingMode::PositiveInfinity], 3.0],
    'named arguments'    => [2.5, ['mode' => RoundingMode::HalfTowardsZero, 'precision' => 0], 2.0],
]);

it('rejects invalid integer rounding modes', function (int $mode) {
    expect(fn () => (new Round(2.5))->decimals(mode: $mode))
        ->toThrow(RoundingException::class, 'Rounding mode must be a valid RoundingMode case or its integer equivalent.');
})->with([-1, 0, 9, 999]);

it('accepts all supported integer rounding modes', function (int $mode, float $expected) {
    expect((new Round(2.1))->decimals(mode: $mode)->get())->toBe($expected);
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
    $rounded = new Round(1234.56);

    expect($rounded->decimals(1)->get())->toBe(1234.6)
        ->and($rounded->decimals(-1)->get())->toBe(1230.0)
        ->and($rounded->get())->toBe(1234.56);
});

it('rounds to the nearest multiple without changing its value', function (int|float $value, int|float $multiple, float $expected) {
    $rounded = new Round($value);

    $result = $rounded->nearest($multiple);

    expect($result)->toBeInstanceOf(Round::class)
        ->not->toBe($rounded)
        ->and($result->get())->toBe($expected)
        ->and($rounded->get())->toBe($value);
})->with([
    'down'                => [12, 5, 10.0],
    'up'                  => [13, 5, 15.0],
    'positive tie'        => [12.5, 5, 15.0],
    'negative tie'        => [-12.5, 5, -15.0],
    'negative value'      => [-12, 5, -10.0],
    'fractional multiple' => [1.03, 0.05, 1.05],
    'exact multiple'      => [15, 5, 15.0],
    'zero'                => [0, 5, 0.0],
]);

it('rejects invalid multiples', function (int|float $multiple) {
    expect(fn () => (new Round(12))->nearest($multiple))
        ->toThrow(MustBePositiveNumberException::class, 'Multiple must be a positive finite number.');
})->with([0, 0.0, -5, INF, -INF, NAN]);

it('rejects non-number values before rounding to a multiple', function () {
    expect(fn () => (new Round('12'))->nearest(5))
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
});

it('allows chaining rounding methods without changing earlier instances', function () {
    $original = new Round(12.54);
    $rounded = $original->decimals(1);
    $nearest = $rounded->nearest(5);
    $result = $nearest->decimals(-1);

    expect($original->get())->toBe(12.54)
        ->and($rounded->get())->toBe(12.5)
        ->and($nearest->get())->toBe(15.0)
        ->and($result->get())->toBe(20.0);
});
