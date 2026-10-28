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
- `schedule(...): Amortization\Schedule` returns the repayment result described below.

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
$first = $schedule[0]; // Line
$first->period;        // 1
$first->payment;       // 100.0
$first->balance;       // 1100.0
$lastBalance = $schedule[11]->balance; // 0.0
$schedule->first();                    // Same row as $schedule[0]
$schedule->last->balance;            // 0.0
$schedule->column('period'); // [1, 2, ..., 12]
```

The `Financial\Amortization\Schedule` result contains a list with one row per payment. Each row contains a one-based integer
`period` and numeric `payment`, `interest`, `principal`, and remaining `balance`.
The final payment is adjusted to repay the remaining balance exactly.
Use `total()` for payment and interest totals.
No amounts are rounded internally. Format monetary values at the display boundary;
this is not a currency-rounded ledger. The schedule is generated in memory, so its
size grows with the period count. All operations preserve the original principal.

Nonnumeric stored principal throws `MustBeNumberException`. Invalid terms or nonfinite
results throw [FinancialException](../Exceptions/FinancialException.md).

## Schedule collection API

The result supports zero-based array reads (`$schedule[0]`), `isset()`, `foreach`,
`count($schedule)`, and `json_encode($schedule)`. Array access and iteration yield readonly
`Financial\Amortization\Schedule\Line` objects. The readonly `rows` property contains
the list of line objects; `toArray()` converts them into a nested array of field values.

| Member | Behavior |
| --- | --- |
| `first(): ?Line` / `$first` | First row, or `null` when empty |
| `last(): ?Line` / `$last` | Last row, or `null` when empty |
| `get(int $index): ?Line` | Row at the index, or `null` when absent |
| `count(): int` | Number of rows |
| `isEmpty(): bool` | Whether there are no rows |
| `column(string $column): array` | Values for a row field, using `array_column()` semantics |
| `toArray(): array` | Plain list for native PHP array functions |

Schedule indexes are zero-based; line period numbers are one-based. Missing array offsets
return `null`. Assigning or unsetting rows through
array access throws `FinancialException`; editing a copy returned by `toArray()` leaves
the schedule unchanged.

`schedule()` now returns an object instead of a plain array. Native functions requiring
an actual array need `toArray()`, for example `array_column($schedule->toArray(), 'period')`.
Other transformations can use native array functions on `$schedule->rows` for line objects
or `$schedule->toArray()` for plain arrays.

## Schedule lines

Each repayment is a separate `Financial\Amortization\Schedule\Line` object with readonly fields:

| Property | Type | Meaning |
| --- | --- | --- |
| `period` | `int` | One-based payment period |
| `payment` | `float` | Payment amount |
| `interest` | `float` | Interest portion |
| `principal` | `float` | Principal repaid |
| `balance` | `float` | Remaining principal |

Use `$schedule[0]->balance` for a field, and `$schedule[0]->toArray()` for the line's
plain array representation. `first()`, `last()`, `get()`, array access, and iteration return the same
line objects, preserving their identity and original period numbers. Properties cannot
be reassigned. Both individual lines and complete schedules serialize to the original
JSON object/list shape.

## Line display values

The read-only `$line->wordify` property and `wordify()` method return a
`Financial\Amortization\Schedule\Line\Wordify` result with all five fields as strings.
The period is an ordinal word; payment, interest, principal, and balance use the existing
currency formatter, including its rounding and currency-specific minor units.

```php
use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;
use function ReeStyleIt\NumberClass\amortization;

$line = amortization(1200)->schedule(0, 12)[0];
$display = $line->wordify(Currency::USD, Locale::EN_US);
$display->period;    // 'first'
$display->payment;   // '$100.00'
$display->interest;  // '$0.00'
$display->principal; // '$100.00'
$display->balance;   // '$1,100.00'
$display->toArray(); // The same fields as a plain array of strings.
```

The signature is `wordify(Currency|string $currency = Currency::EUR, Locale|string|null $locale = null): Wordify`.
The property uses these defaults: euros and the current global locale, or `en_US` if no
global locale is set. The selected locale applies to every field. Each access creates a
new display result, so later global locale changes apply to subsequent accesses.
The display fields are readonly and support `toArray()` and JSON serialization.

Formatting requires `intl`. Numeric line properties, `Line::toArray()`, and the original
line's JSON remain unchanged; display rounding only affects the returned strings.
