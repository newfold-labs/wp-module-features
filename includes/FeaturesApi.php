<?php

namespace NewFoldLabs\WP\Module\Features;

/**
 * Class FeaturesApi
 */
class FeaturesAPI extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'newfold-features/v1';

	/**
	 * An instance of the Features class.
	 *
	 * @var Features
	 */
	protected $features;

	/**
	 * FeaturesApi Controller constructor.
	 */
	public function __construct() {
		$this->features = Features::getInstance();
		$this->register_routes();
	}

	/**
	 * Register API Routes
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/features', 
			array(
				'methods' => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'features' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/feature/enable', 
			array(
				'methods' => \WP_REST_Server::EDITABLE,
				'callback' => array( $this, 'feature_enable' ),
				'permission_callback' => array( $this, 'checkPermission' ),
				'args' => array(
					'feature' => array(
						'required' => true,
						'validate_callback' => array( $this, 'validateFeatureParam' )
					),
				),
			)
		);
	
		register_rest_route(
			$this->namespace,
			'/feature/disable',
			array(
				'methods' => \WP_REST_Server::EDITABLE,
				'callback' => array( $this, 'feature_disable' ),
				'permission_callback' => array( $this, 'checkPermission' ),
				'args' => array(
					'feature' => array(
						'required' => true,
						'validate_callback' => array( $this, 'validateFeatureParam' )
					),
				),
			)
		);
	
		register_rest_route(
			$this->namespace,
			'/feature/isEnabled',
			array(
				'methods' => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'feature_is_enabled' ),
				'permission_callback' => array( $this, 'checkPermission' ),
				'args' => array(
					'feature' => array(
						'required' => true,
						'validate_callback' => array( $this, 'validateFeatureParam' )
					),
				),
			)
		);

	}

	/**
	 * Validate feature callback as string
	 * 
	 * @return bool
	 */
	public function validateFeatureParam( $param, $request, $key ) {
		return is_string( $param );
	}


	/**
	 * Check permissions for routes.
	 *
	 * @return bool
	 */
	public function checkPermission() {
		return (bool) current_user_can( 'manage_options' );
	}

	/**
	 * Get features via REST API.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error The response object or WP_Error on failure.
	 */
	function features( \WP_REST_Request $request ) {
		return new \WP_REST_Response( ['features' => $this->features->getFeatures() ], 200 );
	}

	/**
	 * Enable a feature via REST API.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error The response object or WP_Error on failure.
	 */
	function feature_enable( \WP_REST_Request $request ) {
		$params = $request->get_json_params();
		$name = $params['feature'] ?? '';

		$feature = $this->features->getFeature( $name );
		if ( $feature ) {
			$feature->enable();
			return new \WP_REST_Response( ['feature' => $name, 'isEnabled' => $feature->isEnabled()], 200 );
		} else {
			return new \WP_Error( 'nfd_features_error', 'Failed to enable the feature.', ['status' => 500] );
		}
	}

	/**
	 * Disable a feature via REST API.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error The response object or WP_Error on failure.
	 */
	function feature_disable( \WP_REST_Request $request ) {
		$params = $request->get_json_params();
		$name = $params['feature'] ?? '';

		$feature = $this->features->getFeature( $name );
		if ( $feature ) {
			$feature->disable();
			return new \WP_REST_Response( ['feature' => $name, 'isEnabled' => $feature->isEnabled()], 200 );
		} else {
			return new \WP_Error( 'nfd_features_error', 'Failed to disable the feature.', ['status' => 500] );
		}
	}


	/**
	 * Checks if a feature is enabled via REST API.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response The response object.
	 */
	function feature_is_enabled( \WP_REST_Request $request ) {
		$name = $request['feature'] ?? '';

		$feature = $this->features->getFeature( $name );
		if ( $feature ) {
			$isEnabled = $feature->isEnabled();
			return new \WP_REST_Response( ['feature' => $name, 'isEnabled' => $isEnabled], 200 );
		} else {
			return new \WP_Error( 'nfd_features_error', 'Failed to check if feature isEnabled.', ['status' => 500] );
		}
	}

}
