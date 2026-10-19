<?php

namespace ReeStyleIt\NumberClass\Financial;

use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Number;

class CompoundInterest extends Calculation
{
    public function futureValue(int|float $annualRate, int|float $years, int $compoundsPerYear = 1): Number
    {
        $growth = $this->growth($annualRate, $years, $compoundsPerYear);

        return $this->result($this->value == 0 ? 0.0 : $this->value * exp($growth));
    }

    public function interest(int|float $annualRate, int|float $years, int $compoundsPerYear = 1): Number
    {
        $growth = $this->growth($annualRate, $years, $compoundsPerYear);

        return $this->result($this->value == 0 ? 0.0 : $this->value * expm1($growth));
    }

    private function growth(int|float $annualRate, int|float $years, int $compoundsPerYear): float
    {
        $this->assertPrincipalAndRate($annualRate);

        if (! is_finite($years) || $years < 0) {
            throw new FinancialException('Years must be a non-negative finite number.');
        }

        if ($compoundsPerYear <= 0) {
            throw new FinancialException('Compounds per year must be a positive integer.');
        }

        // log1p retains precision when the periodic rate is close to zero.
        return (log1p($annualRate / $compoundsPerYear) * $compoundsPerYear) * $years;
    }
}
