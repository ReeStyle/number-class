# InvalidCurrencyCodeException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown by Currency::currency() when a string does not contain exactly three ASCII letters.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Currency must be a three-letter currency code.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException;

$default = new InvalidCurrencyCodeException;
$message = $default->getMessage(); // 'Currency must be a three-letter currency code.'
$previous = new \RuntimeException('Underlying failure');
$custom = new InvalidCurrencyCodeException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

Lowercase strings such as 'usd' are uppercased and accepted. This validates code shape, not membership in an ISO registry. Enum cases are accepted directly.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException;
use function ReeStyleIt\NumberClass\currencyFormatter;

try {
    currencyFormatter(12)->currency('EURO');
} catch (InvalidCurrencyCodeException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

