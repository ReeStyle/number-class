<?php

namespace ReeStyleIt\IntClass\Enums;

use NumberFormatter;
use ReeStyleIt\IntClass\Format\Currency;
use RuntimeException;

enum Currencies: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
    case CHF = 'CHF';
    case JPY = 'JPY';
    case CAD = 'CAD';
    case AUD = 'AUD';
    case NZD = 'NZD';
    case CNY = 'CNY';
    case HKD = 'HKD';
    case SGD = 'SGD';
    case INR = 'INR';
    case KRW = 'KRW';
    case SEK = 'SEK';
    case NOK = 'NOK';
    case DKK = 'DKK';
    case PLN = 'PLN';
    case CZK = 'CZK';
    case BRL = 'BRL';
    case MXN = 'MXN';
    case ZAR = 'ZAR';
    case AED = 'AED';
    case SAR = 'SAR';
    case TRY = 'TRY';

    public function symbol(string $locale = 'en_US'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        if (!$formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $this->value)) {
            throw new RuntimeException('Currency symbol lookup failed: ' . $formatter->getErrorMessage());
        }

        $symbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        if ($symbol === false) {
            throw new RuntimeException('Currency symbol lookup failed: ' . $formatter->getErrorMessage());
        }

        return $symbol;
    }
}
