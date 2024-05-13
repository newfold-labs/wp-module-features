<?php

namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\Registry;
use WP_Forge\Options\Options;

/**
 * Base class for a feature in the Newfold plugin.
 *
 * Child classes should define a name property as the feature name for all API calls. This name will be used in the registry.
 * Child class naming convention is {FeatureName}Feature.
 */
abstract class Feature {

	/**
	 * Options object
	 *
	 * @var Options
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
	 *
	 * @param Options $options The associated Options for saving to database
	 */
	final public function __construct( $options ) {

		// assign options
		$this->options = $options;

		// check if state already saved to options
		$this->setValue( $this->options->get( $this->name ) );

		// only initialize if enabled
		if ( $this->isEnabled() ) {
			$this->initialize();
		}

		// else not initialized or loaded - does nothing
	}

	/**
	 * Init
	 *
	 * Add this in the child feature class.
	 */
	protected function initialize() {
		// do initialization stuff - nothing here but in the child class
	}

	/**
	 * Set Value - this updates the value as well as the option
	 *
	 * @param boolean $value The value to set.
	 */
	private function setValue( $value ) {
		$this->value = $value;
		$this->options->set( $this->name, $value );
	}

	/**
	 * Enables the feature.
	 *
	 * @return boolean True if successful, false otherwise
	 */
	final public function enable() {
		if ( $this->canToggleFeature() ) {
			// generic feature onEnable action
			do_action( 'newfold/features/action/onEnable', $this->name );
			// specific feature onEnable action
			do_action( "newfold/features/action/onEnable:{$this->name}" );
			$this->setValue( true );
			return true;
		}
		return false;
	}

	/**
	 * Disables the feature.
	 *
	 * @return boolean True if successful, false otherwise
	 */
	final public function disable() {
		if ( $this->canToggleFeature() ) {
			// generic feature onDisable action
			do_action( 'newfold/features/action/onDisable', $this->name );
			// specific feature onDisable action
			do_action( "newfold/features/action/onDisable:{$this->name}" );
			$this->setValue( false );
			return true;
		}
		return false;
	}

	/**
	 * Checks if the feature is enabled.
	 *
	 * @return bool True if the feature is enabled, false otherwise.
	 */
	final public function isEnabled() {
		return apply_filters(
			// specific feature isEnabled filter
			"newfold/features/filter/isEnabled:{$this->name}",
			apply_filters(
				// generic isEnabled filter
				'newfold/features/filter/isEnabled',
				$this->value
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
