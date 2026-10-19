# Usage documentation

[Project homepage](../README.md) · [Helper functions](helpers.md) · [Changelog](../CHANGELOG.md)

NumberClass requires PHP 8.4 or later within PHP 8.x. Load Composer's autoloader once in your application:

```php
<?php

require 'vendor/autoload.php';

use function ReeStyleIt\NumberClass\number;

echo number(12)->add(3)->multiply(2)->get(); // 30
```

The examples on the following pages assume the autoloader is already loaded.
Each PHP code block can otherwise be run independently. Helpers are namespaced functions;
import them with `use function`. See the [helper reference](helpers.md) for all factories.

## Values and calculations

| Class | Usage |
| --- | --- |
| [Base](Base.md) | Shared storage, numeric validation, `number()` / `number`, and `compare()` / `compare` |
| [Number](Number.md) | Main entry point, arithmetic, type checks, display formatting, and helper accessors |
| [Conversion\Time](Conversion/Time.md) | Starting points from timestamps, date objects, and date strings |
| [Statistical\BoxPlot](Statistical/BoxPlot.md) | Quartiles, IQR, whiskers, and outliers |
| [Statistical\BoxPlot\Result](Statistical/BoxPlot.md) | Precomputed readonly box plot results |
| [Statistical\Median](Statistical/Median.md) | Median of a finite numeric sample |
| [Statistical\Mode](Statistical/Mode.md) | All most frequent sample values |
| [Number\Compare](Number/Compare.md) | Equality and ordered comparisons |
| [Mathematical\Arithmetic](Mathematical/Arithmetic.md) | Chainable arithmetic, powers, roots, and remainders |
| [Mathematical\Geometry](Mathematical/Geometry.md) | Circle and ellipse measurements, and haversine great-circle distances |
| [Mathematical\Pythagoras](Mathematical/Pythagoras.md) | Hypotenuse and missing leg calculations |
| [Mathematical\Trigonometry](Mathematical/Trigonometry.md) | Trigonometric functions and angle conversions |
| [Financial\Amortization](Financial/Amortization.md) | Fixed loan payments, interest totals, and repayment schedules |
| [Financial\Amortization\Total](Financial/Amortization.md) | Readonly access to payment and interest totals |
| [Financial\CompoundInterest](Financial/CompoundInterest.md) | Future value and earned compound interest |
| [Financial\Price](Financial/Price.md) | Exact BCMath decimal prices, percentages, discounts, and tax |
| [Format\Round](Format/Round.md) | Decimal rounding, rounding modes, and nearest multiples |
| [Format\Currency](Format/Currency.md) | Currency amount formatting with a configurable currency and locale |

All names in this index are relative to `ReeStyleIt\NumberClass`.
Calculations return a new value instance; `Number` arithmetic methods return `Arithmetic`.
Call `get()` to obtain the raw result, or `number()` / `number` to use `Number`-specific methods.
All value classes inherit helper accessors from [Base](Base.md#shared-helper-accessors),
creating instances of the corresponding helper class from the stored value.
Currency configuration methods return and update the same formatter.

Every value class also inherits `compare()` and the read-only `compare` property from `Base`.
For example, `geometry(3)->circleDiameter()->compare->equals(6)` returns `true`.

Every value class provides `number(): Number` and the read-only `number` property to continue
working with the stored value through the main numeric API:

```php
use function ReeStyleIt\NumberClass\geometry;

echo geometry(3)->circleSurface()->number->format(2); // 28.27
echo geometry(3)->circleDiameter()->number->add(1)->get(); // 7
```

Constructors accept `mixed $value`. Numeric operations require an actual PHP `int` or `float`;
numeric strings such as `'12'` are rejected when the operation validates the value.
Floating point results follow PHP's native precision and domain behavior.

## Enums

| Enum | Usage |
| --- | --- |
| [Enums\Currencies](Enums/Currencies.md) | Supported currency codes and locale-sensitive symbol lookup |
| [Enums\Locales](Enums/Locales.md) | Locale identifiers, native currencies, symbols, and symbol positions |

The namespace is `Enums` even though the source directory is named `Enum`.
Currency amount formatting requires the PHP `intl` extension. Enum metadata and the bundled
currency-symbol fallback can be used without it.

## Exceptions

| Class | Raised for / purpose |
| --- | --- |
| [BaseInvalidArgumentException](Exceptions/BaseInvalidArgumentException.md) | Common parent for argument and value validation errors |
| [BaseRuntimeException](Exceptions/BaseRuntimeException.md) | Common parent for runtime lookup errors |
| [CurrencyFormattingException](Exceptions/CurrencyFormattingException.md) | Missing `intl` or failed amount formatting |
| [CurrencySymbolLookupException](Exceptions/CurrencySymbolLookupException.md) | Failed ICU symbol lookup |
| [EmptyLocaleException](Exceptions/EmptyLocaleException.md) | An empty or whitespace-only locale |
| [FinancialException](Exceptions/FinancialException.md) | Invalid financial terms or nonfinite financial results |
| [GeometryException](Exceptions/GeometryException.md) | Invalid shape lengths, coordinates, or sphere radius |
| [InvalidCurrencyCodeException](Exceptions/InvalidCurrencyCodeException.md) | A currency code without exactly three ASCII letters |
| [MustBeFiniteCurrencyValue](Exceptions/MustBeFiniteCurrencyValue.md) | An infinite or NaN currency amount |
| [MustBeNumberException](Exceptions/MustBeNumberException.md) | A stored value that is neither an integer nor a float |
| [MustBeIntegerException](Exceptions/MustBeIntegerException.md) | A float stored value used with integer division |
| [MustBePositiveNumberException](Exceptions/MustBePositiveNumberException.md) | An invalid root exponent or rounding multiple |
| [PythagorasException](Exceptions/PythagorasException.md) | Invalid right triangle lengths |
| [RoundingException](Exceptions/RoundingException.md) | An invalid integer rounding mode |
| [StatisticalException](Exceptions/StatisticalException.md) | Empty or invalid statistical samples and box plot overflow |
| [TimeException](Exceptions/TimeException.md) | An invalid date or timestamp starting point |

Division and remainder operations with a zero divisor throw PHP's native `DivisionByZeroError`,
including `0 / 0`. PHP may also throw native type, enum conversion, or engine errors;
the named exceptions above do not replace those errors.

For stored numeric arrays, `Base` exposes `boxPlot`, `median`, and `mode` properties
and matching methods. See [statistical accessors](Base.md#statistical-accessors).
