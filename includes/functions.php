<?php

namespace NewfoldLabs\WP\Features;

use NewfoldLabs\WP\Features\Feature;
// use NewfoldLabs\WP\Features\Features;
use NewfoldLabs\WP\Features\Registry;

/**
 * Register a Feature - We're doing this via a class existing in the module and 
 *
 * @param String $name - the feature name/id
 * @param String $options - array of callbacks and options
 * @return Feature - value of the registered Feature
 */
// function register_feature( $name, $options ) {
    // create Feature

    // add Feature to Features

// }

// Create registry
$newfold_features = new Registry();

// Find extended instances of the Feature class and add to the Registry
foreach ( get_declared_classes() as $class ) {
    if( is_subclass_of( $class, 'NewfoldLabs/WP/Features/Feature' ) ) {
        // instantiate this feature class and pass the registry
        $featureInstance = new $class( $newfold_features );
        echo $featureInstance->name;
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