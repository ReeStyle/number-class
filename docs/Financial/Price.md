# Price

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Financial\Price` is a readonly decimal price value object.
It requires `ext-bcmath` and PHP 8.4 or later. Construct it with `new Price($value)`
or use the namespaced `price($value)` helper. Values can be integers, plain decimal
strings, or another `Price`. Float values, scientific notation, localized numbers,
and whitespace are rejected with `FinancialException`.

```php
use function ReeStyleIt\NumberClass\price;

price('0.10')->add('0.20')->get(); // '0.30'
$unit = price('19.95');
$total = $unit->multiply(3)->discount(15)->withTax(21);
$total->format(); // '61.56'
$unit->get(); // '19.95'; source is unchanged
price('121.00')->withoutTax(21)->format(); // '100.00'
```

## Calculations

Every calculation returns a new `Price`; `get()`, the public readonly `value`, and
string casting return the stored decimal string. `Price` is separate from `Base`
and `Number`, whose numeric operations use native integers and floats. It does not
implicitly convert to a floating-point `Number` or currency formatter.

| Method | Result |
| --- | --- |
| `add($amount): Price` | Add another amount |
| `subtract($amount): Price` | Subtract another amount |
| `multiply($quantity): Price` | Multiply by a quantity |
| `divide($divisor, int $scale = 12): Price` | Divide, truncating to the requested fractional scale |
| `percentage($percent): Price` | Calculate that percentage of the price |
| `discount($percent): Price` | Subtract a percentage discount, between 0 and 100 inclusive |
| `withTax($percent): Price` | Add a non-negative tax percentage |
| `withoutTax($percent, int $scale = 12): Price` | Recover the net amount from a tax-inclusive price |
| `round(int $precision = 2, RoundingMode $mode = RoundingMode::HalfAwayFromZero): Price` | Explicitly round the amount |
| `format(int $decimals = 2, RoundingMode $mode = RoundingMode::HalfAwayFromZero): string` | Round and pad to a fixed number of decimals |

Amount, quantity, divisor, and percentage arguments accept the same values as the
constructor: integers, plain decimal strings, or `Price` instances. Signed amounts
are supported for refunds and credits. Zero divisors throw `DivisionByZeroError`;
negative division scales or display decimal counts throw `FinancialException`.
Negative precision is allowed by `round()` to round to tens, hundreds, and so on.

Percentages use whole percentage units: `15` or `'15'` means 15%, and `'2.5'` means
2.5%. Unlike the annual rates of compound interest and amortization, these arguments
are not decimal fractions. Pass them directly rather than converting with `toPct()`.

## Precision and rounding

Addition, subtraction, multiplication, percentages, discounts, and adding tax retain
the full decimal precision of their inputs. Division and removing tax truncate at
an explicit scale (12 fractional places by default). No operation uses or changes
the global `bcscale()` setting. Round once at the desired billing boundary:

```php
use RoundingMode;

price('0.004')->multiply(3)->format(); // '0.01'
price('1.005')->format(); // '1.01'
price('1.005')->format(mode: RoundingMode::HalfEven); // '1.00'
price(1)->divide(3, scale: 4)->get(); // '0.3333'
```

Rounding uses PHP's [BCMath bcround()](https://www.php.net/manual/en/function.bcround.php).
Formatting provides a plain decimal string, without currency symbols or grouping.
Input and arithmetic validation failures use
[FinancialException](../Exceptions/FinancialException.md), while type errors for
scale and rounding-mode arguments follow PHP's native argument handling.
