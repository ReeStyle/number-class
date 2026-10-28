# Locales

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Enums\Locales` is a string-backed enum of locale identifiers.
Its source file is in `src/Enum`, but its namespace is `Enums`.
Each locale has one native currency, one symbol, and a prefix/suffix position in the bundled
`src/Resources/currency-symbols.php` data. These metadata methods work without `intl`.

## Cases

| Case | Value | Case | Value |
| --- | --- | --- | --- |
| `AR_AE` | `ar_AE` | `AR_SA` | `ar_SA` |
| `CS_CZ` | `cs_CZ` | `DA_DK` | `da_DK` |
| `DE_AT` | `de_AT` | `DE_CH` | `de_CH` |
| `DE_DE` | `de_DE` | `EN_AU` | `en_AU` |
| `EN_CA` | `en_CA` | `EN_GB` | `en_GB` |
| `EN_IN` | `en_IN` | `EN_NZ` | `en_NZ` |
| `EN_SG` | `en_SG` | `EN_US` | `en_US` |
| `EN_ZA` | `en_ZA` | `ES_ES` | `es_ES` |
| `ES_MX` | `es_MX` | `FR_BE` | `fr_BE` |
| `FR_CA` | `fr_CA` | `FR_CH` | `fr_CH` |
| `FR_FR` | `fr_FR` | `HI_IN` | `hi_IN` |
| `IT_CH` | `it_CH` | `IT_IT` | `it_IT` |
| `JA_JP` | `ja_JP` | `KO_KR` | `ko_KR` |
| `NB_NO` | `nb_NO` | `NL_BE` | `nl_BE` |
| `NL_NL` | `nl_NL` | `PL_PL` | `pl_PL` |
| `PT_BR` | `pt_BR` | `PT_PT` | `pt_PT` |
| `SV_SE` | `sv_SE` | `TR_TR` | `tr_TR` |
| `ZH_CN` | `zh_CN` | `ZH_HK` | `zh_HK` |

## Methods and metadata

| Signature | Result |
| --- | --- |
| `currency(): Currencies` | Native currency enum case |
| `symbol(): string` | Native currency symbol from the bundled data |
| `symbolPosition(): string` | `'prefix'` or `'suffix'` |

```php
use ReeStyleIt\NumberClass\Enums\Locales;

$locale = Locales::DE_DE;
$identifier = $locale->value;      // 'de_DE'
$name = $locale->name;             // 'DE_DE'
$currency = $locale->currency();   // Currencies::EUR
$symbol = $locale->symbol();       // '€'
$position = $locale->symbolPosition(); // 'suffix'

$swiss = Locales::DE_CH->currency(); // Currencies::CHF
$swissSymbol = Locales::DE_CH->symbol(); // 'CHF'
$austrianPosition = Locales::DE_AT->symbolPosition(); // 'prefix' in the bundled data
```

Position metadata describes where the currency symbol belongs. It does not encode decimal
separators, grouping, spacing, negative patterns, or currency fraction digits. Use
[Currency](../Format/Currency.md) for full amount formatting. The bundled metadata is generated
with `composer generate:symbols` and reflects the ICU data used at generation time.

## Convert and enumerate

```php
use ReeStyleIt\NumberClass\Enums\Locales;

$locale = Locales::from('nl_NL');       // Locales::NL_NL
$unknown = Locales::tryFrom('xx_XX');  // null
$all = Locales::cases();              // array of all 36 cases
```

The built-in `from(string $value): static` throws `ValueError` for an unknown value;
`tryFrom(string $value): ?static` returns null. Conversion is case-sensitive and matches
the values exactly; enum conversion does not normalize `nl-nl` to `nl_NL`.
Enums have no public constructor.

## Helper integration

There is no locale factory helper. Use a case with `currencyFormatter()` and explicitly
select its native currency if that is desired:

```php
use ReeStyleIt\NumberClass\Enums\Locales;
use function ReeStyleIt\NumberClass\currencyFormatter;

$locale = Locales::EN_US;
(string) currencyFormatter(1234.5)
    ->currency($locale->currency())
    ->locale($locale); // $1,234.50; requires intl
```

Setting only `locale()` on a formatter leaves its currency unchanged (EUR by default).
For symbols of another currency in a locale, use [Currencies::symbol()](Currencies.md#symbol-lookup).
