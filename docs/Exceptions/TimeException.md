# TimeException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\TimeException` extends
[BaseInvalidArgumentException](BaseInvalidArgumentException.md).
[Time::from()](../Conversion/Time.md) throws it when a starting point cannot be parsed.

## Constructor and factories

The inherited constructor accepts `?string $message = null`, `int $code = 0`, and
`?Throwable $previous = null`.

`invalidStartingPoint(?Throwable $previous = null): self` supplies the message
`Starting point must be a valid Unix timestamp or date.` Native parser exceptions are
preserved as the previous exception when available.

```php
use ReeStyleIt\NumberClass\Exceptions\TimeException;

$error = TimeException::invalidStartingPoint();
$message = $error->getMessage(); // 'Starting point must be a valid Unix timestamp or date.'
```

The existing default constructor message remains
`Time must be a non-negative finite number of seconds.` The `tooLarge(): self` factory
remains available with `Time is too large to dissect into whole seconds.`

## Helper usage

Exception objects have no factory helper. Catch this exception around the `time()` helper:

```php
use ReeStyleIt\NumberClass\Exceptions\TimeException;
use function ReeStyleIt\NumberClass\time;

try {
    time()->from('not a date');
} catch (TimeException $error) {
    echo $error->getMessage(); // Starting point must be a valid Unix timestamp or date.
}
```
