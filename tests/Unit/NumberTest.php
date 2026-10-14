<?php

use ReeStyleIt\IntClass\Base;
use ReeStyleIt\IntClass\Number;

dataset('values', [
    'integer' => [7, true, false],
    'zero' => [0, true, false],
    'negative integer' => [-7, true, false],
    'float' => [1.5, false, true],
    'float zero' => [0.0, false, true],
    'numeric string' => ['7', false, false],
    'string' => ['text', false, false],
    'null' => [null, false, false],
    'boolean' => [true, false, false],
    'array' => [[], false, false],
    'object' => [new stdClass(), false, false],
]);

it('preserves the value and identifies its type', function (mixed $value, bool $isInt, bool $isFloat) {
    $number = new Number($value);

    expect($number)->toBeInstanceOf(Base::class)
        ->and($number->get())->toBe($value)
        ->and($number->isInt())->toBe($isInt)
        ->and($number->isFloat())->toBe($isFloat)
        ->and($number->isNumber())->toBe($isInt || $isFloat)
        ->and($number->isNumeric())->toBe($number->isNumber());
})->with('values');

it('keeps its stored value when the retrieved value is changed', function () {
    $number = new Number(7);
    $value = $number->get();
    $value = 8;

    expect($value)->toBe(8)
        ->and($number->get())->toBe(7);
});

dataset('arithmetic', [
    'increase by one' => [9, 'increase', [], 10],
    'increase by a float' => [9, 'increase', [2.5], 11.5],
    'decrease by one' => [9, 'decrease', [], 8],
    'decrease below zero' => [1, 'decrease', [2.5], -1.5],
    'multiply integers' => [6, 'multiply', [3], 18],
    'multiply by a float' => [6, 'multiply', [0.5], 3.0],
    'multiply by zero' => [6, 'multiply', [0], 0],
    'divide evenly' => [6, 'divide', [3], 2],
    'divide with remainder' => [7, 'divide', [2], 3.5],
    'divide by a fraction' => [6, 'divide', [0.5], 12.0],
    'divide by a negative fraction' => [6, 'divide', [-0.5], -12.0],
    'divide zero' => [0, 'divide', [2], 0],
    'power' => [9, 'pow', [2], 81],
    'zero exponent' => [9, 'pow', [0], 1],
    'negative exponent' => [2, 'pow', [-1], 0.5],
    'fractional exponent' => [9, 'pow', [0.5], 3.0],
    'square root' => [9, 'sqrt', [], 3.0],
    'square root of zero' => [0, 'sqrt', [], 0.0],
    'absolute integer' => [-7, 'abs', [], 7],
    'absolute float' => [-1.5, 'abs', [], 1.5],
    'absolute positive value' => [7, 'abs', [], 7],
    'negate positive value' => [7, 'negate', [], -7],
    'negate negative float' => [-1.5, 'negate', [], 1.5],
    'integer remainder' => [7, 'mod', [3], 1],
    'negative remainder' => [-7, 'mod', [3], -1],
    'float remainder' => [5.5, 'mod', [2], 1.5],
    'float divisor' => [7, 'mod', [2.5], 2.0],
    'fractional divisor' => [5.5, 'mod', [0.5], 0.0],
    'negative divisor' => [7, 'mod', [-3], 1],
    'floor positive' => [1.5, 'floor', [], 1.0],
    'floor negative' => [-1.5, 'floor', [], -2.0],
    'ceil positive' => [1.5, 'ceil', [], 2.0],
    'ceil negative' => [-1.5, 'ceil', [], -1.0],
]);

it('returns the calculated value in a new instance', function (int|float $value, string $method, array $arguments, int|float $expected) {
    $number = new Number($value);
    $result = $number->$method(...$arguments);

    expect($result)->toBeInstanceOf(Number::class)
        ->not->toBe($number)
        ->and($result->get())->toBe($expected)
        ->and($number->get())->toBe($value);
})->with('arithmetic');

dataset('operations', [
    'increase' => ['increase', []],
    'decrease' => ['decrease', []],
    'multiply' => ['multiply', [2]],
    'divide' => ['divide', [2]],
    'pow' => ['pow', [2]],
    'sqrt' => ['sqrt', []],
    'abs' => ['abs', []],
    'negate' => ['negate', []],
    'mod' => ['mod', [2]],
    'round' => ['round', []],
    'floor' => ['floor', []],
    'ceil' => ['ceil', []],
]);

it('rejects non-number values before arithmetic', function (string $method, array $arguments, mixed $value) {
    expect(fn () => new Number($value)->$method(...$arguments))
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
})->with('operations')->with([
    'numeric string' => ['7'],
    'string' => ['text'],
    'null' => [null],
    'boolean' => [true],
    'array' => [[]],
    'object' => [new stdClass()],
]);

it('rejects zero divisors', function (string $method, int|float $value, int|float $divisor) {
    expect(fn () => new Number($value)->$method($divisor))
        ->toThrow(DivisionByZeroError::class);
})->with(['divide', 'mod'])->with([0, 6])->with([0, 0.0, -0.0]);

it('validates the value before checking a zero divisor', function (string $method) {
    expect(fn () => new Number('0')->$method(0))
        ->toThrow(UnexpectedValueException::class);
})->with(['divide', 'mod']);

it('returns NaN for the square root of a negative number', function () {
    expect(is_nan(new Number(-1)->sqrt()->get()))->toBeTrue();
});

it('calculates a root with the given exponent in a new instance', function (int|float $value, int $exponent, float $expected) {
    $number = new Number($value);
    $result = $number->sqrt(exponent: $exponent);

    expect($result)->toBeInstanceOf(Number::class)
        ->not->toBe($number)
        ->and($result->get())->toEqualWithDelta($expected, 1e-12)
        ->and($number->get())->toBe($value);
})->with([
    'explicit square root' => [9, 2, 3.0],
    'cube root' => [27, 3, 3.0],
    'fourth root' => [16, 4, 2.0],
    'negative cube root' => [-27, 3, -3.0],
    'negative fifth root' => [-32, 5, -2.0],
    'fractional value' => [0.125, 3, 0.5],
    'zero' => [0, 3, 0.0],
    'first root' => [-7, 1, -7.0],
]);

it('rejects non-positive root exponents', function (int $exponent) {
    expect(fn () => new Number(9)->sqrt($exponent))
        ->toThrow(InvalidArgumentException::class, 'Root exponent must be greater than 0.');
})->with([0, -1, -2]);

it('returns NaN for an even root of a negative number', function () {
    expect(is_nan(new Number(-16)->sqrt(4)->get()))->toBeTrue();
});

it('allows chaining without changing the original value', function () {
    $number = new Number(9);
    $result = $number->increase(3)->multiply(2)->divide(4)->negate()->abs();

    expect($result->get())->toBe(6)
        ->and($number->get())->toBe(9);
});
