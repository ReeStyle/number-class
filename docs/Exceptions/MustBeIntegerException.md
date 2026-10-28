# MustBeIntegerException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException` extends
[BaseInvalidArgumentException](BaseInvalidArgumentException.md). It is raised by
[Arithmetic::intdiv()](../Mathematical/Arithmetic.md#division-and-remainders) and
`Number::intdiv()` when the stored value is a float, including an integer-valued float such as `7.0`.

## Constructor

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

The default message is `Value must be an integer.` Pass a custom message, code, or previous
exception through the inherited constructor when needed.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException;

$error = new MustBeIntegerException;
$error->getMessage(); // 'Value must be an integer.'
```

## Helper usage

Exception objects have no factory helper. Catch this exception when integer division is used:

```php
use ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException;
use function ReeStyleIt\NumberClass\arithmetic;

try {
    arithmetic(7.5)->intdiv(2);
} catch (MustBeIntegerException $error) {
    $error->getMessage(); // Value must be an integer.
}
```

Non-numeric stored values fail the shared type check first with `MustBeNumberException`.
Zero divisors still throw native `DivisionByZeroError`, and `PHP_INT_MIN / -1` throws
native `ArithmeticError`. See the [exception index](../README.md#exceptions).

[Ordinal formatting](../Conversion/Ordinal.md) also throws this exception for floats,
including whole-valued floats such as `1.0`.
