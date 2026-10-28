# Number

[Usage index](README.md)

`ReeStyleIt\NumberClass\Number` is the main numeric value class. It extends [Base](Base.md),
inheriting `__construct(mixed $value)`, `public readonly mixed $value`, and `get(): mixed`.

## Create and calculate

Arithmetic operations delegate to [Arithmetic](Mathematical/Arithmetic.md) and return its new
`Arithmetic` result directly; they leave the original `Number` unchanged.

```php
use ReeStyleIt\NumberClass\Number;

$price = new Number(10);
$total = $price->add(5)->multiply(2)->subtract(4)->divide(2);
$total->get(); // 13
$price->get(); // 10
```

## Arithmetic methods

Every method below returns `Mathematical\Arithmetic`. Parameters marked `int|float` accept either numeric type.
See [Arithmetic](Mathematical/Arithmetic.md) for detailed root, remainder, and numeric behavior.

| Method | Example result from `get()` |
| --- | --- |
| `abs()` | `new Number(-8)->abs()->get()` → `8` |
| `add(int|float $amount = 1)` | `new Number(8)->add(2)->get()` → `10` |
| `ceil()` | `new Number(8.2)->ceil()->get()` → `9.0` |
| `decrease(int|float $amount = 1)` | Alias of `subtract()`; `8` becomes `7` by default |
| `divide(int|float $divisor)` | `new Number(8)->divide(2)->get()` → `4` |
| `floor()` | `new Number(8.9)->floor()->get()` → `8.0` |
| `increase(int|float $amount = 1)` | Alias of `add()`; `8` becomes `9` by default |
| `intdiv(int $divisor)` | `new Number(7)->intdiv(2)->get()` → `3`; stored value must be an integer |
| `mod(int|float $divisor)` | `new Number(8)->mod(3)->get()` → `2` |
| `multiply(int|float $factor)` | `new Number(8)->multiply(2)->get()` → `16` |
| `negate()` | `new Number(8)->negate()->get()` → `-8` |
| `pow(int|float $exponent)` | `new Number(8)->pow(2)->get()` → `64` |
| `sqrt(int $exponent = 2)` | `new Number(27)->sqrt(3)->get()` → approximately `3.0` |
| `subtract(int|float $amount = 1)` | `new Number(8)->subtract(2)->get()` → `6` |

```php
use ReeStyleIt\NumberClass\Number;

$squareRoot = new Number(81)->sqrt()->get();       // 9.0
$cubeRoot = new Number(-27)->sqrt(3)->get();        // approximately -3.0
$power = new Number(2)->pow(-2)->get();            // 0.25
$remainder = new Number(5.5)->mod(2)->get();       // 1.5
$negative = new Number(4)->negate()->abs()->get(); // 4
```

Arithmetic results can continue through other arithmetic operations and the inherited helper
accessors. Use `number()` or the `number` property when calling `Number`-specific methods:

```php
use function ReeStyleIt\NumberClass\number;

$result = number(9)->add(3)->divide(4); // Arithmetic containing 3
$text = $result->number->format(2);   // '3.00'
$isNumber = $result->number->isNumber(); // true
```

## Type checks

`isInt()`, `isFloat()`, `isNumber()`, and `isNumeric()` all return `bool`.
`isNumeric()` aliases `isNumber()` and checks actual PHP numeric types, unlike PHP's `is_numeric()`.

```php
use ReeStyleIt\NumberClass\Number;

$integer = new Number(7);
$integer->isInt();     // true
$integer->isFloat();   // false
$integer->isNumber();  // true
$integer->isNumeric(); // true

$float = new Number(7.0);
$float->isFloat(); // true

$string = new Number('7');
$string->get();      // '7'; storage itself permits mixed values
$string->isNumber(); // false; arithmetic would throw MustBeNumberException
```

## Display formatting

`format(int $decimals = 0, ?string $decimal_separator = '.', ?string $thousands_separator = ','): string`
uses PHP's `number_format()` parameters and returns text. It does not change the stored value.
Negative decimal counts round digits before the decimal point. Null separators use PHP's defaults.

```php
use ReeStyleIt\NumberClass\Number;

$number = new Number(1234.567);
$number->format();              // 1,235
$number->format(2);             // 1,234.57
$number->format(2, ',', '.');   // 1.234,57
$number->format(2, '.', '');    // 1234.57
$number->format(-2);            // 1,200
$number->format(decimals: 1, decimal_separator: ',', thousands_separator: ' '); // 1 234,6
```

For numeric rounding results use [Round](Format/Round.md). For currency text use
[Currency](Format/Currency.md).

## Helper methods and properties

All nine accessors work as a method or a read-only property. Each access creates a fresh instance
with the current value and validates that the value is numeric.

These properties and methods are inherited from [Base](Base.md#shared-helper-accessors),
so they are also available on every other value class.

| Method / property | Return type and usage |
| --- | --- |
| `arithmetic()` / `$arithmetic` | [Arithmetic](Mathematical/Arithmetic.md) |
| `compare()` / `$compare` | [Compare](Number/Compare.md), inherited from [Base](Base.md) |
| `currency()` / `$currency` | [Currency](Format/Currency.md) |
| `geometry(int|float|null $latitude = null, int|float $elevation = 0)` / `$geometry` | [Geometry](Mathematical/Geometry.md); provide latitude for geographic points, using the stored value as longitude |
| `number()` / `$number` | A fresh `Number` with the same value, inherited from [Base](Base.md) |
| `ordinal()` / `$ordinal` | [Ordinal](Conversion/Ordinal.md), locale-aware numeric ordinals |
| `pythagoras()` / `$pythagoras` | [Pythagoras](Mathematical/Pythagoras.md) |
| `round()` / `$round` | [Round](Format/Round.md) |
| `time()` / `$time` | [Time](Conversion/Time.md), retaining the stored value and supporting a date starting point |
| `trigonometry()` / `$trigonometry` | [Trigonometry](Mathematical/Trigonometry.md) |

```php
use ReeStyleIt\NumberClass\Number;

$number = new Number(3);
$equal = $number->compare->equals(3.0);              // true
$greater = $number->compare->higherThan(2);        // true
$product = $number->arithmetic->multiply(4)->get();  // 12
$length = $number->pythagoras->hypotenuse(4)->get();  // 5.0
$rounded = new Number(12.345)->round->decimals(2)->get(); // 12.35
$sine = new Number(90)->trigonometry->deg2rad()->sin()->get(); // 1.0
```

Keep a currency helper in a variable if you want to reuse its configuration:

```php
use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Number;

$formatter = new Number(25)->currency->currency(Currency::USD)->locale(Locale::EN_US);
(string) $formatter; // $25.00; requires intl
```

## Helper usage

```php
use function ReeStyleIt\NumberClass\number;

$result = number(12)->add(3)->multiply(2);
$result->get(); // 30
```

## Errors

Non-numeric stored values cause [MustBeNumberException](Exceptions/MustBeNumberException.md)
when performing arithmetic, formatting, or obtaining a helper. Type checks and `get()` remain usable.
Zero divisors, including `0 / 0`, throw `DivisionByZeroError`.
Root exponents at or below zero throw [MustBePositiveNumberException](Exceptions/MustBePositiveNumberException.md).
Even roots of negative numbers produce `NAN`. Floating point calculations use PHP's native precision.

## Continue with a Number

Both `number(): Number` and the read-only `number` property return a fresh
`ReeStyleIt\NumberClass\Number` containing the raw stored value. Each access validates that
the value is an integer or float; otherwise it throws `MustBeNumberException`.
The source instance remains unchanged.

```php
use ReeStyleIt\NumberClass\Number;

$value = new Number(6);
$converted = $value->number->add(1)->get(); // 7
$viaProperty = $value->number->multiply(2)->get(); // 12
```

See [the shared base API](Base.md) for numeric validation and storage.

## Numbers as words

`wordify(Locale|string|null $locale = null): string` spells out the stored number as
cardinal words. The read-only `wordify` property calls the method without a locale override.
The separate internal `ReeStyleIt\NumberClass\Number\Wordify` class performs conversion.

```php
use function ReeStyleIt\NumberClass\number;

number(21)->wordify;                // 'twenty-one'
number(21)->wordify('nl');          // 'eenentwintig'
number(1.25)->wordify('en');         // 'one point two five'
number(21)->ordinal->wordify;       // 'twenty-first'
number(20)->add(1)->number->wordify; // 'twenty-one'
```

Without an explicit locale, conversion uses the current [global locale](Base.md#global-default-locale),
or `en_US` when no global override is set. Explicit locales can be strings or `Locale` cases.
The PHP `intl` extension is required. ICU controls spelling, grammatical form, and decimal
wording; optional soft hyphens are removed from the returned text. Floating point inputs
follow native precision. Stored values remain unchanged.

Values must be finite integers or floats within `Number::MIN_SAFE_INTEGER` and
`Number::MAX_SAFE_INTEGER`. Non-numeric inputs throw `MustBeNumberException`, blank
locales throw `EmptyLocaleException`, and nonfinite or out-of-range values, missing `intl`,
and ICU failures throw [NumberFormattingException](Exceptions/NumberFormattingException.md).
