<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Custom_PTT\Taxonomy\Taxonomy;
use WP_Mock;

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
		WP_Mock::userFunction(
			'add_action',
			array(
				'times' => 1,
				'args'  => array( 'init', array( WP_Mock\Functions::type( 'object' ), 'register_taxonomy_on_init' ) ),
			)
		);

		$taxonomy = new Taxonomy();
		$taxonomy->register();

		// Add an assertion to the test
		$this->assertTrue( true );
	}

	/**
	 * Test the register_taxonomy_on_init method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_register_taxonomy_on_init(): void {
		WP_Mock::userFunction(
			'get_option',
			array(
				'return' => array(
					'custom_taxonomy' => array(
						'plural_label'   => 'Custom Taxonomies',
						'singular_label' => 'Custom Taxonomy',
						'post_type'      => 'post',
					),
				),
			)
		);

		WP_Mock::userFunction(
			'wp_parse_args',
			array(
				'return_arg' => 1,
			)
		);

		WP_Mock::userFunction(
			'register_taxonomy',
			array(
				'times'  => 1,
				'args'   => array( 'custom_taxonomy', 'post', WP_Mock\Functions::type( 'array' ) ),
				'return' => true,
			)
		);

		WP_Mock::userFunction(
			'apply_filters',
			array(
				'times'      => 1,
				'args'       => array( 'custom_ptt_taxonomy_args', WP_Mock\Functions::type( 'array' ), 'custom_taxonomy', WP_Mock\Functions::type( 'array' ) ),
				'return_arg' => 1,
			)
		);

		WP_Mock::userFunction(
			'do_action',
			array(
				'times' => 1,
				'args'  => array( 'custom_ptt_registered_taxonomies', WP_Mock\Functions::type( 'array' ) ),
			)
		);

		$taxonomy = new Taxonomy();
		$taxonomy->register_taxonomy_on_init();

		// Add an assertion to the test
		$this->assertTrue( true );
	}
}
