# Base

[Usage index](README.md)

`ReeStyleIt\NumberClass\Base` is the abstract parent of all value classes.
It supplies storage and shared numeric validation; instantiate a concrete class such as
[`Number`](Number.md) instead.

## Shared API

| Member | Behavior |
| --- | --- |
| `__construct(mixed $value)` | Stores the required value without converting or validating it |
| `public readonly mixed $value` | Reads the originally stored value; it cannot be reassigned |
| `get(): mixed` | Returns the stored value without formatting or conversion |
| `protected assertNumber(): void` | Throws `MustBeNumberException` unless the value is an `int` or `float` |

`assertNumber()` is available to subclasses, not callers. Numeric strings, booleans, null,
arrays, and objects fail this check. `INF` and `NAN` are floats and pass it;
classes requiring finite values perform additional checks.

```php
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Number;

$number = new Number(12);
$raw = $number->get();    // 12
$same = $number->value;   // 12
$isBase = $number instanceof Base; // true

// $number->value = 20; // Would throw PHP Error: the property is readonly.
```

## Extending the base

Call the protected validation method before performing a numeric operation.

```php
use ReeStyleIt\NumberClass\Base;

$value = new class(6) extends Base {
    public function doubled(): self
    {
        $this->assertNumber();

        return new self($this->value * 2);
    }
};

$result = $value->doubled()->get(); // 12; $value still contains 6
```

## Helper usage

There is no `Base` factory because the class is abstract. All value helpers create subclasses
with the same storage API:

```php
use function ReeStyleIt\NumberClass\number;

$value = number(42);
echo $value->get(); // 42
```

See the [helper reference](helpers.md) and [validation exception](Exceptions/MustBeNumberException.md).
