# ReeStyleIt\NumberClass\Enum\Currency

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$name` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |

## Methods

- [cases()](#method-cases)
- [from()](#method-from)
- [symbol()](#method-symbol)
- [tryFrom()](#method-tryfrom)

---

<a id="method-cases"></a>

### `public static function cases()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.

---

<a id="method-from"></a>

### `public static function from(string|int $value)`

Return: [static](Currency.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) | — |

---

<a id="method-symbol"></a>

### `public function symbol(Locale|string|null $locale = null)`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$locale` | [ReeStyleIt\NumberClass\Enum\Locale](Locale.md) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [null](https://www.php.net/manual/en/language.types.null.php) | `null` |

---

<a id="method-tryfrom"></a>

### `public static function tryFrom(string|int $value)`

Return: ?[static](Currency.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) | — |
