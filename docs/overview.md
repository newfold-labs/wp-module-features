---
name: wp-module-features
title: Overview
description: What the module does and who maintains it.
updated: 2025-03-18
---

# Overview

## What the module does

**wp-module-features** provides feature flags for Newfold WordPress brand plugins. Other modules and the host register features via a filter; this module stores each feature’s enabled/disabled state in the WordPress options table and exposes it via:

- **PHP helpers:** `isEnabled( $name )`, `enable( $name )`, `disable( $name )`
- **REST API:** GET list of features, POST to enable/disable (see [api.md](api.md))
- **CLI:** WP-CLI commands for listing and toggling features
- **Filter:** `newfold/features/filter/isEnabled` to override or default unknown features

The admin app and other modules use these to gate UI and behavior by feature.

## Who maintains it

- **Newfold Labs** (Newfold Digital). Distributed via Newfold Satis and used by all Newfold WordPress brand plugins.

## High-level features

- Registry of feature instances (each extends `Feature`, has name and enabled state).
- Persistence via `WP_Forge\Options\Options` (option name `newfold_features`).
- REST namespace `newfold-features/v1` and WP-CLI integration.
- i18n (text domain `wp-module-features`, languages in `languages/`).
