<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit\Post_Type;

use WP_UnitTestCase;
use Custom_PTT\Post_Type\Post_Type;

/**
 * Post_Type Test.
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.2.1
 */
class Post_Type_Test extends WP_UnitTestCase {

	/**
	 * Post_Type instance.
	 *
	 * @var Post_Type
	 */
	private Post_Type $post_type;

	/**
	 * Set up before class.
	 *
	 * @since 0.2.1
	 */
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		
		if ( ! defined( 'CUSTOM_PTT_POST_TYPE_OPTION_NAME' ) ) {
			define( 'CUSTOM_PTT_POST_TYPE_OPTION_NAME', 'custom_ptt_post_types' );
		}
		
		if ( ! defined( 'HOUR_IN_SECONDS' ) ) {
			define( 'HOUR_IN_SECONDS', 3600 );
		}
	}

	/**
	 * Set up each test.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		
		$this->post_type = new Post_Type();
		
		// Clear any existing post types and cache
		delete_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME );
		wp_cache_flush_group( 'custom_ptt_post_types' );
	}

	/**
	 * Tear down each test.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		// Clean up
		delete_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME );
		wp_cache_flush_group( 'custom_ptt_post_types' );
		
		parent::tearDown();
	}

	/**
	 * Test the register method hooks the init action.
	 *
	 * This test should FAIL initially to demonstrate TDD Red phase.
	 *
	 * @since 0.2.1
	 */
	public function test_register_hooks_init_action(): void {
		// Arrange - Clear any existing hooks
		remove_all_actions( 'init' );
		
		// Act - Register the post type
		$this->post_type->register();

		// Assert - Verify the hook was registered at priority 10
		$this->assertEquals( 10, has_action( 'init', array( $this->post_type, 'register_post_type_on_init' ) ) );
	}

	/**
	 * Test that Post_Type implements Registerable interface.
	 *
	 * This test should FAIL initially to demonstrate TDD Red phase.
	 *
	 * @since 0.2.1
	 */
	public function test_post_type_implements_registerable_interface(): void {
		// Assert - Post_Type should implement Registerable interface
		$this->assertInstanceOf( 'Custom_PTT\Infrastructure\Registerable', $this->post_type );
	}

	/**
	 * Test that register method exists and is callable.
	 *
	 * This test should PASS to verify our basic setup.
	 *
	 * @since 0.2.1
	 */
	public function test_register_method_exists_and_is_callable(): void {
		// Assert - register method should exist and be callable
		$this->assertTrue( method_exists( $this->post_type, 'register' ) );
		$this->assertTrue( is_callable( array( $this->post_type, 'register' ) ) );
	}

	/**
	 * Test cache validation feature (NEW FEATURE - TDD Red Phase).
	 *
	 * This test should FAIL because we haven't implemented cache validation yet.
	 *
	 * @since 0.2.1
	 */
	public function test_validate_cache_integrity_returns_true_for_valid_cache(): void {
		// Arrange - Set up valid cache data
		$post_type_data = array(
			'test_product' => array(
				'post_type_key'  => 'test_product',
				'plural_label'   => 'Test Products',
				'singular_label' => 'Test Product',
				'post_type_slug' => 'test_product',
			),
		);
		
		update_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, $post_type_data );
		wp_cache_set( 'registered_post_types', $post_type_data, 'custom_ptt_post_types', HOUR_IN_SECONDS );

		// Act - Validate cache integrity (method doesn't exist yet)
		$is_valid = $this->post_type->validate_cache_integrity();

		// Assert - Should return true for valid cache
		$this->assertTrue( $is_valid );
	}

	/**
	 * Test cache validation detects corrupted cache (NEW FEATURE - TDD Red Phase).
	 *
	 * This test should FAIL because we haven't implemented cache validation yet.
	 *
	 * @since 0.2.1
	 */
	public function test_validate_cache_integrity_returns_false_for_corrupted_cache(): void {
		// Arrange - Set up mismatched cache and option data
		$option_data = array(
			'real_product' => array(
				'post_type_key'  => 'real_product',
				'plural_label'   => 'Real Products',
				'singular_label' => 'Real Product',
				'post_type_slug' => 'real_product',
			),
		);
		
		$corrupted_cache = array(
			'fake_product' => array(
				'post_type_key'  => 'fake_product',
				'plural_label'   => 'Fake Products',
				'singular_label' => 'Fake Product',
				'post_type_slug' => 'fake_product',
			),
		);

		update_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, $option_data );
		wp_cache_set( 'registered_post_types', $corrupted_cache, 'custom_ptt_post_types', HOUR_IN_SECONDS );

		// Act - Validate cache integrity (method doesn't exist yet)
		$is_valid = $this->post_type->validate_cache_integrity();

		// Assert - Should return false for corrupted cache
		$this->assertFalse( $is_valid );
	}
}