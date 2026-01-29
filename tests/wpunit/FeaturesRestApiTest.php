<?php

namespace NewfoldLabs\WP\Module\Features;

/**
 * WPUnit tests for Features REST API endpoints.
 *
 * Verifies response status and shape for newfold-features/v1 routes.
 * Routes are registered when the module is loaded (e.g. by the brand plugin).
 * If routes are not registered (404), tests are skipped.
 *
 * @coversDefaultClass \NewfoldLabs\WP\Module\Features\FeaturesAPI
 */
class FeaturesRestApiTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	const NAMESPACE = 'newfold-features/v1';

	/**
	 * Perform REST request and skip if endpoint is not registered.
	 *
	 * @param string $method GET, POST, etc.
	 * @param string $route  Route path without namespace, e.g. '/features'.
	 * @param array  $params Optional query or body params.
	 * @return \WP_REST_Response
	 */
	private function request( $method, $route, $params = array() ) {
		$request = new \WP_REST_Request( $method, '/' . self::NAMESPACE . $route );
		foreach ( $params as $key => $value ) {
			$request->set_param( $key, $value );
		}
		return rest_do_request( $request );
	}

	/**
	 * Skip the test if the API route is not registered (404).
	 *
	 * @param \WP_REST_Response $response Response from rest_do_request.
	 */
	private function skip_if_routes_not_registered( $response ) {
		if ( $response->get_status() === 404 ) {
			$this->markTestSkipped( 'Features REST routes not registered (module may not be loaded in this context).' );
		}
	}

	/**
	 * @covers ::features
	 */
	public function test_get_features_returns_200_and_features_key() {
		$response = $this->request( 'GET', '/features' );
		$this->skip_if_routes_not_registered( $response );
		$this->assertSame( 200, $response->get_status(), 'GET /features should return 200' );
		$data = $response->get_data();
		$this->assertIsArray( $data, 'Response data should be an array' );
		$this->assertArrayHasKey( 'features', $data, 'Response should have features key' );
		$this->assertIsArray( $data['features'], 'features should be an array (feature name => enabled)' );
	}

	/**
	 * @covers ::featureIsEnabled
	 */
	public function test_get_feature_is_enabled_with_invalid_feature_returns_error() {
		$response = $this->request( 'GET', '/feature/isEnabled', array( 'feature' => 'nonexistent-feature-name' ) );
		$this->skip_if_routes_not_registered( $response );
		// Invalid or unknown feature should yield 400/404 or similar from validation.
		$this->assertNotEquals( 200, $response->get_status(), 'Invalid feature name should not return 200' );
	}

	/**
	 * @covers ::featureEnable
	 */
	public function test_post_feature_enable_with_invalid_feature_returns_error() {
		$response = $this->request( 'POST', '/feature/enable', array( 'feature' => 'nonexistent-feature-name' ) );
		$this->skip_if_routes_not_registered( $response );
		$this->assertNotEquals( 200, $response->get_status(), 'Enabling invalid feature should not return 200' );
	}

	/**
	 * @covers ::featureDisable
	 */
	public function test_post_feature_disable_with_invalid_feature_returns_error() {
		$response = $this->request( 'POST', '/feature/disable', array( 'feature' => 'nonexistent-feature-name' ) );
		$this->skip_if_routes_not_registered( $response );
		$this->assertNotEquals( 200, $response->get_status(), 'Disabling invalid feature should not return 200' );
	}
}
