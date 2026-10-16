# Helper functions

[Usage index](README.md)

Composer automatically loads `src/helper.php`. Numeric helpers take a `mixed $value` argument and create a new instance;
`time()` defaults this value to `0`. The equivalent constructor has the same parameters.
`geometry()` additionally accepts optional latitude and elevation arguments for geographic points.
Import helpers from `ReeStyleIt\NumberClass` with `use function`.

| Helper signature | Equivalent constructor and full usage |
| --- | --- |
| `number(mixed $value): Number` | [`new Number($value)`](Number.md) |
| `arithmetic(mixed $value): Arithmetic` | [`new Arithmetic($value)`](Mathematical/Arithmetic.md) |
| `compare(mixed $value): Compare` | [`new Compare($value)`](Number/Compare.md) |
| `diff(DateTimeInterface\|Carbon\|int\|string $from): Diff` | [`new Time\Diff($from)`](Conversion/Time.md) |
| `currencyFormatter(mixed $value): Currency` | [`new Currency($value)`](Format/Currency.md) |
| `geometry(mixed $value, int|float|null $latitude = null, int|float $elevation = 0): Geometry` | [`new Geometry($value, $latitude, $elevation)`](Mathematical/Geometry.md) |
| `pythagoras(mixed $value): Pythagoras` | [`new Pythagoras($value)`](Mathematical/Pythagoras.md) |
| `round(mixed $value): Round` | [`new Round($value)`](Format/Round.md) |
| `time(mixed $value = 0): Time` | [`new Time($value)`](Conversion/Time.md) |
| `trigonometry(mixed $value): Trigonometry` | [`new Trigonometry($value)`](Mathematical/Trigonometry.md) |

```php
use function ReeStyleIt\NumberClass\arithmetic;
use function ReeStyleIt\NumberClass\compare;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\pythagoras;
use function ReeStyleIt\NumberClass\round;
use function ReeStyleIt\NumberClass\trigonometry;

$total = number(10)->add(5)->get();                 // 15
$product = arithmetic(6)->multiply(7)->get();        // 42
$equal = compare(7)->equals(7.0);                   // true
$length = pythagoras(3)->hypotenuse(4)->get();       // 5.0
$rounded = round(12.345)->decimals(2)->get();        // 12.35
$sine = trigonometry(90)->deg2rad()->sin()->get();   // 1.0
```

Currency formatting requires `intl`:

```php
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use function ReeStyleIt\NumberClass\currencyFormatter;

echo currencyFormatter(1234.5)
    ->currency(Currencies::USD)
    ->locale(Locales::EN_US); // $1,234.50
```

The package's `round()` returns an object. After importing it, use `\round(12.345, 2)`
to call PHP's built-in function, which returns a float. Helpers do not convert strings to numbers
or validate numeric values at construction time; the operation being called performs validation.
The `diff()` helper validates its starting point when creating the `Time\Diff` instance.
There are no factory helpers for the abstract base, enums, or exception classes.

The imported `time()` helper creates a `Time` instance; use `\time()` for PHP's native timestamp function:

```php
use function ReeStyleIt\NumberClass\time;

$start = time()->from('2024-10-17T12:00:00Z');
$timestamp = $start->from->getTimestamp(); // 1729166400
$seconds = time(60)->from->diff(90)->s; // 30
```

Use `diff()` to create a `Time\Diff` directly with a Unix timestamp, date object, or date string:

```php
use function ReeStyleIt\NumberClass\diff;

$interval = diff(from: 60)->diff(90); // DateInterval
$seconds = $interval->s; // 30
```
