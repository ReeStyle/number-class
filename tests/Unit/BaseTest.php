<?php

namespace Tests\Unit;

use Error;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Financial\Amortization;
use ReeStyleIt\NumberClass\Financial\CompoundInterest;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

dataset('value classes', [
    Amortization::class,
    CompoundInterest::class,
    Arithmetic::class,
    Compare::class,
    Currency::class,
    Geometry::class,
    Number::class,
    Pythagoras::class,
    Round::class,
    Time::class,
    Trigonometry::class,
]);

it('exposes the stored value through its getter', function () {
    $value = new class('example') extends Base {};

    expect($value->get())->toBe('example');
});

it('exposes the stored value as a public readonly property', function () {
    $value = new class(7) extends Base {};

    expect($value->value)->toBe(7)
        ->and($value->get())->toBe(7);
});

it('prevents changing an initialized readonly value', function () {
    $value = new class(7) extends Base {};
    expect(function () use ($value) {
        $value->value = 8; // This is an attempt to modify a readonly property, should throw an error
    })->toThrow(Error::class)
        ->and($value->get())->toBe(7);
});

it('provides fresh Number instances through methods and properties', function (string $class, int|float $raw) {
    $value = new $class($raw);
    $method = $value->number();
    $property = $value->number;

    expect($method)->toBeInstanceOf(Number::class)
        ->not->toBe($value)
        ->and($property)->toBeInstanceOf(Number::class)
        ->not->toBe($value)->not->toBe($method)
        ->and($value->number)->not->toBe($property)
        ->and($value->number())->not->toBe($method)
        ->and($method->get())->toBe($raw)
        ->and($property->get())->toBe($raw)
        ->and($value->get())->toBe($raw);
})->with('value classes')->with([7, 7.5]);

it('validates numeric values before providing a Number instance', function (mixed $raw) {
    $value = new class($raw) extends Base {};

    expect(fn () => $value->number())->toThrow(MustBeNumberException::class)
        ->and(fn () => $value->number)->toThrow(MustBeNumberException::class)
        ->and(fn () => $value->compare())->toThrow(MustBeNumberException::class)
        ->and(fn () => $value->compare)->toThrow(MustBeNumberException::class);
})->with([
    'numeric string' => ['7'],
    'boolean' => [true],
    'null' => [null],
    'array' => [[7]],
    'object' => [new \stdClass],
]);

it('validates conversion on every value class', function (string $class) {
    $value = new $class('7');

    expect(fn () => $value->number())->toThrow(MustBeNumberException::class)
        ->and(fn () => $value->number)->toThrow(MustBeNumberException::class)
        ->and(fn () => $value->compare())->toThrow(MustBeNumberException::class)
        ->and(fn () => $value->compare)->toThrow(MustBeNumberException::class);
})->with('value classes');

it('prevents assigning the Number property', function (string $class) {
    $value = new $class(7);

    expect(function () use ($value) {
        $value->number = new Number(8);
    })->toThrow(Error::class)
        ->and($value->get())->toBe(7);
})->with('value classes');

it('continues calculations from specialized results as a Number', function () {
    expect(new Geometry(3)->circleSurface()->number->divide(M_PI)->sqrt()->get())
        ->toEqualWithDelta(3.0, 1e-12)
        ->and(new Pythagoras(3)->hypotenuse(4)->number->add(1)->get())->toBe(6.0)
        ->and(new Round(12.345)->decimals(2)->number->format(2))->toBe('12.35');
});

it('uses the raw stored value when converting configured value objects', function () {
    $currency = new Currency(12.5)->currency('USD')->locale('en_US');
    $point = new Geometry(4, 52, 10);

    expect($currency->number->get())->toBe(12.5)
        ->and($currency->currencyCode)->toBe('USD')
        ->and($currency->locale)->toBe('en_US')
        ->and($point->number->get())->toBe(4)
        ->and($point->latitude)->toBe(52)
        ->and($point->elevation)->toBe(10);
});

it('provides fresh Compare instances through methods and properties on every value class', function (string $class) {
    $value = new $class(7.5);
    $method = $value->compare();
    $property = $value->compare;

    expect($method)->toBeInstanceOf(Compare::class)->not->toBe($value)
        ->and($property)->toBeInstanceOf(Compare::class)->not->toBe($value)->not->toBe($method)
        ->and($value->compare())->not->toBe($method)
        ->and($value->compare)->not->toBe($property)
        ->and($method->get())->toBe(7.5)
        ->and($property->get())->toBe(7.5)
        ->and($method->higherThan(7))->toBeTrue()
        ->and($property->equals(new Number(7.5)))->toBeTrue()
        ->and($value->get())->toBe(7.5);
})->with('value classes');

it('prevents assigning the inherited Compare property', function (string $class) {
    $value = new $class(7);

    expect(function () use ($value) {
        $value->compare = new Compare(8);
    })->toThrow(Error::class)
        ->and($value->get())->toBe(7);
})->with('value classes');

it('compares calculation results directly through the shared base', function () {
    expect(new Geometry(3)->circleDiameter()->compare->equals(6))->toBeTrue()
        ->and(new Pythagoras(3)->hypotenuse(4)->compare->higherThanOrEqual(5))->toBeTrue()
        ->and(new Round(12.345)->decimals(2)->compare->lowerThan(13))->toBeTrue();
});
