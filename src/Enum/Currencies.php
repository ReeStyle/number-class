<?php

namespace ReeStyleIt\NumberClass\Enums;

use NumberFormatter;
use ReeStyleIt\NumberClass\Exceptions\CurrencySymbolLookupException;

enum Currencies: string
{
    case AED = 'AED';
    case AUD = 'AUD';
    case BRL = 'BRL';
    case CAD = 'CAD';
    case CHF = 'CHF';
    case CNY = 'CNY';
    case CZK = 'CZK';
    case DKK = 'DKK';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case HKD = 'HKD';
    case INR = 'INR';
    case JPY = 'JPY';
    case KRW = 'KRW';
    case MXN = 'MXN';
    case NOK = 'NOK';
    case NZD = 'NZD';
    case PLN = 'PLN';
    case SAR = 'SAR';
    case SEK = 'SEK';
    case SGD = 'SGD';
    case TRY = 'TRY';
    case USD = 'USD';
    case ZAR = 'ZAR';

    public function symbol(Locales|string $locale = Locales::EN_US): string
    {
        $locale = $locale instanceof Locales ? $locale->value : $locale;

        if (!class_exists(NumberFormatter::class)) {
            return $this->fallbackSymbol($locale);
        }

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        if (!$formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $this->value)) {
            throw new CurrencySymbolLookupException($formatter->getErrorMessage());
        }

        $symbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        if ($symbol === false) {
            throw new CurrencySymbolLookupException($formatter->getErrorMessage());
        }

        return $symbol;
    }

    private function fallbackSymbol(string $locale): string
    {
        $locale = str_replace('-', '_', preg_split('/[.@]/', $locale)[0]);
        $resolved = Locales::EN_US;

        foreach (Locales::cases() as $candidate) {
            if (strcasecmp($candidate->value, $locale) === 0) {
                $resolved = $candidate;
                break;
            }
        }

        return $resolved->currency() === $this ? $resolved->symbol() : $this->value;
    }
}
