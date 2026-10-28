# NumberFormattingException

[Usage index](../README.md) · [Number](../Number.md#numbers-as-words)

`ReeStyleIt\NumberClass\Exceptions\NumberFormattingException` extends
`BaseInvalidArgumentException` and reports failures when spelling cardinal numbers:
nonfinite or out-of-range inputs, missing optional `intl` support, or an ICU formatting failure.

The default message is `Number formatting failed.`. Constructor details are prefixed
with `Number formatting failed: `. Named factories are `intlRequired()`, `mustBeFinite()`,
and `outOfRange(int $min, int $max)`.
