# Amortization

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Financial\Amortization` extends `Calculation`, which extends
[Base](../Base.md). Its stored value is the initial loan principal. Use
`new Amortization(mixed $value)`, the namespaced `amortization($value)` helper,
or a value object's `amortization` property or method.

## Payments and totals

```php
use function ReeStyleIt\NumberClass\amortization;
use function ReeStyleIt\NumberClass\number;

$loan = amortization(100000);
$payment = $loan->payment(annualRate: 0.06, periods: 360); // Number
$payment->number->format(2); // '599.55'
$total = $loan->total(0.06, 360);
$total->payments->number->format(2); // '215,838.19'
$total->interest->number->format(2); // '115,838.19'
number(1200)->amortization->payment(0, 12)->get(); // 100.0
```

The payment, schedule, and total calculation methods accept `int|float $annualRate`, `int $periods`, and optional
`int $paymentsPerYear = 12`. `periods` is the total number of payments, not years:
360 monthly payments represent 30 years. Annual rates use decimals (`0.06` means 6%).
The periodic rate is `annualRate / paymentsPerYear`; compounding and payment frequency
are assumed equal. Payments occur at the end of equal periods, at a fixed rate,
without extra payments, fees, or date-based day counts.

- `payment(...): Number` returns the fixed payment per period.
- `total(...): Amortization\Total` calculates both totals once. Its readonly `payments`
  property holds a `Number` containing payment multiplied by periods; its readonly
  `interest` property holds a `Number` containing total payments less principal.
- `schedule(...): array` returns the repayment rows described below.

For principal P, periodic rate i, and N payments, payment is
`P * i / (1 - (1 + i) ** -N)`. At zero rate it is `P / N`.
The calculation uses `log1p` and `expm1` for stable small-rate and long-term results.
Principal and annual rate must be finite and non-negative. Period count and frequency
must be positive integers. Zero principal is accepted.

Totals belong to the readonly `Financial\Amortization\Total` class.
`$total = $loan->total(0.06, 360)` pre-calculates both results; read them through
`$total->payments` and `$total->interest` without further calculations.
Each call to `total()` returns a new result object.

## Repayment schedule

```php
$schedule = amortization(1200)->schedule(annualRate: 0, periods: 12);
$first = $schedule[0];
// ['period' => 1, 'payment' => 100.0, 'interest' => 0.0,
//  'principal' => 100.0, 'balance' => 1100.0]
$lastBalance = $schedule[11]['balance']; // 0.0
```

The schedule is a list with one row per payment. Each row contains a one-based integer
`period` and numeric `payment`, `interest`, `principal`, and remaining `balance`.
The final payment is adjusted to repay the remaining balance exactly. Summed schedule
payments and formula totals may differ slightly due to floating-point precision.
No amounts are rounded internally. Format monetary values at the display boundary;
this is not a currency-rounded ledger. The schedule is generated in memory, so its
size grows with the period count. All operations preserve the original principal.

Nonnumeric stored principal throws `MustBeNumberException`. Invalid terms or nonfinite
results throw [FinancialException](../Exceptions/FinancialException.md).
