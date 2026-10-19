<?php

namespace Tests\Unit;

use Error;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\StatisticalException;
use ReeStyleIt\NumberClass\Financial\Amortization;
use ReeStyleIt\NumberClass\Financial\CompoundInterest;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Dimension;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;
use ReeStyleIt\NumberClass\Statistical\BoxPlot;
use ReeStyleIt\NumberClass\Statistical\Median;
use ReeStyleIt\NumberClass\Statistical\Mode;

dataset('helper properties', [
    'dimension' => ['dimension', Dimension::class],
    'amortization' => ['amortization', Amortization::class],
    'compoundInterest' => ['compoundInterest', CompoundInterest::class],
    'compare' => ['compare', Compare::class],
    'round' => ['round', Round::class],
    'arithmetic' => ['arithmetic', Arithmetic::class],
    'geometry' => ['geometry', Geometry::class],
    'trigonometry' => ['trigonometry', Trigonometry::class],
    'pythagoras' => ['pythagoras', Pythagoras::class],
    'currency' => ['currency', Currency::class],
    'number' => ['number', Number::class],
    'time' => ['time', Time::class],
]);

dataset('helper sources', [
    Dimension::class,
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

dataset('statistical helper properties', [
    ['boxPlot', BoxPlot::class],
    ['median', Median::class],
    ['mode', Mode::class],
]);

it('creates fresh statistical calculations from arrays on every value class', function (
    string $property,
    string $class,
    string $sourceClass,
) {
    $values = [3, 1, 2, 2];
    $source = new $sourceClass($values);
    $helper = $source->$property;
    $method = $source->$property();

    expect($helper)->toBeInstanceOf($class)
        ->and($method)->toBeInstanceOf($class)->not->toBe($helper)
        ->and($helper->values)->toBe([1, 2, 2, 3])
        ->and($helper->get())->toEqual($method->get())
        ->and($source->get())->toBe($values);
})->with('statistical helper properties')->with('helper sources');

it('validates stored arrays before statistical calculations', function (string $property, string $class, mixed $value) {
    $source = new Number($value);

    expect(fn () => $source->$property)->toThrow(StatisticalException::class)
        ->and(fn () => $source->$property())->toThrow(StatisticalException::class);
})->with('statistical helper properties')->with([
    'scalar' => [7],
    'numeric string' => ['7'],
    'null' => [null],
    'empty array' => [[]],
    'array with numeric string' => [[1, '2']],
    'nonfinite' => [[1, INF]],
    'iterable object' => [new \ArrayIterator([1, 2])],
]);

it('calculates statistics through the shared entry point', function () {
    $source = new Number([3, 1, 2, 2]);

    expect($source->median->get())->toBe(2)
        ->and($source->mode->get())->toBe([2])
        ->and($source->boxPlot->median)->toBe(2);

    expect(function () use ($source) {
        $source->median = new Median([1]);
    })->toThrow(Error::class);
});

it('provides matching helper instances on every value class', function (
    string $property,
    string $class,
    string $sourceClass,
) {
    $number = new $sourceClass(7);
    $helper = $number->$property;
    $method = $number->$property();

    expect($helper)->toBeInstanceOf($class)
        ->not->toBe($number)
        ->and($helper->get())->toBe(7)
        ->and($method)->toBeInstanceOf($class)->not->toBe($helper)->not->toBe($number)
        ->and($method->get())->toBe(7)
        ->and($number->$property)->not->toBe($helper)
        ->and($number->$property())->not->toBe($method)
        ->and($number->get())->toBe(7);
})->with('helper properties')->with('helper sources');

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

it('validates values before accessing helpers on every value class', function (
    string $property,
    string $class,
    string $sourceClass,
) {
    expect(fn () => new $sourceClass('7')->$property)
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.')
        ->and(fn () => new $sourceClass('7')->$property())
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with('helper properties')->with('helper sources');

it('prevents assigning helper properties on every value class', function (
    string $property,
    string $class,
    string $sourceClass,
) {
    $number = new $sourceClass(7);
    $replacement = new $class(8);

    expect(function () use ($number, $property, $replacement) {
        $number->$property = $replacement;
    })->toThrow(Error::class);
})->with('helper properties')->with('helper sources');

it('supports calculations and currency formatting through helper properties', function () {
    expect(new Number(3)->arithmetic->pow(2)->get())->toBe(9)
        ->and(new Number(90)->trigonometry->deg2rad()->sin()->get())->toBe(1.0)
        ->and(new Number(3)->pythagoras->hypotenuse(4)->get())->toBe(5.0)
        ->and((string) new Number(7)->currency->currency('USD')->locale('en_US'))->toBe('$7.00');
});

it('switches between specialized classes while preserving calculation results', function () {
    expect(new Geometry(3)->circleDiameter()->arithmetic->add(1)->round->nearest(5)->get())->toBe(5.0)
        ->and(new Pythagoras(3)->hypotenuse(4)->geometry->circleDiameter()->get())->toBe(10.0)
        ->and(new Round(90)->trigonometry->deg2rad()->sin()->get())->toBe(1.0)
        ->and((string) new Arithmetic(7)->currency->currency('USD')->locale('en_US'))->toBe('$7.00');
});

it('passes geographic arguments through the inherited geometry method', function () {
    $source = new Arithmetic(4);
    $point = $source->geometry(latitude: 52, elevation: 10);

    expect($point->longitude)->toBe(4)
        ->and($point->latitude)->toBe(52)
        ->and($point->elevation)->toBe(10)
        ->and($point->haversine(4, 52, 110)->get())->toBe(100.0)
        ->and($source->get())->toBe(4);
});
