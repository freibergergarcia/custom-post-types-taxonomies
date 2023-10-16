<?php
/**
 * Dependency Injection Configuration for the WordPress Related Plugin.
 *
 * This file defines the services and dependencies of the WordPress Related plugin.
 *
 * @package   WORDPRESS_RELATED
 * @copyright Copyright (C) 2023-2023, WordPress Related - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/wordpress-related
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 *
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace WordPress_Related;

use function DI\create;
use function DI\get;

return [
	'Admin_Menu'             => create( 'WordPress_Related\Admin\Admin_Menu' ),
	'Taxonomy'               => create( 'WordPress_Related\Taxonomy\Taxonomy' ),
	'Post_Type'              => create( 'WordPress_Related\Post_Type\Post_Type' ),
	'Taxonomy_Config_Loader' => create( 'WordPress_Related\Config\Config_Loader' )
		->constructor( __DIR__, 'taxonomy-config.json' ),
	'Taxonomy_Form_Page'     => create( 'WordPress_Related\Taxonomy\Taxonomy_Form_Page' )
		->constructor( get( 'Taxonomy_Config_Loader' ) ),
];
