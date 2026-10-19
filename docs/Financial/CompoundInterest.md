# Compound interest

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Financial\CompoundInterest` extends `Calculation`, which extends
[Base](../Base.md). Its stored value is the initial principal. Construct it with
`new CompoundInterest(mixed $value)`, use the namespaced `compoundInterest($value)`
helper, or use another value object's `compoundInterest` property or method.

## Calculations

```php
use function ReeStyleIt\NumberClass\compoundInterest;
use function ReeStyleIt\NumberClass\number;

$investment = compoundInterest(1000);
$future = $investment->futureValue(annualRate: 0.05, years: 10); // Number
$future->number->format(2); // '1,628.89'
$earned = $investment->interest(0.05, 10)->get(); // approximately 628.894626777
$monthly = number(1000)->compoundInterest->futureValue(0.12, 1, compoundsPerYear: 12);
$monthly->number->format(2); // '1,126.83'
$investment->get(); // 1000; principal is preserved
```

- `futureValue(int|float $annualRate, int|float $years, int $compoundsPerYear = 1): Number`
- `interest(int|float $annualRate, int|float $years, int $compoundsPerYear = 1): Number`

Rates are nominal annual decimals: `0.05` means 5%, not `5`.
With principal P, annual rate r, frequency n, and years t, future value is
`P * (1 + r / n) ** (n * t)`. Earned interest excludes the initial principal.
Fractional years are allowed. Annual compounding is the default; use 12 for monthly,
4 for quarterly, or another positive integer frequency. Logarithmic calculations
preserve small-rate precision. This models a fixed rate without additional deposits,
withdrawals, fees, or taxes. Results use native floating point without currency rounding.

Principal, rate, and years must be finite and non-negative. Frequency must be positive.
Zero years or rate returns the original principal and zero interest. Zero principal
returns zero. Numeric strings as principal throw `MustBeNumberException` when calculating;
invalid financial inputs or nonfinite results throw
[FinancialException](../Exceptions/FinancialException.md).
