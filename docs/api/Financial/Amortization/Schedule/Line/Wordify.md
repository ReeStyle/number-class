# ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line\Wordify

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$balance` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |
| `$interest` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |
| `$payment` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |
| `$period` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |
| `$principal` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |

## Methods

- [__construct()](#method-__construct)
- [jsonSerialize()](#method-jsonserialize)
- [toArray()](#method-toarray)

---

<a id="method-__construct"></a>

### `public function __construct(string $period, string $payment, string $interest, string $principal, string $balance)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$period` | [string](https://www.php.net/manual/en/language.types.string.php) | — |
| `$payment` | [string](https://www.php.net/manual/en/language.types.string.php) | — |
| `$interest` | [string](https://www.php.net/manual/en/language.types.string.php) | — |
| `$principal` | [string](https://www.php.net/manual/en/language.types.string.php) | — |
| `$balance` | [string](https://www.php.net/manual/en/language.types.string.php) | — |

---

<a id="method-jsonserialize"></a>

### `public function jsonSerialize()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.

---

<a id="method-toarray"></a>

### `public function toArray()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.
