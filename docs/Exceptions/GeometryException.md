# GeometryException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\GeometryException` extends
[BaseInvalidArgumentException](BaseInvalidArgumentException.md).
It is raised by [Geometry](../Mathematical/Geometry.md) for missing starting latitude,
invalid coordinate ranges, nonfinite coordinates or elevations, negative or nonfinite shape lengths, or a haversine sphere radius
that is not positive and finite.

## Constructor

`__construct(?string $message = null, int $code = 0, ?\Throwable $previous = null)`

The inherited constructor defaults to `Invalid geometry dimensions.` when the message is null
or omitted. Pass a custom message, exception code, or previous exception when constructing it.
Inherited methods such as `getMessage()`, `getCode()`, and `getPrevious()` expose those values.

```php
use ReeStyleIt\NumberClass\Exceptions\GeometryException;

$error = new GeometryException;
$message = $error->getMessage(); // 'Invalid geometry dimensions.'

$latitude = GeometryException::invalidLatitude();
$latitude->getMessage(); // 'Latitude must be a finite number between -90 and 90 degrees.'

$longitude = GeometryException::invalidLongitude();
$longitude->getMessage(); // 'Longitude must be a finite number between -180 and 180 degrees.'

$radius = GeometryException::invalidRadius();
$radius->getMessage(); // 'Radius must be a positive finite number.'

$length = GeometryException::invalidLength();
$length->getMessage(); // 'Length must be a non-negative finite number.'

$elevation = GeometryException::invalidElevation();
$elevation->getMessage(); // 'Elevation must be a finite number.'

$missing = GeometryException::missingLatitude();
$missing->getMessage(); // 'A starting latitude is required for haversine distance.'
```

Each static factory has no parameters and returns a new `self` with the specific message.
Both coordinate ranges include their endpoints. Zero latitude and longitude are valid;
zero sphere radius is invalid for `haversine()`. Zero circle and ellipse lengths are accepted.
A non-numeric stored value raises `MustBeNumberException` instead.

## Helper usage

There is no exception factory helper. Catch the exception when calculating through `geometry()`:

```php
use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use function ReeStyleIt\NumberClass\geometry;

try {
    geometry(0, 91, 0)->haversine(0, 0, 0);
} catch (GeometryException $error) {
    echo $error->getMessage(); // Latitude must be a finite number between -90 and 90 degrees.
}
```

Catch `BaseInvalidArgumentException` instead if handling this alongside other package value
validation errors. See the [exception index](../README.md#exceptions).

[Dimension](../Mathematical/Dimension.md) shape constructors also raise this exception
for invalid lengths and impossible triangles. Shape measurements raise it when the
calculated result is nonfinite.
