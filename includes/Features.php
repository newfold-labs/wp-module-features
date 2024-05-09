<?php
namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\Registry;
use NewfoldLabs\WP\Module\Features\FeaturesAPI;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * This class Features functionality.
 **/
class Features {

	/**
	 * Singleton instance
	 *
	 * @var Features
	 */
	private static $instance = null;

	/**
	 * Registry
	 *
	 * @var Registry
	 */
	private static $registry = null;

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Create registry
		self::$registry = new Registry();

		if ( function_exists( 'add_action' ) ) {

			// Find and add all features to registry
			add_action(
				'plugins_loaded',
				function () {
					// Find extended instances of the Feature class and add to the Registry
					foreach ( get_declared_classes() as $class ) {
						if ( is_subclass_of( $class, 'NewfoldLabs\WP\Module\Features\Feature' ) ) {
							// error_log( 'NewfoldLabs\WP\Module\Features child class found: '.$class );
							// add class to registry and instantiate
							self::$registry->set( $class );
						}
					}
				},
				1
			);

			// Register API endpoints
			add_action(
				'rest_api_init',
				function () {
					$api_instance = new FeaturesAPI();
				}
			);

			// Add CLI commands
			add_action(
				'cli_init',
				function () {
					\WP_CLI::add_command(
						'newfold features',
						'NewfoldLabs\WP\Module\Features\FeaturesCLI',
						array(
							'shortdesc' => 'Operations for Newfold Features.',
							'longdesc'  => 'Internal commands to handle features.' .
											PHP_EOL . 'Subcommands: all, enable, disable, isEnabled.',
						)
					);
				}
			);
			
			// Register API script and localized values
			add_action(
				'admin_enqueue_scripts',
				function () {
					// Register features API script
					$scriptPath = container()->plugin()->url . 'vendor/newfold-labs/wp-module-features/static/js/features.js';
					wp_register_script(
						'newfold-features',
						$scriptPath,
						array( 'wp-api-fetch', 'newfold-features-local' ),
						container()->plugin()->version,
						true
					);
					// Register Localized Script with initial feature values
					wp_register_script( 'newfold-features-local', null, null, container()->plugin()->version, true );
					wp_localize_script(
						'newfold-features-local',
						'NewfoldFeatures',
						array(
							'features' => getFeatures(),
							'restUrl'  => esc_url_raw( rest_url() ) . 'newfold-features/v1',
						)
					);
				}
			);
		}

		// Add default filter to make any feature null value return false
		if ( function_exists( 'add_filter' ) ) {
			add_filter(
				'newfold/features/filter/isEnabled',
				function ( $value ) {
					// if feature state is null, return false
					if ( ! isset( $value ) ) {
						$value = false;
					}
					return $value;
				},
				99, // low priority so modules can override easily if needed
				1
			);
		}
	}

	/**
	 * Get instance for singleton Features
	 *
	 * @return Features instance
	 */
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new Features();
		}
		return self::$instance;
	}

	/**
	 * Get Features
	 *
	 * @return Array list of features and enabled states (key:name value:enabled)
	 */
	public static function getFeatures() {
		return self::$registry->all();
	}

	/**
	 * Get feature list
	 *
	 * @return Array list of features
	 */
	public static function getFeatureList() {
		return self::$registry->keys();
	}

	/**
	 * Get Feature
	 *
	 * @param string $name the name of the feature
	 * @return Feature instance
	 */
	public static function getFeature( $name ) {
		return self::$registry->get( $name );
	}
}
