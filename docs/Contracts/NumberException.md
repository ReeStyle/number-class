# NumberException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Contracts\NumberException` is the common interface for all
library-defined exceptions. It extends PHP's `Throwable` interface, so caught values expose
`getMessage()`, `getCode()`, `getPrevious()`, and the standard exception API.

```php
use ReeStyleIt\NumberClass\Contracts\NumberException;
use function ReeStyleIt\NumberClass\number;

try {
    number('invalid')->add(1);
} catch (NumberException $exception) {
    echo $exception->getMessage(); // Value must be an integer or float.
}
```

Both `BaseInvalidArgumentException` and `BaseRuntimeException` implement this interface;
all named library exceptions inherit it. Their existing PHP exception parent classes and
individual types remain available for more specific catch clauses.

Native PHP errors such as `DivisionByZeroError`, `TypeError`, and `ArithmeticError` do not
implement this interface and retain their existing behavior.
