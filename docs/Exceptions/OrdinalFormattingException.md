# OrdinalFormattingException

[Usage index](../README.md) · [Ordinal](../Conversion/Ordinal.md)

`ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException` extends
`BaseInvalidArgumentException` and reports ordinal formatting errors: missing optional
`intl` support, integers outside ICU's exact range, or an ICU formatting failure.

The default message is `Ordinal formatting failed.`. Optional constructor details are
prefixed with `Ordinal formatting failed: `. `intlRequired()` creates an exception
explaining the required extension.
