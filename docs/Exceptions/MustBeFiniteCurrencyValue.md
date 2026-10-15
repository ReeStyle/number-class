# MustBeFiniteCurrencyValue

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown when converting a Currency containing INF, -INF, or NAN to a string.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Currency value must be finite.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue;

$default = new MustBeFiniteCurrencyValue;
$message = $default->getMessage(); // 'Currency value must be finite.'
$previous = new \RuntimeException('Underlying failure');
$custom = new MustBeFiniteCurrencyValue('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

The numeric check runs first, then the finite-value check, then the intl extension check. A numeric string raises MustBeNumberException instead. The class name intentionally has no Exception suffix.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue;
use function ReeStyleIt\NumberClass\currencyFormatter;

try {
    $text = (string) currencyFormatter(INF);
} catch (MustBeFiniteCurrencyValue $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

