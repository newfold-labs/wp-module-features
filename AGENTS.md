# Agent guidance – wp-module-features

This file gives AI agents a quick orientation to the repo. For full detail, see the **docs/** directory.

## What this project is

- **wp-module-features** – Provides an interface for feature flags/toggles in Newfold WordPress brand plugins. Features are registered via the `newfold/features/filter/register` filter; the module stores enabled state in the options table (`newfold_features`) and exposes it to the admin app and via REST API and CLI. Maintained by Newfold Labs.

- **Stack:** PHP 7.3+. Uses `wp-forge/wp-options` for persistence. No frontend UI in this repo; host apps consume the REST API and `isEnabled()` helper.

- **Architecture:** Singleton `Features::getInstance()` initializes a `Registry` and hooks into `plugins_loaded`, `rest_api_init`, `cli_init`, and `admin_enqueue_scripts`. Features are registered by other modules via the filter; the module provides REST routes under `newfold-features/v1` and a CLI command.

## Key paths

| Purpose | Location |
|---------|----------|
| Entry / singleton | `includes/Features.php` – `getInstance()`, init, filters |
| Registry & options | `includes/Registry.php` – feature instances and `newfold_features` option |
| Feature model | `includes/Feature.php` |
| REST API | `includes/FeaturesAPI.php` – namespace `newfold-features/v1` |
| CLI | `includes/FeaturesCLI.php` |
| Helpers | `includes/functions.php` – `isEnabled()`, `enable()`, `disable()` |
| Tests | `tests/` (Codeception wpunit) |

## Essential commands

```bash
composer install
composer run lint
composer run fix
composer run test
composer run test-coverage
composer run i18n-pot   # and i18n, i18n-json, etc.
```

## Documentation

- **Full documentation** is in **docs/**. Start with **docs/index.md**.
- **CLAUDE.md** is a symlink to this file (AGENTS.md).

---

## Keeping documentation current

**When you change code, features, or workflows, update the docs so they stay accurate.**

- Keep all docs current, not only the ones listed here.
- When adding or changing REST routes, update **docs/api.md**. When adding or changing dependencies, update **dependencies.md**. When cutting a release, update **docs/changelog.md**.
