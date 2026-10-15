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
        static $data = null;

        $data ??= require __DIR__ . '/../Resources/currency-symbols.php';

        $locale = strtolower(str_replace('-', '_', preg_split('/[.@]/', $locale)[0]));
        $language = explode('_', $locale)[0];

        foreach ([$locale, $language] as $candidate) {
            $candidate = $data['aliases'][$candidate] ?? $candidate;

            if (isset($data['locales'][$candidate])) {
                return $data['locales'][$candidate][$this->value] ?? $data['default'][$this->value];
            }
        }

        return $data['default'][$this->value];
    }
}
