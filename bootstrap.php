<?php
/**
 * Features Boostrap
 *
 * @package NewfoldLabs\WP\Module\Features
 */
use NewfoldLabs\WP\Module\Features\Registry;
use NewfoldLabs\WP\Module\Features\Feature;

require_once BLUEHOST_PLUGIN_DIR . '/vendor/newfold-labs/wp-module-staging/includes/StagingFeature.php';

// Create registry
$nfd_feature_registry = new Registry();

if ( function_exists( 'add_action' ) ) {

	// Find and add all features to registry
	add_action(
		'plugins_loaded',
		function () {
            global $nfd_feature_registry;
            // Find extended instances of the Feature class and add to the Registry
            foreach ( get_declared_classes() as $class ) {
                if( is_subclass_of( $class, 'NewfoldLabs\WP\Module\Features\Feature' ) ) {
                    // error_log( 'NewfoldLabs\WP\Module\Features child class found: '.$class );
                    // add class to registry and instantiate
                    $nfd_feature_registry->set( $class );
                }
            }
        },
        1
    );
}

// Add default filter to make any feature null value return false
if ( function_exists( 'add_filter' ) ) {
	add_filter(
		'newfold/features/filter/isEnabled',
		function ($value) {
			// if feature state is null, return false
            if ( !isset($value) ) {
                $value = false;
            }
            return $value;
		},
        99, // low priority so modules can override easily if needed
        1
	);
}


/**
 * Questions:
 * 
 * Should hooks onEnable and onDisable hooks be within the can_toggle check?
 * 
 * Should we have beforeEnable and afterEnable action hooks instead of the onEnable? etc.
 * 
 * Should isEnabled be used when saving to db?
 * - Staging has a filter for the enabled value when context is atomic, but this still saves as true to db.
 * 
 * How to add unit tests for the Registry, the Features base class and the API endpoints and CLI commands.
 * 
 * Autoloading troubleshooting and `get_declared_classes` and `is_subclass_of`
 * 
 */