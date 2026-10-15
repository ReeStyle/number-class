# EmptyLocaleException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown by Currency::locale() for an empty or whitespace-only locale string.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Locale must not be empty.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;

$default = new EmptyLocaleException;
$message = $default->getMessage(); // 'Locale must not be empty.'
$previous = new \RuntimeException('Underlying failure');
$custom = new EmptyLocaleException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

The setter checks trim($locale) for emptiness. Other nonempty locale strings are stored unchanged; it does not validate the complete identifier.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use function ReeStyleIt\NumberClass\currencyFormatter;

try {
    currencyFormatter(12)->locale('   ');
} catch (EmptyLocaleException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

