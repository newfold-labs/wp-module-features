<?php
namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\Features;

/**
 * Helper function to check if a feature is enabled by name
 *
 * @param string $name - the feature name
 * @return bool indicating if the feature is enabled
 */
function isEnabled( $name ) {
	return Features::getInstance()->getFeature( $name )->isEnabled();
}

/**
 * Helper function to enable a feature by name
 *
 * @param string $name - the feature name
 * @return bool indicating if the feature was enabled
 */
function enable( $name ) {
	return Features::getInstance()->getFeature( $name )->enable();
}

/**
 * Helper function to disable a feature by name
 *
 * @param string $name - the feature name
 * @return bool indicating if the feature was disabled
 */
function disable( $name ) {
	return Features::getInstance()->getFeature( $name )->disable();
}
