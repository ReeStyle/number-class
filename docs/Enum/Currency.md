# Currency

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Enum\Currency` is a string-backed enum of common currency codes.
Its source file is `src/Enum/Currency.php`; use the plural `Enum` namespace in application code.

## Cases and conversion

Each case's name and value are the same three-letter code:

| Cases |
| --- |
| `AED`, `AUD`, `BRL`, `CAD`, `CHF`, `CNY` |
| `CZK`, `DKK`, `EUR`, `GBP`, `HKD`, `INR` |
| `JPY`, `KRW`, `MXN`, `NOK`, `NZD`, `PLN` |
| `SAR`, `SEK`, `SGD`, `TRY`, `USD`, `ZAR` |

```php
use ReeStyleIt\NumberClass\Enum\Currency;

$currency = Currency::EUR;
$code = $currency->value;               // 'EUR'
$name = $currency->name;                // 'EUR'
$usd = Currency::from('USD');         // Currency::USD
$unknown = Currency::tryFrom('XXX');  // null
$all = Currency::cases();             // array of all 24 cases
```

The built-in `from(string $value): static` throws `ValueError` for an unknown value;
`tryFrom(string $value): ?static` returns null. Both are case-sensitive: normalize external
codes with `strtoupper()` first if appropriate. Enum have no public constructor.

## Symbol lookup

`symbol(Locale|string|null $locale = null): string` returns a locale-sensitive symbol.
An omitted or null locale uses the [global default](../Base.md#global-default-locale),
or `en_US` when no override is configured. An explicit locale takes precedence.

```php
use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;

$dollar = Currency::USD->symbol();                 // '$'
$euro = Currency::EUR->symbol(Locale::NL_NL);     // '€'
$franc = Currency::CHF->symbol('de_CH');           // 'CHF'
```

With `intl`, lookup uses ICU and can disambiguate a foreign currency, for example `CA$`
for Canadian dollars in `en_US`. Exact symbols can vary by ICU version. Failed ICU lookup
throws [CurrencySymbolLookupException](../Exceptions/CurrencySymbolLookupException.md).

Without `intl`, lookup uses [Locale](Locale.md) metadata:

- The locale's native currency returns that locale's single stored symbol.
- A different currency returns its three-letter currency code.
- Locale matching ignores case, accepts hyphens in place of underscores, and strips encoding
  or modifier suffixes such as `.UTF-8` or `@...`.
- An unrecognized locale falls back to `en_US`.

Thus `Currency::CAD->symbol('en_US')` returns `'CAD'` without `intl`, while
`Currency::CAD->symbol('en_CA')` returns `'$'`.

## Helper integration

There is no enum factory helper. Pass a case directly to `currencyFormatter()`:

```php
use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;
use function ReeStyleIt\NumberClass\currencyFormatter;

(string) currencyFormatter(42.5)
    ->currency(Currency::USD)
    ->locale(Locale::EN_US); // $42.50; requires intl
```

See [Currency](../Format/Currency.md) for amount formatting and
[Locale](Locale.md) for symbol placement metadata.
