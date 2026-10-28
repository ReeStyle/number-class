# Base

[Usage index](README.md)

`ReeStyleIt\NumberClass\Base` is the abstract parent of all value classes.
It supplies storage and shared numeric validation; instantiate a concrete class such as
[`Number`](Number.md) instead.

## Shared API

| Member | Behavior |
| --- | --- |
| `public const int MAX_SAFE_INTEGER` | `9007199254740991`: upper bound of the range where every integer is exactly representable as an IEEE 754 double |
| `public const int MIN_SAFE_INTEGER` | `-9007199254740991`: matching lower bound |
| `__construct(mixed $value)` | Stores the required value without converting or validating it |
| `public readonly mixed $value` | Reads the originally stored value; it cannot be reassigned |
| `get(): mixed` | Returns the stored value without formatting or conversion |
| `compare(): Compare` | Validates the value and returns a new `Number\Compare` containing it |
| `public Compare $compare` | Read-only property accessor for `compare()` |
| `number(): Number` | Validates the value and returns a new `Number` containing it |
| `public Number $number` | Read-only property accessor for `number()` |
| `protected assertNumber(): void` | Throws `MustBeNumberException` unless the value is an `int` or `float` |

## Global default locale

Call `Base::setDefaultLocale(Locales|string|null $locale)` once during application setup.
The inherited `Number::setDefaultLocale()` method and namespaced `setDefaultLocale()` helper
configure the same package-wide setting.
`getDefaultLocale(): ?string` reads the override; `null` means each formatter uses its original default.

```php
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Enums\Locales;
use function ReeStyleIt\NumberClass\number;

Number::setDefaultLocale(Locales::NL_NL);
echo number(21)->ordinal;          // 21ste
echo number(21)->ordinal->wordify; // eenentwintigste

Number::setDefaultLocale(null); // Restore the original defaults.
```

New ordinal and currency formatters capture this default when constructed, including those
created by helpers and shared accessors. Existing instances retain their configuration.
Per-instance `locale()` calls and assignments to the `locale` property override the default.
`Currencies::symbol()` also uses the global setting when its locale argument is omitted or null.
The setting changes formatting language and conventions, while the currency code remains unchanged.

Without a global override, ordinals use `en_US`, currency amounts use `nl_NL`, and
currency symbol lookup uses `en_US`. Blank global locales throw `EmptyLocaleException`
without changing the current setting. The setting lasts for the current PHP process;
long-running workers can change or clear it between jobs.

## Shared helper accessors

The safe integer constants are inherited by subclasses, for example `Number::MAX_SAFE_INTEGER`
and `Ordinal::MIN_SAFE_INTEGER`. They describe floating point precision boundaries, rather
than PHP's integer storage limits. Individual classes determine whether to enforce them;
ordinal formatting does, while native integer arithmetic and BCMath prices use their own ranges.

Every value class inherits the following methods and read-only properties. Each access
validates the stored value and creates a fresh instance of the requested class.
Prefer property access for default behavior; use methods when supplying arguments,
such as `geometry(latitude: 52, elevation: 10)` or `currency('USD')`.

| Method | Property | Result |
| --- | --- | --- |
| `arithmetic(): Arithmetic` | `arithmetic` | [Arithmetic](Mathematical/Arithmetic.md) |
| `compare(): Compare` | `compare` | [Compare](Number/Compare.md) |
| `currency(): Currency` | `currency` | [Currency](Format/Currency.md) |
| `geometry(int|float|null $latitude = null, int|float $elevation = 0): Geometry` | `geometry` | [Geometry](Mathematical/Geometry.md) |
| `number(): Number` | `number` | [Number](Number.md) |
| `ordinal(): Ordinal` | `ordinal` | [Ordinal](Conversion/Ordinal.md) |
| `pythagoras(): Pythagoras` | `pythagoras` | [Pythagoras](Mathematical/Pythagoras.md) |
| `round(): Round` | `round` | [Round](Format/Round.md) |
| `time(): Time` | `time` | [Time](Conversion/Time.md), retaining the stored value and supporting a date starting point |
| `trigonometry(): Trigonometry` | `trigonometry` | [Trigonometry](Mathematical/Trigonometry.md) |

```php
use function ReeStyleIt\NumberClass\arithmetic;
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\pythagoras;

$rounded = geometry(3)->circleDiameter()->arithmetic->add(1)->round->nearest(5)->get(); // 5.0
$diameter = pythagoras(3)->hypotenuse(4)->geometry->circleDiameter()->get(); // 10.0
$text = (string) arithmetic(7)->currency->currency('USD')->locale('en_US'); // '$7.00'
```

Accessors pass the raw stored value; they do not copy formatter configuration or geographic
metadata. To create a point through `geometry()`, pass latitude and optional elevation;
the stored value becomes longitude. The property form supplies no starting latitude.
On `Currency`, `currency('USD')` remains the configuration setter, while `currency()` with
no argument and the `currency` property create a fresh formatter. Its code is stored in
`currencyCode`.

`assertNumber()` is available to subclasses, not callers. Numeric strings, booleans, null,
arrays, and objects fail this check. `INF` and `NAN` are floats and pass it;
classes requiring finite values perform additional checks.

```php
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Number;

$number = new Number(12);
$raw = $number->get();    // 12
$same = $number->value;   // 12
$isBase = $number instanceof Base; // true

// $number->value = 20; // Would throw PHP Error: the property is readonly.
```

## Compare a stored value

Every value class inherits `compare(): Compare` and the read-only `compare` property,
including `Number` and `Compare` itself. Each access creates a fresh comparison object
containing the raw stored value, leaving the source unchanged. Non-numeric values throw
`MustBeNumberException` before the object is created.

```php
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\pythagoras;

$equal = geometry(3)->circleDiameter()->compare->equals(6); // true
$higher = pythagoras(3)->hypotenuse(4)->compare->higherThan(4); // true
```

See [Compare](Number/Compare.md) for all comparison operations. On a currency formatter this
compares the raw amount; on a geographic point it compares the stored longitude. Comparisons
return booleans, while accessing `compare` returns the comparison object.

## Extending the base

Call the protected validation method before performing a numeric operation.

```php
use ReeStyleIt\NumberClass\Base;

$value = new class(6) extends Base {
    public function doubled(): self
    {
        $this->assertNumber();

        return new self($this->value * 2);
    }
};

$result = $value->doubled()->get(); // 12; $value still contains 6
```

## Helper usage

There is no `Base` factory because the class is abstract. All value helpers create subclasses
with the same storage API:

```php
use function ReeStyleIt\NumberClass\number;

$value = number(42);
echo $value->get(); // 42
```

See the [helper reference](helpers.md) and [validation exception](Exceptions/MustBeNumberException.md).

## Continue with a Number

Both `number(): Number` and the read-only `number` property return a fresh
`ReeStyleIt\NumberClass\Number` containing the raw stored value. Each access validates that
the value is an integer or float; otherwise it throws `MustBeNumberException`.
The source instance remains unchanged.

```php
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

$value = new Arithmetic(6)->multiply(7);
$converted = $value->number->add(8)->get(); // 50
$viaProperty = $value->number->divide(2)->get(); // 21
```

This API is inherited by every concrete value class, including `Number` itself.

## Financial accessors

`amortization` and `amortization(): Amortization` create a fresh
[Financial\Amortization](Financial/Amortization.md) from the stored numeric principal.
`compoundInterest` and `compoundInterest(): CompoundInterest` similarly create a
[Financial\CompoundInterest](Financial/CompoundInterest.md). All value classes inherit
these accessors; the original stored value is preserved.

## Statistical accessors

When the stored value is a non-empty array of finite integers or floats, `Base`
provides readonly statistical properties and matching methods:

| Property / method | Class |
| --- | --- |
| `boxPlot` / `boxPlot()` | [Statistical\BoxPlot](Statistical/BoxPlot.md) |
| `median` / `median()` | [Statistical\Median](Statistical/Median.md) |
| `mode` / `mode()` | [Statistical\Mode](Statistical/Mode.md) |

```php
use function ReeStyleIt\NumberClass\number;

$sample = number([3, 1, 2, 2]);
$sample->median->get(); // 2
$sample->median()->get(); // 2
$sample->mode->get(); // [2]
$sample->boxPlot->q1; // 1.5
```

All subclasses of `Base` inherit these accessors. Each access creates a fresh statistical
object and preserves the original stored array. Scalar values, iterable objects,
empty arrays, and arrays containing invalid entries throw `StatisticalException`.
Standalone statistical constructors and helpers additionally accept other iterables.

## Shape dimensions

`dimension` and `dimension(): Dimension` create a fresh
[Mathematical\Dimension](Mathematical/Dimension.md) factory from the stored numeric
value. For example, `number(3)->dimension->rectangle(4)->area()->get()` returns `12`.
All subclasses of `Base` inherit these accessors.
