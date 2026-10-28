# CurrencySymbolLookupException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\CurrencySymbolLookupException` extends [BaseRuntimeException](BaseRuntimeException.md).

Thrown when ICU cannot set a currency code or retrieve the currency symbol in Currency::symbol().

## Constructor and message

`__construct(string $details = '', int $code = 0, ?\Throwable $previous = null)`

Default message: `Currency symbol lookup failed.`

The first argument is detail text, not a replacement message. Nonempty details are prefixed with `Currency symbol lookup failed: `. An empty string uses the default message.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\CurrencySymbolLookupException;

$default = new CurrencySymbolLookupException;
$message = $default->getMessage(); // 'Currency symbol lookup failed.'
$previous = new \RuntimeException('Underlying failure');
$custom = new CurrencySymbolLookupException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

Without intl, symbol lookup uses bundled locale metadata instead and does not take the ICU failure path. Currency amount formatting still requires intl and has its own exception.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The operation normally succeeds; the catch handles the documented failure path.

```php
use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Exceptions\CurrencySymbolLookupException;
use function ReeStyleIt\NumberClass\currencyFormatter;

try {
    $formatter = currencyFormatter(12)->currency(Currency::USD);
    $symbol = Currency::USD->symbol();
} catch (CurrencySymbolLookupException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

