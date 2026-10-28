# ReeStyleIt\NumberClass\Mathematical\Arithmetic

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
- [abs()](#method-abs)
- [add()](#method-add)
- [amortization()](#method-amortization)
- [arithmetic()](#method-arithmetic)
- [boxPlot()](#method-boxplot)
- [ceil()](#method-ceil)
- [compare()](#method-compare)
- [compoundInterest()](#method-compoundinterest)
- [currency()](#method-currency)
- [decrease()](#method-decrease)
- [dimension()](#method-dimension)
- [divide()](#method-divide)
- [floor()](#method-floor)
- [geometry()](#method-geometry)
- [get()](#method-get)
- [getDefaultLocale()](#method-getdefaultlocale)
- [increase()](#method-increase)
- [intdiv()](#method-intdiv)
- [median()](#method-median)
- [mod()](#method-mod)
- [mode()](#method-mode)
- [multiply()](#method-multiply)
- [negate()](#method-negate)
- [number()](#method-number)
- [ordinal()](#method-ordinal)
- [pow()](#method-pow)
- [pythagoras()](#method-pythagoras)
- [round()](#method-round)
- [setDefaultLocale()](#method-setdefaultlocale)
- [sqrt()](#method-sqrt)
- [subtract()](#method-subtract)
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

<a id="method-abs"></a>

### `public function abs()`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

None.

---

<a id="method-add"></a>

### `public function add(int|float $amount = 1)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$amount` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | `1` |

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

<a id="method-boxplot"></a>

### `public function boxPlot()`

Return: [ReeStyleIt\NumberClass\Statistical\BoxPlot](../Statistical/BoxPlot.md)

Parameters

None.

---

<a id="method-ceil"></a>

### `public function ceil()`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

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

<a id="method-decrease"></a>

### `public function decrease(int|float $amount = 1)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$amount` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | `1` |

---

<a id="method-dimension"></a>

### `public function dimension()`

Return: [ReeStyleIt\NumberClass\Mathematical\Dimension](Dimension.md)

Parameters

None.

---

<a id="method-divide"></a>

### `public function divide(int|float $divisor)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$divisor` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | — |

---

<a id="method-floor"></a>

### `public function floor()`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

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

<a id="method-increase"></a>

### `public function increase(int|float $amount = 1)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$amount` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | `1` |

---

<a id="method-intdiv"></a>

### `public function intdiv(int $divisor)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$divisor` | [int](https://www.php.net/manual/en/language.types.integer.php) | — |

---

<a id="method-median"></a>

### `public function median()`

Return: [ReeStyleIt\NumberClass\Statistical\Median](../Statistical/Median.md)

Parameters

None.

---

<a id="method-mod"></a>

### `public function mod(int|float $divisor)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$divisor` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | — |

---

<a id="method-mode"></a>

### `public function mode()`

Return: [ReeStyleIt\NumberClass\Statistical\Mode](../Statistical/Mode.md)

Parameters

None.

---

<a id="method-multiply"></a>

### `public function multiply(int|float $factor)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$factor` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | — |

---

<a id="method-negate"></a>

### `public function negate()`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

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

<a id="method-pow"></a>

### `public function pow(int|float $exponent)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$exponent` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | — |

---

<a id="method-pythagoras"></a>

### `public function pythagoras()`

Return: [ReeStyleIt\NumberClass\Mathematical\Pythagoras](Pythagoras.md)

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

<a id="method-sqrt"></a>

### `public function sqrt(int $exponent = 2)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$exponent` | [int](https://www.php.net/manual/en/language.types.integer.php) | `2` |

---

<a id="method-subtract"></a>

### `public function subtract(int|float $amount = 1)`

Return: [ReeStyleIt\NumberClass\Mathematical\Arithmetic](Arithmetic.md)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$amount` | [int](https://www.php.net/manual/en/language.types.integer.php) \| [float](https://www.php.net/manual/en/language.types.float.php) | `1` |

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
