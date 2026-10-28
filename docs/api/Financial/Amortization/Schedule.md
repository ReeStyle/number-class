# ReeStyleIt\NumberClass\Financial\Amortization\Schedule

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$first` | ?[ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line](Schedule/Line.md) | read-only |
| `$last` | ?[ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line](Schedule/Line.md) | read-only |
| `$rows` | [array](https://www.php.net/manual/en/language.types.array.php) | read-only |

## Methods

- [__construct()](#method-__construct)
- [column()](#method-column)
- [count()](#method-count)
- [first()](#method-first)
- [get()](#method-get)
- [getIterator()](#method-getiterator)
- [isEmpty()](#method-isempty)
- [jsonSerialize()](#method-jsonserialize)
- [last()](#method-last)
- [offsetExists()](#method-offsetexists)
- [offsetGet()](#method-offsetget)
- [offsetSet()](#method-offsetset)
- [offsetUnset()](#method-offsetunset)
- [toArray()](#method-toarray)

---

<a id="method-__construct"></a>

### `public function __construct(array $rows)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$rows` | [array](https://www.php.net/manual/en/language.types.array.php) | — |

---

<a id="method-column"></a>

### `public function column(string $column)`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$column` | [string](https://www.php.net/manual/en/language.types.string.php) | — |

---

<a id="method-count"></a>

### `public function count()`

Return: [int](https://www.php.net/manual/en/language.types.integer.php)

Parameters

None.

---

<a id="method-first"></a>

### `public function first()`

Return: ?[ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line](Schedule/Line.md)

Parameters

None.

---

<a id="method-get"></a>

### `public function get(int $index)`

Return: ?[ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line](Schedule/Line.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$index` | [int](https://www.php.net/manual/en/language.types.integer.php) | — |

---

<a id="method-getiterator"></a>

### `public function getIterator()`

Return: [ArrayIterator](https://www.php.net/manual/en/class.arrayiterator.php)

Parameters

None.

---

<a id="method-isempty"></a>

### `public function isEmpty()`

Return: [bool](https://www.php.net/manual/en/language.types.boolean.php)

Parameters

None.

---

<a id="method-jsonserialize"></a>

### `public function jsonSerialize()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.

---

<a id="method-last"></a>

### `public function last()`

Return: ?[ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line](Schedule/Line.md)

Parameters

None.

---

<a id="method-offsetexists"></a>

### `public function offsetExists(mixed $offset)`

Return: [bool](https://www.php.net/manual/en/language.types.boolean.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$offset` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-offsetget"></a>

### `public function offsetGet(mixed $offset)`

Return: ?[ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line](Schedule/Line.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$offset` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-offsetset"></a>

### `public function offsetSet(mixed $offset, mixed $value)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$offset` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |
| `$value` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-offsetunset"></a>

### `public function offsetUnset(mixed $offset)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$offset` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-toarray"></a>

### `public function toArray()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.
