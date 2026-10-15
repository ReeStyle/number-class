# Currency

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Format\Currency` extends [Base](../Base.md).
Its constructor is `__construct(mixed $value)`: pass the amount, then configure currency and
locale through chainable methods. Full amount formatting requires PHP's `intl` extension.

## Format an amount

```php
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Format\Currency;

$formatter = new Currency(1234.5);
$formatter->currency(Currencies::USD)->locale(Locales::EN_US);

echo $formatter;                  // $1,234.50
$text = (string) $formatter;      // '$1,234.50'
$amount = $formatter->get();      // 1234.5
$original = $formatter->value;    // 1234.5; public readonly
```

`__toString(): string` performs formatting when cast to a string, echoed, or interpolated.
There is no separate `format()` method. Output follows ICU locale conventions, including
currency-specific fraction digits and nonbreaking spaces; exact text can vary by ICU version.
Display rounding leaves the stored amount unchanged. Selecting another currency changes
the label and display rules; it does not convert the amount using an exchange rate.

## Configuration

| Member | Default / behavior |
| --- | --- |
| `public string $currency` | `'EUR'` |
| `public string $locale` | `'nl_NL'` |
| `currency(Currencies|string $currency): self` | Set the currency code and return the same formatter |
| `locale(Locales|string $locale): self` | Set the locale identifier and return the same formatter |

Currency strings are uppercased and must contain exactly three ASCII letters. This is a
shape check, not a check against every valid ISO code. Locale strings must not be blank;
the method does not validate the complete locale syntax or supported ICU locales.
Use the setters to apply these checks: assigning the public configuration properties directly
bypasses them. The amount remains readonly.

```php
use ReeStyleIt\NumberClass\Format\Currency;

$formatter = new Currency(25);
$same = $formatter->currency('usd')->locale('en_US');
$isSameObject = $same === $formatter; // true
$code = $formatter->currency;         // 'USD'
$locale = $formatter->locale;         // 'en_US'
echo $formatter;                     // $25.00
```

Setting a locale does not select its native currency. To choose both from locale metadata,
set them explicitly:

```php
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Format\Currency;

$locale = Locales::DE_CH;
$formatter = new Currency(1250);
$formatter->currency($locale->currency())->locale($locale);
echo $formatter; // Swiss franc amount using de_CH conventions
```

## Helper usage and Number access

```php
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use function ReeStyleIt\NumberClass\currencyFormatter;
use function ReeStyleIt\NumberClass\number;

echo currencyFormatter(19.95)->currency(Currencies::USD)->locale(Locales::EN_US); // $19.95

$fromProperty = number(19.95)->currency;
$fromMethod = number(19.95)->currency();
// Each accessor creates a fresh Currency; keep it if reusing its configuration.
$text = (string) $fromProperty->currency('USD')->locale('en_US'); // '$19.95'
```

## Errors and extension availability

The constructor stores any value. Conversion to a string checks it and may throw:

- [MustBeNumberException](../Exceptions/MustBeNumberException.md) for a non-numeric stored value.
- [MustBeFiniteCurrencyValue](../Exceptions/MustBeFiniteCurrencyValue.md) for `INF`, `-INF`, or `NAN`.
- [CurrencyFormattingException](../Exceptions/CurrencyFormattingException.md) when `intl` is missing
  or ICU reports a failed amount format.

The setters throw [InvalidCurrencyCodeException](../Exceptions/InvalidCurrencyCodeException.md)
and [EmptyLocaleException](../Exceptions/EmptyLocaleException.md) for invalid arguments.

[Currencies::symbol()](../Enums/Currencies.md) can fall back to bundled locale symbols without
`intl`. That fallback does not provide currency amount formatting.
