# CurrencyFormattingException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown when Currency string conversion cannot format an amount: intl is unavailable, or ICU formatCurrency() returns false.

## Constructor and message

`__construct(string $details = '', int $code = 0, ?\Throwable $previous = null)`

Default message: `Currency formatting failed.`

The first argument is detail text, not a replacement message. Nonempty details are prefixed with `Currency formatting failed: `. An empty string uses the default message.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;

$default = new CurrencyFormattingException;
$message = $default->getMessage(); // 'Currency formatting failed.'
$previous = new \RuntimeException('Underlying failure');
$custom = new CurrencyFormattingException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

The helper example succeeds when intl and ICU formatting are available. This exception is an argument-family exception, not a BaseRuntimeException. Numeric and finite-value validation run before the extension check.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The operation normally succeeds; the catch handles the documented failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;
use function ReeStyleIt\NumberClass\currencyFormatter;

try {
    $text = (string) currencyFormatter(12);
} catch (CurrencyFormattingException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

## Missing extension factory

`intlRequired(): self` creates the standard exception used when the intl extension is missing.

```php
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;

$error = CurrencyFormattingException::intlRequired();
$error->getMessage();
// 'Currency formatting failed: The intl extension is required for currency amount formatting.'
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

