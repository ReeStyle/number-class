# ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$balance` | [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$interest` | [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$payment` | [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$period` | [int](https://www.php.net/manual/en/language.types.integer.php) | read-only |
| `$principal` | [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$wordify` | [ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line\Wordify](Line/Wordify.md) | read-only |

## Methods

- [__construct()](#method-__construct)
- [jsonSerialize()](#method-jsonserialize)
- [toArray()](#method-toarray)
- [wordify()](#method-wordify)

---

<a id="method-__construct"></a>

### `public function __construct(int $period, float $payment, float $interest, float $principal, float $balance)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$period` | [int](https://www.php.net/manual/en/language.types.integer.php) | — |
| `$payment` | [float](https://www.php.net/manual/en/language.types.float.php) | — |
| `$interest` | [float](https://www.php.net/manual/en/language.types.float.php) | — |
| `$principal` | [float](https://www.php.net/manual/en/language.types.float.php) | — |
| `$balance` | [float](https://www.php.net/manual/en/language.types.float.php) | — |

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

---

<a id="method-wordify"></a>

### `public function wordify(Currency|string $currency = Currency::EUR, Locale|string|null $locale = null)`

Return: [ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line\Wordify](Line/Wordify.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$currency` | [ReeStyleIt\NumberClass\Enum\Currency](../../../Enum/Currency.md) \| [string](https://www.php.net/manual/en/language.types.string.php) | `ReeStyleIt\NumberClass\Enum\Currency::EUR` |
| `$locale` | [ReeStyleIt\NumberClass\Enum\Locale](../../../Enum/Locale.md) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [null](https://www.php.net/manual/en/language.types.null.php) | `null` |
