<?php

namespace Tests\Unit\Mathematical;

use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\number;

it('calculates trigonometry in a new instance without changing the original', function (int|float $value, string $method, array $arguments, float $expected) {
    $trigonometry = new Trigonometry($value);
    $result = $trigonometry->$method(...$arguments);

    expect($result)->toBeInstanceOf(Trigonometry::class)
        ->not->toBe($trigonometry)
        ->and($result->get())->toEqualWithDelta($expected, 1e-12)
        ->and($trigonometry->get())->toBe($value);
})->with([
    'sine' => [M_PI / 2, 'sin', [], 1.0],
    'negative sine' => [-M_PI / 2, 'sin', [], -1.0],
    'cosine' => [M_PI, 'cos', [], -1.0],
    'tangent' => [M_PI / 4, 'tan', [], 1.0],
    'inverse sine' => [1, 'asin', [], M_PI / 2],
    'inverse cosine' => [0, 'acos', [], M_PI / 2],
    'inverse tangent' => [1, 'atan', [], M_PI / 4],
    'atan2 first quadrant' => [1, 'atan2', [1], M_PI / 4],
    'atan2 second quadrant' => [1, 'atan2', [-1], 3 * M_PI / 4],
    'atan2 third quadrant' => [-1, 'atan2', [-1], -3 * M_PI / 4],
    'atan2 fourth quadrant' => [-1, 'atan2', [1], -M_PI / 4],
    'atan2 zero x' => [1, 'atan2', [0], M_PI / 2],
    'degrees to radians' => [180, 'deg2rad', [], M_PI],
    'radians to degrees' => [M_PI, 'rad2deg', [], 180.0],
    'zero' => [0, 'sin', [], 0.0],
]);

it('creates trigonometry calculations from a Number and chains them', function () {
    $number = number(90);
    $trigonometry = $number->trigonometry();

    expect($trigonometry)->toBeInstanceOf(Trigonometry::class)
        ->and($trigonometry->deg2rad()->sin()->get())->toEqualWithDelta(1.0, 1e-12)
        ->and($trigonometry->deg2rad()->rad2deg()->get())->toEqualWithDelta(90.0, 1e-12)
        ->and($number->get())->toBe(90);
});

it('validates values before trigonometry calculations', function (string $method, array $arguments) {
    expect(fn () => new Trigonometry('1')->$method(...$arguments))
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with([
    ['sin', []], ['cos', []], ['tan', []],
    ['asin', []], ['acos', []], ['atan', []],
    ['atan2', [1]], ['deg2rad', []], ['rad2deg', []],
]);

it('validates a Number before creating trigonometry calculations', function () {
    expect(fn () => new Number('1')->trigonometry())->toThrow(MustBeNumberException::class);
});

it('returns NaN for inverse functions outside their real domain', function (string $method, int $value) {
    expect(is_nan(new Trigonometry($value)->$method()->get()))->toBeTrue();
})->with([
    ['asin', 2], ['asin', -2], ['acos', 2], ['acos', -2],
]);
