# MustBePositiveNumberException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown for an invalid root degree or a nonpositive or nonfinite rounding multiple.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Number must be greater than 0.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;

$default = new MustBePositiveNumberException;
$message = $default->getMessage(); // 'Number must be greater than 0.'
$previous = new \RuntimeException('Underlying failure');
$custom = new MustBePositiveNumberException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

Arithmetic::sqrt() and Number::sqrt() require an integer root exponent greater than zero. Round::nearest() requires a positive finite multiple.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;
use function ReeStyleIt\NumberClass\round;

try {
    round(12)->nearest(0);
} catch (MustBePositiveNumberException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

## Named factories

Both factories return a new exception instance:

```php
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;

$root = MustBePositiveNumberException::rootExponent();
$root->getMessage(); // 'Root exponent must be greater than 0.'

$multiple = MustBePositiveNumberException::multiple();
$multiple->getMessage(); // 'Multiple must be a positive finite number.'
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

