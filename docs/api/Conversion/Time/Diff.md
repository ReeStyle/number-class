# ReeStyleIt\NumberClass\Conversion\Time\Diff

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$from` | [Carbon\Carbon](https://carbon.nesbot.com/develop/reference.html) | read-only |

## Methods

- [__construct()](#method-__construct)
- [diff()](#method-diff)

---

<a id="method-__construct"></a>

### `public function __construct(DateTimeInterface|Carbon|string|int $from)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$from` | [DateTimeInterface](https://www.php.net/manual/en/class.datetimeinterface.php) \| [Carbon\Carbon](https://carbon.nesbot.com/develop/reference.html) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) | — |

---

<a id="method-diff"></a>

### `public function diff(DateTimeInterface|Carbon|string|int|null $to = null)`

Return: [DateInterval](https://www.php.net/manual/en/class.dateinterval.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$to` | [DateTimeInterface](https://www.php.net/manual/en/class.datetimeinterface.php) \| [Carbon\Carbon](https://carbon.nesbot.com/develop/reference.html) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) \| [null](https://www.php.net/manual/en/language.types.null.php) | `null` |
