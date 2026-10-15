<?php

use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

dataset('helper properties', [
    'compare' => ['compare', Compare::class],
    'round' => ['round', Round::class],
    'arithmetic' => ['arithmetic', Arithmetic::class],
    'trigonometry' => ['trigonometry', Trigonometry::class],
    'pythagoras' => ['pythagoras', Pythagoras::class],
    'currency' => ['currency', Currency::class],
]);

it('provides matching helper instances through properties and methods', function (string $property, string $class) {
    $number = new Number(7);
    $helper = $number->$property;

    expect($helper)->toBeInstanceOf($class)
        ->and($helper->get())->toBe(7)
        ->and($number->$property())->toBeInstanceOf($class)
        ->and($number->get())->toBe(7);
})->with('helper properties');

it('provides comparison access as a property', function () {
    $number = new Number(7);

    expect($number->compare)->toBeInstanceOf(Compare::class)
        ->and($number->compare->equals(7.0))->toBeTrue()
        ->and($number->compare->higherThan(6))->toBeTrue()
        ->and($number->get())->toBe(7);
});

it('provides rounding access as a property', function () {
    $number = new Number(12.54);

    expect($number->round)->toBeInstanceOf(Round::class)
        ->and($number->round->decimals(1)->nearest(5)->get())->toBe(15.0)
        ->and($number->get())->toBe(12.54);
});

it('validates values before accessing helper properties', function (string $property, string $class) {
    expect(fn () => new Number('7')->$property)
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with('helper properties');

it('prevents assigning helper properties', function (string $property, string $class) {
    $number = new Number(7);
    $replacement = new $class(8);

    expect(function () use ($number, $property, $replacement) {
        $number->$property = $replacement;
    })->toThrow(Error::class);
})->with('helper properties');

it('supports calculations and currency formatting through helper properties', function () {
    expect(new Number(3)->arithmetic->pow(2)->get())->toBe(9)
        ->and(new Number(90)->trigonometry->deg2rad()->sin()->get())->toBe(1.0)
        ->and(new Number(3)->pythagoras->hypotenuse(4)->get())->toBe(5.0)
        ->and((string) new Number(7)->currency->currency('USD')->locale('en_US'))->toBe('$7.00');
});
