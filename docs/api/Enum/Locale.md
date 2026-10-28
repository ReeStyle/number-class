# ReeStyleIt\NumberClass\Enum\Locale

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$name` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |

## Methods

- [cases()](#method-cases)
- [currency()](#method-currency)
- [from()](#method-from)
- [symbol()](#method-symbol)
- [symbolPosition()](#method-symbolposition)
- [tryFrom()](#method-tryfrom)

---

<a id="method-cases"></a>

### `public static function cases()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.

---

<a id="method-currency"></a>

### `public function currency()`

Return: [ReeStyleIt\NumberClass\Enum\Currency](Currency.md)

Parameters

None.

---

<a id="method-from"></a>

### `public static function from(string|int $value)`

Return: [static](Locale.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) | — |

---

<a id="method-symbol"></a>

### `public function symbol()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-symbolposition"></a>

### `public function symbolPosition()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-tryfrom"></a>

### `public static function tryFrom(string|int $value)`

Return: ?[static](Locale.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) | — |
