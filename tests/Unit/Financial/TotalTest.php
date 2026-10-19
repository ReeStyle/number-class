<?php

namespace Tests\Unit\Financial;

use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Financial\Amortization;
use ReeStyleIt\NumberClass\Financial\Amortization\Total;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\amortization;

it('precalculates both totals with a single payment calculation', function () {
    $loan = new class(1200) extends Amortization {
        public int $calculations = 0;

        public function payment(int|float $annualRate, int $periods, int $paymentsPerYear = 12): Number
        {
            $this->calculations++;

            return parent::payment($annualRate, $periods, $paymentsPerYear);
        }
    };
    $total = $loan->total(0, 12);

    expect($total)->toBeInstanceOf(Total::class)
        ->and($loan->calculations)->toBe(1)
        ->and($total->payments->get())->toBe(1200.0)
        ->and($total->interest->get())->toBe(0.0)
        ->and($total->payments)->toBe($total->payments)
        ->and($loan->calculations)->toBe(1)
        ->and($loan->get())->toBe(1200);
});

it('keeps precalculated totals readonly and independent across calls', function () {
    $loan = amortization(1200);
    $total = $loan->total(0, 12);
    $other = $loan->total(0.06, 12);

    expect($other)->not->toBe($total)
        ->and($other->interest->get())->toBeGreaterThan(0)
        ->and($total->interest->get())->toBe(0.0);

    expect(function () use ($total) {
        $total->payments = new Number(0);
    })->toThrow(\Error::class);
});

it('rejects overflowing totals even when each payment is finite', function () {
    $loan = amortization(PHP_FLOAT_MAX / 2);

    expect(is_finite($loan->payment(1, 2, 1)->get()))->toBeTrue()
        ->and(fn () => $loan->total(1, 2, 1))->toThrow(FinancialException::class);
});
