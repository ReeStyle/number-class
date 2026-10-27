# BoxPlotImageException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\BoxPlotImageException` extends
[BaseRuntimeException](BaseRuntimeException.md). It is raised by
[BoxPlot image rendering](../Statistical/BoxPlot.md#image-rendering-optional-gd-extension)
when the optional GD extension or image creation function is unavailable, or image
allocation fails. Its default message is
`The GD extension is required to render box plot images.`

Statistical calculations and readonly results remain available without GD.
The inherited constructor accepts a custom message, code, and previous exception.
Invalid image dimensions raise `StatisticalException` instead.
