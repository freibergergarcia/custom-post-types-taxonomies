<?php
/**
 * Custom PTT Plugin.
 *
 * @package   Custom_PTT
 * @copyright Copyright (C) 2023-2025, Custom PTT
 * @link      https://www.github.com/freibergergarcia/custom-post-types-taxonomies
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 *
 * @wordpress-plugin
 * @vip-compatible
 * @vip-go-compatible
 * 
 * Plugin Name:       Custom PTT
 * Version:           0.2.0
 * Plugin URI:        https://www.github.com/freibergergarcia/custom-post-types-taxonomies
 * Description:       Enhance your WordPress site with related post types and taxonomies functionality provided by the Custom PTT plugin.
 * Author:            Bruno Freiberger Garcia
 * Author URI:        https://www.github.com/freibergergarcia
 * Domain Path:       /languages
 * Requires PHP:      8.0
 * Requires WP:       5.8
 * Text Domain:       custom-post-types-taxonomies
 * License:           GNU General Public License v2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

declare( strict_types=1 );

use Custom_PTT\Admin\Notices;
use Custom_PTT\Container_Singleton;
use Custom_PTT\Plugin_Factory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	die( 'Autoload file not found. Please run composer install.' );
}
require_once __DIR__ . '/vendor/autoload.php';

if ( ! defined( 'CUSTOM_PTT_FILE' ) ) {
	define( 'CUSTOM_PTT_FILE', __FILE__ );
}

if ( ! defined( 'CUSTOM_PTT_DIR' ) ) {
	define( 'CUSTOM_PTT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CUSTOM_PTT_URL' ) ) {
	define( 'CUSTOM_PTT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'CUSTOM_PTT_TAXONOMY_OPTION_NAME' ) ) {
	define( 'CUSTOM_PTT_TAXONOMY_OPTION_NAME', 'custom_ptt_taxonomies' );
}

if ( ! defined( 'CUSTOM_PTT_POST_TYPE_OPTION_NAME' ) ) {
	define( 'CUSTOM_PTT_POST_TYPE_OPTION_NAME', 'custom_ptt_post_types' );
}

if ( ! defined( 'CUSTOM_PTT_VERSION' ) ) {
	define( 'CUSTOM_PTT_VERSION', '0.2.0' );
}

if ( ! defined( 'CUSTOM_PTT_MIN_WP_VERSION' ) ) {
	define( 'CUSTOM_PTT_MIN_WP_VERSION', '5.8' );
}

if ( class_exists( 'Custom_PTT\Plugin' ) ) {
	register_uninstall_hook( __FILE__, array( 'Custom_PTT\Plugin', 'on_uninstall' ) );
}

/**
 * Activate Plugin
 *
 * @return void
 * @throws Exception
 */
function bootstrap_plugin(): void {
	// Check WordPress version
	if ( version_compare( get_bloginfo( 'version' ), CUSTOM_PTT_MIN_WP_VERSION, '<' ) ) {
		Notices::add_error(
			sprintf(
				/* translators: %s: WordPress version */
				esc_html__( 'Custom PTT requires WordPress version %s or higher.', 'custom-post-types-taxonomies' ),
				CUSTOM_PTT_MIN_WP_VERSION
			)
		);
		return;
	}

	$container = Container_Singleton::get_instance();
	Plugin_Factory::create( $container )
		->boot();
}

bootstrap_plugin();
