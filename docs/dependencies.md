# Dependencies

## Runtime

| Package | Purpose |
|---------|---------|
| **wp-forge/wp-options** | Persistent key-value storage for feature enabled state. The module uses an `Options` instance with the key `newfold_features`; the registry reads/writes via the options object. |

## Runtime expectations

- The module uses `NewfoldLabs\WP\ModuleLoader\container()` to get the plugin instance (e.g. `container()->plugin()->url`). The host must have set the container via the loader before features are initialized (e.g. on `plugins_loaded`).

## Dev

- **johnpbloch/wordpress** – WordPress core for WPUnit tests.
- **lucatume/wp-browser** – Codeception and WPUnit.
- **newfold-labs/wp-php-standards** – PHPCS rules.
- **phpunit/phpcov** – Coverage.
- **wp-cli/i18n-command** – i18n pot/po/mo/json generation.
