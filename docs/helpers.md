# Helper functions

[Usage index](README.md)

Composer automatically loads `src/helper.php`. Numeric helpers take a `mixed $value` argument and create a new instance;
`time()` defaults this value to `0`. The equivalent constructor has the same parameters.
`geometry()` additionally accepts optional latitude and elevation arguments for geographic points.
Import helpers from `ReeStyleIt\NumberClass` with `use function`.

| Helper signature | Equivalent constructor or usage |
| --- | --- |
| `amortization(mixed $value): Amortization` | [`new Amortization($value)`](Financial/Amortization.md) |
| `arithmetic(mixed $value): Arithmetic` | [`new Arithmetic($value)`](Mathematical/Arithmetic.md) |
| `boxPlot(iterable $values): BoxPlot` | [`new BoxPlot($values)`](Statistical/BoxPlot.md) |
| `compare(mixed $value): Compare` | [`new Compare($value)`](Number/Compare.md) |
| `compoundInterest(mixed $value): CompoundInterest` | [`new CompoundInterest($value)`](Financial/CompoundInterest.md) |
| `currencyFormatter(mixed $value): Currency` | [`new Currency($value)`](Format/Currency.md) |
| `diff(DateTimeInterface\|Carbon\|int\|string $from): Diff` | [`new Time\Diff($from)`](Conversion/Time.md) |
| `dimension(mixed $value): Dimension` | [`new Dimension($value)`](Mathematical/Dimension.md) |
| `geometry(mixed $value, int|float|null $latitude = null, int|float $elevation = 0): Geometry` | [`new Geometry($value, $latitude, $elevation)`](Mathematical/Geometry.md) |
| `median(iterable $values): Median` | [`new Median($values)`](Statistical/Median.md) |
| `mode(iterable $values): Mode` | [`new Mode($values)`](Statistical/Mode.md) |
| `number(mixed $value): Number` | [`new Number($value)`](Number.md) |
| `price(mixed $value): Price` | [`new Price($value)`](Financial/Price.md) |
| `pythagoras(mixed $value): Pythagoras` | [`new Pythagoras($value)`](Mathematical/Pythagoras.md) |
| `round(mixed $value): Round` | [`new Round($value)`](Format/Round.md) |
| `time(mixed $value = 0): Time` | [`new Time($value)`](Conversion/Time.md) |
| `toPct(int\|float\|string $value, bool $exact = false): float\|string` | Divides by 100; exact mode uses BCMath and returns a decimal string |
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
The `price()` helper validates its decimal input immediately; pass an integer, plain
decimal string, or `Price`, rather than a float.
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

`toPct()` returns a float by default. Pass `exact: true` to divide with BCMath and
receive a decimal string, preserving all fractional digits. Use a decimal string as
input when its full precision matters; float inputs use their decimal representation.

```php
use function ReeStyleIt\NumberClass\toPct;

toPct(15); // 0.15
toPct(15, exact: true); // '0.15'
toPct('15.123456789123456789', exact: true); // '0.15123456789123456789'
```

The `boxPlot()`, `median()`, and `mode()` helpers accept iterable samples and validate
them immediately. They return readonly statistical objects; call `get()` for the readonly box plot result,
median value, or mode list. Samples must be non-empty finite integers or floats.
