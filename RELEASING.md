# Publishing number-class

The package name is `reestyle-it/number-class`. Classes and helper functions are namespaced under `ReeStyleIt\NumberClass`.

## Before the first release

1. Confirm the intended license metadata. `composer.json` currently declares `proprietary`, while the supplied `license.txt` is headed "Elastic License 2.0". The preparation changes preserve both. Align the declaration and intended license terms before publishing.
2. Create a public Git repository and configure its remote. The local repository has no commits or remote yet.
3. Add the repository URL to Composer's `homepage` and `support.source` fields, and add its issue tracker to `support.issues` if available.
4. Run the checks below and commit the source, documentation, configuration, tests, and lock file. IDE files and installed dependencies are ignored.

## Validation

```bash
composer install
composer validate --strict
composer dump-autoload --optimize --strict-psr
composer test
composer archive --format=zip --dir=/tmp/int-class-release
```

Inspect the archive. It should contain the source, `composer.json`, README, and license. Tests, IDE files, installed dependencies, and the development lock file are excluded from distribution archives.

## Publish

1. Push the committed project to the public repository and check that CI passes.
2. Create and push a semantic version tag, for example `v1.0.0` once the API is ready for a stable release. Do not add a `version` field to `composer.json`; Composer derives versions from Git tags.
3. Submit the public repository URL through [Packagist's submission page](https://packagist.org/packages/submit).
4. Configure the repository integration or webhook for automatic Packagist updates.
5. Verify installation from a separate project with `composer require reestyle-it/number-class`.

For subsequent releases, update the changelog, run the checks, commit, and push a new version tag. Follow [Packagist's publication guide](https://packagist.org/about) for repository integrations.
