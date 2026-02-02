<?php

namespace NewfoldLabs\WP\Module\Features;

/**
 * Registry wpunit tests (empty registry and basic methods).
 *
 * @coversDefaultClass \NewfoldLabs\WP\Module\Features\Registry
 */
class RegistryWPUnitTest extends \lucatume\WPBrowser\TestCase\WPTestCase {

	/**
	 * Registry instance.
	 *
	 * @var Registry
	 */
	private $registry;

	/**
	 * Set up test.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->registry = new Registry();
	}

	/**
	 * Returns false for unregistered feature from has().
	 *
	 * @return void
	 */
	public function test_has_returns_false_when_empty() {
		$this->assertFalse( $this->registry->has( 'nonexistent' ) );
	}

	/**
	 * Returns null for unregistered feature from get().
	 *
	 * @return void
	 */
	public function test_get_returns_null_when_empty() {
		$this->assertNull( $this->registry->get( 'nonexistent' ) );
	}

	/**
	 * Returns empty array when no features registered from keys().
	 *
	 * @return void
	 */
	public function test_keys_returns_empty_array_when_empty() {
		$this->assertSame( array(), $this->registry->keys() );
	}

	/**
	 * Returns array from options from all().
	 *
	 * @return void
	 */
	public function test_all_returns_array() {
		$this->assertIsArray( $this->registry->all() );
	}

	/**
	 * Clears registry and options from reset().
	 *
	 * @return void
	 */
	public function test_reset_clears_registry() {
		$this->registry->reset();
		$this->assertSame( array(), $this->registry->keys() );
		$this->assertIsArray( $this->registry->all() );
	}
}
