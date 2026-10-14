<?php

namespace ReeStyleIt\IntClass;

use ReeStyleIt\IntClass\Format\Currency;

if (!function_exists(__NAMESPACE__ . '\\number')) {
    function number(mixed $value): Number
    {
        return new Number($value);
    }
}

if (!function_exists(__NAMESPACE__ . '\\currencyFormatter')) {
    function currencyFormatter(mixed $value): Currency
    {
        return new Currency($value);
    }
}
