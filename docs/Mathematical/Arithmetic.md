# Arithmetic

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Mathematical\Arithmetic` extends [Base](../Base.md).
Create it with `new Arithmetic(mixed $value)`. Every calculation returns a new `Arithmetic`;
use inherited `get(): mixed` or the public readonly `$value` property to read the result.

```php
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

$original = new Arithmetic(10);
$result = $original->add(5)->subtract(3)->multiply(4)->divide(2);
$result->get();   // 24
$original->get(); // 10
```

## Methods

All calculation methods return `self`.

| Signature | Calculation |
| --- | --- |
| `abs(): self` | Absolute value |
| `add(int|float $amount = 1): self` | Add an amount |
| `ceil(): self` | Round upward to an integer-valued float |
| `decrease(int|float $amount = 1): self` | Alias of `subtract()` |
| `divide(int|float $divisor): self` | Divide by a nonzero divisor |
| `floor(): self` | Round downward to an integer-valued float |
| `increase(int|float $amount = 1): self` | Alias of `add()` |
| `intdiv(int $divisor): self` | Integer quotient, truncated toward zero; stored value must be an integer |
| `mod(int|float $divisor): self` | Remainder, with the dividend's sign |
| `multiply(int|float $factor): self` | Multiply by a factor |
| `negate(): self` | Change the sign |
| `pow(int|float $exponent): self` | Raise to a power |
| `sqrt(int $exponent = 2): self` | Take a root; exponent means the root's degree |
| `subtract(int|float $amount = 1): self` | Subtract an amount |

```php
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

$added = new Arithmetic(5)->add()->get();             // 6
$increased = new Arithmetic(5)->increase(2)->get();    // 7
$subtracted = new Arithmetic(5)->subtract()->get();   // 4
$decreased = new Arithmetic(5)->decrease(2)->get();    // 3
$absolute = new Arithmetic(-5)->abs()->get();         // 5
$negated = new Arithmetic(5)->negate()->get();        // -5
$up = new Arithmetic(-1.2)->ceil()->get();            // -1.0
$down = new Arithmetic(-1.2)->floor()->get();          // -2.0
```

## Powers and roots

`pow()` supports negative and fractional exponents. `sqrt()` requires an integer degree
greater than zero, defaults to a square root, and supports real roots of negative numbers
when the degree is odd. Even roots of negative numbers return `NAN`.

```php
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

$square = new Arithmetic(3)->pow(2)->get();       // 9
$reciprocal = new Arithmetic(2)->pow(-1)->get();  // 0.5
$root = new Arithmetic(16)->sqrt()->get();        // 4.0
$cube = new Arithmetic(-27)->sqrt(3)->get();       // approximately -3.0
$identity = new Arithmetic(-7)->sqrt(1)->get();   // -7.0
$undefined = new Arithmetic(-4)->sqrt()->get();   // NAN
$isNaN = is_nan($undefined);                     // true
```

## Division and remainders

`intdiv(int $divisor): self` uses native `\intdiv()`. It returns an integer quotient, truncating
toward zero. The stored value must be an actual integer; floats such as `7.0` are rejected with
[MustBeIntegerException](../Exceptions/MustBeIntegerException.md). The divisor parameter is typed `int`.
Zero divisors throw `DivisionByZeroError`; `PHP_INT_MIN` divided by `-1` throws `ArithmeticError`.

```php
use function ReeStyleIt\NumberClass\arithmetic;
use function ReeStyleIt\NumberClass\number;

$quotient = arithmetic(7)->intdiv(2)->get(); // 3
$negative = arithmetic(-7)->intdiv(2)->get(); // -3
$viaNumber = number(7)->intdiv(2)->get(); // 3; also an Arithmetic result
```

Division follows PHP's native `/` result type. Integer remainders use `%`; if either input
is a float, the operation uses `fmod()` and preserves the fractional remainder.

```php
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

$quotient = new Arithmetic(7)->divide(2)->get();  // 3.5
$integer = new Arithmetic(7)->mod(3)->get();      // 1
$fraction = new Arithmetic(7.5)->mod(2)->get();   // 1.5
$negative = new Arithmetic(-7)->mod(3)->get();   // -1

try {
    new Arithmetic(0)->divide(0);
} catch (DivisionByZeroError $error) {
    // Every zero divisor is rejected, including 0 / 0.
}
```

## Helper usage

```php
use function ReeStyleIt\NumberClass\arithmetic;

arithmetic(6)->multiply(7)->get(); // 42
```

[Number](../Number.md) also provides all these operations directly, returning an `Arithmetic` result,
or exposes this class through `number(6)->arithmetic`.

## Errors and numeric behavior

Each operation checks the stored value and throws
[MustBeNumberException](../Exceptions/MustBeNumberException.md) for values other than `int` or `float`.
`divide(0)` and `mod(0)` throw `DivisionByZeroError`.
Invalid root degrees throw [MustBePositiveNumberException](../Exceptions/MustBePositiveNumberException.md).
Results otherwise follow PHP's arithmetic behavior, including floating point approximation,
overflow, `INF`, and `NAN`; this class does not provide arbitrary precision arithmetic.

## Continue with a Number

Both `number(): Number` and the read-only `number` property return a fresh
`ReeStyleIt\NumberClass\Number` containing the raw stored value. Each access validates that
the value is an integer or float; otherwise it throws `MustBeNumberException`.
The source instance remains unchanged.

```php
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

$value = new Arithmetic(6)->multiply(7);
$converted = $value->number->format(2); // '42.00'
$viaProperty = $value->number->compare->equals(42); // true
```

See [the shared base API](../Base.md) for numeric validation and storage.
