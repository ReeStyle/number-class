# Geometry

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Mathematical\Geometry` extends [Base](../Base.md) and provides circle,
ellipse, and haversine distance calculations. Construct shapes with `new Geometry(mixed $value)`
and geographic points with `new Geometry($longitude, $latitude, $elevation)`.
The meaning of the stored value depends on the method: a radius, diameter, semi-axis length,
or starting longitude. Each calculation returns a new `Geometry`; read its result with `get()`
or the public readonly `$value` property. The original instance remains unchanged.

## Circles

| Method | Stored input | Result |
| --- | --- | --- |
| `circleSurface(): self` | Radius `r` | Surface area `πr²` |
| `circleCircumference(): self` | Radius `r` | Circumference `2πr` |
| `circleDiameter(): self` | Radius `r` | Diameter `2r` |
| `circleRadius(): self` | Diameter `d` | Radius `d / 2` |

```php
use ReeStyleIt\NumberClass\Mathematical\Geometry;

$circle = new Geometry(3); // radius
$surface = $circle->circleSurface()->get();             // approximately 28.2743338823
$circumference = $circle->circleCircumference()->get(); // approximately 18.8495559215
$diameter = $circle->circleDiameter()->get();           // 6
$radius = new Geometry(6)->circleRadius()->get();      // 3
$unchanged = $circle->get();                           // 3
```

`circleRadius()` specifically converts a diameter; it does not interpret the input as
an area or circumference. A diameter result can be converted back to a radius and then
used to calculate area:

```php
use function ReeStyleIt\NumberClass\geometry;

$surface = geometry(6)->circleRadius()->circleSurface()->get(); // approximately 28.2743338823
$originalRadius = geometry(3)->circleDiameter()->circleRadius()->get(); // 3
```

## Ellipses

`ellipseSurface(int|float $otherSemiAxis): self` calculates `πab`, where the stored value is
one semi-axis length and `$otherSemiAxis` is the other. Semi-axis lengths are half the full
width and height; either axis may be supplied first.

`ellipseCircumference(int|float $otherSemiAxis): self` uses
[Ramanujan's second approximation](https://arxiv.org/abs/math/0506384):

```text
h = ((a - b) / (a + b))²
circumference ≈ π(a + b)(1 + 3h / (10 + sqrt(4 - 3h)))
```

Unlike the surface formula, ellipse circumference is approximate. Floating point error and
approximation error are distinct; very elongated ellipses have greater approximation error.
The implementation normalizes the axes before calculating `h` to avoid unnecessary overflow
or underflow. If one semi-axis is zero, it uses the degenerate limit `4 × the other semi-axis`;
if both are zero, the circumference is zero. Equal semi-axes give the corresponding circle.

```php
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\number;

// Full width 6 and full height 4: semi-axes 3 and 2.
$ellipse = new Geometry(3);
$surface = $ellipse->ellipseSurface(2)->get();             // approximately 18.8495559215
$circumference = $ellipse->ellipseCircumference(2)->get(); // approximately 15.8654395893
$same = geometry(2)->ellipseSurface(3)->get();             // same surface

$viaProperty = number(3)->geometry->circleSurface()->get();
$converted = number(3)->geometry->ellipseSurface(2)->get();
```

Use the same length units for every dimension. Circumference, radius, and diameter use those
units; surface area uses their square (for example, square metres from radii in metres).
Circle radii, diameters, and ellipse semi-axes must be non-negative and finite. Zero-sized
shapes are accepted. Invalid lengths throw [GeometryException](../Exceptions/GeometryException.md).
Non-numeric stored values throw [MustBeNumberException](../Exceptions/MustBeNumberException.md).
Native floating point limits still apply: an unrepresentable final result can overflow or underflow.

## Haversine distance

```text
__construct(mixed $value, int|float|null $latitude = null, int|float $elevation = 0)

haversine(
    int|float $longitude,
    int|float $latitude,
    int|float $elevation,
    int|float $radius = 6371000.0,
): self
```

For geographic points, the constructor's `$value` is longitude. Its second argument is latitude,
and its third is elevation (zero by default). A starting latitude must be supplied explicitly.
The method's three required arguments are the destination's longitude, latitude, and elevation,
in that order. Longitude and latitude use degrees, with negative values for west and south.

The default sphere radius is 6,371,000 metres. Elevations and returned distances therefore use
metres by default. A custom radius and both elevations must use the same length unit; the
result uses that unit too. To obtain kilometres with the default radius, divide `get()` by 1000.

Haversine itself calculates horizontal distance along a sphere. This method then combines that
surface distance with the elevation difference using `hypot(surfaceDistance, elevationDifference)`.
This is a [short-distance approximation for elevation](https://geopy.readthedocs.io/en/stable/#calculating-distance),
not a terrain-following route, a full ellipsoidal calculation, or a straight chord through space.
Equal elevations leave the horizontal distance unchanged; their absolute height does not
change the sphere's radius.

```php
use ReeStyleIt\NumberClass\Mathematical\Geometry;

$london = new Geometry(-0.1278, 51.5074, 10);
$distance = $london->haversine(
    longitude: 2.3522,
    latitude: 48.8566,
    elevation: 35,
);

$metres = $distance->get();         // approximately 343556.061251
$longitude = $london->longitude;    // -0.1278; same as $london->get()
$latitude = $london->latitude;      // 51.5074
$elevation = $london->elevation;    // 10
$raw = $distance->value;           // same distance; public readonly
```

Starting longitude, latitude, and elevation are read-only. Numeric and geographic validation
runs when calculating. Shape construction with a single value remains supported.
The result is a new `Geometry` containing a distance; it has no starting latitude.
Reuse the original point or create another point for another route, rather than chaining a
second `haversine()` onto the distance result.

This API replaces the earlier latitude-only construction and kilometre default. Existing
haversine calls must move the starting longitude into the constructor, supply destination
elevation, and account for the default result now being in metres.

## Units and boundary cases

```php
use ReeStyleIt\NumberClass\Mathematical\Geometry;

$kilometres = new Geometry(-0.1278, 51.5074, 0)->haversine(2.3522, 48.8566, 0, radius: 6371)->get();
// Approximately 343.556060341 kilometres; elevations must also be in kilometres for this radius.

$unitSphere = new Geometry(0, 0, 0)->haversine(90, 0, 0, radius: 1)->get(); // approximately M_PI / 2
$samePoint = new Geometry(4, 52, 10)->haversine(4, 52, 10)->get(); // 0.0
$vertical = new Geometry(4, 52, 10)->haversine(4, 52, 110)->get(); // 100.0 metres
$dateLine = new Geometry(179, 0, 0)->haversine(-179, 0, 0)->get(); // approximately 222389.853289 metres
```

The calculation handles the date line and poles. Its intermediate haversine value is bounded
to `[0, 1]` to avoid invalid square roots from floating point error near opposite points.
Results use native floating point precision. The spherical model is an approximation when
used for Earth; use an ellipsoidal geodesic implementation when that accuracy is required.

## Helper usage and Number access

```php
use function ReeStyleIt\NumberClass\geometry;
use function ReeStyleIt\NumberClass\number;

$distance = geometry(-0.1278, 51.5074, 10)->haversine(2.3522, 48.8566, 35)->get();
$viaMethod = number(-0.1278)->geometry(latitude: 51.5074, elevation: 10)
    ->haversine(2.3522, 48.8566, 35)->get();
// Both return approximately 343556.061251 metres.

$display = number($distance)->divide(1000)->number->format(2); // '343.56'
```

The `geometry` property accessor still supports shape calculations. For points, use the
method accessor with latitude and optional elevation so the starting point is complete.

## Haversine validation

- Both latitudes must be finite and within `[-90, 90]` degrees, including the endpoints.
- Both longitudes must be finite and within `[-180, 180]` degrees, including the endpoints.
- Both elevations must be finite; negative elevations are valid.
- Radius must be finite and greater than zero.
- A starting latitude must be present; omitting it does not assume the equator.

Invalid or incomplete coordinates or radius throw [GeometryException](../Exceptions/GeometryException.md).
A stored value other than an integer or float throws
[MustBeNumberException](../Exceptions/MustBeNumberException.md) before coordinate validation.
Constructors and the helper store the value without validation; `haversine()` validates it.
Longitude values outside the accepted range are rejected rather than normalized.

## Continue with a Number

Both `number(): Number` and the read-only `number` property return a fresh
`ReeStyleIt\NumberClass\Number` containing the raw stored value. Each access validates that
the value is an integer or float; otherwise it throws `MustBeNumberException`.
The source instance remains unchanged.

```php
use ReeStyleIt\NumberClass\Mathematical\Geometry;

$value = new Geometry(3)->circleSurface();
$converted = $value->number->format(2); // '28.27'
$viaProperty = $value->number->divide(M_PI)->sqrt()->get(); // approximately 3.0
```

Convert a completed calculation to work with its distance or measurement. On a geographic
point itself, conversion wraps its stored longitude; latitude and elevation are not copied.

See [the shared base API](../Base.md) for numeric validation and storage.

For separate readonly shape objects, use the
[Dimension factory](Dimension.md), for example `dimension(3)->circle()->area()`.
