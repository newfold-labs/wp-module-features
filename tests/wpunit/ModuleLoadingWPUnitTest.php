<?php

namespace NewfoldLabs\WP\Module\Features;

use NewfoldLabs\WP\Module\Features\FeaturesAPI;
use NewfoldLabs\WP\Module\Features\FeaturesCLI;

/**
 * Module loading wpunit tests.
 *
 * @coversDefaultClass \NewfoldLabs\WP\Module\Features\Features
 */
class ModuleLoadingWPUnitTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	/**
	 * Verify core module classes exist.
	 *
	 * @return void
	 */
	public function test_module_classes_load() {
		$this->assertTrue( class_exists( Feature::class ) );
		$this->assertTrue( class_exists( Features::class ) );
		$this->assertTrue( class_exists( Registry::class ) );
		$this->assertTrue( class_exists( FeaturesAPI::class ) );
		$this->assertTrue( class_exists( FeaturesCLI::class ) );
	}

	/**
	 * Verify WordPress factory is available.
	 *
	 * @return void
	 */
	public function test_wordpress_factory_available() {
		$this->assertTrue( function_exists( 'get_option' ) );
		$this->assertNotEmpty( get_option( 'blogname' ) );
	}
}
