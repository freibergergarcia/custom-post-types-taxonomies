<?php


define( 'TESTS_PLUGIN_DIR', dirname( __DIR__, 2 ) );
$_test_root = getenv( 'WP_TESTS_DIR' );

if ( false === getenv( 'WP_TESTS_DIR' ) ) {
	exit( 'WP_TESTS_DIR environment variable is not set. Please set it to the root of your WordPress installation.' );
}

if ( ! file_exists( TESTS_PLUGIN_DIR . '/vendor/autoload.php' ) ) {
	exit( 'The vendor directory is missing. Please run composer install.' );
}

if ( ! file_exists( $_test_root . '/includes/bootstrap.php' ) ) {
	exit( 'Could not load /includes/bootstrap.php' );
}

require TESTS_PLUGIN_DIR . '/vendor/autoload.php';

define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname( __DIR__, 2 ) . '/vendor/yoast/phpunit-polyfills' );

require_once $_test_root . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin(): void {
	require dirname( __DIR__, 2 ) . '/custom-post-types-taxonomies.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_test_root . '/includes/bootstrap.php';
