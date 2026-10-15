# Usage documentation

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
| [Base](Base.md) | Shared constructor, public readonly value, `get()`, and numeric validation |
| [Number](Number.md) | Main entry point, arithmetic, type checks, display formatting, and helper accessors |
| [Number\Compare](Number/Compare.md) | Equality and ordered comparisons |
| [Mathematical\Arithmetic](Mathematical/Arithmetic.md) | Chainable arithmetic, powers, roots, and remainders |
| [Mathematical\Geometry](Mathematical/Geometry.md) | Circle and ellipse measurements, and haversine great-circle distances |
| [Mathematical\Pythagoras](Mathematical/Pythagoras.md) | Hypotenuse and missing leg calculations |
| [Mathematical\Trigonometry](Mathematical/Trigonometry.md) | Trigonometric functions and angle conversions |
| [Format\Round](Format/Round.md) | Decimal rounding, rounding modes, and nearest multiples |
| [Format\Currency](Format/Currency.md) | Currency amount formatting with a configurable currency and locale |

All names in this index are relative to `ReeStyleIt\NumberClass`.
Calculations return a new instance of their class; call `get()` to obtain its raw result.
`Number` helper accessors create instances of the corresponding helper class.
Currency configuration methods return and update the same formatter.

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
| [GeometryException](Exceptions/GeometryException.md) | Invalid shape lengths, coordinates, or sphere radius |
| [InvalidCurrencyCodeException](Exceptions/InvalidCurrencyCodeException.md) | A currency code without exactly three ASCII letters |
| [MustBeFiniteCurrencyValue](Exceptions/MustBeFiniteCurrencyValue.md) | An infinite or NaN currency amount |
| [MustBeNumberException](Exceptions/MustBeNumberException.md) | A stored value that is neither an integer nor a float |
| [MustBePositiveNumberException](Exceptions/MustBePositiveNumberException.md) | An invalid root exponent or rounding multiple |
| [PythagorasException](Exceptions/PythagorasException.md) | Invalid right triangle lengths |
| [RoundingException](Exceptions/RoundingException.md) | An invalid integer rounding mode |

Division and remainder operations with a zero divisor throw PHP's native `DivisionByZeroError`,
including `0 / 0`. PHP may also throw native type, enum conversion, or engine errors;
the named exceptions above do not replace those errors.
