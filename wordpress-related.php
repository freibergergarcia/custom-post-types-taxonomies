<?php

/**
 * WordPress Related Plugin.
 *
 * @package   WORDPRESS_RELATED
 * @copyright Copyright (C) 2023-2023, WordPress Related - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/wordpress-related
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Related
 * Version:           0.0.1
 * Plugin URI:        https://www.github.com/freibergergarcia/wordpress-related
 * Description:       WordPress Related functionalities
 * Author:            Bruno Freiberger Garcia
 * Author URI:        https://www.github.com/freibergergarcia
 * Domain Path:       /languages
 * Requires PHP:      8.0
 * Text Domain:       wordpress-related
 * License:           Apache License 2.0
 * License URI:       https://www.apache.org/licenses/LICENSE-2.0
 */

declare( strict_types=1 );

use WordPress_Related\Plugin_Factory;

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	die();
}
require_once __DIR__ . '/vendor/autoload.php';


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

	$plugin = Plugin_Factory::create();
	$plugin->register( $container );
}

try {
	bootstrap_plugin();
} catch ( Exception $e ) {

}
