# RoundingException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\RoundingException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown by Round::decimals() and its with() alias for an invalid integer rounding mode.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Rounding mode must be a valid RoundingMode case or its integer equivalent.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\RoundingException;

$default = new RoundingException;
$message = $default->getMessage(); // 'Rounding mode must be a valid RoundingMode case or its integer equivalent.'
$previous = new \RuntimeException('Underlying failure');
$custom = new RoundingException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

The implementation currently accepts integer equivalents 1 through 8 or a PHP RoundingMode case. Invalid argument types can raise PHP TypeError instead.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\RoundingException;
use function ReeStyleIt\NumberClass\round;

try {
    round(12.5)->decimals(0, 99);
} catch (RoundingException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

