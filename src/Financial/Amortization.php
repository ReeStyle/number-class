<?php

namespace ReeStyleIt\NumberClass\Financial;

use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Financial\Amortization\Total;
use ReeStyleIt\NumberClass\Number;

class Amortization extends Calculation
{
    public function payment(int|float $annualRate, int $periods, int $paymentsPerYear = 12): Number
    {
        $this->assertTerms($annualRate, $periods, $paymentsPerYear);
        $rate = $annualRate / $paymentsPerYear;

        if ($rate == 0) {
            return $this->result((float) $this->value / $periods);
        }

        // Discounting avoids overflowing (1 + rate) ** periods on long loans.
        $factor = $rate / -expm1(-$periods * log1p($rate));

        return $this->result($this->value * $factor);
    }

    /**
     * @return list<array{period: int, payment: float, interest: float, principal: float, balance: float}>
     */
    public function schedule(int|float $annualRate, int $periods, int $paymentsPerYear = 12): array
    {
        $payment = $this->payment($annualRate, $periods, $paymentsPerYear)->get();
        $rate = $annualRate / $paymentsPerYear;
        $balance = (float) $this->value;
        $schedule = [];

        for ($period = 1; $period <= $periods; $period++) {
            $interest = $this->result($balance * $rate)->get();
            $principal = $period === $periods ? $balance : min($balance, max(0.0, $payment - $interest));
            $balance = max(0.0, $balance - $principal);
            $schedule[] = [
                'period' => $period,
                'payment' => $this->result($principal + $interest)->get(),
                'interest' => $interest,
                'principal' => $principal,
                'balance' => $balance,
            ];
        }

        return $schedule;
    }

    public function total(int|float $annualRate, int $periods, int $paymentsPerYear = 12): Total
    {
        $payments = $this->result($this->payment($annualRate, $periods, $paymentsPerYear)->get() * $periods);
        $interest = $this->result(max(0.0, $payments->get() - $this->value));

        return new Total($payments, $interest);
    }

    private function assertTerms(int|float $annualRate, int $periods, int $paymentsPerYear): void
    {
        $this->assertPrincipalAndRate($annualRate);

        if ($periods <= 0) {
            throw new FinancialException('Periods must be a positive integer.');
        }

        if ($paymentsPerYear <= 0) {
            throw new FinancialException('Payments per year must be a positive integer.');
        }
    }
}
