# Compare

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Number\Compare` extends [Base](../Base.md).
Construct it with `new Compare(mixed $value)`. It inherits readonly `$value` and `get()`.
Comparison methods return a `bool`; they do not return another value object.

## Methods

Every comparison accepts `int|float|Base $other`, where `Base` is
`ReeStyleIt\NumberClass\Base`. Package value objects are unwrapped to their stored
number, including arithmetic results, formatting objects, and other comparisons.

| Signature | Comparison |
| --- | --- |
| `equals(int\|float\|Base $other, bool $strict = false): bool` | `==`, or `===` when strict |
| `higherThan(int\|float\|Base $other): bool` | `>` |
| `higherThanOrEqual(int\|float\|Base $other): bool` | `>=` |
| `lowerThan(int\|float\|Base $other): bool` | `<` |
| `lowerThanOrEqual(int\|float\|Base $other): bool` | `<=` |

```php
use ReeStyleIt\NumberClass\Number;
use ReeStyleIt\NumberClass\Number\Compare;

$comparison = new Compare(7);
$comparison->equals(7.0);                  // true
$comparison->equals(7.0, strict: true);    // false; integer versus float
$comparison->equals(7, strict: true);      // true
$comparison->higherThan(6);                // true
$comparison->higherThanOrEqual(7);         // true
$comparison->lowerThan(8);                 // true
$comparison->lowerThanOrEqual(7);          // true
$comparison->equals(new Number(7));        // true
$comparison->higherThan(new Number(10));   // false
$comparison->equals(new Number(3)->add(4)); // true
$comparison->get();                        // 7; the stored value is unchanged
```

`equals()` defaults to numeric equality: an integer and a float with equal values compare equal.
Pass `strict: true` to require matching types as well as values. Package objects are
unwrapped first, preserving the integer or float type of their stored value.
It does not provide a floating point tolerance. All five comparisons involving `NAN` return false.
Another `Compare` instance is accepted as `$other`, using its stored numeric value.

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

The stored value and any supplied `Base` value object are validated before comparison.
A non-numeric value raises [MustBeNumberException](../Exceptions/MustBeNumberException.md).
Unsupported argument types can raise PHP's native `TypeError`; callers should supply
actual integers, floats, or `Base` value objects rather than relying on PHP scalar coercion.

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
