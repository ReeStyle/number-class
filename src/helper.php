<?php

namespace ReeStyleIt\NumberClass;

use Carbon\Carbon;
use DateTimeInterface;
use ReeStyleIt\NumberClass\Conversion\Ordinal;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Conversion\Time\Diff;
use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Financial\Amortization;
use ReeStyleIt\NumberClass\Financial\CompoundInterest;
use ReeStyleIt\NumberClass\Financial\Price;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Round;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Dimension;
use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number\Compare;
use ReeStyleIt\NumberClass\Statistical\BoxPlot;
use ReeStyleIt\NumberClass\Statistical\Median;
use ReeStyleIt\NumberClass\Statistical\Mode;

if (! function_exists(__NAMESPACE__ . '\\amortization')) {
    function amortization(mixed $value): Amortization
    {
        return new Amortization($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\arithmetic')) {
    function arithmetic(mixed $value): Arithmetic
    {
        return new Arithmetic($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\boxPlot')) {
    function boxPlot(iterable $values): BoxPlot
    {
        return new BoxPlot($values);
    }
}

if (! function_exists(__NAMESPACE__ . '\\compare')) {
    function compare(mixed $value): Compare
    {
        return new Compare($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\compoundInterest')) {
    function compoundInterest(mixed $value): CompoundInterest
    {
        return new CompoundInterest($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\currencyFormatter')) {
    function currencyFormatter(mixed $value): Currency
    {
        return new Currency($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\diff')) {
    function diff(DateTimeInterface|Carbon|int|string $from): Diff
    {
        return new Diff($from);
    }
}

if (! function_exists(__NAMESPACE__ . '\\dimension')) {
    function dimension(mixed $value): Dimension
    {
        return new Dimension($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\geometry')) {
    function geometry(mixed $value, int|float|null $latitude = null, int|float $elevation = 0): Geometry
    {
        return new Geometry($value, $latitude, $elevation);
    }
}

if (! function_exists(__NAMESPACE__ . '\\median')) {
    function median(iterable $values): Median
    {
        return new Median($values);
    }
}

if (! function_exists(__NAMESPACE__ . '\\mode')) {
    function mode(iterable $values): Mode
    {
        return new Mode($values);
    }
}

if (! function_exists(__NAMESPACE__ . '\\number')) {
    function number(mixed $value): Number
    {
        return new Number($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\ordinal')) {
    function ordinal(mixed $value): Ordinal
    {
        return new Ordinal($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\price')) {
    function price(mixed $value): Price
    {
        return new Price($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\pythagoras')) {
    function pythagoras(mixed $value): Pythagoras
    {
        return new Pythagoras($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\round')) {
    function round(mixed $value): Round
    {
        return new Round($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\setDefaultLocale')) {
    function setDefaultLocale(Locale|string|null $locale): void
    {
        Base::setDefaultLocale($locale);
    }
}

if (! function_exists(__NAMESPACE__ . '\\time')) {
    function time(mixed $value = 0): Time
    {
        return new Time($value);
    }
}

if (! function_exists(__NAMESPACE__ . '\\toPct')) {
    function toPct(int|float|string $value, bool $exact = false): float|string
    {
        if (! $exact) {
            return $value / 100;
        }

        // Expand a float's scientific notation before passing it to BCMath.
        if (is_float($value)) {
            $value = (string) $value;

            if (preg_match('/^(-?)([0-9]+)(?:\.([0-9]+))?[eE]([+-]?[0-9]+)$/D', $value, $parts)) {
                $digits = $parts[2] . $parts[3];
                $point = strlen($parts[2]) + (int) $parts[4];
                $value = $parts[1] . ($point <= 0
                    ? '0.' . str_repeat('0', -$point) . $digits
                    : substr(str_pad($digits, $point, '0'), 0, $point) . '.' . substr($digits, $point));
            }
        }

        $decimal = new Price($value);
        $parts = explode('.', $decimal->get());

        return $decimal->divide(100, strlen($parts[1] ?? '') + 2)->get();
    }
}

if (! function_exists(__NAMESPACE__ . '\\trigonometry')) {
    function trigonometry(mixed $value): Trigonometry
    {
        return new Trigonometry($value);
    }
}
