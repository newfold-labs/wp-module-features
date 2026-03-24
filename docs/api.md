---
name: wp-module-features
title: REST API
description: REST API or public API reference.
updated: 2025-03-18
---

# REST API

The module registers REST routes under the **`newfold-features/v1`** namespace. All routes require permission (typically `manage_options`). Base URL: `{site}/wp-json/newfold-features/v1`.

## Endpoints

| Method | Path | Description |
|--------|------|-------------|
| GET | `/newfold-features/v1/features` | List all registered features and their enabled state. |
| POST | `/newfold-features/v1/feature/enable` | Enable a feature. Body: `{ "feature": "feature-name" }`. |
| POST | `/newfold-features/v1/feature/disable` | Disable a feature. Body: `{ "feature": "feature-name" }`. |

## Response

- **GET /features** – Returns an array of feature objects (name, enabled, and any other fields the feature exposes).
- **POST /feature/enable** and **/feature/disable** – Return the updated feature or an error if the feature name is invalid or not found.

Permission callback is defined in `FeaturesAPI::checkPermission()` (typically requires `manage_options`).
