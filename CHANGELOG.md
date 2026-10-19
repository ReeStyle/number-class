# Changelog

## Unreleased

- Added readonly `Statistical\BoxPlot`, `Median`, and `Mode` sample calculations,
  alphabetized factory helpers, and `StatisticalException` validation.

- Added readonly `Financial\Price` calculations using BCMath, decimal string inputs,
  percentage discounts and taxes, and explicit currency rounding; require `ext-bcmath`.

- Added `Financial\CompoundInterest` and `Financial\Amortization` with financial validation,
  alphabetized factory helpers, shared accessors, and repayment schedules.

- Rebuilt `Conversion\Time` around a chainable `from()` starting point accepting timestamps,
  date objects, and date strings, stored as a Carbon date with Unix timestamp access.
- Added named starting-point validation errors and the optional-value `time()` helper.
- Exposed `Time::$results` for public reading while restricting writes to `Time` with `private(set)`.
- Added `Arithmetic::intdiv()` and `Number::intdiv()`.
- Prepared a searchable MkDocs site and GitHub Pages workflow, with strict builds and generated-link validation.
- Prefer helper properties in internal calls and usage examples; retain accessor methods for compatibility and arguments.
- Retained arithmetic methods on `Number` while returning their `Arithmetic` results directly.
- Moved all helper properties and accessor methods from `Number` to `Base` for use across value classes.
- Renamed `Currency::$currency` to `currencyCode`; `currency()` without arguments and the inherited
  `currency` property now return a fresh formatter, while `currency($code)` keeps configuring the current one.
- Moved `compare()` and the read-only `compare` property to `Base`, making comparisons available on every value class.
- Shared `number()` method and read-only `number` property on all value classes for continuing calculations as a `Number`.
- Haversine now accepts longitude, latitude, and elevation for both points, combines surface distance
  with elevation difference, and returns metres by default instead of kilometres.
- Circle and ellipse surfaces and circumferences, plus circle radius/diameter conversions.
- Geometry with haversine distances, coordinate validation, and a configurable sphere radius.
- Numeric values with immutable arithmetic results and a `get()` accessor.
- Dedicated arithmetic and comparison classes.
- Configurable rounding and nearest-multiple calculations.
- Currency formatting and a currency enum with symbol lookup.
- Trigonometry and right triangle length calculations.
- Namespaced helper functions and PSR-4 autoloading for Composer installation.
- PHP 8.4+ and `intl` requirements, Pest tests, and GitHub Actions checks.
