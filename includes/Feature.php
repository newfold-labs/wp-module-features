<?php
// File: feature.php

namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\Registry;

/**
 * Base class for a feature in the Newfold plugin.
 * 
 * Child classes should define a name property as the feature name for all API calls. This name will be used in the registry.
 * Child class naming convention is {FeatureName}Feature.
 */
abstract class Feature {

    /**
     * Registry object
     */
    private $options;

    /**
     * The feature name.
     *
     * @var string
     */
    protected $name;

    /**
     * The feature value.
     *
     * @var boolean
     */
    protected $value = false;

    /**
     * Constructor
     */
    final function __construct(Registry $options) {
        // assign global options
        $this->options = $options;

        // set initial value
        $this->options->set($this->name, $this->value);

        // only initialize if enabled
        if ( $this->isEnabled() ) {
            $this->initialize();
        } else {
            // not initialized or loaded
            // does nothing
        }
    }

    /**
     * Init
     * 
     * Add this in the child feature class.
     */
    protected function initialize() {
        // do initilization stuff
        // does nothing here in the base class
    }

    /**
     * Enables the feature.
     */
    public function enable() {
        if ( $this->canToggleFeature()) {
            // generic feature onEnable action
            do_action("newfold/features/action/onEnable", $this->name);
            // specific feature onEnable action
            do_action("newfold/features/action/onEnable/{$this->name}");

            $this->options->set($this->name, true);
        }
    }

    /**
     * Disables the feature.
     */
    public function disable() {
        if ( $this->canToggleFeature()) {

            // generic feature onDisable action
            do_action("newfold/features/action/onDisable", $this->name);
            // specific feature onDisable action
            do_action("newfold/features/action/onDisable/{$this->name}");

            $this->options->set($this->name, false);
        }
    }

    /**
     * Checks if the feature is enabled.
     *
     * @return bool True if the feature is enabled, false otherwise.
     */
    public function isEnabled() {
        return apply_filters(
            // specific feature isEnabled filter
            "newfold/features/filter/isEnabled/{$this->name}",
            apply_filters(
                // generic isEnabled filter
                "newfold/features/filter/isEnabled",
                $this->options->get($this->name)
            )
        );
    }

    /**
     * Checks if the feature can be toggled - user has permissions to toggle.
     *
     * @return bool True if the feature toggle is allowed, false otherwise.
     */
    public function canToggleFeature() {
        return (bool) current_user_can( 'manage_options' );
    }

    /**
     * Get Name
     */
    public function getName() {
        return $this->name;
    }

}