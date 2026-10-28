# BaseRuntimeException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\BaseRuntimeException` extends PHP's `\RuntimeException`.

The common parent for runtime failures such as ICU currency symbol lookup errors.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Runtime error.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\BaseRuntimeException;

$default = new BaseRuntimeException;
$message = $default->getMessage(); // 'Runtime error.'
$previous = new \RuntimeException('Underlying failure');
$custom = new BaseRuntimeException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

CurrencySymbolLookupException extends this base. CurrencyFormattingException belongs to the separate BaseInvalidArgumentException family.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The operation normally succeeds; the catch handles the documented failure path.

```php
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Exceptions\BaseRuntimeException;
use function ReeStyleIt\NumberClass\currencyFormatter;

try {
    $formatter = currencyFormatter(12)->currency(Currencies::USD);
    $symbol = Currencies::USD->symbol();
} catch (BaseRuntimeException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).


This class implements [NumberException](../Contracts/NumberException.md), allowing integrations to catch
all library-defined exceptions through that interface.
