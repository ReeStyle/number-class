# PythagorasException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\PythagorasException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown by Pythagoras for negative or nonfinite lengths, or a leg exceeding the hypotenuse.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Invalid right triangle dimensions.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\PythagorasException;

$default = new PythagorasException;
$message = $default->getMessage(); // 'Invalid right triangle dimensions.'
$previous = new \RuntimeException('Underlying failure');
$custom = new PythagorasException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

Zero lengths are accepted. A non-numeric stored value raises MustBeNumberException before triangle-length validation.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\PythagorasException;
use function ReeStyleIt\NumberClass\pythagoras;

try {
    pythagoras(5)->leg(6);
} catch (PythagorasException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

## Named factories

```php
use ReeStyleIt\NumberClass\Exceptions\PythagorasException;

$length = PythagorasException::invalidLength();
$length->getMessage(); // 'Length must be a non-negative finite number.'

$leg = PythagorasException::legTooLong();
$leg->getMessage(); // 'A leg cannot be longer than the hypotenuse.'
```

Each factory returns a new exception instance.

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

