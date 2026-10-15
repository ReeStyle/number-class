# Pythagoras

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Mathematical\Pythagoras` extends [Base](../Base.md) and calculates
right triangle lengths. Its constructor is `__construct(mixed $value)`.
Both methods return a new `Pythagoras`; read the length with `get()` or readonly `$value`.
Use the same units for all input lengths; the result uses those units.

## Hypotenuse

`hypotenuse(int|float $otherLeg): self` treats the stored value and `$otherLeg` as the two
perpendicular legs and calculates `sqrt(a² + b²)`.

```php
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;

$triangle = new Pythagoras(3);
$hypotenuse = $triangle->hypotenuse(4);
echo $hypotenuse->get(); // 5.0
echo $triangle->get();   // 3

$diagonal = new Pythagoras(1)->hypotenuse(1)->get(); // approximately 1.4142135623730951
$zero = new Pythagoras(0)->hypotenuse(0)->get();    // 0.0
```

## Missing leg

`leg(int|float $otherLeg): self` treats the stored value as the hypotenuse and calculates
the remaining leg using `sqrt(c² - a²)`. The supplied leg cannot exceed the hypotenuse.

```php
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;

$missing = new Pythagoras(13)->leg(5)->get(); // approximately 12.0
$equal = new Pythagoras(5)->leg(5)->get();    // 0.0
$zero = new Pythagoras(0)->leg(0)->get();     // 0.0
```

Zero lengths and degenerate triangles are accepted. Results use floating point arithmetic.
The implementation scales the calculation to reduce overflow and underflow from squaring
very large or small lengths, though an unrepresentable final result can still overflow.

## Helper usage and Number access

```php
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\pythagoras;

$length = pythagoras(3)->hypotenuse(4)->get();     // 5.0
$leg = pythagoras(5)->leg(3)->get();              // approximately 4.0
$viaProperty = number(3)->pythagoras->hypotenuse(4)->get();   // 5.0
$viaMethod = number(3)->pythagoras()->hypotenuse(4)->get();   // 5.0
```

For angles, use [Trigonometry](Trigonometry.md), for example `atan2()` with the two legs.
This class uses [Number](../Number.md) arithmetic internally.

## Errors

A non-numeric stored value throws [MustBeNumberException](../Exceptions/MustBeNumberException.md).
Negative, infinite, or NaN lengths throw [PythagorasException](../Exceptions/PythagorasException.md),
as does a leg longer than the hypotenuse.

```php
use ReeStyleIt\NumberClass\Exceptions\PythagorasException;
use function ReeStyleIt\NumberClass\pythagoras;

try {
    pythagoras(5)->leg(6);
} catch (PythagorasException $error) {
    echo $error->getMessage(); // A leg cannot be longer than the hypotenuse.
}
```
