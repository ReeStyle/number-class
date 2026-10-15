# MustBeNumberException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\MustBeNumberException` extends [BaseInvalidArgumentException](BaseInvalidArgumentException.md).

Thrown by Base::assertNumber() when a numeric operation receives a stored value that is neither an int nor a float.

## Constructor and message

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

Default message: `Value must be an integer or float.`

Omitting the message or passing null uses the default message. A supplied string replaces it.
The code and previous exception are passed through to PHP's exception constructor.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;

$default = new MustBeNumberException;
$message = $default->getMessage(); // 'Value must be an integer or float.'
$previous = new \RuntimeException('Underlying failure');
$custom = new MustBeNumberException('Additional context', 42, $previous);
$code = $custom->getCode();         // 42
$cause = $custom->getPrevious();    // $previous
```

Constructors and helpers accept mixed values without validation. Numeric strings, booleans, null, arrays, and objects fail when a numeric operation validates them. INF and NAN pass this type check because they are floats; specific classes may reject them separately.

## Helper usage and catching the exception

Exception classes have no factory helper. This example uses a value helper inside a matching
catch block. The invalid input intentionally demonstrates the failure path.

```php
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use function ReeStyleIt\NumberClass\number;

try {
    number('12')->add(1);
} catch (MustBeNumberException $error) {
    $message = $error->getMessage();
    // Report or handle the failure in your application.
}
```

Inherited PHP exception methods include `getMessage()`, `getCode()`, `getPrevious()`,
`getFile()`, `getLine()`, `getTrace()`, `getTraceAsString()`, and `__toString()`.
See the [helper reference](../helpers.md) and [exception index](../README.md#exceptions).

