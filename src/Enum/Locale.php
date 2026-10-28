<?php

namespace ReeStyleIt\NumberClass\Enum;

enum Locale: string
{
    case AR_AE = 'ar_AE';
    case AR_SA = 'ar_SA';
    case CS_CZ = 'cs_CZ';
    case DA_DK = 'da_DK';
    case DE_AT = 'de_AT';
    case DE_CH = 'de_CH';
    case DE_DE = 'de_DE';
    case EN_AU = 'en_AU';
    case EN_CA = 'en_CA';
    case EN_GB = 'en_GB';
    case EN_IN = 'en_IN';
    case EN_NZ = 'en_NZ';
    case EN_SG = 'en_SG';
    case EN_US = 'en_US';
    case EN_ZA = 'en_ZA';
    case ES_ES = 'es_ES';
    case ES_MX = 'es_MX';
    case FR_BE = 'fr_BE';
    case FR_CA = 'fr_CA';
    case FR_CH = 'fr_CH';
    case FR_FR = 'fr_FR';
    case HI_IN = 'hi_IN';
    case IT_CH = 'it_CH';
    case IT_IT = 'it_IT';
    case JA_JP = 'ja_JP';
    case KO_KR = 'ko_KR';
    case NB_NO = 'nb_NO';
    case NL_BE = 'nl_BE';
    case NL_NL = 'nl_NL';
    case PL_PL = 'pl_PL';
    case PT_BR = 'pt_BR';
    case PT_PT = 'pt_PT';
    case SV_SE = 'sv_SE';
    case TR_TR = 'tr_TR';
    case ZH_CN = 'zh_CN';
    case ZH_HK = 'zh_HK';

    public function currency(): Currency
    {
        return Currency::from($this->currencyFormat()['currency']);
    }

    public function symbol(): string
    {
        return $this->currencyFormat()['symbol'];
    }

    public function symbolPosition(): string
    {
        return $this->currencyFormat()['position'];
    }

    private function currencyFormat(): array
    {
        static $data = null;

        $data ??= require __DIR__ . '/../Resources/currency-symbols.php';

        return $data[$this->value];
    }
}
