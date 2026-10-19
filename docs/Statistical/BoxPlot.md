# BoxPlot

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Statistical\BoxPlot` provides precomputed, readonly data for
a box-and-whisker plot. It extends readonly `Sample`. Use
`new BoxPlot(iterable $values)` or the namespaced `boxPlot($values)` helper.

```php
use function ReeStyleIt\NumberClass\boxPlot;

$plot = boxPlot([-100, 1, 2, 3, 4, 5, 6, 7, 100]);
$plot->median; // 4
$plot->q1; // 1.5
$plot->q3; // 6.5
$plot->iqr; // 5.0
$plot->lowerWhisker; // 1
$plot->upperWhisker; // 7
$plot->outliers; // [-100, 100]
$summary = $plot->get(); // readonly Statistical\BoxPlot\Result
$summary->median; // 4
$summary->outliers; // [-100, 100]
```

| Property | Meaning |
| --- | --- |
| `min` | Smallest observation, including outliers |
| `q1` | Median of the lower half |
| `median` | Median of the entire sample |
| `q3` | Median of the upper half |
| `max` | Largest observation, including outliers |
| `iqr` | `q3 - q1` |
| `lowerWhisker` | Smallest observation within the fences |
| `upperWhisker` | Largest observation within the fences |
| `outliers` | Sorted observations outside the fences, retaining duplicates |

All scalar summary properties are `int|float`. `get(): BoxPlot\Result` returns a
precomputed readonly `ReeStyleIt\NumberClass\Statistical\BoxPlot\Result`. It exposes the
properties in the table, including a readonly `outliers` array. Repeated calls return
the same result without recalculating. The properties also remain accessible directly
on `BoxPlot`.
The inherited `values` property exposes a sorted copy of all observations.

Quartiles use medians of the lower and upper halves, excluding the middle observation
for odd sample counts. A singleton uses its value for both quartiles, median, and both
whiskers. This is an explicit convention; other software may interpolate quartiles
differently. Fences are `q1 - 1.5 * iqr` and `q3 + 1.5 * iqr`; observations exactly on
a fence remain inliers. Whiskers end on actual observations. See the
[NIST box plot reference](https://www.itl.nist.gov/div898/handbook/eda/section3/boxplot.htm)
for the IQR outlier rule.

Inputs follow [Median](Median.md)'s finite numeric sample rules. Invalid samples or
nonfinite calculated fences throw [StatisticalException](../Exceptions/StatisticalException.md).

All `Base` value objects expose `boxPlot` and `boxPlot()` for stored numeric arrays.
See [statistical accessors](../Base.md#statistical-accessors).
