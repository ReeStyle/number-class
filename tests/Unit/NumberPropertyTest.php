<?php

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Format\Rounded;
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

it('provides comparison access as a property', function () {
    $number = new Number(7);

    expect($number->compare)->toBeInstanceOf(Compare::class)
        ->and($number->compare->equals(7.0))->toBeTrue()
        ->and($number->compare->higherThan(6))->toBeTrue()
        ->and($number->get())->toBe(7);
});

it('provides rounding access as a property', function () {
    $number = new Number(12.54);

    expect($number->round)->toBeInstanceOf(Rounded::class)
        ->and($number->round->with(1)->nearest(5)->get())->toBe(15.0)
        ->and($number->get())->toBe(12.54);
});

it('validates values before accessing helper properties', function (string $property) {
    expect(fn () => new Number('7')->$property)
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
})->with(['compare', 'round']);

it('prevents assigning helper properties', function (string $property, Base $replacement) {
    $number = new Number(7);

    expect(function () use ($number, $property, $replacement) {
        $number->$property = $replacement;
    })->toThrow(Error::class);
})->with([
    'compare' => ['compare', new Compare(8)],
    'round' => ['round', new Rounded(8)],
]);
