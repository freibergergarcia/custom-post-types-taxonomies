<?php
/**
 * WordPress Related Plugin.
 *
 * @package   WORDPRESS_RELATED
 * @copyright Copyright (C) 2023-2023, WordPress Related - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/wordpress-related
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Related
 * Version:           1.0.0
 * Plugin URI:        https://www.github.com/freibergergarcia/wordpress-related
 * Description:       Enhance your WordPress site with related post types and taxonomies functionality provided by the WordPress Related plugin.
 * Author:            Bruno Freiberger Garcia
 * Author URI:        https://www.github.com/freibergergarcia
 * Domain Path:       /languages
 * Requires PHP:      8.0
 * Text Domain:       wordpress-related
 * License:           GNU General Public License v2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

declare( strict_types=1 );

use WordPress_Related\Plugin_Factory;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if composer autoload file exists
if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	die( 'Autoload file not found. Please run composer install.' );
}
require_once __DIR__ . '/vendor/autoload.php';

// Define plugin file
if ( ! defined( 'WORDPRESS_RELATED_FILE' ) ) {
	define( 'WORDPRESS_RELATED_FILE', __FILE__ );
}

// Register activation hook
if ( class_exists( 'WordPress_Related\Plugin' ) ) {
	register_uninstall_hook( __FILE__, [ 'WordPress_Related\Plugin', 'on_uninstall' ] );
}

/**
 * Activate Plugin
 *
 * @return void
 * @throws Exception
 */
function bootstrap_plugin(): void {

	$container_builder = new DI\ContainerBuilder();
	$container_builder->addDefinitions( __DIR__ . '/di-config.php' );
	$container = $container_builder->build();

	$plugin = Plugin_Factory::create( $container );
	$plugin->boot();
	$plugin->init();
}

try {
	bootstrap_plugin();
} catch ( Exception $e ) {

}
