# Ordinal

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Conversion\Ordinal` formats integers as numeric ordinals using
ICU's locale rules. It requires the PHP `intl` extension. English (`en_US`) is the default
unless a [global locale](../Base.md#global-default-locale) is configured before construction.

```php
use ReeStyleIt\NumberClass\Enum\Locale;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\ordinal;

ordinal(21)->format();                            // '21st'
ordinal(1)->locale(Locale::NL_NL)->format();      // '1ste'
ordinal(1)->locale('fr')->format();                // '1er'
ordinal(2)->locale('es_ES')->format();             // '2.º'
ordinal(3)->locale(Locale::IT_IT)->format();       // '3º'
(string) number(20)->add(1)->ordinal;                  // 21st
(string) number(2)->ordinal()->locale('fr_FR');        // 2e
ordinal(21)->wordify();                           // 'twenty-first'
ordinal(21)->locale('nl')->wordify();             // 'eenentwintigste'
number(21)->ordinal->wordify;                     // 'twenty-first'
```

| Member | Behavior |
| --- | --- |
| `__construct(mixed $value)` | Stores the value; validation occurs when formatting |
| `locale(Locale|string $locale): self` | Configures the same formatter and returns it |
| `public string $locale` | Global default captured at construction, or `en_US` |
| `format(): string` | Returns the localized numeric ordinal |
| `wordify(): string` | Returns the ordinal spelled out in the configured locale |
| `public string $wordify` | Read-only property equivalent of `wordify()` |
| `__toString(): string` | Calls `format()`, allowing `echo` or a string cast |
| `get(): mixed` | Returns the original, unformatted value |

Both language codes such as `nl` and regional identifiers such as `nl_BE` are accepted.
Dutch uses the full suffixes `ste` and `de`: `1ste`, `2de`, `3de`, `8ste`, `11de`,
`20ste`, `21ste`, and `22ste`. The last two digits determine the suffix: `0`, `1`,
`8`, and `20` through `99` use `ste`; the remaining endings use `de`.
This also produces `101ste`, `102de`, and `111de`. Zero is an exception: `0de`.
Additional languages supported by the installed ICU include German (`2.`), Portuguese
(`2º`), and Swedish (`2:a`). ICU determines fallback behavior for unknown locales;
output, grouping separators, and regional notation follow the installed ICU version.
For example, Spanish uses `21.º` for `es_ES` and `21º.` for `es_MX`.
The formatter uses ICU's default grammatical form, typically masculine where relevant.

Values must be PHP integers between `-9007199254740991` and `9007199254740991` inclusive.
These bounds are available as `Ordinal::MIN_SAFE_INTEGER` and `Ordinal::MAX_SAFE_INTEGER`,
inherited from [Base](../Base.md).
Zero and negative integers are accepted; ICU can use a Unicode minus sign for negatives.
The range prevents precision loss in ICU's ordinal rules. Floats, including `1.0`, are
rejected rather than rounded. The raw value remains unchanged when formatting or setting a locale.

Non-numeric values throw [MustBeNumberException](../Exceptions/MustBeNumberException.md).
Floats throw [MustBeIntegerException](../Exceptions/MustBeIntegerException.md).
Empty or whitespace-only locales throw [EmptyLocaleException](../Exceptions/EmptyLocaleException.md).
Missing `intl`, out-of-range integers, and ICU formatting failures throw
[OrdinalFormattingException](../Exceptions/OrdinalFormattingException.md).

Use the `ordinal($value)` helper, construct `new Ordinal($value)`, or access the shared
`ordinal` property / `ordinal()` method on any numeric value class. Shared accessors
create a fresh formatter containing the original value, with the global default locale, or English when no override is set.

## Ordinal words

`Ordinal::wordify()` and the read-only `wordify` property spell ordinal words,
including through `number(21)->ordinal->wordify`. Each property access converts the
value using the current locale, just like calling the method.
It uses the configured locale and leaves the raw value, numeric formatting, and string cast unchanged.
`Ordinal` delegates word conversion to the separate internal class
`ReeStyleIt\NumberClass\Conversion\Ordinal\Wordify`, after validating the value and locale.
There is no separate word converter helper or shared `wordify()` accessor on `Base`.
For cardinal words such as `twenty-one`, use [Number::wordify()](../Number.md#numbers-as-words).

| Locale | Value | Words |
| --- | --- | --- |
| `en` | 1 | `first` |
| `nl` | 1 | `eerste` |
| `fr` | 1 | `premier` |
| `es` | 1 | `primero` |
| `it` | 1 | `primo` |

Word conversion uses ICU's generic ordinal rules, or masculine singular rules where the
language has gendered forms. It removes optional soft hyphens from the output. Dutch zero
is rendered as `nulde`, matching the numeric `0de` exception.
The same integer validation, range, locale checks, and `intl` requirement apply as for `format()`.
If ICU does not provide a generic or masculine ordinal word ruleset for the locale,
`wordify()` throws `OrdinalFormattingException` rather than returning cardinal words.
