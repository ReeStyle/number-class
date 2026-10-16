# BaseInvalidArgumentException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\BaseInvalidArgumentException` extends PHP's `\InvalidArgumentException`.

The common parent for the package's argument and value validation exceptions.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Invalid argument.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\BaseInvalidArgumentException;

$default = new BaseInvalidArgumentException;
$message = $default->getMessage(); // 'Invalid argument.'
$previous = new \RuntimeException('Underlying failure');
$custom = new BaseInvalidArgumentException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

This catches every package exception derived from this base, including currency amount formatting failures. It does not catch the runtime exception family or PHP's DivisionByZeroError.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\BaseInvalidArgumentException;
use function ReeStyleIt\NumberClass\number;

try {
    number('12')->add(1);
} catch (BaseInvalidArgumentException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

## Derived classes

[CurrencyFormattingException](CurrencyFormattingException.md),
[EmptyLocaleException](EmptyLocaleException.md),
[GeometryException](GeometryException.md),
[InvalidCurrencyCodeException](InvalidCurrencyCodeException.md),
[MustBeFiniteCurrencyValue](MustBeFiniteCurrencyValue.md),
[MustBeIntegerException](MustBeIntegerException.md),
[MustBeNumberException](MustBeNumberException.md),
[MustBePositiveNumberException](MustBePositiveNumberException.md),
[PythagorasException](PythagorasException.md), [RoundingException](RoundingException.md),
and [TimeException](TimeException.md)
all extend this base.

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).
