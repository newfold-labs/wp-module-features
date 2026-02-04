<?php

namespace NewfoldLabs\WP\Module\Features;

/**
 * Features static filter wpunit tests (no container).
 *
 * @coversDefaultClass \NewfoldLabs\WP\Module\Features\Features
 */
class FeaturesFilterWPUnitTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	/**
	 * Returns false for null/undefined value from defaultIsEnabledFilter().
	 *
	 * @return void
	 */
	public function test_default_is_enabled_filter_returns_false_for_null() {
		$value = Features::defaultIsEnabledFilter( null );
		$this->assertFalse( $value );
	}

	/**
	 * Returns true when value is true from defaultIsEnabledFilter().
	 *
	 * @return void
	 */
	public function test_default_is_enabled_filter_returns_true_when_true() {
		$value = Features::defaultIsEnabledFilter( true );
		$this->assertTrue( $value );
	}

	/**
	 * Returns false when value is false from defaultIsEnabledFilter().
	 *
	 * @return void
	 */
	public function test_default_is_enabled_filter_returns_false_when_false() {
		$value = Features::defaultIsEnabledFilter( false );
		$this->assertFalse( $value );
	}
}
