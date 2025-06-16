<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit\Taxonomy;

use WP_UnitTestCase;
use Custom_PTT\Taxonomy\Taxonomy;
use WP_Error;
use Exception;

/**
 * Taxonomy Test.
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.1.0-alpha
 */
class Taxonomy_Test extends WP_UnitTestCase {

	/**
	 * Taxonomy instance.
	 *
	 * @var Taxonomy
	 */
	private Taxonomy $taxonomy;

	/**
	 * Set up before class.
	 *
	 * @since 0.1.0-alpha
	 */
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		
		if ( ! defined( 'CUSTOM_PTT_TAXONOMY_OPTION_NAME' ) ) {
			define( 'CUSTOM_PTT_TAXONOMY_OPTION_NAME', 'custom_ptt_taxonomies' );
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
		
		$this->taxonomy = new Taxonomy();
		
		// Clear any existing taxonomies and cache
		delete_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME );
		wp_cache_flush_group( 'custom_ptt_taxonomies' );
	}

	/**
	 * Tear down each test.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		// Clean up
		delete_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME );
		wp_cache_flush_group( 'custom_ptt_taxonomies' );
		
		parent::tearDown();
	}

	/**
	 * Test the register method hooks the init action.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_register_hooks_init_action(): void {
		$this->taxonomy->register();

		$this->assertEquals( 10, has_action( 'init', array( $this->taxonomy, 'register_taxonomy_on_init' ) ) );
	}

	/**
	 * Test register_taxonomy_on_init with empty options.
	 *
	 * @since 0.2.1
	 */
	public function test_register_taxonomy_on_init_with_empty_options(): void {
		// Ensure no taxonomies are stored
		$this->assertEquals( array(), get_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, array() ) );

		// Should not throw any exceptions
		$this->taxonomy->register_taxonomy_on_init();
		
		// Should not register any taxonomies
		$this->assertEmpty( get_taxonomies( array( '_builtin' => false ) ) );
	}

	/**
	 * Test register_taxonomy_on_init with valid taxonomy data.
	 *
	 * @since 0.2.1
	 */
	public function test_register_taxonomy_on_init_with_valid_data(): void {
		$taxonomy_data = array(
			'test_category' => array(
				'plural_label'   => 'Test Categories',
				'singular_label' => 'Test Category',
				'post_type'      => array( 'post' ),
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$this->taxonomy->register_taxonomy_on_init();

		// Verify taxonomy was registered
		$this->assertTrue( taxonomy_exists( 'test_category' ) );
		
		// Verify taxonomy object properties
		$taxonomy_object = get_taxonomy( 'test_category' );
		$this->assertEquals( 'Test Categories', $taxonomy_object->labels->name );
		$this->assertEquals( 'Test Category', $taxonomy_object->labels->singular_name );
		$this->assertTrue( $taxonomy_object->public );
		$this->assertTrue( $taxonomy_object->show_ui );
	}

	/**
	 * Test register_taxonomy_on_init with multiple taxonomies.
	 *
	 * @since 0.2.1
	 */
	public function test_register_taxonomy_on_init_with_multiple_taxonomies(): void {
		$taxonomy_data = array(
			'product_category' => array(
				'plural_label'   => 'Product Categories',
				'singular_label' => 'Product Category',
				'post_type'      => array( 'product' ),
			),
			'product_tag'      => array(
				'plural_label'   => 'Product Tags',
				'singular_label' => 'Product Tag',
				'post_type'      => array( 'product' ),
				'hierarchical'   => false,
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$this->taxonomy->register_taxonomy_on_init();

		// Verify both taxonomies were registered
		$this->assertTrue( taxonomy_exists( 'product_category' ) );
		$this->assertTrue( taxonomy_exists( 'product_tag' ) );
		
		// Verify hierarchical setting
		$product_tag = get_taxonomy( 'product_tag' );
		$this->assertFalse( $product_tag->hierarchical );
	}

	/**
	 * Test caching mechanism for taxonomy options.
	 *
	 * @since 0.2.1
	 */
	public function test_caching_mechanism_for_options(): void {
		$taxonomy_data = array(
			'cached_category' => array(
				'plural_label'   => 'Cached Categories',
				'singular_label' => 'Cached Category',
				'post_type'      => array( 'post' ),
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		// First call should set cache
		$this->taxonomy->register_taxonomy_on_init();
		
		// Verify cache was set
		$cached_taxonomies = wp_cache_get( 'registered_taxonomies', 'custom_ptt_taxonomies' );
		$this->assertEquals( $taxonomy_data, $cached_taxonomies );

		// Modify option but don't clear cache
		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, array() );

		// Second call should use cached data
		$this->taxonomy->register_taxonomy_on_init();
		
		// Taxonomy should still exist because cache was used
		$this->assertTrue( taxonomy_exists( 'cached_category' ) );
	}

	/**
	 * Test filter hook integration.
	 *
	 * @since 0.2.1
	 */
	public function test_filter_hook_integration(): void {
		$taxonomy_data = array(
			'filtered_category' => array(
				'plural_label'   => 'Filtered Categories',
				'singular_label' => 'Filtered Category',
				'post_type'      => array( 'post' ),
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		// Add filter to modify taxonomy args
		add_filter(
			'custom_ptt_taxonomy_args',
			function ( $args, $taxonomy_slug, $taxonomy_data ) {
				if ( 'filtered_category' === $taxonomy_slug ) {
					$args['show_in_rest'] = false;
				}
				return $args;
			},
			10,
			3 
		);

		$this->taxonomy->register_taxonomy_on_init();

		$taxonomy_object = get_taxonomy( 'filtered_category' );
		$this->assertFalse( $taxonomy_object->show_in_rest );
	}

	/**
	 * Test action hook fires after registration.
	 *
	 * @since 0.2.1
	 */
	public function test_action_hook_fires_after_registration(): void {
		$taxonomy_data = array(
			'action_category' => array(
				'plural_label'   => 'Action Categories',
				'singular_label' => 'Action Category',
				'post_type'      => array( 'post' ),
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$action_fired  = false;
		$received_data = null;

		// Add action to verify it fires
		add_action(
			'custom_ptt_registered_taxonomies',
			function ( $taxonomies ) use ( &$action_fired, &$received_data ) {
				$action_fired  = true;
				$received_data = $taxonomies;
			} 
		);

		$this->taxonomy->register_taxonomy_on_init();

		$this->assertTrue( $action_fired );
		$this->assertEquals( $taxonomy_data, $received_data );
	}

	/**
	 * Test error handling for invalid taxonomy data.
	 *
	 * @since 0.2.1
	 */
	public function test_error_handling_with_invalid_data(): void {
		$taxonomy_data = array(
			'invalid_category' => array(
				'plural_label'   => 'Invalid Categories',
				'singular_label' => 'Invalid Category',
				// Missing required post_type
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		// Should not throw exception due to try-catch
		$this->taxonomy->register_taxonomy_on_init();
		
		// Invalid taxonomy should not be registered
		$this->assertFalse( taxonomy_exists( 'invalid_category' ) );
	}

	/**
	 * Test error logging in debug mode.
	 *
	 * @since 0.2.1
	 */
	public function test_error_logging_in_debug_mode(): void {
		if ( ! defined( 'WP_DEBUG' ) ) {
			define( 'WP_DEBUG', true );
		}

		$taxonomy_data = array(
			'debug_category' => array(
				'plural_label'   => 'Debug Categories',
				'singular_label' => 'Debug Category',
				// Missing post_type to trigger error
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		// Capture error log output
		$error_logged = false;
		
		// Mock error_log function to capture calls
		$original_handler = set_error_handler(
			function ( $errno, $errstr ) use ( &$error_logged ) {
				if ( strpos( $errstr, 'Custom PTT Plugin - Error registering taxonomy' ) !== false ) {
						$error_logged = true;
				}
				return false; // Let default handler process
			} 
		);

		$this->taxonomy->register_taxonomy_on_init();

		// Invalid taxonomy should not be registered due to missing post_type
		$this->assertFalse( taxonomy_exists( 'debug_category' ) );

		// Restore original error handler
		if ( $original_handler ) {
			set_error_handler( $original_handler );
		} else {
			restore_error_handler();
		}
	}

	/**
	 * Test cache key generation for taxonomy arguments.
	 *
	 * @since 0.2.1
	 */
	public function test_cache_key_generation(): void {
		$taxonomy_data = array(
			'cache_test_category' => array(
				'plural_label'   => 'Cache Test Categories',
				'singular_label' => 'Cache Test Category',
				'post_type'      => array( 'post' ),
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$this->taxonomy->register_taxonomy_on_init();

		// Verify cache was set for registered taxonomies
		$cached_taxonomies = wp_cache_get( 'registered_taxonomies', 'custom_ptt_taxonomies' );
		$this->assertEquals( $taxonomy_data, $cached_taxonomies );

		// Verify the taxonomy was actually registered
		$this->assertTrue( taxonomy_exists( 'cache_test_category' ) );
	}

	/**
	 * Test that invalid taxonomies are not cached.
	 *
	 * @since 0.2.1
	 */
	public function test_invalid_taxonomies_not_cached(): void {
		$taxonomy_data = array(
			'valid_category' => array(
				'plural_label'   => 'Valid Categories',
				'singular_label' => 'Valid Category',
				'post_type'      => array( 'post' ),
			),
			'invalid_category' => array(
				'plural_label'   => 'Invalid Categories',
				'singular_label' => 'Invalid Category',
				// Missing required post_type
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$this->taxonomy->register_taxonomy_on_init();

		// Verify cache contains only valid taxonomy
		$cached_taxonomies = wp_cache_get( 'registered_taxonomies', 'custom_ptt_taxonomies' );
		
		// Should only contain the valid taxonomy
		$this->assertArrayHasKey( 'valid_category', $cached_taxonomies );
		$this->assertArrayNotHasKey( 'invalid_category', $cached_taxonomies );
		
		// Verify only valid taxonomy was registered
		$this->assertTrue( taxonomy_exists( 'valid_category' ) );
		$this->assertFalse( taxonomy_exists( 'invalid_category' ) );
	}

	/**
	 * Test taxonomy registration with custom arguments.
	 *
	 * @since 0.2.1
	 */
	public function test_taxonomy_registration_with_custom_args(): void {
		$taxonomy_data = array(
			'custom_category' => array(
				'plural_label'   => 'Custom Categories',
				'singular_label' => 'Custom Category',
				'post_type'      => array( 'post', 'page' ),
				'hierarchical'   => true,
				'show_in_menu'   => false,
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$this->taxonomy->register_taxonomy_on_init();

		$taxonomy_object = get_taxonomy( 'custom_category' );
		$this->assertTrue( $taxonomy_object->hierarchical );
		$this->assertFalse( $taxonomy_object->show_in_menu );
		
		// Verify attached to multiple post types
		$this->assertContains( 'post', $taxonomy_object->object_type );
		$this->assertContains( 'page', $taxonomy_object->object_type );
	}

	/**
	 * Test wp_parse_args integration for default values.
	 *
	 * @since 0.2.1
	 */
	public function test_wp_parse_args_default_values(): void {
		$taxonomy_data = array(
			'default_category' => array(
				'plural_label'   => 'Default Categories',
				'singular_label' => 'Default Category',
				'post_type'      => array( 'post' ),
				// No additional args provided
			),
		);

		update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomy_data );

		$this->taxonomy->register_taxonomy_on_init();

		$taxonomy_object = get_taxonomy( 'default_category' );
		
		// Verify default values are applied
		$this->assertTrue( $taxonomy_object->public );
		$this->assertTrue( $taxonomy_object->show_ui );
		$this->assertTrue( $taxonomy_object->show_in_menu );
		$this->assertTrue( $taxonomy_object->show_in_nav_menus );
		$this->assertTrue( $taxonomy_object->show_in_rest );
	}
}
