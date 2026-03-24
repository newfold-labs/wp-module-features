---
name: wp-module-features
title: Development
description: Lint, test, and workflow.
updated: 2025-03-18
---

# Development

## Linting

- **PHP:** `composer run lint` (PHPCS), `composer run fix` (PHPCBF). Uses `phpcs.xml` and `newfold-labs/wp-php-standards`.

## Testing

- **WPUnit (Codeception):** `composer run test` runs the `wpunit` suite.
- **Coverage:** `composer run test-coverage`; then open `tests/_output/html/index.html`.

## i18n

- Text domain: **wp-module-features**. Use `composer run i18n-pot` and `composer run i18n` (or the individual i18n-* scripts). Language files live in `languages/`.

## Day-to-day workflow

1. Make changes in `includes/` or add new feature classes.
2. Run `composer run lint` and `composer run test` before committing.
3. When adding or changing REST routes, update [api.md](api.md). When adding or changing the register filter or feature API, update [integration.md](integration.md) and [overview.md](overview.md).
4. When cutting a release, update **docs/changelog.md** with the version and changes.
