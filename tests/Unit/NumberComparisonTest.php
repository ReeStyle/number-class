<?php

use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

it('creates a comparison object with the original number value', function () {
    $number = new Number(7);
    $comparison = $number->compare();

    expect($comparison)->toBeInstanceOf(Compare::class)
        ->and($comparison->get())->toBe(7)
        ->and($comparison->equals(7.0))->toBeTrue()
        ->and($number->get())->toBe(7);
});

it('supports constructing a comparison directly', function () {
    expect((new Compare(7))->higherThan(new Number(6)))->toBeTrue()
        ->and(fn () => (new Compare('7'))->equals(7))->toThrow(UnexpectedValueException::class);
});

it('compares numeric values and Number instances without changing them', function (int|float $value, int|float $other, array $expected) {
    $number = new Number($value);
    $otherNumber = new Number($other);

    foreach ($expected as $method => $result) {
        expect($number->compare()->$method($other))->toBe($result)
            ->and($number->compare()->$method($otherNumber))->toBe($result);
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
    expect(fn () => new Number('7')->compare()->$method(7))
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.')
        ->and(fn () => new Number(7)->compare()->$method(new Number('7')))
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
})->with(['equals', 'higherThan', 'lowerThan', 'higherThanOrEqual', 'lowerThanOrEqual']);
