# Median

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Statistical\Median` is a readonly numeric sample calculation.
Construct it with `new Median(iterable $values)` or the namespaced `median($values)`
helper. `get(): int|float` returns the median; `values` exposes the sorted sample.

```php
use function ReeStyleIt\NumberClass\median;

median([7, 1, 3])->get(); // 3
median([4, 1, 2, 3])->get(); // 2.5
median([3.5])->get(); // 3.5
```

For odd samples, the median is the middle sorted value. For even samples, it is the
average of the two middle values. Large-value averaging avoids intermediate overflow.
Results retain PHP's native integer or float precision; equal integer middle values
remain integers. See [OpenStax's measures of center](https://openstax.org/books/introductory-statistics-2e/pages/2-5-measures-of-the-center-of-the-data).

All statistical classes accept arrays, generators, or other iterables. They consume
the iterable once, ignore keys, copy its values, and sort numerically without changing
the original array. Values must be finite PHP integers or floats; strings (even numeric
strings), booleans, null, objects, and nested arrays are rejected. Empty samples are
rejected with [StatisticalException](../Exceptions/StatisticalException.md).

These sample classes extend readonly `Statistical\Sample`, independently of the
single-value `Base` hierarchy. Their helpers return calculation objects, not scalars;
call `get()` to obtain the result.

## Access through Base

All `Base` value objects expose the `median` property and `median()` method when their
stored value is an array of numbers: `number([7, 1, 3])->median->get()` returns `3`.
See [statistical accessors](../Base.md#statistical-accessors). Scalar stored values
are rejected with `StatisticalException`.
