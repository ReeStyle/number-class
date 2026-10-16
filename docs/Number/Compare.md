# Compare

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Number\Compare` extends [Base](../Base.md).
Construct it with `new Compare(mixed $value)`. It inherits readonly `$value` and `get()`.
Comparison methods return a `bool`; they do not return another value object.

## Methods

Every comparison accepts `int|float|Number $other`, where `Number` is
`ReeStyleIt\NumberClass\Number`.

| Signature | Comparison |
| --- | --- |
| `equals(int|float|Number $other): bool` | `==` |
| `higherThan(int|float|Number $other): bool` | `>` |
| `higherThanOrEqual(int|float|Number $other): bool` | `>=` |
| `lowerThan(int|float|Number $other): bool` | `<` |
| `lowerThanOrEqual(int|float|Number $other): bool` | `<=` |

```php
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

$comparison = new Compare(7);
$comparison->equals(7.0);                  // true
$comparison->higherThan(6);                // true
$comparison->higherThanOrEqual(7);         // true
$comparison->lowerThan(8);                 // true
$comparison->lowerThanOrEqual(7);          // true
$comparison->equals(new Number(7));        // true
$comparison->higherThan(new Number(10));   // false
$comparison->get();                        // 7; the stored value is unchanged
```

`equals()` uses numeric equality: an integer and a float with equal values compare equal.
It does not provide a floating point tolerance. All five comparisons involving `NAN` return false.
Another `Compare` instance is not accepted as `$other`; pass its `get()` value or a `Number`.

## Helper usage and shared access

Every value class inherits `compare()` and the read-only `compare` property from
[Base](../Base.md). Use them directly on calculation results:

```php
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\round;

$equal = geometry(3)->circleDiameter()->compare->equals(6); // true
$lower = round(12.345)->decimals(2)->compare->lowerThan(13); // true
```

```php
use function ReeStyleIt\NumberClass\compare;
use function ReeStyleIt\NumberClass\number;

$equal = compare(12)->equals(12.0);                 // true
$higher = number(12)->compare->higherThan(10);      // true
$lower = number(12)->compare->lowerThanOrEqual(12); // true

if (compare(18)->higherThanOrEqual(18)) {
    // Handle a value meeting the threshold.
}
```

## Errors

The stored value and any supplied `Number` are validated before comparison.
A non-numeric value raises [MustBeNumberException](../Exceptions/MustBeNumberException.md).
Unsupported argument types can raise PHP's native `TypeError`; callers should supply
actual integers, floats, or `Number` instances rather than relying on PHP scalar coercion.

## Continue with a Number

Both `number(): Number` and the read-only `number` property return a fresh
`ReeStyleIt\NumberClass\Number` containing the raw stored value. Each access validates that
the value is an integer or float; otherwise it throws `MustBeNumberException`.
The source instance remains unchanged.

```php
use ReeStyleIt\NumberClass\Number\Compare;

$value = new Compare(7);
$converted = $value->number->add(1)->get(); // 8
$viaProperty = $value->number->multiply(2)->get(); // 14
```

Conversion wraps the stored comparison operand, not the boolean returned by a comparison method.

See [the shared base API](../Base.md) for numeric validation and storage.
