# Trigonometry

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Mathematical\Trigonometry` extends [Base](../Base.md).
Construct it with `new Trigonometry(mixed $value)`. Each method returns a new `Trigonometry`;
use `get()` or public readonly `$value` for the result. Angles are expressed in radians
unless explicitly converting degrees with `deg2rad()` or `rad2deg()`.

## Methods

| Signature | Stored value and result |
| --- | --- |
| `sin(): self` | Angle in radians → sine |
| `cos(): self` | Angle in radians → cosine |
| `tan(): self` | Angle in radians → tangent |
| `asin(): self` | Sine ratio → angle in radians |
| `acos(): self` | Cosine ratio → angle in radians |
| `atan(): self` | Tangent ratio → angle in radians |
| `atan2(int|float $x): self` | Stored y-coordinate and supplied x-coordinate → angle in radians |
| `deg2rad(): self` | Degrees → radians |
| `rad2deg(): self` | Radians → degrees |

## Angles and ratios

```php
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;

$sine = new Trigonometry(M_PI / 2)->sin()->get(); // 1.0
$cosine = new Trigonometry(0)->cos()->get();      // 1.0
$tangent = new Trigonometry(M_PI / 4)->tan()->get(); // approximately 1.0

$degrees = new Trigonometry(90);
$radians = $degrees->deg2rad();
echo $radians->get(); // approximately 1.5707963267948966
echo $degrees->get(); // 90; the original remains unchanged
$convertedBack = $radians->rad2deg()->get(); // approximately 90.0
```

Inverse functions return radians; convert to degrees when needed.
`atan2()` uses both coordinates to determine the quadrant and does not require dividing by x.

```php
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;

$asin = new Trigonometry(0.5)->asin()->rad2deg()->get(); // approximately 30.0
$acos = new Trigonometry(0.5)->acos()->rad2deg()->get(); // approximately 60.0
$atan = new Trigonometry(1)->atan()->rad2deg()->get();   // approximately 45.0
$quadrant = new Trigonometry(1)->atan2(-1)->rad2deg()->get(); // 135.0

// Opposite leg 3, adjacent leg 4:
$angle = new Trigonometry(3)->atan2(4)->rad2deg()->get(); // approximately 36.8699
```

## Helper usage and lengths

```php
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\trigonometry;

$sine = trigonometry(30)->deg2rad()->sin()->get(); // approximately 0.5
$oppositeLeg = number(10)->multiply($sine)->get(); // approximately 5.0
$viaProperty = number(90)->trigonometry->deg2rad()->sin()->get(); // 1.0
$converted = number(0)->trigonometry->cos()->get(); // 1.0
```

Use [Pythagoras](Pythagoras.md) when calculating a third side from two known sides.

## Validation and precision

Every operation throws [MustBeNumberException](../Exceptions/MustBeNumberException.md)
if the stored value is not an `int` or `float`. `asin()` and `acos()` outside `[-1, 1]`
return `NAN` following PHP's native behavior. Results use floating point precision;
compare approximate results with a tolerance rather than expecting exact equality.
The tangent near odd multiples of π/2 can be extremely large because the angle is approximate.

## Continue with a Number

Both `number(): Number` and the read-only `number` property return a fresh
`ReeStyleIt\NumberClass\Number` containing the raw stored value. Each access validates that
the value is an integer or float; otherwise it throws `MustBeNumberException`.
The source instance remains unchanged.

```php
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;

$value = new Trigonometry(30)->deg2rad()->sin();
$converted = $value->number->multiply(10)->get(); // approximately 5.0
$viaProperty = $value->number->format(2); // '0.50'
```

See [the shared base API](../Base.md) for numeric validation and storage.
