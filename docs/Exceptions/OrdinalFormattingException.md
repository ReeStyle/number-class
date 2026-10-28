# OrdinalFormattingException

[Usage index](../README.md) · [Ordinal](../Conversion/Ordinal.md)

`ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException` extends
`BaseInvalidArgumentException` and reports ordinal formatting errors: missing optional
`intl` support, integers outside ICU's exact range, an ICU formatting failure, or unavailable ordinal word rules for the configured locale.

The default message is `Ordinal formatting failed.`. Optional constructor details are
prefixed with `Ordinal formatting failed: `. `intlRequired()` creates an exception
explaining the required extension.
`outOfRange(int $min, int $max)` creates an exception describing the allowed inclusive range.
