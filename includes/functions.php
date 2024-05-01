<?php
namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\Features;

// Instantiate Features singleton
$newfold_features = Features::getInstance();


/**
 * Helper function to get all features with their enabled state
 */
function getFeatures() {
    $newfold_features = Features::getInstance();
    return $newfold_features->getFeatures();
}

/**
 * Helper function to get an instance of a feature by name
 */
function getFeature($name) {
    $newfold_features = Features::getInstance();
    return $newfold_features->getFeature($name);
}

/**
 * Helper function to check if a feature is enabled by name
 */
function isEnabled($name) {
    $feature = getFeature($name);
    return $feature->isEnabled();
}