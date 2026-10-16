# Time

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Conversion\Time` extends [Base](../Base.md).
Create it with `new Time(mixed $value = 0)`.
`from(int|DateTimeInterface|string $from, int|DateTimeInterface|string|null $to = null): Time\Diff|DateInterval`
creates a new
`ReeStyleIt\NumberClass\Conversion\Time\Diff` with the supplied starting point.
When `$to` is supplied, it calls `Diff::diff($to)` and returns the resulting native
`DateInterval` directly. An explicit `null` endpoint means now; omitting the endpoint
returns the `Time\Diff` object.
The read-only `from` accessor creates a new `Time\Diff` using the stored value instead:

```php
$seconds = new Time(60)->from->diff(90)->s; // 30
$seconds = new Time()->from(60, 90)->s; // 30
```

## Duration formatting

`asTime(bool $includeDays = false): string` treats the stored numeric value as elapsed
seconds. It returns `HH:MM:SS` below 24 hours and `DD:HH:MM:SS` for longer durations.
Pass `includeDays: true` to always include days, including `00`. Each component has at
least two digits; hours wrap at 24 and days have no upper display limit.
Fractional seconds are truncated. The stored value is preserved.

```php
time(3661)->asTime(); // '01:01:01'
time(3661)->asTime(includeDays: true); // '00:01:01:01'
time(90061)->asTime(); // '01:01:01:01'
```

Formatting requires an integer or float. Other values throw `MustBeNumberException`.
Negative or nonfinite durations and floats too large for a whole-second integer
throw `TimeException`.

## Starting point

On the returned `Time\Diff`, the public `from` property holds a `Carbon\Carbon` date.
Only `Time\Diff` can assign this property. Read its Unix timestamp with `getTimestamp()`.

```php
use ReeStyleIt\NumberClass\Conversion\Time;

$time = new Time()->from('2024-10-17T14:00:00+02:00');
$timestamp = $time->from->getTimestamp(); // 1729166400
```

Accepted values include integer Unix timestamps, signed integer timestamp strings,
`DateTime`, `DateTimeImmutable`, Carbon instances, and date strings understood by PHP.
Zero and negative timestamps are valid. Integer timestamps are interpreted in UTC;
date strings without a timezone use PHP's default timezone.

```php
use ReeStyleIt\NumberClass\Conversion\Time;

$epoch = new Time()->from(0);
$beforeEpoch = new Time()->from('-1');
$timestampString = new Time()->from('1729166400');
$date = new DateTimeImmutable('2024-10-17T12:00:00Z');
$copied = new Time()->from($date);
```

Date objects are copied, preserving their timezone and microseconds. Later changes to
an input date do not change the stored starting point. The stored Carbon object itself
is mutable; `private(set)` protects property assignment, not changes to that object.
Every call to `Time::from()` or read of `Time::$from` creates an independent difference
object. The original `Time` object remains unchanged.

Empty strings, invalid dates (including dates PHP would normalize such as February 30),
null bytes, and timestamps outside the supported range throw
[TimeException](../Exceptions/TimeException.md). A failed call preserves the previous start.

## Difference

`diff(DateTimeInterface|Carbon|int|string|null $to = null): DateInterval` returns a
fresh native `DateInterval` containing the calendar difference from the starting point
to the endpoint. It accepts the same inputs as `from()` and defaults to now when omitted
or `null`. Read years, months, days, hours, minutes, seconds, and fractional seconds from
`y`, `m`, `d`, `h`, `i`, `s`, and `f`. The `days` property contains the total number of
full days. An earlier endpoint sets `invert` to `1`; otherwise it is `0`.
Each call recalculates the difference and preserves the starting point.
The method belongs to `Time\Diff`; an invalid endpoint throws `TimeException`.

```php
$time = new Time()->from('2024-10-17T12:00:00Z');
$difference = $time->diff('2024-10-17T13:30:00Z'); // DateInterval
$hours = $difference->h; // 1
$minutes = $difference->i; // 30
$display = $difference->format('%R%h:%I:%S'); // '+1:30:00'
```

## Helper and shared accessors

The namespaced `diff(DateTimeInterface|Carbon|int|string $from): Time\Diff` helper
creates a difference object directly: `diff(60)->diff(90)` returns a `DateInterval`.
Import it with `use function ReeStyleIt\NumberClass\diff;`.

The namespaced `time(mixed $value = 0): Time` helper follows the constructor parameters.
Import it with `use function`; PHP's native timestamp function remains available as `\time()`.

```php
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\time;

$start = time()->from('2024-10-17T12:00:00Z');
$timestamp = $start->from->getTimestamp(); // 1729166400
$shared = number(60)->time;
$seconds = $shared->from->diff(90)->s; // 30
$original = $shared->get(); // 60
```

`from()` is independent of the inherited numeric `value`; `get()` returns that original
value. Shared accessors such as `number`, `compare`, and `arithmetic` use the original value.

The public `results` array starts empty and can only be written from inside `Time`.
Starting points and difference calculations belong to `Time\Diff`; duration conversion
and dissection are reserved for subsequent development.
