# NumberClass

[![Tests](https://github.com/ReeStyle/number-class/actions/workflows/tests.yml/badge.svg)](https://github.com/ReeStyle/number-class/actions/workflows/tests.yml)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](composer.json)

Chainable numbers, geometry, trigonometry, comparisons, rounding, and currency formatting for PHP.
Start with a value, calculate, then read or format the result.

[Installation](#installation) · [Quick start](#quick-start) · [Examples](#examples) ·
[Documentation](#documentation) · [Development](#development) · [License](#license)

```php
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\number;

number(12)->add(3)->multiply(2)->get();              // 30
geometry(3)->circleSurface()->number->format(2);   // '28.27'
```

The examples assume Composer's autoloader is loaded; the quick start shows a complete setup.

## Installation

Requires **PHP 8.4 or later within PHP 8.x**. Install the PHP `intl` extension for full
currency amount formatting. Other calculations and bundled currency symbol lookup work without it.

For a published Packagist release:

```bash
composer require reestyle-it/number-class
```

To work from a checkout of this repository, run `composer install` in the project directory.
Classes and helper functions use the `ReeStyleIt\NumberClass` namespace; Composer loads the
helpers automatically.

## Quick start

```php
<?php

use ReeStyleIt\NumberClass\Number;
use function ReeStyleIt\NumberClass\number;

require 'vendor/autoload.php';

$original = number(12);
$result = $original->add(3)->multiply(2);

echo $result->get();   // 30
// Calculations return new instances.
echo $original->get(); // 12

// Constructors and helpers provide the same API.
echo new Number(1234.567)->format(2, ',', '.'); // 1.234,57
```

Every value class exposes its raw value through `get()` and the public readonly `value`
property. Its `number()` method and read-only `number` property create a fresh `Number`,
allowing you to continue calculations or format a result from another class.

Arithmetic methods on `Number` return `Arithmetic` instances. Continue calculating directly,
or use `number()` / `number` to access `Number`-specific formatting and type checks.

```php
use function ReeStyleIt\NumberClass\pythagoras;

$result = pythagoras(3)->hypotenuse(4);
echo $result->number->add(1)->get(); // 6
echo $result->number->format(2);      // 5.00
```

Every value class also inherits `compare()` and the read-only `compare` property from `Base`.
For example, `pythagoras(3)->hypotenuse(4)->compare->equals(5)` returns `true`.

On every value class, specialized classes are available through methods and read-only properties:
`arithmetic`, `currency`, `geometry`, `pythagoras`, `round`, `time`, and `trigonometry`.
These accessors are inherited from `Base`. Each access creates a fresh instance.
Examples prefer the property form; use methods when passing configuration arguments.
For geographic points, pass starting latitude and
optional elevation to `geometry()`; the stored number supplies longitude.

## Examples

### Arithmetic, comparisons, and rounding

```php
use function ReeStyleIt\NumberClass\compare;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\round;

number(10)->subtract(2)->divide(4)->get();     // 2
number(7)->intdiv(2)->get();                  // 3; integer division truncates toward zero
number(27)->sqrt(3)->get();                  // approximately 3.0
number(-27)->sqrt(3)->get();                 // approximately -3.0
number(5.5)->mod(2)->get();                  // 1.5

compare(7)->equals(7.0);                     // true
number(7)->compare->higherThanOrEqual(6);    // true

round(12.345)->decimals(2)->get();           // 12.35
number(2.5)->round->decimals(mode: RoundingMode::HalfEven)->get(); // 2.0
number(13)->round->nearest(5)->get();        // 15.0
number(1234.56)->format(2, ',', '.');       // '1.234,56'
```

`increase()` and `decrease()` remain aliases of `add()` and `subtract()`.
`Round::with()` aliases `decimals()`. The imported `round()` helper returns a `Round` object;
use `\round()` to call PHP's native rounding function from the same scope.

[Arithmetic reference](docs/Mathematical/Arithmetic.md) ·
[Comparison reference](docs/Number/Compare.md) · [Rounding reference](docs/Format/Round.md)

### Circles, ellipses, and geographic distances

```php
use function ReeStyleIt\NumberClass\geometry;

// Circle input is radius, except circleRadius(), which takes a stored diameter.
geometry(3)->circleSurface()->get();         // approximately 28.2743
geometry(3)->circleCircumference()->get();   // approximately 18.8496
geometry(3)->circleDiameter()->get();        // 6
geometry(6)->circleRadius()->get();          // 3

// Ellipse inputs are semi-axis lengths: half the full width and height.
geometry(3)->ellipseSurface(2)->get();       // approximately 18.8496
geometry(3)->ellipseCircumference(2)->get(); // approximately 15.8654

// Both points use longitude, latitude, elevation order.
$distance = geometry(-0.1278, 51.5074, 10)
    ->haversine(2.3522, 48.8566, 35)
    ->number;

echo $distance->divide(1000)->number->format(2); // 343.56 kilometres, London to Paris
```

Ellipse circumference uses Ramanujan's second approximation. Geographic coordinates use
degrees; elevation and distance use metres with the default radius. Haversine surface distance
is combined with elevation difference as a short-distance approximation. A custom radius
and both elevations must use the same length unit.

[Geometry reference and distance model](docs/Mathematical/Geometry.md)

### Trigonometry and right triangles

```php
use function ReeStyleIt\NumberClass\pythagoras;
use function ReeStyleIt\NumberClass\trigonometry;

trigonometry(90)->deg2rad()->sin()->get();              // 1.0
trigonometry(3)->atan2(4)->rad2deg()->get();             // approximately 36.87 degrees
pythagoras(3)->hypotenuse(4)->get();                    // 5.0
pythagoras(5)->leg(3)->get();                           // 4.0
```

Trigonometric functions use radians. `atan2($x)` treats the stored value as y.
For `hypotenuse($otherLeg)`, the stored value is one perpendicular side;
for `leg($otherLeg)`, it is the hypotenuse.

[Trigonometry reference](docs/Mathematical/Trigonometry.md) ·
[Pythagoras reference](docs/Mathematical/Pythagoras.md)

### Time starting points

```php
use function ReeStyleIt\NumberClass\time;

$start = time()->from('2024-10-17T14:00:00+02:00');
$timestamp = $start->from->getTimestamp(); // 1729166400
$epoch = time()->from(0);
$fromDate = time()->from(new DateTimeImmutable('2024-10-17T12:00:00Z'));
```

`from()` accepts Unix timestamps, date objects, and parseable date strings. It returns
a new `Time\Diff` instance, which stores a Carbon date in its public `from` property.
The `Time` property accessor uses the stored value: `time(60)->from->diff(90)->s`
returns `30`. Each method call or property read creates a fresh difference object.
Supply an endpoint to calculate immediately: `time()->from(60, 90)` returns a native
`DateInterval`. An explicit `null` endpoint uses now.
Invalid dates throw `TimeException`. Import the namespaced helper with `use function`;
PHP's native timestamp function remains available as `\time()`.

[Time reference](docs/Conversion/Time.md)

### Currency and locale formatting

Requires the PHP `intl` extension:

```php
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use function ReeStyleIt\NumberClass\currencyFormatter;

$formatter = currencyFormatter(1234.5)
    ->currency(Currencies::USD)
    ->locale(Locales::EN_US);

echo $formatter;                          // $1,234.50
$amount = $formatter->number->get();       // 1234.5
$symbol = Currencies::EUR->symbol(Locales::NL_NL); // '€'
$position = Locales::DE_DE->symbolPosition();     // 'suffix'
```

Currency defaults to EUR and `nl_NL`. Currency and locale setters configure the same formatter;
formatting happens through `__toString()`. Locale does not automatically select a currency.
The configured code is available through `currencyCode`; the `currency` property and
`currency()` with no argument create fresh formatters, as they do on other value classes.
Formatting changes the display, not the amount or exchange rate. Exact symbols and spacing
follow the installed ICU data.

Without `intl`, currency symbols fall back to bundled locale data. A locale's native currency
returns its stored symbol; another currency returns its code. Full amount formatting still
requires `intl`.

[Currency reference](docs/Format/Currency.md) ·
[Currency enum](docs/Enums/Currencies.md) · [Locale enum](docs/Enums/Locales.md)

## Documentation

Start with the **[complete usage index](docs/README.md)** for every class, enum, and exception.
Each value class page includes constructor, method, property, and helper examples.

| Class or guide | What it covers |
| --- | --- |
| [Number](docs/Number.md) | Main numeric API, type checks, arithmetic, formatting, and helper accessors |
| [Time](docs/Conversion/Time.md) | Starting points normalized to Carbon dates with Unix timestamps |
| [Base](docs/Base.md) | Shared storage, validation, `number()` / `number`, and `compare()` / `compare` |
| [Arithmetic](docs/Mathematical/Arithmetic.md) | Operators, powers, roots, and remainders |
| [Compare](docs/Number/Compare.md) | Equality and ordered comparisons |
| [Geometry](docs/Mathematical/Geometry.md) | Circle and ellipse measurements, coordinates, and haversine distance |
| [Pythagoras](docs/Mathematical/Pythagoras.md) | Right triangle lengths |
| [Trigonometry](docs/Mathematical/Trigonometry.md) | Angles, ratios, and conversions |
| [Round](docs/Format/Round.md) | Decimal precision, rounding modes, and nearest multiples |
| [Currency](docs/Format/Currency.md) | Currency amount formatting |
| [Currencies](docs/Enums/Currencies.md) / [Locales](docs/Enums/Locales.md) | Codes, symbols, and locale metadata |
| [Helpers](docs/helpers.md) | All namespaced factory functions |
| [Exceptions](docs/README.md#exceptions) | Named errors and their shared base classes |

Constructors accept mixed values. Numeric operations and conversion to `Number` require
an actual integer or float; numeric strings are rejected with `MustBeNumberException`.
Comparisons return booleans, display formatting returns strings, and calculations return value
objects. Division and remainder by zero throw PHP's `DivisionByZeroError`.
Calculations use native PHP integers and floats, including their precision and range limits.

## Development

```bash
composer install
composer validate --strict
composer dump-autoload --optimize --strict-psr
composer test
```

Pest tests mirror the source namespaces under `tests/Unit`. GitHub Actions runs tests on
PHP 8.4 and 8.5 and checks currency symbol fallback without `intl`.

When locale cases change, regenerate the bundled currency metadata with `intl` available:

```bash
composer generate:symbols
```

[Changelog](CHANGELOG.md) · [Release preparation](RELEASING.md)

## License

See [license.txt](license.txt) for the supplied license terms.

## Note

This is my first AI-first project, although I keep an eye on every written line of code.
