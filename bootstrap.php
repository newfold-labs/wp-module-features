<?php
/**
 * Features Boostrap
 *
 * @package NewfoldLabs\WP\Module\Features
 */
use NewfoldLabs\WP\Module\Features\Registry;
// use NewfoldLabs\WP\Features\Feature;

// Create registry
$newfold_features = new Registry();

// Find extended instances of the Feature class and add to the Registry
foreach ( get_declared_classes() as $class ) {
    if( is_subclass_of( $class, 'NewfoldLabs/WP/Module/Features/Feature' ) ) {
        // instantiate this feature class and pass the registry
        $featureInstance = new $class( $newfold_features );
        // add instantiated feature class to the registry
        $newfold_features->set( $featureInstance->name, $featureInstance->isEnabled );
    }
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
 * How to add unit tests for the Registry, the Features base class and the API endpoints and CLI commands.
 * 
 * 
 * 
 * 
 */