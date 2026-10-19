<?php

namespace ReeStyleIt\NumberClass\Financial;

use DivisionByZeroError;
use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use RoundingMode;

readonly class Price
{
    public string $value;

    public function __construct(mixed $value)
    {
        $value = self::decimal($value);
        $this->value = bcadd($value, '0', self::scale($value));
    }

    public function get(): string
    {
        return $this->value;
    }

    public function add(mixed $amount): self
    {
        $amount = self::decimal($amount);

        return new self(bcadd($this->value, $amount, max(self::scale($this->value), self::scale($amount))));
    }

    public function subtract(mixed $amount): self
    {
        $amount = self::decimal($amount);

        return new self(bcsub($this->value, $amount, max(self::scale($this->value), self::scale($amount))));
    }

    public function multiply(mixed $quantity): self
    {
        $quantity = self::decimal($quantity);

        return new self(bcmul($this->value, $quantity, self::scale($this->value) + self::scale($quantity)));
    }

    public function divide(mixed $divisor, int $scale = 12): self
    {
        $divisor = self::decimal($divisor);
        self::assertScale($scale);

        if (bccomp($divisor, '0', self::scale($divisor)) === 0) {
            throw new DivisionByZeroError('Cannot divide a price by zero.');
        }

        return new self(bcdiv($this->value, $divisor, $scale));
    }

    public function percentage(mixed $percent): self
    {
        $percent = self::decimal($percent);
        $scale = self::scale($this->value) + self::scale($percent) + 2;

        return $this->multiply($percent)->divide(100, $scale);
    }

    public function discount(mixed $percent): self
    {
        $percent = self::nonNegativePercent($percent);

        if (bccomp($percent, '100', self::scale($percent)) > 0) {
            throw new FinancialException('Discount must be between 0 and 100 percent.');
        }

        return $this->subtract($this->percentage($percent));
    }

    public function withTax(mixed $percent): self
    {
        return $this->add($this->percentage(self::nonNegativePercent($percent)));
    }

    public function withoutTax(mixed $percent, int $scale = 12): self
    {
        $rate = new self(self::nonNegativePercent($percent))->percentage(1)->add(1);

        return $this->divide($rate, $scale);
    }

    public function round(int $precision = 2, RoundingMode $mode = RoundingMode::HalfAwayFromZero): self
    {
        return new self(bcround($this->value, $precision, $mode));
    }

    public function format(int $decimals = 2, RoundingMode $mode = RoundingMode::HalfAwayFromZero): string
    {
        self::assertScale($decimals);

        return bcadd(bcround($this->value, $decimals, $mode), '0', $decimals);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function decimal(mixed $value): string
    {
        if ($value instanceof self) {
            return $value->value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (! is_string($value) || ! preg_match('/^[+-]?(?:[0-9]+(?:\.[0-9]*)?|\.[0-9]+)$/D', $value)) {
            throw new FinancialException('Price values must be integers, plain decimal strings, or Price instances.');
        }

        return $value;
    }

    private static function scale(string $value): int
    {
        $point = strpos($value, '.');

        return $point === false ? 0 : strlen($value) - $point - 1;
    }

    private static function assertScale(int $scale): void
    {
        if ($scale < 0) {
            throw new FinancialException('Decimal scale must be non-negative.');
        }
    }

    private static function nonNegativePercent(mixed $percent): string
    {
        $percent = self::decimal($percent);

        if (bccomp($percent, '0', self::scale($percent)) < 0) {
            throw new FinancialException('Percentage must be non-negative.');
        }

        return $percent;
    }
}
