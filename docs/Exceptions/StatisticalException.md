# StatisticalException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\StatisticalException` extends
[BaseInvalidArgumentException](BaseInvalidArgumentException.md). Statistical sample
constructors throw it for empty samples, nonnumeric entries, or nonfinite numbers.
The default message is `Statistics require a non-empty sample of finite integers or floats.`
BoxPlot also throws it if its calculated fences exceed the finite numeric range.
The inherited constructor accepts a custom message, code, and previous exception.
