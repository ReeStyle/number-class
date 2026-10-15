# Changelog

## Unreleased

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
