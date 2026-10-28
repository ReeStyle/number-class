<?php

namespace ReeStyleIt\NumberClass\Financial\Amortization\Schedule;

use JsonSerializable;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Conversion\Ordinal;
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line\Wordify;
use ReeStyleIt\NumberClass\Format\Currency;

class Line implements JsonSerializable
{
    public Wordify $wordify {
        get {
            return $this->wordify();
        }
    }

    public function __construct(
        public readonly int $period,
        public readonly float $payment,
        public readonly float $interest,
        public readonly float $principal,
        public readonly float $balance,
    ) {}

    public function wordify(Currencies|string $currency = Currencies::EUR, Locales|string|null $locale = null): Wordify
    {
        $locale ??= Base::getDefaultLocale() ?? Locales::EN_US;
        $format = fn (float $amount): string => (string) new Currency($amount)->currency($currency)->locale($locale);

        return new Wordify(
            period: new Ordinal($this->period)->locale($locale)->wordify(),
            payment: $format($this->payment),
            interest: $format($this->interest),
            principal: $format($this->principal),
            balance: $format($this->balance),
        );
    }

    /** @return array{period: int, payment: float, interest: float, principal: float, balance: float} */
    public function toArray(): array
    {
        return [
            'period'    => $this->period,
            'payment'   => $this->payment,
            'interest'  => $this->interest,
            'principal' => $this->principal,
            'balance'   => $this->balance,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
