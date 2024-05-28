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
- Feature functionality should only run if a feature is enabled.

## API

### PHP Functions
- enable($name)
- disable($name)
- isEnabled($name)

### REST API Endpoints
- POST `newfold-features/v1/feature/enable`
  - Request payload: `{feature: featureName}`
  - Response payload: `{feature: featureName, isEnabled: true}`
- POST `newfold-features/v1/feature/disable`
  - Request payload: `{feature: featureName}`
  - Response payload: `{feature: featureName, isEnabled: false}`
- GET `newfold-features/v1/feature/isEnabled`
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
A set of generic hooks as well as dynamic hooks specific to each {featureName}.
- `newfold/features/filter/register`
- `newfold/features/filter/canToggle`
- `newfold/features/filter/canToggle:{$featureName}`
- `newfold/features/filter/defaultValue:{$featureName}`
- `newfold/features/filter/isEnabled`
- `newfold/features/filter/isEnabled:{featureName}`
- `newfold/features/action/onInitialize:{$featureName}`
- `newfold/features/action/beforeEnable`
- `newfold/features/action/beforeEnable:{featureName}`
- `newfold/features/action/onEnable`
- `newfold/features/action/onEnable:{featureName}`
- `newfold/features/action/afterEnable`
- `newfold/features/action/afterEnable:{featureName}`
- `newfold/features/action/beforeDisable`
- `newfold/features/action/beforeDisable:{featureName}`
- `newfold/features/action/onDisable`
- `newfold/features/action/onDisable:{featureName}`
- `newfold/features/action/afterDisable`
- `newfold/features/action/afterDisable:{featureName}`

## Feature PHP Class
- A base class called `NewfoldLabs\WP\Features\Feature` that can be extended.
- The methods on the base class are:
  - __constructor
    - We should make the abstract base class have a `final __constructor()` method so people can't add their own. The `__constructor()` should call a `private initialize()` method where any required functionality can be kicked off. When the feature discovery is happening (probably on a late priority for `after_setup_theme`), the constructors will run automatically when we instantiate the objects and add them to the registry. The base `__constructor` will handle the conditional firing of the `initialize()` method in the child instance. This way, we fully control and encapsulate the conditional logic in a way that can't be externally modified without using the provided hooks. It also keeps the implementation consistent across all modules.
  - initialize
    - module-specific setup, this should always be overridden in the module Feature class extending this base class.  
  - enable
    - Updates the option in the DB for the feature to be `true` (on)
    - Applies the `newfold/features/action/onEnable` hook, and similar `before` and `after` actions
  - disable
    - Updates the option in the DB for the feature to be `false` (off)
    - Applies the `newfold/features/action/onDisable` hook
  - isEnabled
    - Returns boolean based on state stored in DB
    - Applies the `newfold/features/filter/isEnabled` filter
  - canToggle
    - Returns boolean
    - Defaults to true if the user has `manage_options` permission in WordPress
    - Can be overridden in child class or with filters
- The base class can be extended to register a new feature. The class should extend `NewfoldLabs\WP\Features\Feature`, and it needs to be added to the `newfold/features/filter/register` hook to be instantiated and added to the registry.
- Child classes should define a `name` property as the feature name for all API calls. This name will be used in the registry.
- Child class naming convention is `{FeatureName}Feature`.

## Notes
- This module is a Composer package and does not use the Newfold module loader.
- A `NewfoldLabs\WP\Features\Registry` class should be created and have the following methods: has, get, set, remove, keys, reset, all
- All feature states (on vs. off) should be stored in a single option in the options table, named `newfold_features`. The data structure would be a key/value pair where the key is the feature's name, and the value is a boolean based on whether the feature is enabled.
- Add a `newfold/features/filter/isEnabled` default filter in the features module to make any null value false. This should be on a priority of 99. If a feature needs to default to true if not set, then the module registering the feature should hook in on the normal priority of 10 and change any null value to true.
- Enabling/Disabling a feature should send an event.

# Adoption

## Instructions on adding the features module to another module
The features module will replace the module loader register method. The module will need a Feature class which extends the `NewfoldLabs\WP\Features\Feature` base. Move module setup requirements into the initialization method. Any hook implementation should be in a seperate class so they can still be accessible when the features is disabeld, since a disabled feature will not initialize.

Use the registered features filter to add your class like so:
```
if ( function_exists( 'add_filter' ) ) {
	add_filter(
		'newfold/features/filter/register',
		function ( $features ) {
			return array_merge( $features, array( NewFeature::class ) );
		}
	);
}
```