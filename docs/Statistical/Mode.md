# Mode

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Statistical\Mode` extends readonly `Sample`.
Construct it with `new Mode(iterable $values)` or the namespaced `mode($values)` helper.
`get(): array` returns a numerically sorted list of every value tied for the highest
frequency. If no value occurs at least twice, including singleton samples, it returns
`[]`. This follows the [no-repeats convention](https://openstax.org/books/principles-data-science/pages/3-1-measures-of-center).

```php
use function ReeStyleIt\NumberClass\mode;

mode([3, 2, 1, 2])->get(); // [2]
mode([3, 2, 3, 1, 2])->get(); // [2, 3]
mode([1, 2, 3])->get(); // []
mode([1.25, 1.5, 1.25])->get(); // [1.25]
```

Numeric equality groups integers and equal floats together (`1` and `1.0` count as the
same value). A group retains the type of its first input representative. Frequencies
are counted by comparing sorted values, avoiding conversion to integer array keys
or decimal string keys.

The readonly `values` array contains the sorted sample. Input validation and iterable
snapshot behavior match [Median](Median.md): require a non-empty sample of finite
integers or floats, or throw [StatisticalException](../Exceptions/StatisticalException.md).

All `Base` value objects expose `mode` and `mode()` for stored numeric arrays.
See [statistical accessors](../Base.md#statistical-accessors).
