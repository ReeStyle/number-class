<?php

namespace ReeStyleIt\NumberClass\Mathematical;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Circle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cone;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cube;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cuboid;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cylinder;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Ellipse;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Pyramid;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Rectangle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Sphere;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Square;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Triangle;

class Dimension extends Base
{
    public function circle(): Circle
    {
        $this->assertNumber();

        return new Circle($this->value);
    }

    public function cone(int|float $height): Cone
    {
        $this->assertNumber();

        return new Cone($this->value, $height);
    }

    public function cube(): Cube
    {
        $this->assertNumber();

        return new Cube($this->value);
    }

    public function cuboid(int|float $width, int|float $height): Cuboid
    {
        $this->assertNumber();

        return new Cuboid($this->value, $width, $height);
    }

    public function cylinder(int|float $height): Cylinder
    {
        $this->assertNumber();

        return new Cylinder($this->value, $height);
    }

    public function ellipse(int|float $otherSemiAxis): Ellipse
    {
        $this->assertNumber();

        return new Ellipse($this->value, $otherSemiAxis);
    }

    public function equilateralTriangle(): Triangle
    {
        $this->assertNumber();

        return new Triangle($this->value, $this->value, $this->value);
    }

    public function pyramid(int|float $width, int|float $height): Pyramid
    {
        $this->assertNumber();

        return new Pyramid($this->value, $width, $height);
    }

    public function rectangle(int|float $width): Rectangle
    {
        $this->assertNumber();

        return new Rectangle($this->value, $width);
    }

    public function rightTriangle(int|float $otherLeg): Triangle
    {
        $this->assertNumber();

        return new Triangle($this->value, $otherLeg, hypot($this->value, $otherLeg));
    }

    public function sphere(): Sphere
    {
        $this->assertNumber();

        return new Sphere($this->value);
    }

    public function square(): Square
    {
        $this->assertNumber();

        return new Square($this->value);
    }

    public function triangle(int|float $sideB, int|float $sideC): Triangle
    {
        $this->assertNumber();

        return new Triangle($this->value, $sideB, $sideC);
    }
}
