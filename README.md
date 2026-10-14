# int-class

Chainable numeric values, arithmetic, comparisons, trigonometry, right triangle calculations, rounding, and currency formatting for PHP.

## Requirements

- PHP 8.4 or later within PHP 8.x.
- The PHP `intl` extension.

## Installation

After the package is published on Packagist:

```bash
composer require rafaeldenheijer/int-class
```

## Getting started

```php
<?php

require 'vendor/autoload.php';

use ReeStyleIt\IntClass\Number;
use ReeStyleIt\IntClass\Enums\Currencies;
use function ReeStyleIt\IntClass\number;
use function ReeStyleIt\IntClass\currencyFormatter;

$value = number(12)->increase(3)->multiply(2);
echo $value->get(); // 30

// Constructors are also available.
$number = new Number(1234.56);
echo $number->format(2, ',', '.'); // 1.234,56

echo currencyFormatter(1234.5)
    ->currency(Currencies::USD)
    ->locale('en_US'); // $1,234.50
```

Classes and helper functions use the `ReeStyleIt\IntClass` namespace. Import the helpers with `use function` before calling them by their short names.

## Arithmetic

```php
use function ReeStyleIt\IntClass\number;

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
| `increase($amount = 1)` | Add an amount |
| `decrease($amount = 1)` | Subtract an amount |
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

Comparisons accept integers, floats, or another `Number`. Results are booleans; equality compares numeric values rather than their integer/float types. `compare` is available as a method or a read-only property.

## Rounding and number formatting

```php
number(1.236)->round()->with(2)->get(); // 1.24
number(2.5)->round->with(mode: RoundingMode::HalfEven)->get(); // 2.0
number(13)->round->nearest(5)->get(); // 15.0

number(1234.56)->format(); // "1,235"
number(1234.56)->format(2, ',', '.'); // "1.234,56"
```

`round` is available as a method or a read-only property. Its `with()` and `nearest()` methods return new `Format\Rounded` instances. `with()` follows PHP's `round()` precision and mode parameters. `nearest()` requires a positive finite multiple. `Number::format()` returns a string and follows the optional parameters of PHP's `number_format()`.

## Currency

```php
use ReeStyleIt\IntClass\Enums\Currencies;
use ReeStyleIt\IntClass\Format\Currency;
use function ReeStyleIt\IntClass\currencyFormatter;

echo number(1234.5)->currency(); // € 1.234,50
echo new Currency(1234.5)->currency('USD')->locale('en_US'); // $1,234.50
echo currencyFormatter(1234.5)->currency(Currencies::USD)->locale('en_US');

Currencies::EUR->symbol();        // €
Currencies::CAD->symbol('en_CA'); // $
```

Currency formatting defaults to EUR and `nl_NL`. Currency codes may be enum cases or three-letter strings. Currency symbols, spacing, decimal places, and separators follow the selected locale and the installed ICU data. Formatting changes the display only; it does not convert amounts between currencies.

The currency and locale configuration methods return the same formatter. The stored amount remains readonly. `Currency` is stringable through `__toString()`.

## Trigonometry and right triangles

```php
number(90)->trigonometry()->deg2rad()->sin()->get(); // 1.0
number(3)->trigonometry()->atan2(4)->rad2deg()->get(); // approximately 36.87

number(3)->pythagoras()->hypotenuse(4)->get(); // 5.0
number(5)->pythagoras()->leg(3)->get();        // 4.0
```

`Mathmatical\Trigonometry` provides `sin()`, `cos()`, `tan()`, `asin()`, `acos()`, `atan()`, `atan2($x)`, `deg2rad()`, and `rad2deg()`. Angles use radians unless explicitly converted. In `atan2($x)`, the stored value is the y coordinate.

For `Mathmatical\Pythagoras::hypotenuse($otherLeg)`, the stored value is one perpendicular side. For `leg($otherLeg)`, the stored value is the hypotenuse. Lengths must be non-negative and finite, and a leg cannot exceed the hypotenuse. Both methods return new instances.

## Value validation

`Number` stores a readonly `mixed` value, accessible with `get()`. `isInt()`, `isFloat()`, `isNumber()`, and its alias `isNumeric()` inspect the value. Calculations accept integer and float values and throw `UnexpectedValueException` for other stored types, including numeric strings. Currency formatting also rejects infinite values and `NAN`.

These calculations use native PHP integers and floats; they do not provide arbitrary-precision decimal arithmetic.

## Development

```bash
composer install
composer validate --strict
composer dump-autoload --optimize --strict-psr
composer test
```

Pest is a development dependency. Consumers need only PHP and `intl`. GitHub Actions checks PHP 8.4 and 8.5.

See [RELEASING.md](RELEASING.md) for the publication procedure.

## License

See [license.txt](license.txt) for the supplied license terms.
