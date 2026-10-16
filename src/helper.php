<?php

namespace ReeStyleIt\NumberClass;

use Carbon\Carbon;
use DateTimeInterface;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Conversion\Time\Diff;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number\Compare;

if (!function_exists(__NAMESPACE__ . '\\arithmetic')) {
    function arithmetic(mixed $value): Arithmetic
    {
        return new Arithmetic($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\compare')) {
    function compare(mixed $value): Compare
    {
        return new Compare($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\currencyFormatter')) {
    function currencyFormatter(mixed $value): Currency
    {
        return new Currency($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\diff')) {
    function diff(DateTimeInterface|Carbon|int|string $from): Diff
    {
        return new Diff($from);
    }
}

if (!function_exists(__NAMESPACE__ . '\\geometry')) {
    function geometry(mixed $value, int|float|null $latitude = null, int|float $elevation = 0): Geometry
    {
        return new Geometry($value, $latitude, $elevation);
    }
}

if (!function_exists(__NAMESPACE__ . '\\number')) {
    function number(mixed $value): Number
    {
        return new Number($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\pythagoras')) {
    function pythagoras(mixed $value): Pythagoras
    {
        return new Pythagoras($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\round')) {
    function round(mixed $value): Round
    {
        return new Round($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\time')) {
    function time(mixed $value = 0): Time
    {
        return new Time($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\trigonometry')) {
    function trigonometry(mixed $value): Trigonometry
    {
        return new Trigonometry($value);
    }
}
