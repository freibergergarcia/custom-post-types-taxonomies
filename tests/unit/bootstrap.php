<?php

if ( false === getenv( 'WP_TESTS_DIR' ) ) {
	exit( 'WP_TESTS_DIR environment variable is not set. Please set it to the root of your WordPress installation.' );
}

define( 'TESTS_PLUGIN_DIR', dirname( __DIR__, 2 ) );

if ( ! file_exists( TESTS_PLUGIN_DIR . '/vendor/autoload.php' ) ) {
	exit( 'The vendor directory is missing. Please run composer install.' );
}

require TESTS_PLUGIN_DIR . '/vendor/autoload.php';

$_test_root = getenv( 'WP_TESTS_DIR' );

if ( ! file_exists( $_test_root . '/includes/bootstrap.php' ) ) {
	exit( 'Could not load /includes/bootstrap.php' );
}

require $_test_root . '/includes/bootstrap.php';
