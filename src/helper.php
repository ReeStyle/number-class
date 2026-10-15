<?php

namespace ReeStyleIt\NumberClass;

use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Number\Compare;

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

if (!function_exists(__NAMESPACE__ . '\\compare')) {
    function compare(mixed $value): Compare
    {
        return new Compare($value);
    }
}
