# Ordinal

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Conversion\Ordinal` formats integers as numeric ordinals using
ICU's locale rules. It requires the PHP `intl` extension. English (`en_US`) is the default.

```php
use ReeStyleIt\NumberClass\Enums\Locales;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\ordinal;

ordinal(21)->format();                            // '21st'
ordinal(1)->locale(Locales::NL_NL)->format();      // '1ste'
ordinal(1)->locale('fr')->format();                // '1er'
ordinal(2)->locale('es_ES')->format();             // '2.º'
ordinal(3)->locale(Locales::IT_IT)->format();       // '3º'
echo number(20)->add(1)->ordinal;                  // 21st
echo number(2)->ordinal()->locale('fr_FR');        // 2e
```

| Member | Behavior |
| --- | --- |
| `__construct(mixed $value)` | Stores the value; validation occurs when formatting |
| `locale(Locales|string $locale): self` | Configures the same formatter and returns it |
| `public string $locale` | Locale identifier, initially `en_US` |
| `format(): string` | Returns the localized numeric ordinal |
| `__toString(): string` | Calls `format()`, allowing `echo` or a string cast |
| `get(): mixed` | Returns the original, unformatted value |

Both language codes such as `nl` and regional identifiers such as `nl_BE` are accepted.
Dutch uses the full suffixes `ste` and `de`: `1ste`, `2de`, `3de`, `8ste`, `11de`,
`20ste`, `21ste`, and `22ste`. The last two digits determine the suffix: `0`, `1`,
`8`, and `20` through `99` use `ste`; the remaining endings use `de`.
This also produces `101ste`, `102de`, and `111de`.
Additional languages supported by the installed ICU include German (`2.`), Portuguese
(`2º`), and Swedish (`2:a`). ICU determines fallback behavior for unknown locales;
output, grouping separators, and regional notation follow the installed ICU version.
For example, Spanish uses `21.º` for `es_ES` and `21º.` for `es_MX`.
The formatter uses ICU's default grammatical form, typically masculine where relevant.

Values must be PHP integers between `-9007199254740991` and `9007199254740991` inclusive.
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
create a fresh formatter containing the original value, with the default English locale.
