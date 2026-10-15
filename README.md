# Number class

---

Chainable numeric values, arithmetic, comparisons, trigonometry, right triangle calculations, rounding, and currency formatting for PHP.

---

## Requirements

- PHP 8.4 or later within PHP 8.x.
- The PHP `intl` extension for full currency amount formatting. Currency symbol lookup has a built-in fallback.


---

## Installation

After the package is published on Packagist:

```bash
composer require reestyle-it/number-class
```

## Getting started

See the [complete usage documentation](docs/README.md) for every class, enum, helper function, and exception.

```php
<?php

require 'vendor/autoload.php';

use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Enums\Currencies;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\currencyFormatter;

$value = number(12)->increase(3)->multiply(2);
echo $value->get(); // 30

// Constructors are also available.
$number = new Number(1234.56);
echo $number->format(2, ',', '.'); // 1.234,56

echo currencyFormatter(1234.5)
    ->currency(Currencies::USD)
    ->locale('en_US'); // $1,234.50
```

Classes and helper functions use the `ReeStyleIt\NumberClass` namespace. Import the helpers with `use function` before calling them by their short names.

All helper accessors work as methods or read-only properties: `arithmetic`, `compare`, `currency`, `geometry`, `pythagoras`, `round`, and `trigonometry`. For example, `number(3)->pythagoras->hypotenuse(4)->get()` returns `5.0`. Each access creates a helper instance containing the number's value.

Each concrete value class also has a factory helper accepting `mixed $value`: `number()`, `compare()`, `currencyFormatter()`, `arithmetic()`, `geometry()`, `pythagoras()`, `round()`, and `trigonometry()`.

```php
use function ReeStyleIt\NumberClass\arithmetic;
use function ReeStyleIt\NumberClass\pythagoras;
use function ReeStyleIt\NumberClass\round;
use function ReeStyleIt\NumberClass\trigonometry;

arithmetic(7)->add(3)->multiply(2)->get(); // 20
pythagoras(3)->hypotenuse(4)->get(); // 5.0
round(1.236)->decimals(2)->get(); // 1.24
trigonometry(90)->deg2rad()->sin()->get(); // 1.0
```

The imported `round()` helper creates a `Format\Round` instance. PHP's native rounding function remains accessible as `\round()`.

## Arithmetic

```php
use function ReeStyleIt\NumberClass\number;

$original = number(9);
$result = $original->increase(3)->multiply(2)->divide(4);

$result->get();   // 6
$original->get(); // 9

number(27)->sqrt(3)->get(); // 3.0 (cube root)
number(-27)->sqrt(3)->get(); // -3.0

// Access the dedicated calculation class.
number(9)->arithmetic()->pow(2)->get(); // 81
```

| Method | Operation |
| --- | --- |
| `add($amount = 1)` | Add an amount; `increase()` is an alias |
| `subtract($amount = 1)` | Subtract an amount; `decrease()` is an alias |
| `multiply($factor)` | Multiply |
| `divide($divisor)` | Divide |
| `pow($exponent)` | Raise to a power |
| `sqrt($exponent = 2)` | Calculate a root |
| `abs()` | Absolute value |
| `negate()` | Reverse the sign |
| `mod($divisor)` | Integer or floating-point remainder |
| `floor()` / `ceil()` | Round down / up |

Arithmetic methods return new instances. Division and remainder by zero throw `DivisionByZeroError`. Root exponents must be greater than zero; even roots of negative values return `NAN`.

## Comparisons

```php
number(7)->compare()->equals(7.0); // true
number(7)->compare->higherThan(6); // true
number(7)->compare->lowerThan(8);  // true
number(7)->compare->higherThanOrEqual(7); // true
number(7)->compare->lowerThanOrEqual(7);  // true
```

The `compare()` helper creates the comparison object directly:

```php
use function ReeStyleIt\NumberClass\compare;

compare(7)->equals(7.0); // true
compare(7)->higherThan(6); // true
```

Comparisons accept integers, floats, or another `Number`. Results are booleans; equality compares numeric values rather than their integer/float types. `compare` is available as a method or a read-only property.

## Rounding and number formatting

```php
number(1.236)->round()->decimals(2)->get(); // 1.24
number(2.5)->round->decimals(mode: RoundingMode::HalfEven)->get(); // 2.0
number(13)->round->nearest(5)->get(); // 15.0

number(1234.56)->format(); // "1,235"
number(1234.56)->format(2, ',', '.'); // "1.234,56"
```

`round` is available as a method or a read-only property. Its `decimals()` and `nearest()` methods return new `Format\Round` instances. `with()` is an alias of `decimals()`. `decimals()` follows PHP's `round()` precision and mode parameters. `nearest()` requires a positive finite multiple. `Number::format()` returns a string and follows the optional parameters of PHP's `number_format()`.

## Currency

```php
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Format\Currency;
use function ReeStyleIt\NumberClass\currencyFormatter;

echo number(1234.5)->currency(); // € 1.234,50
echo new Currency(1234.5)->currency('USD')->locale('en_US'); // $1,234.50
echo currencyFormatter(1234.5)->currency(Currencies::USD)->locale('en_US');

Currencies::EUR->symbol();        // €
Currencies::CAD->symbol('en_CA'); // $
```

Currency formatting defaults to EUR and `nl_NL`. Currency codes may be enum cases or three-letter strings. Currency symbols, spacing, decimal places, and separators follow the selected locale and the installed ICU data. Formatting changes the display only; it does not convert amounts between currencies.

`Currencies::symbol()` also works without `intl`, using one default currency symbol per supported locale. Locale strings accept underscores or hyphens; unknown locales fall back to `en_US`. When the requested currency differs from the locale's default currency, the fallback returns its currency code instead of using the wrong symbol. Full amount formatting still requires `intl` and throws `CurrencyFormattingException` if it is unavailable.

`Enums\Locales` provides common locale codes, including `Locales::NL_NL`, `Locales::EN_US`, and `Locales::EN_GB`. Both `Currency::locale()` and `Currencies::symbol()` accept an enum case or a locale string:

```php
use ReeStyleIt\NumberClass\Enums\Locales;

echo currencyFormatter(1234.5)->currency(Currencies::USD)->locale(Locales::EN_US);
Currencies::CAD->symbol(Locales::EN_CA); // $
```

German-speaking regional locales include `Locales::DE_DE` (Germany), `Locales::DE_AT` (Austria), and `Locales::DE_CH` (Switzerland). Use EUR for German/Austrian amounts and CHF for Swiss franc amounts.

Each locale exposes its default currency, its single symbol, and the symbol's position in a positive currency string:

```php
Locales::DE_DE->currency();       // Currencies::EUR
Locales::DE_DE->symbol();         // €
Locales::DE_DE->symbolPosition(); // suffix
Locales::DE_CH->symbol();         // CHF
Locales::DE_CH->symbolPosition(); // prefix
```

The currency and locale configuration methods return the same formatter. The stored amount remains readonly. `Currency` is stringable through `__toString()`.

## Geometry: circles, ellipses, and haversine distance

`Mathematical\Geometry` calculates circle and ellipse surfaces and circumferences,
and converts between circle radius and diameter. All methods return a new `Geometry`.

```php
use function ReeStyleIt\NumberClass\geometry;

geometry(3)->circleSurface()->get();         // approximately 28.2743; stored value is radius
geometry(3)->circleCircumference()->get();   // approximately 18.8496; stored value is radius
geometry(3)->circleDiameter()->get();        // 6; stored value is radius
geometry(6)->circleRadius()->get();          // 3; stored value is diameter
geometry(3)->ellipseSurface(2)->get();       // approximately 18.8496; inputs are semi-axis lengths
geometry(3)->ellipseCircumference(2)->get(); // approximately 15.8654; Ramanujan approximation
```

`Mathematical\Geometry` calculates the great-circle distance between two latitude/longitude pairs.
Construct the starting point with longitude, latitude, and elevation, then supply those same
three coordinates for the destination. Longitude and latitude use degrees; elevations and
returned distances use metres with the default sphere radius.

```php
use function ReeStyleIt\NumberClass\geometry;

// London to Paris, with elevations in metres and a sphere radius of 6,371,000 metres.
$distance = geometry(-0.1278, 51.5074, 10)->haversine(2.3522, 48.8566, 35)->get();
// Approximately 343556.061251 metres.
```

The result combines haversine surface distance and elevation difference using `hypot()`;
the elevation correction is a short-distance approximation. Use the optional `radius`
argument to choose another sphere or distance unit, keeping elevations in the same unit.
See [Geometry usage](docs/Mathematical/Geometry.md) for examples, validation, and `Number` access.

## Trigonometry and right triangles

```php
number(90)->trigonometry()->deg2rad()->sin()->get(); // 1.0
number(3)->trigonometry()->atan2(4)->rad2deg()->get(); // approximately 36.87

number(3)->pythagoras()->hypotenuse(4)->get(); // 5.0
number(5)->pythagoras()->leg(3)->get();        // 4.0
```

`Mathematical\Trigonometry` provides `sin()`, `cos()`, `tan()`, `asin()`, `acos()`, `atan()`, `atan2($x)`, `deg2rad()`, and `rad2deg()`. Angles use radians unless explicitly converted. In `atan2($x)`, the stored value is the y coordinate.

For `Mathematical\Pythagoras::hypotenuse($otherLeg)`, the stored value is one perpendicular side. For `leg($otherLeg)`, the stored value is the hypotenuse. Lengths must be non-negative and finite, and a leg cannot exceed the hypotenuse. Both methods return new instances.

## Value validation

`Base` exposes a public readonly `mixed` property named `value`, also accessible with `get()`. `Number` inherits it and provides `isInt()`, `isFloat()`, `isNumber()`, and its alias `isNumeric()` to inspect the value. Calculations accept integer and float values and throw `MustBeNumberException` for other stored types, including numeric strings. Currency formatting also rejects infinite values and `NAN`.

These calculations use native PHP integers and floats; they do not provide arbitrary-precision decimal arithmetic.

### Exceptions

The following exceptions are available under `ReeStyleIt\NumberClass\Exceptions`:

- `MustBeNumberException`: the stored value is not an integer or float.
- `RoundingException`: an integer rounding mode is outside the supported cases.
- `MustBePositiveNumberException`: a root exponent is not greater than zero, or a nearest-rounding multiple is not positive and finite.
- `PythagorasException`: a triangle length is negative or non-finite, or a leg exceeds the hypotenuse.
- `GeometryException`: a shape length is invalid, starting latitude is missing, coordinates or elevations are invalid, or a sphere radius is not positive and finite.

Currency formatting provides these additional named exceptions:

- `InvalidCurrencyCodeException`: the code is not three letters.
- `EmptyLocaleException`: the locale is empty or whitespace.
- `MustBeFiniteCurrencyValue`: the amount is infinite or `NAN`.
- `CurrencyFormattingException`: the formatter fails to produce a string.

The exceptions listed above extend `BaseInvalidArgumentException`, which extends PHP's `InvalidArgumentException`.

`Currencies::symbol()` throws `CurrencySymbolLookupException` for failures configuring or retrieving a currency symbol. This exception extends `BaseRuntimeException`, which extends PHP's `RuntimeException`, and preserves the formatter's error details.

Catch either package base class to handle its exception family together. Exception messages are defined inside the exception classes.

Division by zero continues to throw PHP's `DivisionByZeroError`.

## Development

Unit tests mirror the source namespaces under `tests/Unit`, with `Enums`, `Exceptions`, `Format`, `Mathematical`, and `Number` subfolders. Tests for root classes and helper functions stay directly under `tests/Unit`.

```bash
composer install
composer validate --strict
composer dump-autoload --optimize --strict-psr
composer test
```

Pest is a development dependency. Consumers need PHP; install `intl` for full currency formatting. GitHub Actions checks PHP 8.4 and 8.5 and includes a separate symbol lookup check without `intl`.

The fallback symbol data is stored in `src/Resources/currency-symbols.php`, which returns a flat array keyed by locale. Each entry has `currency`, `symbol`, and `position` (`prefix` or `suffix`). The array is loaded once per process. To regenerate it from the installed ICU data when locale cases change, run this command with `intl` available:

```bash
composer generate:symbols
```

See [RELEASING.md](RELEASING.md) for the publication procedure.

## License

See [license.txt](license.txt) for the supplied license terms.

## Note

This is my first full AI project, although I keep an eye on every written line of code.
