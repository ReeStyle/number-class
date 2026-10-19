# FinancialException

[Usage index](../README.md)

`ReeStyleIt\NumberClass\Exceptions\FinancialException` extends
[BaseInvalidArgumentException](BaseInvalidArgumentException.md). Compound interest and
amortization calculations raise it for negative or nonfinite principal or annual rates,
invalid terms or frequencies, and nonfinite results. The default message is
`Invalid financial calculation.` Individual validation errors include a specific message.

The inherited constructor accepts a custom message, code, and previous exception.
A nonnumeric principal raises `MustBeNumberException` instead. Both can be caught through
`BaseInvalidArgumentException`.

`Financial\Price` also raises this exception for invalid decimal inputs, discount or tax
percentages, and negative division or display scales. Division by zero raises native
`DivisionByZeroError`.
