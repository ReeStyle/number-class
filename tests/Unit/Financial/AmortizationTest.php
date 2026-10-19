<?php

namespace Tests\Unit\Financial;

use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Financial\Amortization;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\amortization;
use function ReeStyleIt\NumberClass\number;

it('calculates payments and totals for a fixed rate loan', function () {
    $loan = amortization(100000);
    $payment = $loan->payment(0.06, 360);

    expect($payment)->toBeInstanceOf(Number::class)
        ->and($payment->get())->toEqualWithDelta(599.5505251527524, 1e-9)
        ->and($loan->total(0.06, 360)->payments->get())->toEqualWithDelta(215838.18905499086, 1e-7)
        ->and($loan->total(0.06, 360)->interest->get())->toEqualWithDelta(115838.18905499086, 1e-7)
        ->and($loan->get())->toBe(100000);
});

it('supports zero rate, zero principal, annual payments, and tiny rates', function () {
    expect(amortization(1200)->payment(0, 12)->get())->toBe(100.0)
        ->and(amortization(1200)->total(0, 12)->interest->get())->toBe(0.0)
        ->and(amortization(0)->payment(0.05, 12)->get())->toBe(0.0)
        ->and(amortization(1000)->payment(0.1, 1, 1)->get())->toEqualWithDelta(1100, 1e-10)
        ->and(amortization(1200)->payment(1e-18, 12)->get())->toEqualWithDelta(100, 1e-10);
});

it('produces a schedule whose principal and interest reconcile with the loan', function () {
    $loan = amortization(100000);
    $schedule = $loan->schedule(0.06, 360);
    $previous = 100000.0;

    foreach ($schedule as $index => $row) {
        expect($row['period'])->toBe($index + 1)
            ->and($row['interest'])->toEqualWithDelta($previous * 0.005, 1e-8)
            ->and($row['payment'])->toEqualWithDelta($row['principal'] + $row['interest'], 1e-8)
            ->and($row['balance'])->toEqualWithDelta($previous - $row['principal'], 1e-8);
        $previous = $row['balance'];
    }

    expect($schedule)->toHaveCount(360)
        ->and($schedule[359]['balance'])->toBe(0.0)
        ->and(array_sum(array_column($schedule, 'principal')))->toEqualWithDelta(100000, 1e-7)
        ->and(array_sum(array_column($schedule, 'interest')))->toEqualWithDelta($loan->total(0.06, 360)->interest->get(), 1e-6);
});

it('settles the last payment without rounding residuals', function () {
    $schedule = amortization(100)->schedule(0, 3);

    expect($schedule[2]['balance'])->toBe(0.0)
        ->and(array_sum(array_column($schedule, 'principal')))->toEqualWithDelta(100, 1e-12)
        ->and(array_sum(array_column($schedule, 'interest')))->toBe(0.0);
});

it('supports the shared amortization accessors', function () {
    $principal = number(1200);

    expect($principal->amortization)->toBeInstanceOf(Amortization::class)
        ->and($principal->amortization())->not->toBe($principal->amortization)
        ->and($principal->amortization->payment(0, 12)->get())->toBe(100.0);
});

it('rejects invalid amortization terms across all operations', function (mixed $principal, float $rate, int $periods, int $frequency) {
    foreach (['payment', 'schedule', 'total'] as $method) {
        expect(fn () => amortization($principal)->$method($rate, $periods, $frequency))->toThrow(FinancialException::class);
    }

})->with([
    [-1, 0.05, 12, 12], [INF, 0.05, 12, 12], [NAN, 0.05, 12, 12],
    [1000, -0.05, 12, 12], [1000, INF, 12, 12], [1000, NAN, 12, 12],
    [1000, 0.05, 0, 12], [1000, 0.05, -1, 12],
    [1000, 0.05, 12, 0], [1000, 0.05, 12, -1],
]);

it('rejects nonnumeric principal and overflowing results', function () {
    expect(fn () => amortization('1000')->payment(0.05, 12))->toThrow(MustBeNumberException::class)
        ->and(fn () => amortization(PHP_FLOAT_MAX)->payment(1, 1, 1))->toThrow(FinancialException::class);
});
