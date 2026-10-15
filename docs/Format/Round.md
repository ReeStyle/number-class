# Round

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Format\Round` extends [Base](../Base.md).
Construct it with `new Round(mixed $value)`. Rounding methods return a new `Round` containing
a numeric result; the original stays unchanged. Read results through `get()` or readonly `$value`.

## Decimal precision

`decimals(int $precision = 0, int|RoundingMode $mode = RoundingMode::HalfAwayFromZero): self`
uses PHP's native `round()`. Positive precision rounds after the decimal point;
zero rounds to an integer-valued float; negative precision rounds tens, hundreds, and so on.

```php
use ReeStyleIt\NumberClass\Format\Round;

$original = new Round(1234.567);
$rounded = $original->decimals(2);
$rounded->get();              // 1234.57
$original->get();             // 1234.567
$original->decimals()->get(); // 1235.0
$original->decimals(-2)->get(); // 1200.0
```

`with(int $precision = 0, int|RoundingMode $mode = RoundingMode::HalfAwayFromZero): self`
is an alias of `decimals()` with identical behavior.

```php
use ReeStyleIt\NumberClass\Format\Round;

$result = new Round(12.345)->with(2)->get(); // 12.35
```

## Rounding modes

The global PHP `RoundingMode` enum supplies these cases:

| Mode | Behavior |
| --- | --- |
| `HalfAwayFromZero` | Nearest value, ties away from zero; the default |
| `HalfTowardsZero` | Nearest value, ties toward zero |
| `HalfEven` | Nearest value, ties toward the even digit |
| `HalfOdd` | Nearest value, ties toward the odd digit |
| `TowardsZero` | Always toward zero |
| `AwayFromZero` | Always away from zero |
| `NegativeInfinity` | Always downward |
| `PositiveInfinity` | Always upward |

```php
use ReeStyleIt\NumberClass\Format\Round;

$away = new Round(2.5)->decimals(0, RoundingMode::HalfAwayFromZero)->get(); // 3.0
$toward = new Round(2.5)->decimals(0, RoundingMode::HalfTowardsZero)->get(); // 2.0
$even = new Round(2.5)->decimals(0, RoundingMode::HalfEven)->get(); // 2.0
$odd = new Round(2.5)->decimals(0, RoundingMode::HalfOdd)->get(); // 3.0
$legacy = new Round(2.5)->decimals(0, PHP_ROUND_HALF_EVEN)->get(); // 2.0
```

The current implementation also accepts integer equivalents `1` through `8` and rejects
integer modes outside that range. Prefer enum cases or the corresponding PHP constants
for readable code.

## Nearest multiple

`nearest(int|float $multiple): self` rounds to the nearest positive finite multiple.
Ties use PHP's default half-away-from-zero rounding.

```php
use ReeStyleIt\NumberClass\Format\Round;

$five = new Round(13)->nearest(5)->get();       // 15.0
$quarter = new Round(1.13)->nearest(0.25)->get(); // 1.25
$tie = new Round(-12.5)->nearest(5)->get();     // -15.0
```

Floating point multiples can produce small representation errors. This method produces a
number, not a fixed number of display digits. Use [Number::format()](../Number.md#display-formatting)
to show trailing zeroes.

## Helper usage and Number access

```php
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\round;

$rounded = round(12.345)->decimals(2)->get();          // 12.35
$viaProperty = number(13)->round->nearest(5)->get();   // 15.0
$viaMethod = number(12.345)->round()->with(2)->get();  // 12.35
$native = \round(12.345, 2); // 12.35; PHP's native function returns a float
```

Importing the helper makes the short name `round()` refer to this package's factory.
Use `\round()` when calling the native function from that scope.

## Errors

Non-numeric stored values throw [MustBeNumberException](../Exceptions/MustBeNumberException.md).
Invalid integer modes throw [RoundingException](../Exceptions/RoundingException.md).
Zero, negative, infinite, and NaN multiples throw
[MustBePositiveNumberException](../Exceptions/MustBePositiveNumberException.md).
