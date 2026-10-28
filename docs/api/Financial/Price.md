# ReeStyleIt\NumberClass\Financial\Price

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$value` | [string](https://www.php.net/manual/en/language.types.string.php) | read-only |

## Methods

- [__construct()](#method-__construct)
- [__toString()](#method-__tostring)
- [add()](#method-add)
- [discount()](#method-discount)
- [divide()](#method-divide)
- [format()](#method-format)
- [get()](#method-get)
- [multiply()](#method-multiply)
- [percentage()](#method-percentage)
- [round()](#method-round)
- [subtract()](#method-subtract)
- [withTax()](#method-withtax)
- [withoutTax()](#method-withouttax)

---

<a id="method-__construct"></a>

### `public function __construct(mixed $value)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-__tostring"></a>

### `public function __toString()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-add"></a>

### `public function add(mixed $amount)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$amount` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-discount"></a>

### `public function discount(mixed $percent)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$percent` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-divide"></a>

### `public function divide(mixed $divisor, int $scale = 12)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$divisor` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |
| `$scale` | [int](https://www.php.net/manual/en/language.types.integer.php) | `12` |

---

<a id="method-format"></a>

### `public function format(int $decimals = 2, RoundingMode $mode = RoundingMode::HalfAwayFromZero)`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$decimals` | [int](https://www.php.net/manual/en/language.types.integer.php) | `2` |
| `$mode` | [RoundingMode](https://www.php.net/manual/en/enum.roundingmode.php) | `RoundingMode::HalfAwayFromZero` |

---

<a id="method-get"></a>

### `public function get()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-multiply"></a>

### `public function multiply(mixed $quantity)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$quantity` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-percentage"></a>

### `public function percentage(mixed $percent)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$percent` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-round"></a>

### `public function round(int $precision = 2, RoundingMode $mode = RoundingMode::HalfAwayFromZero)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$precision` | [int](https://www.php.net/manual/en/language.types.integer.php) | `2` |
| `$mode` | [RoundingMode](https://www.php.net/manual/en/enum.roundingmode.php) | `RoundingMode::HalfAwayFromZero` |

---

<a id="method-subtract"></a>

### `public function subtract(mixed $amount)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$amount` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-withtax"></a>

### `public function withTax(mixed $percent)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$percent` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-withouttax"></a>

### `public function withoutTax(mixed $percent, int $scale = 12)`

Return: [ReeStyleIt\NumberClass\Financial\Price](Price.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$percent` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |
| `$scale` | [int](https://www.php.net/manual/en/language.types.integer.php) | `12` |
