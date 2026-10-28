# ReeStyleIt\NumberClass\Mathematical\Trigonometry

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$amortization` | [ReeStyleIt\NumberClass\Financial\Amortization](../Financial/Amortization.md) | read-only |
| `$arithmetic` | [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md) | read-only |
| `$boxPlot` | [ReeStyleIt\NumberClass\Statistical\BoxPlot](../Statistical/BoxPlot.md) | read-only |
| `$compare` | [ReeStyleIt\NumberClass\Number\Compare](../Number/Compare.md) | read-only |
| `$compoundInterest` | [ReeStyleIt\NumberClass\Financial\CompoundInterest](../Financial/CompoundInterest.md) | read-only |
| `$currency` | [ReeStyleIt\NumberClass\Format\Currency](../Format/Currency.md) | read-only |
| `$dimension` | [ReeStyleIt\NumberClass\Mathematical\Dimension](Dimension.md) | read-only |
| `$geometry` | [ReeStyleIt\NumberClass\Mathematical\Geometry](Geometry.md) | read-only |
| `$median` | [ReeStyleIt\NumberClass\Statistical\Median](../Statistical/Median.md) | read-only |
| `$mode` | [ReeStyleIt\NumberClass\Statistical\Mode](../Statistical/Mode.md) | read-only |
| `$number` | [ReeStyleIt\NumberClass\Number](../Number.md) | read-only |
| `$ordinal` | [ReeStyleIt\NumberClass\Conversion\Ordinal](../Conversion/Ordinal.md) | read-only |
| `$pythagoras` | [ReeStyleIt\NumberClass\Mathematical\Pythagoras](Pythagoras.md) | read-only |
| `$round` | [ReeStyleIt\NumberClass\Format\Round](../Format/Round.md) | read-only |
| `$time` | [ReeStyleIt\NumberClass\Conversion\Time](../Conversion/Time.md) | read-only |
| `$trigonometry` | [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md) | read-only |
| `$value` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | read-only |

## Methods

- [__construct()](#method-__construct)
- [acos()](#method-acos)
- [amortization()](#method-amortization)
- [arithmetic()](#method-arithmetic)
- [asin()](#method-asin)
- [atan()](#method-atan)
- [atan2()](#method-atan2)
- [boxPlot()](#method-boxplot)
- [compare()](#method-compare)
- [compoundInterest()](#method-compoundinterest)
- [cos()](#method-cos)
- [currency()](#method-currency)
- [deg2rad()](#method-deg2rad)
- [dimension()](#method-dimension)
- [geometry()](#method-geometry)
- [get()](#method-get)
- [getDefaultLocale()](#method-getdefaultlocale)
- [median()](#method-median)
- [mode()](#method-mode)
- [number()](#method-number)
- [ordinal()](#method-ordinal)
- [pythagoras()](#method-pythagoras)
- [rad2deg()](#method-rad2deg)
- [round()](#method-round)
- [setDefaultLocale()](#method-setdefaultlocale)
- [sin()](#method-sin)
- [tan()](#method-tan)
- [time()](#method-time)
- [trigonometry()](#method-trigonometry)

---

<a id="method-__construct"></a>

### `public function __construct(mixed $value)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | — |

---

<a id="method-acos"></a>

### `public function acos()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-amortization"></a>

### `public function amortization()`

Return: [ReeStyleIt\NumberClass\Financial\Amortization](../Financial/Amortization.md)

Parameters

None.

---

<a id="method-arithmetic"></a>

### `public function arithmetic()`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

None.

---

<a id="method-asin"></a>

### `public function asin()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-atan"></a>

### `public function atan()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-atan2"></a>

### `public function atan2(int|float $x)`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$x` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | — |

---

<a id="method-boxplot"></a>

### `public function boxPlot()`

Return: [ReeStyleIt\NumberClass\Statistical\BoxPlot](../Statistical/BoxPlot.md)

Parameters

None.

---

<a id="method-compare"></a>

### `public function compare()`

Return: [ReeStyleIt\NumberClass\Number\Compare](../Number/Compare.md)

Parameters

None.

---

<a id="method-compoundinterest"></a>

### `public function compoundInterest()`

Return: [ReeStyleIt\NumberClass\Financial\CompoundInterest](../Financial/CompoundInterest.md)

Parameters

None.

---

<a id="method-cos"></a>

### `public function cos()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-currency"></a>

### `public function currency()`

Return: [ReeStyleIt\NumberClass\Format\Currency](../Format/Currency.md)

Parameters

None.

---

<a id="method-deg2rad"></a>

### `public function deg2rad()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-dimension"></a>

### `public function dimension()`

Return: [ReeStyleIt\NumberClass\Mathematical\Dimension](Dimension.md)

Parameters

None.

---

<a id="method-geometry"></a>

### `public function geometry(int|float|null $latitude = null, int|float $elevation = 0)`

Return: [ReeStyleIt\NumberClass\Mathematical\Geometry](Geometry.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$latitude` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) \| [null](https://www.php.net/manual/en/language.types.null.php) | `null` |
| `$elevation` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | `0` |

---

<a id="method-get"></a>

### `public function get()`

Return: [mixed](https://www.php.net/manual/en/language.types.mixed.php)

Parameters

None.

---

<a id="method-getdefaultlocale"></a>

### `public static function getDefaultLocale()`

Return: ?[string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-median"></a>

### `public function median()`

Return: [ReeStyleIt\NumberClass\Statistical\Median](../Statistical/Median.md)

Parameters

None.

---

<a id="method-mode"></a>

### `public function mode()`

Return: [ReeStyleIt\NumberClass\Statistical\Mode](../Statistical/Mode.md)

Parameters

None.

---

<a id="method-number"></a>

### `public function number()`

Return: [ReeStyleIt\NumberClass\Number](../Number.md)

Parameters

None.

---

<a id="method-ordinal"></a>

### `public function ordinal()`

Return: [ReeStyleIt\NumberClass\Conversion\Ordinal](../Conversion/Ordinal.md)

Parameters

None.

---

<a id="method-pythagoras"></a>

### `public function pythagoras()`

Return: [ReeStyleIt\NumberClass\Mathematical\Pythagoras](Pythagoras.md)

Parameters

None.

---

<a id="method-rad2deg"></a>

### `public function rad2deg()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-round"></a>

### `public function round()`

Return: [ReeStyleIt\NumberClass\Format\Round](../Format/Round.md)

Parameters

None.

---

<a id="method-setdefaultlocale"></a>

### `public static function setDefaultLocale(Locale|string|null $locale)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$locale` | [ReeStyleIt\NumberClass\Enum\Locale](../Enum/Locale.md) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [null](https://www.php.net/manual/en/language.types.null.php) | — |

---

<a id="method-sin"></a>

### `public function sin()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-tan"></a>

### `public function tan()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.

---

<a id="method-time"></a>

### `public function time()`

Return: [ReeStyleIt\NumberClass\Conversion\Time](../Conversion/Time.md)

Parameters

None.

---

<a id="method-trigonometry"></a>

### `public function trigonometry()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](Trigonometry.md)

Parameters

None.
