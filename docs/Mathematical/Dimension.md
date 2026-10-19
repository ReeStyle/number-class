# Dimension

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Mathematical\Dimension` extends [Base](../Base.md).
It creates separate readonly shape objects in `Mathematical\Dimension`, using the
stored value as the first length. Use `new Dimension(mixed $value)`, the namespaced
`dimension($value)` helper, or another value object's `dimension` property or method.

```php
use function ReeStyleIt\NumberClass\dimension;
use function ReeStyleIt\NumberClass\number;

$rectangle = dimension(3)->rectangle(4); // Dimension\Rectangle
$rectangle->area()->get(); // 12
$rectangle->perimeter()->get(); // 14
$rectangle->diagonal()->get(); // 5.0
$circle = number(3)->dimension->circle(); // radius 3
$circle->area()->number->format(2); // '28.27'
$solid = dimension(3)->cuboid(4, 5);
$solid->volume()->get(); // 60
$solid->surface()->get(); // 94
```

## Factory methods

Every call returns a new shape; it preserves the factory's stored value. Factory
arguments and shape properties are PHP integers or floats. The first dimension must
be stored as a number; a numeric string throws `MustBeNumberException` when used.

| Method | Stored value | Other dimensions | Returned class |
| --- | --- | --- | --- |
| `rectangle($width)` | length | width | `Rectangle` |
| `square()` | side | none | `Square` |
| `triangle($sideB, $sideC)` | side A | sides B and C | `Triangle` |
| `rightTriangle($otherLeg)` | first perpendicular leg | other leg; hypotenuse is calculated | `Triangle` |
| `equilateralTriangle()` | side | none | `Triangle` |
| `circle()` | radius | none | `Circle` |
| `ellipse($otherSemiAxis)` | first semi-axis | other semi-axis, not full diameter | `Ellipse` |
| `cuboid($width, $height)` | length | width and perpendicular height | `Cuboid` |
| `cube()` | side | none | `Cube` |
| `pyramid($width, $height)` | base length | base width and perpendicular height | `Pyramid` |
| `cylinder($height)` | radius | perpendicular height | `Cylinder` |
| `cone($height)` | radius | perpendicular height | `Cone` |
| `sphere()` | radius | none | `Sphere` |

All names in the returned-class column belong to
`ReeStyleIt\NumberClass\Mathematical\Dimension`. These shapes can also be constructed
directly, with every dimension supplied:

```php
use ReeStyleIt\NumberClass\Mathematical\Dimension\Rectangle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Triangle;

$rectangle = new Rectangle(length: 3, width: 4);
$triangle = new Triangle(sideA: 3, sideB: 4, sideC: 5);
$triangle->area()->get(); // approximately 6
```

## Measurements

All measurement methods return a fresh `Number`, allowing further arithmetic,
comparison, rounding, or formatting. Public dimensions are readonly. Shape objects
hold multiple measurements and do not extend the single-value `Base` class.

Plane shapes provide `area(): Number`, `surface(): Number` (an alias for area), and
`perimeter(): Number`:

- Rectangle and Square expose `length` and `width`; Square also exposes `side`.
  Both provide `diagonal()` using `hypot()`.
- Triangle exposes `sideA`, `sideB`, and `sideC`. Its area uses a factored Heron formula
  to limit intermediate overflow and loss of precision in thin triangles.
- Circle exposes `radius`, with `diameter()` and `circumference()` (alias for perimeter).
- Ellipse exposes `semiAxisA` and `semiAxisB`, with `circumference()` as an alias for
  perimeter. Its perimeter uses the existing Geometry Ramanujan approximation;
  it is not an exact elliptic integral.

Solid shapes provide `volume(): Number`, `surface(): Number`, and `surfaceArea(): Number`
(an alias for surface). Surface area includes the bases:

- Cuboid exposes `length`, `width`, and `height`. Cube sets all three from its `side`.
  Both provide the space `diagonal()`.
- Pyramid exposes rectangular base `length`, `width`, and perpendicular `height`.
  The apex lies directly above the base center. Surface area uses the two distinct
  slant heights of its triangular faces. It does not model arbitrary or oblique pyramids.
- Cylinder and Cone expose `radius` and perpendicular `height`. They are right circular
  solids. Cone also provides `slantHeight()`.
- Sphere exposes `radius`.

All lengths use the same caller-chosen unit. Areas use squared units; volumes use cubed
units. No unit conversion is performed.

## Validation and limits

Dimensions must be finite and non-negative. Zero-sized and degenerate shapes are
accepted; degenerate triangles have zero area. Triangle sides must satisfy the triangle
inequality. Invalid lengths or impossible triangles throw
[GeometryException](../Exceptions/GeometryException.md). Measurements whose calculations
produce infinity or NaN also throw that exception. Calculations otherwise follow PHP's
native numeric precision, including floating-point underflow and rounding limits.

Circle and ellipse calculations reuse [Geometry](Geometry.md). Existing Geometry
methods remain available alongside these shape classes.
