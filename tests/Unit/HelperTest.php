<?php

namespace Tests\Unit;

use Closure;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Conversion\Time\Diff;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

use function ReeStyleIt\NumberClass\compare;
use function ReeStyleIt\NumberClass\diff;
use function ReeStyleIt\NumberClass\arithmetic;
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\pythagoras;
use function ReeStyleIt\NumberClass\round;
use function ReeStyleIt\NumberClass\time;
use function ReeStyleIt\NumberClass\trigonometry;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\currencyFormatter;

it('creates a Number through the number helper without converting its value', function (mixed $value) {
    $number = number(value: $value);

    expect($number)->toBeInstanceOf(Number::class)
        ->and($number->get())->toBe($value);
})->with([
    'integer' => [7],
    'float' => [7.5],
    'numeric string' => ['7'],
]);

it('uses the helpers together in a calculation and currency formatting', function () {
    $number = number(12)->increase(3)->multiply(2);
    $formatter = currencyFormatter($number->get())->currency(Currencies::USD)->locale('en_US');

    expect($number->get())->toBe(30)
        ->and((string) $formatter)->toBe('$30.00');
});

it('creates a currency formatter with the supplied value', function () {
    $formatter = currencyFormatter(value: 1234.5);

    expect($formatter)->toBeInstanceOf(Currency::class)
        ->and($formatter->get())->toBe(1234.5)
        ->and((string) $formatter->currency(Currencies::USD)->locale('en_US'))->toBe('$1,234.50');
});

it('creates a Compare directly through the helper', function () {
    $comparison = compare(value: 7);

    expect($comparison)->toBeInstanceOf(Compare::class)
        ->and($comparison->get())->toBe(7)
        ->and($comparison->equals(7.0))->toBeTrue()
        ->and($comparison->higherThan(6))->toBeTrue()
        ->and($comparison->lowerThan(number(8)))->toBeTrue();
});

it('validates values from the compare helper when comparing', function () {
    expect(fn () => compare('7')->equals(7))->toThrow(MustBeNumberException::class);
});

it('creates arithmetic calculations through the helper', function () {
    $arithmetic = arithmetic(value: 7);

    expect($arithmetic)->toBeInstanceOf(Arithmetic::class)
        ->and($arithmetic->add(3)->multiply(2)->get())->toBe(20)
        ->and($arithmetic->get())->toBe(7);
});

it('creates geometry calculations through the helper', function () {
    $geometry = geometry(value: 0, latitude: 0, elevation: 10);

    expect($geometry)->toBeInstanceOf(Geometry::class)
        ->and($geometry->haversine(90, 0, 10, radius: 1)->get())->toEqualWithDelta(M_PI / 2, 1e-12)
        ->and($geometry->latitude)->toBe(0)
        ->and($geometry->longitude)->toBe(0)
        ->and($geometry->elevation)->toBe(10)
        ->and($geometry->get())->toBe(0);
});

it('creates Pythagoras calculations through the helper', function () {
    $pythagoras = pythagoras(value: 3);

    expect($pythagoras)->toBeInstanceOf(Pythagoras::class)
        ->and($pythagoras->hypotenuse(4)->leg(3)->get())->toBe(4.0)
        ->and($pythagoras->get())->toBe(3);
});

it('creates trigonometry calculations through the helper', function () {
    $trigonometry = trigonometry(value: 90);

    expect($trigonometry)->toBeInstanceOf(Trigonometry::class)
        ->and($trigonometry->deg2rad()->sin()->get())->toBe(1.0)
        ->and($trigonometry->get())->toBe(90);
});

it('creates rounding calculations through the helper', function () {
    $round = round(value: 1.236);

    expect($round)->toBeInstanceOf(Round::class)
        ->and($round->decimals(2)->get())->toBe(1.24)
        ->and($round->get())->toBe(1.236);
});

it('creates time starting points through the helper', function () {
    $duration = time()->from(0);

    expect($duration)->toBeInstanceOf(\ReeStyleIt\NumberClass\Conversion\Time\Diff::class)
        ->and($duration->from->getTimestamp())->toBe(0);
});

it('creates a difference directly through the helper', function () {
    $difference = diff(from: '1970-01-01T00:01:00Z');

    expect($difference)->toBeInstanceOf(Diff::class)
        ->and($difference->from->getTimestamp())->toBe(60)
        ->and($difference->diff(90)->s)->toBe(30);
});

it('validates values from the new helpers when calculating', function (Closure $calculate) {
    expect($calculate)->toThrow(MustBeNumberException::class);
})->with([
    'arithmetic' => [fn () => arithmetic('7')->add(1)],
    'geometry' => [fn () => geometry('0')->haversine(0, 0, 90)],
    'pythagoras' => [fn () => pythagoras('3')->hypotenuse(4)],
    'round' => [fn () => round('1.236')->decimals(2)],
    'time' => [fn () => time('60')->number],
    'trigonometry' => [fn () => trigonometry('90')->deg2rad()],
]);
