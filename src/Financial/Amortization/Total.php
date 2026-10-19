<?php

namespace ReeStyleIt\NumberClass\Financial\Amortization;

use ReeStyleIt\NumberClass\Number;

readonly class Total
{
    public function __construct(
        public Number $payments,
        public Number $interest,
    ) {}
}
