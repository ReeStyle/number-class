# ReeStyleIt\NumberClass\Conversion\Time

## Public properties

| property | type | access |
| :------ | :------ | :------ |
| `$amortization` | [ReeStyleIt\NumberClass\Financial\Amortization](../Financial/Amortization.md) | read-only |
| `$arithmetic` | [ReeStyleIt\NumberClass\Mathematical\Arithmetic](../Mathematical/Arithmetic.md) | read-only |
| `$boxPlot` | [ReeStyleIt\NumberClass\Statistical\BoxPlot](../Statistical/BoxPlot.md) | read-only |
| `$compare` | [ReeStyleIt\NumberClass\Number\Compare](../Number/Compare.md) | read-only |
| `$compoundInterest` | [ReeStyleIt\NumberClass\Financial\CompoundInterest](../Financial/CompoundInterest.md) | read-only |
| `$currency` | [ReeStyleIt\NumberClass\Format\Currency](../Format/Currency.md) | read-only |
| `$dimension` | [ReeStyleIt\NumberClass\Mathematical\Dimension](../Mathematical/Dimension.md) | read-only |
| `$from` | [ReeStyleIt\NumberClass\Conversion\Time\Diff](Time/Diff.md) | read-only |
| `$geometry` | [ReeStyleIt\NumberClass\Mathematical\Geometry](../Mathematical/Geometry.md) | read-only |
| `$median` | [ReeStyleIt\NumberClass\Statistical\Median](../Statistical/Median.md) | read-only |
| `$mode` | [ReeStyleIt\NumberClass\Statistical\Mode](../Statistical/Mode.md) | read-only |
| `$number` | [ReeStyleIt\NumberClass\Number](../Number.md) | read-only |
| `$ordinal` | [ReeStyleIt\NumberClass\Conversion\Ordinal](Ordinal.md) | read-only |
| `$pythagoras` | [ReeStyleIt\NumberClass\Mathematical\Pythagoras](../Mathematical/Pythagoras.md) | read-only |
| `$results` | [array](https://www.php.net/manual/en/language.types.array.php) | read-only |
| `$round` | [ReeStyleIt\NumberClass\Format\Round](../Format/Round.md) | read-only |
| `$time` | [ReeStyleIt\NumberClass\Conversion\Time](Time.md) | read-only |
| `$trigonometry` | [ReeStyleIt\NumberClass\Mathematical\Trigonometry](../Mathematical/Trigonometry.md) | read-only |
| `$value` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | read-only |

## Methods

- [__construct()](#method-__construct)
- [amortization()](#method-amortization)
- [arithmetic()](#method-arithmetic)
- [asTime()](#method-astime)
- [boxPlot()](#method-boxplot)
- [compare()](#method-compare)
- [compoundInterest()](#method-compoundinterest)
- [currency()](#method-currency)
- [dimension()](#method-dimension)
- [from()](#method-from)
- [geometry()](#method-geometry)
- [get()](#method-get)
- [getDefaultLocale()](#method-getdefaultlocale)
- [median()](#method-median)
- [mode()](#method-mode)
- [number()](#method-number)
- [ordinal()](#method-ordinal)
- [pythagoras()](#method-pythagoras)
- [round()](#method-round)
- [setDefaultLocale()](#method-setdefaultlocale)
- [time()](#method-time)
- [trigonometry()](#method-trigonometry)

---

<a id="method-__construct"></a>

### `public function __construct(mixed $value = 0)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$value` | [mixed](https://www.php.net/manual/en/language.types.mixed.php) | `0` |

---

<a id="method-amortization"></a>

### `public function amortization()`

Return: [ReeStyleIt\NumberClass\Financial\Amortization](../Financial/Amortization.md)

Parameters

None.

---

<a id="method-arithmetic"></a>

### `public function arithmetic()`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](../Mathematical/Arithmetic.md)

Parameters

None.

---

<a id="method-astime"></a>

### `public function asTime(bool $includeDays = false)`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$includeDays` | [bool](https://www.php.net/manual/en/language.types.boolean.php) | `false` |

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

<a id="method-currency"></a>

### `public function currency()`

Return: [ReeStyleIt\NumberClass\Format\Currency](../Format/Currency.md)

Parameters

None.

---

<a id="method-dimension"></a>

### `public function dimension()`

Return: [ReeStyleIt\NumberClass\Mathematical\Dimension](../Mathematical/Dimension.md)

Parameters

None.

---

<a id="method-from"></a>

### `public function from(DateTimeInterface|Carbon|string|int $from, DateTimeInterface|Carbon|string|int|null $to = null)`

Return: [ReeStyleIt\NumberClass\Conversion\Time\Diff](Time/Diff.md) \| [DateInterval](https://www.php.net/manual/en/class.dateinterval.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$from` | [DateTimeInterface](https://www.php.net/manual/en/class.datetimeinterface.php) \| [Carbon\Carbon](https://carbon.nesbot.com/develop/reference.html) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) | — |
| `$to` | [DateTimeInterface](https://www.php.net/manual/en/class.datetimeinterface.php) \| [Carbon\Carbon](https://carbon.nesbot.com/develop/reference.html) \| [string](https://www.php.net/manual/en/language.types.string.php) \| [int](https://www.php.net/manual/en/language.types.integer.php) \| [null](https://www.php.net/manual/en/language.types.null.php) | `null` |

---

<a id="method-geometry"></a>

### `public function geometry(int|float|null $latitude = null, int|float $elevation = 0)`

Return: [ReeStyleIt\NumberClass\Mathematical\Geometry](../Mathematical/Geometry.md)

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

Return: [ReeStyleIt\NumberClass\Conversion\Ordinal](Ordinal.md)

Parameters

None.

---

<a id="method-pythagoras"></a>

### `public function pythagoras()`

Return: [ReeStyleIt\NumberClass\Mathematical\Pythagoras](../Mathematical/Pythagoras.md)

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

<a id="method-time"></a>

### `public function time()`

Return: [ReeStyleIt\NumberClass\Conversion\Time](Time.md)

Parameters

None.

---

<a id="method-trigonometry"></a>

### `public function trigonometry()`

Return: [ReeStyleIt\NumberClass\Mathematical\Trigonometry](../Mathematical/Trigonometry.md)

Parameters

None.
