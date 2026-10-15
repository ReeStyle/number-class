# Publishing number-class

---

The package name is `reestyle-it/number-class`. Classes and helper functions are namespaced under `ReeStyleIt\NumberClass`.

---

## Validation

```bash
composer install
composer validate --strict
composer dump-autoload --optimize --strict-psr
composer test
composer archive --format=zip --dir=/tmp/int-class-release
```

Inspect the archive. It should contain the source, `composer.json`, README, and license. Tests, IDE files, installed dependencies, and the development lock file are excluded from distribution archives.
