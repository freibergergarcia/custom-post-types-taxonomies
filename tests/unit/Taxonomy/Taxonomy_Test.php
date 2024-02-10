<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit\Taxonomy;

use PHPUnit\Framework\TestCase;
use Custom_PTT\Taxonomy\Taxonomy;

/**
 * Taxonomy Test.
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.1.0-alpha
 */
class Taxonomy_Test extends TestCase {

	/**
	 * Set up before class.
	 *
	 * @since 0.1.0-alpha
	 */
	public static function setUpBeforeClass(): void {
		if ( ! defined( 'CUSTOM_PTT_TAXONOMY_OPTION_NAME' ) ) {
			define( 'CUSTOM_PTT_TAXONOMY_OPTION_NAME', 'custom_ptt_taxonomy_option_name' );
		}
	}

	/**
	 * Test the register method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_register(): void {
		$taxonomy = new Taxonomy();
		$taxonomy->register();

		$this->assertEquals( 10, has_action( 'init', array( $taxonomy, 'register_taxonomy_on_init' ) ) );
	}
}
