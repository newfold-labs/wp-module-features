<div style="text-align: center;">
 <a href="https://newfold.com/" target="_blank">
  <img src="https://newfold.com/content/experience-fragments/newfold/site-header/master/_jcr_content/root/header/logo.coreimg.svg/1621395071423/newfold-digital.svg" alt="Newfold Logo" title="Newfold Digital" height="42" />
 </a>
</div>

# WordPress Features Module

This module manages feature registration and standardizes verifying whether a feature is enabled, offering methods to enable or disable specific features.

## Module Responsibilities
- Registers a feature given a feature class is present.
- Enables a feature using the appropriate APIs.
- Disables a feature using the appropriate APIs.
- Checks if a feature is enabled using the appropriate APIs.

## Critical Paths
- A new feature can be registered.
- A feature can be enabled.
- A feature can be disabled.
- A feature status can be checked.

## API

### PHP Functions
- enable($name)
- disable($name)
- isEnabled($name)

### REST API Endpoints
- POST newfold/features/v1/enable
  - Request payload: {feature: featureName}
  - Response payload: {feature: featureName, isEnabled: true}
- POST newfold/features/v1/disable
  - Request payload: {feature: featureName}
  - Response payload: {feature: featureName, isEnabled: false}
- GET newfold/features/v1/isEnabled
  - Request parameters: `?feature=featureName`

### JavaScript API
- enable($name)
- disable($name)
- isEnabled($name)

### WP-CLI Commands
- newfold features enable {featureName}
- newfold features disable {featureName}
- newfold features isEnabled {featureName}

## Actions & Filters
- `newfold/features/filter/isEnabled`
  - isEnabled
  - Feature name
- `newfold/features/action/onEnable`
  - Feature name
- `newfold/features/action/onDisable`
  - Feature name

## Feature PHP Class
- A base class called `NewfoldLabs\WP\Features\Feature` that can be extended.
- The methods on the base class are:
  - enable
    - Updates the option in the DB for the feature to be `true` (on)
    - Applies the `newfold/features/enable` filter
  - disable
    - Updates the option in the DB for the feature to be `false` (off)
    - Applies the `newfold/features/disable` filter
  - isEnabled
    - Returns boolean based on state stored in DB
    - Applies the `newfold/features/isEnabled` filter
  - canToggleFeature
    - Returns boolean
    - Defaults to true if the user has `manage_options` permission in WordPress
    - Can be overridden in child class
- The base class can be extended to register a new feature. If the class extends `NewfoldLabs\WP\Features\Feature` and is auto-loaded via Composer, it will be automatically instantiated, and the object will be automatically added to the registry.
- The `get_declared_classes()` function will automatically find all applicable feature classes and instantiate them before adding them to the registry by name.
- Child classes should define a `name` property as the feature name for all API calls. This name will be used in the registry.
- Child class naming convention is `{FeatureName}Feature`.

## Notes
- This module is a Composer package and should not use the Newfold module loader.
- A `NewfoldLabs\WP\Features\Registry` class should be created and have the following methods: has, get, set, remove, keys, reset, all
- All feature states (on vs. off) should be stored in a single option in the options table, named `newfold_features`. The data structure would be a key/value pair where the key is the feature's name, and the value is a boolean based on whether the feature is enabled.
- Add a `newfold/features/isEnabled` default filter in the features module to make any null value false. This should be on a priority of 99. If a feature needs to default to true if not set, then the module registering the feature should hook in on the normal priority of 10 and change any null value to true.
