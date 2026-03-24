---
name: wp-module-features
title: Getting started
description: Prerequisites, install, and run.
updated: 2025-03-18
---

# Getting started

## Prerequisites

- **PHP** 7.3+ (see `composer.json` platform).
- **Composer** for dependencies.
- **WordPress** (for running the module; tests use johnpbloch/wordpress as dev dependency).

## Install

From the package root:

```bash
composer install
```

This pulls in `wp-forge/wp-options` and dev dependencies (WordPress, Codeception, PHPCS, etc.).

## Run tests

```bash
composer run test
```

Uses Codeception with the `wpunit` suite. For coverage:

```bash
composer run test-coverage
```

Then open `tests/_output/html/index.html` to view the report.

## Lint and fix

```bash
composer run lint
composer run fix
```

## i18n

```bash
composer run i18n-pot    # generate .pot
composer run i18n        # full pipeline (pot, po, mo, php, json)
```

Text domain: **wp-module-features**. Language files live in `languages/`.

## Using in a host plugin

1. Depend on `newfold-labs/wp-module-features` via Composer.
2. Register features on the `newfold/features/filter/register` filter (pass an array of feature class names that extend `NewfoldLabs\WP\Module\Features\Feature`).
3. Use `NewfoldLabs\WP\Module\Features\isEnabled( 'feature-name' )` to gate behavior. The admin app can call the REST API to list and toggle features.

See [integration.md](integration.md) for details.
