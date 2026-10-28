<?php

namespace ReeStyleIt\NumberClass\Financial\Amortization;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line;

/**
 * @implements ArrayAccess<int, Line>
 * @implements IteratorAggregate<int, Line>
 */
class Schedule implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /** @var list<Line> */
    public readonly array $rows;

    /** @var Line|null */
    public ?Line $first {
        get {
            return $this->first();
        }
    }

    /** @var Line|null */
    public ?Line $last {
        get {
            return $this->last();
        }
    }

    /** @param array<int, Line> $rows */
    public function __construct(array $rows)
    {
        foreach ($rows as $row) {
            if (! $row instanceof Line) {
                throw new FinancialException('Schedule rows must be Line instances.');
            }
        }

        $this->rows = array_values($rows);
    }

    /** @return Line|null */
    public function first(): ?Line
    {
        return $this->rows[0] ?? null;
    }

    /** @return Line|null */
    public function last(): ?Line
    {
        return $this->rows[count($this->rows) - 1] ?? null;
    }

    /** @return Line|null */
    public function get(int $index): ?Line
    {
        return $this->rows[$index] ?? null;
    }

    /** @return list<array{period: int, payment: float, interest: float, principal: float, balance: float}> */
    public function toArray(): array
    {
        return array_map(fn (Line $line) => $line->toArray(), $this->rows);
    }

    public function count(): int
    {
        return count($this->rows);
    }

    public function isEmpty(): bool
    {
        return $this->rows === [];
    }

    public function column(string $column): array
    {
        return array_column($this->rows, $column);
    }

    public function offsetExists(mixed $offset): bool
    {
        return (is_int($offset) || is_string($offset)) && isset($this->rows[$offset]);
    }

    public function offsetGet(mixed $offset): ?Line
    {
        return $this->offsetExists($offset) ? $this->rows[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new FinancialException('Schedule rows are read-only.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new FinancialException('Schedule rows are read-only.');
    }

    /** @return ArrayIterator<int, Line> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->rows);
    }

    public function jsonSerialize(): array
    {
        return $this->rows;
    }
}
