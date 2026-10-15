<?php

namespace Tests\Unit\Mathematical;

use DivisionByZeroError;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\number;

it('creates arithmetic calculations from a Number', function () {
    $number = number(7);
    $arithmetic = $number->arithmetic();

    expect($arithmetic)->toBeInstanceOf(Arithmetic::class)
        ->and($arithmetic->multiply(2)->increase(1)->get())->toBe(15)
        ->and($number->get())->toBe(7);
});

it('calculates into a new Arithmetic instance without changing the original', function (int|float $value, string $method, array $arguments, int|float $expected) {
    $arithmetic = new Arithmetic($value);
    $result = $arithmetic->$method(...$arguments);

    expect($result)->toBeInstanceOf(Arithmetic::class)
        ->not->toBe($arithmetic)
        ->and($result->get())->toBe($expected)
        ->and($arithmetic->get())->toBe($value);
})->with([
    'add' => [7, 'add', [2.5], 9.5],
    'subtract' => [7, 'subtract', [], 6],
    'increase' => [7, 'increase', [], 8],
    'decrease' => [7, 'decrease', [2.5], 4.5],
    'multiply' => [7, 'multiply', [2], 14],
    'divide' => [7, 'divide', [0.5], 14.0],
    'power' => [7, 'pow', [2], 49],
    'square root' => [9, 'sqrt', [], 3.0],
    'negative cube root' => [-8, 'sqrt', [3], -2.0],
    'absolute' => [-7, 'abs', [], 7],
    'negate' => [7, 'negate', [], -7],
    'integer remainder' => [7, 'mod', [3], 1],
    'float remainder' => [5.5, 'mod', [2], 1.5],
    'floor' => [-1.5, 'floor', [], -2.0],
    'ceil' => [-1.5, 'ceil', [], -1.0],
]);

it('chains arithmetic calculations', function () {
    expect(new Arithmetic(9)->increase(3)->multiply(2)->divide(4)->negate()->abs()->get())->toBe(6);
});

it('validates stored values before calculating', function (string $method, array $arguments) {
    expect(fn () => new Arithmetic('7')->$method(...$arguments))
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with([
    ['add', []], ['subtract', []],
    ['increase', []], ['decrease', []], ['multiply', [2]], ['divide', [2]],
    ['pow', [2]], ['sqrt', []], ['abs', []], ['negate', []],
    ['mod', [2]], ['floor', []], ['ceil', []],
]);

it('rejects division and remainder by zero', function (string $method) {
    expect(fn () => new Arithmetic(7)->$method(0))->toThrow(DivisionByZeroError::class);
})->with(['divide', 'mod']);

it('rejects non-positive root exponents in arithmetic calculations', function () {
    expect(fn () => new Arithmetic(7)->sqrt(0))
        ->toThrow(MustBePositiveNumberException::class, 'Root exponent must be greater than 0.');
});
