<?php

namespace Tests\Unit\Number;

use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

it('creates a comparison object with the original number value', function () {
    $number = new Number(7);
    $comparison = $number->compare;

    expect($comparison)->toBeInstanceOf(Compare::class)
        ->and($comparison->get())->toBe(7)
        ->and($comparison->equals(7.0))->toBeTrue()
        ->and($number->get())->toBe(7);
});

it('supports constructing a comparison directly', function () {
    expect((new Compare(7))->higherThan(new Number(6)))->toBeTrue()
        ->and(fn () => (new Compare('7'))->equals(7))->toThrow(MustBeNumberException::class);
});

it('compares numeric values and Number instances without changing them', function (int|float $value, int|float $other, array $expected) {
    $number = new Number($value);
    $otherNumber = new Number($other);

    foreach ($expected as $method => $result) {
        expect($number->compare->$method($other))->toBe($result)
            ->and($number->compare->$method($otherNumber))->toBe($result);
    }

    if (!is_nan($value) && !is_nan($other)) {
        expect($number->get())->toBe($value)
            ->and($otherNumber->get())->toBe($other);
    }
})->with([
    'equal integers' => [7, 7, ['equals' => true, 'higherThan' => false, 'lowerThan' => false, 'higherThanOrEqual' => true, 'lowerThanOrEqual' => true]],
    'equal integer and float' => [7, 7.0, ['equals' => true, 'higherThan' => false, 'lowerThan' => false, 'higherThanOrEqual' => true, 'lowerThanOrEqual' => true]],
    'higher' => [8, 7, ['equals' => false, 'higherThan' => true, 'lowerThan' => false, 'higherThanOrEqual' => true, 'lowerThanOrEqual' => false]],
    'lower' => [6, 7, ['equals' => false, 'higherThan' => false, 'lowerThan' => true, 'higherThanOrEqual' => false, 'lowerThanOrEqual' => true]],
    'negative values' => [-1.5, -1, ['equals' => false, 'higherThan' => false, 'lowerThan' => true, 'higherThanOrEqual' => false, 'lowerThanOrEqual' => true]],
    'zero' => [0, 0.0, ['equals' => true, 'higherThan' => false, 'lowerThan' => false, 'higherThanOrEqual' => true, 'lowerThanOrEqual' => true]],
    'infinity' => [INF, 7, ['equals' => false, 'higherThan' => true, 'lowerThan' => false, 'higherThanOrEqual' => true, 'lowerThanOrEqual' => false]],
    'NaN as value' => [NAN, 7, ['equals' => false, 'higherThan' => false, 'lowerThan' => false, 'higherThanOrEqual' => false, 'lowerThanOrEqual' => false]],
    'NaN as argument' => [7, NAN, ['equals' => false, 'higherThan' => false, 'lowerThan' => false, 'higherThanOrEqual' => false, 'lowerThanOrEqual' => false]],
]);

it('rejects non-numeric stored values on either side of a comparison', function (string $method) {
    expect(fn () => new Number('7')->compare->$method(7))
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.')
        ->and(fn () => new Number(7)->compare->$method(new Number('7')))
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with(['equals', 'higherThan', 'lowerThan', 'higherThanOrEqual', 'lowerThanOrEqual']);

it('compares package value objects using their validated numbers', function (string $class) {
    $other = new $class(7);
    $comparison = new Compare(7);

    foreach (['equals' => true, 'higherThan' => false, 'lowerThan' => false,
        'higherThanOrEqual' => true, 'lowerThanOrEqual' => true] as $method => $expected) {
        expect($comparison->$method($other))->toBe($expected)
            ->and(fn () => $comparison->$method(new $class('7')))->toThrow(MustBeNumberException::class);
    }

    expect(new Compare(8)->higherThan($other))->toBeTrue()
        ->and(new Compare(6)->lowerThan($other))->toBeTrue()
        ->and($other->get())->toBe(7);
})->with([
    Arithmetic::class, Compare::class, Currency::class, Geometry::class,
    Number::class, Pythagoras::class, Round::class, Time::class, Trigonometry::class,
]);

it('compares calculation results directly', function () {
    $result = new Number(3)->add(4);

    expect(new Number(7)->compare->equals($result))->toBeTrue()
        ->and(new Number(8)->compare->higherThan($result))->toBeTrue()
        ->and($result->get())->toBe(7);
});

it('supports strict equality without converting numeric types', function (int|float $value, int|float $other, bool $expected) {
    $comparison = new Compare($value);

    expect($comparison->equals($other, strict: true))->toBe($expected)
        ->and($comparison->equals(new Number($other), strict: true))->toBe($expected)
        ->and($comparison->equals(new Arithmetic($other), strict: true))->toBe($expected);
})->with([
    'integers' => [7, 7, true],
    'floats' => [7.0, 7.0, true],
    'integer and float' => [7, 7.0, false],
    'float and integer' => [7.0, 7, false],
    'different floats' => [7.5, 7.0, false],
    'zero types' => [0, 0.0, false],
    'infinity' => [INF, INF, true],
    'NaN' => [NAN, NAN, false],
]);

it('validates both operands when using strict equality', function () {
    expect(fn () => new Compare('7')->equals(7, strict: true))->toThrow(MustBeNumberException::class)
        ->and(fn () => new Compare(7)->equals(new Arithmetic('7'), strict: true))
        ->toThrow(MustBeNumberException::class);
});
