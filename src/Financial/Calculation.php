<?php

namespace ReeStyleIt\NumberClass\Financial;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Number;

abstract class Calculation extends Base
{
    protected function assertPrincipalAndRate(int|float $annualRate): void
    {
        $this->assertNumber();

        if (! is_finite($this->value) || $this->value < 0) {
            throw new FinancialException('Principal must be a non-negative finite number.');
        }

        if (! is_finite($annualRate) || $annualRate < 0) {
            throw new FinancialException('Annual rate must be a non-negative finite decimal.');
        }
    }

    protected function result(int|float $value): Number
    {
        if (! is_finite($value)) {
            throw new FinancialException('Financial result exceeds the supported numeric range.');
        }

        return new Number($value);
    }
}
