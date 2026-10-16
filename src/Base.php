<?php

namespace ReeStyleIt\NumberClass;

use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number\Compare;

abstract class Base
{
    public Arithmetic $arithmetic {
        get {
            $this->assertNumber();

            return new Arithmetic($this->value);
        }
    }

    public Compare $compare {
        get {
            $this->assertNumber();

            return new Compare($this->value);
        }
    }

    public Currency $currency {
        get {
            $this->assertNumber();

            return new Currency($this->value);
        }
    }

    public Geometry $geometry {
        get {
            $this->assertNumber();

            return new Geometry($this->value);
        }
    }

    public Number $number {
        get {
            $this->assertNumber();

            return new Number($this->value);
        }
    }

    public Pythagoras $pythagoras {
        get {
            $this->assertNumber();

            return new Pythagoras($this->value);
        }
    }

    public Round $round {
        get {
            $this->assertNumber();

            return new Round($this->value);
        }
    }

    public Time $time {
        get {
            $this->assertNumber();

            return new Time($this->value);
        }
    }

    public Trigonometry $trigonometry {
        get {
            $this->assertNumber();

            return new Trigonometry($this->value);
        }
    }

    public readonly mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function arithmetic(): Arithmetic
    {
        return $this->arithmetic;
    }

    public function compare(): Compare
    {
        return $this->compare;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function geometry(int|float|null $latitude = null, int|float $elevation = 0): Geometry
    {
        $this->assertNumber();

        return new Geometry($this->value, $latitude, $elevation);
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function number(): Number
    {
        return $this->number;
    }

    public function pythagoras(): Pythagoras
    {
        return $this->pythagoras;
    }

    public function round(): Round
    {
        return $this->round;
    }

    public function time(): Time
    {
        return $this->time;
    }

    public function trigonometry(): Trigonometry
    {
        return $this->trigonometry;
    }

    protected function assertNumber(): void
    {
        if (!is_int($this->value) && !is_float($this->value)) {
            throw new MustBeNumberException();
        }
    }
}
