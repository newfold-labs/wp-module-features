<?php
namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\Features;

// Instantiate Features singleton
Features::getInstance();

/**
 * Helper function to get an instance of a feature by name
 *
 * @param string $name - the feature name
 * @return Feature instance
 */
function getFeature( $name ) {
	return Features::getInstance()->getFeature( $name );
}

/**
 * Helper function to check if a feature is enabled by name
 *
 * @param string $name - the feature name
 * @return bool indicating if the feature is enabled
 */
function isEnabled( $name ) {
	return getFeature( $name )->isEnabled();
}

/**
 * Helper function to enable a feature by name
 *
 * @param string $name - the feature name
 * @return bool indicating if the feature was enabled
 */
function enable( $name ) {
	return getFeature( $name )->enable();
}

/**
 * Helper function to disable a feature by name
 *
 * @param string $name - the feature name
 * @return bool indicating if the feature was disabled
 */
function disable( $name ) {
	return getFeature( $name )->disable();
}

/**
 * Helper function to check if a feature can be modified by name
 *
 * @param string $name - the feature name
 * @return bool indicating if modifying the feature status is allowed
 */
function canToggle( $name ) {
	return getFeature( $name )->canToggleFeature();
}
