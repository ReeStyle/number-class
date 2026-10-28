<?php

namespace ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line;

use JsonSerializable;

readonly class Wordify implements JsonSerializable
{
    public function __construct(
        public string $period,
        public string $payment,
        public string $interest,
        public string $principal,
        public string $balance,
    ) {}

    /** @return array{period: string, payment: string, interest: string, principal: string, balance: string} */
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
