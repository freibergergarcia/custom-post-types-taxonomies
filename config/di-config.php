<?php
/**
 * Dependency Injection Configuration for the Custom PTT Plugin.
 *
 * This file defines the services and dependencies of the Custom PTT plugin.
 *
 * @package   Custom_PTT
 * @copyright Copyright (C) 2023-2023, Custom PTT - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/custom-post-types-taxonomies
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 *
 * @since 0.0.1
 */

declare( strict_types=1 );

namespace Custom_PTT;

use function DI\create;
use function DI\get;

return array(
	'Admin_Menu'             => create( 'Custom_PTT\Admin\Admin_Menu' ),
	'Taxonomy'               => create( 'Custom_PTT\Taxonomy\Taxonomy' ),
	'Post_Type'              => create( 'Custom_PTT\Post_Type\Post_Type' ),
	'Taxonomy_Config_Loader' => create( 'Custom_PTT\Config\Config_Loader' )
		->constructor( __DIR__, 'taxonomy-config.json' ),
	'Taxonomy_Form_Page'     => create( 'Custom_PTT\Admin\Taxonomy_Form_Page' )
		->constructor( get( 'Taxonomy_Config_Loader' ) ),
	'Taxonomy_Form_Handler'  => create( 'Custom_PTT\Admin\Taxonomy_Form_Handler' ),
);
