# Integration

## Registering features

Other modules or the host plugin register features by adding to the `newfold/features/filter/register` filter. The value is an array of class names; each class must extend `NewfoldLabs\WP\Module\Features\Feature` and will be instantiated with the module’s options object. The feature’s `getName()` is used as the key in the registry.

Example:

```php
add_filter( 'newfold/features/filter/register', function ( $features ) {
    $features[] = MyFeature::class;
    return $features;
} );
```

Each feature class extends `Feature`, implements `getName()`, and can override default enabled state and behavior. State is persisted in the option `newfold_features` (keyed by feature name).

## Checking and toggling

- **PHP:** `isEnabled( $name )`, `enable( $name )`, `disable( $name )` (in namespace `NewfoldLabs\WP\Module\Features`). `isEnabled` returns a boolean or `WP_Error` if the feature is not found.
- **REST:** GET `/newfold-features/v1/features` to list; POST to `/newfold-features/v1/feature/enable` or `.../feature/disable` with body `{ "feature": "name" }`. See [api.md](api.md).
- **CLI:** WP-CLI commands are registered on `cli_init`; see the FeaturesCLI class for command names and args.

## Filter: isEnabled

The filter `newfold/features/filter/isEnabled` runs when resolving whether a feature is enabled. The module adds a default handler at priority 99 so that any feature not explicitly set returns false. Other code can use this filter to override or provide defaults.

## Container

The module uses `NewfoldLabs\WP\ModuleLoader\container()` to get the plugin instance (e.g. for asset URLs). It does not register itself with the loader; it is loaded by the host and other modules register features that may depend on container data.
