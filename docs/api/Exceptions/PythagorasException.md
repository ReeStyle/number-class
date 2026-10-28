# ReeStyleIt\NumberClass\Exceptions\PythagorasException

## Public properties

None.

## Methods

- [__construct()](#method-__construct)
- [__toString()](#method-__tostring)
- [__wakeup()](#method-__wakeup)
- [getCode()](#method-getcode)
- [getFile()](#method-getfile)
- [getLine()](#method-getline)
- [getMessage()](#method-getmessage)
- [getPrevious()](#method-getprevious)
- [getTrace()](#method-gettrace)
- [getTraceAsString()](#method-gettraceasstring)
- [invalidLength()](#method-invalidlength)
- [legTooLong()](#method-legtoolong)

---

<a id="method-__construct"></a>

### `public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

| param | type | default |
| :------ | :------ | :------ |
| `$message` | ?[string](https://www.php.net/manual/en/language.types.string.php) | `null` |
| `$code` | [int](https://www.php.net/manual/en/language.types.integer.php) | `0` |
| `$previous` | ?[Throwable](https://www.php.net/manual/en/class.throwable.php) | `null` |

---

<a id="method-__tostring"></a>

### `public function __toString()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-__wakeup"></a>

### `public function __wakeup()`

Return: [void](https://www.php.net/manual/en/language.types.void.php)

Parameters

None.

---

<a id="method-getcode"></a>

### `public function getCode()`

Return: [mixed](https://www.php.net/manual/en/language.types.mixed.php)

Parameters

None.

---

<a id="method-getfile"></a>

### `public function getFile()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-getline"></a>

### `public function getLine()`

Return: [int](https://www.php.net/manual/en/language.types.integer.php)

Parameters

None.

---

<a id="method-getmessage"></a>

### `public function getMessage()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-getprevious"></a>

### `public function getPrevious()`

Return: ?[Throwable](https://www.php.net/manual/en/class.throwable.php)

Parameters

None.

---

<a id="method-gettrace"></a>

### `public function getTrace()`

Return: [array](https://www.php.net/manual/en/language.types.array.php)

Parameters

None.

---

<a id="method-gettraceasstring"></a>

### `public function getTraceAsString()`

Return: [string](https://www.php.net/manual/en/language.types.string.php)

Parameters

None.

---

<a id="method-invalidlength"></a>

### `public static function invalidLength()`

Return: [ReeStyleIt\NumberClass\Exceptions\PythagorasException](PythagorasException.md)

Parameters

None.

---

<a id="method-legtoolong"></a>

### `public static function legTooLong()`

Return: [ReeStyleIt\NumberClass\Exceptions\PythagorasException](PythagorasException.md)

Parameters

None.
