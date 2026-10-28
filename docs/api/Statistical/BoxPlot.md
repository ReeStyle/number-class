# ReeStyleIt\NumberClass\Statistical\BoxPlot

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$iqr` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$lowerWhisker` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$max` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$median` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$min` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$outliers` | [array](https://www.php.net/manual/en/language.types.array.php) | read-only |
| `$q1` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$q3` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$upperWhisker` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | read-only |
| `$values` | [array](https://www.php.net/manual/en/language.types.array.php) | read-only |

## Methods

- [__construct()](#method-__construct)
- [get()](#method-get)
- [image()](#method-image)

---

<a id="method-__construct"></a>

### `public function __construct(iterable $values)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$values` | [iterable](https://www.php.net/manual/en/language.types.iterable.php) | — |

---

<a id="method-get"></a>

### `public function get()`

Return: [ReeStyleIt\NumberClass\Statistical\BoxPlot\Result](BoxPlot/Result.md)

Parameters

None.

---

<a id="method-image"></a>

### `public function image(int $width = 800, int $height = 300)`

Return: [GdImage](https://www.php.net/manual/en/class.gdimage.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$width` | [int](https://www.php.net/manual/en/language.types.integer.php) | `800` |
| `$height` | [int](https://www.php.net/manual/en/language.types.integer.php) | `300` |
