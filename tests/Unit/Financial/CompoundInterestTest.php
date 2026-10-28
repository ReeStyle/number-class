<?php

namespace Tests\Unit\Financial;

use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Financial\CompoundInterest;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\compoundInterest;
use function ReeStyleIt\NumberClass\number;

it('calculates compound growth and earned interest', function (float $rate, float $years, int $frequency, float $expected) {
    $investment = compoundInterest(1000);
    $result = $investment->futureValue($rate, $years, $frequency);

    expect($result)->toBeInstanceOf(Number::class)
        ->and($result->get())->toEqualWithDelta($expected, 1e-8)
        ->and($investment->interest($rate, $years, $frequency)->get())->toEqualWithDelta($expected - 1000, 1e-8)
        ->and($investment->get())->toBe(1000);
})->with([
    'annual'          => [0.05, 10.0, 1, 1628.894626777442],
    'monthly'         => [0.12, 1.0, 12, 1126.82503013197],
    'fractional year' => [0.21, 0.5, 1, 1100.0],
    'zero rate'       => [0.0, 10.0, 12, 1000.0],
    'zero term'       => [0.05, 0.0, 12, 1000.0],
]);

it('handles zero principal and very small rates', function () {
    expect(compoundInterest(0)->futureValue(1, 10000)->get())->toBe(0.0)
        ->and(compoundInterest(1000)->interest(1e-18, 1)->get())->toEqualWithDelta(1e-15, 1e-28);
});

it('supports shared accessors and continuing arithmetic', function () {
    $value = number(1000);

    expect($value->compoundInterest)->toBeInstanceOf(CompoundInterest::class)
        ->and($value->compoundInterest())->not->toBe($value->compoundInterest)
        ->and($value->compoundInterest->futureValue(0.1, 1)->add(50)->get())->toEqualWithDelta(1150, 1e-10);
});

it('rejects invalid compound interest terms', function (mixed $principal, float $rate, float $years, int $frequency) {
    expect(fn () => compoundInterest($principal)->futureValue($rate, $years, $frequency))->toThrow(FinancialException::class)
        ->and(fn () => compoundInterest($principal)->interest($rate, $years, $frequency))->toThrow(FinancialException::class);
})->with([
    [-1, 0.05, 1.0, 1], [INF, 0.05, 1.0, 1], [NAN, 0.05, 1.0, 1],
    [1000, -0.05, 1.0, 1], [1000, INF, 1.0, 1], [1000, NAN, 1.0, 1],
    [1000, 0.05, -1.0, 1], [1000, 0.05, INF, 1], [1000, 0.05, NAN, 1],
    [1000, 0.05, 1.0, 0], [1000, 0.05, 1.0, -1],
]);

it('rejects nonnumeric principal and overflow', function () {
    expect(fn () => compoundInterest('1000')->futureValue(0.05, 1))->toThrow(MustBeNumberException::class)
        ->and(fn () => compoundInterest(1000)->futureValue(1, 10000))->toThrow(FinancialException::class)
        ->and(fn () => compoundInterest(1000)->interest(1, 10000))->toThrow(FinancialException::class);
});
