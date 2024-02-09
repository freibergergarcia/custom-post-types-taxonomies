# Custom post types and taxonomies (Custom PTT)

The Custom PTT plugin is a simple WordPress Plugin to extend the functionality of WordPress by creating Custom Taxonomies and Custom Post Types.

![Unit Tests](https://github.com/freibergergarcia/custom-post-types-taxonomies/actions/workflows/run-phpunit.yml/badge.svg)
![PHP Code Sniffer](https://github.com/freibergergarcia/custom-post-types-taxonomies/actions/workflows/run-phpcs.yml/badge.svg)

## Features

Once the plugin is activate, it doesn't generate any code to be placced anywhere in the `theme` or `plugin`. 

**We initialize the Custom Post Types and Custom Taxonomies through WordPress hook**.

- Easily add Custom Taxonomies
- Easily add Custom Post Types
- Extend with a built-in filters and actions in case you would like to modify the default arguments

### Filters and Actions

#### Taxonomies

`custom_ptt_taxonomy_args` filter is available to modify the default arguments used when registering a taxonomy.
```php 
/**
 * Filters the arguments used when registering a taxonomy.
 *
 * @param array $args The arguments used when registering a taxonomy.
 * @param string $taxonomy_slug The taxonomy slug.
 * @param array $taxonomy_data The taxonomy data.
 * @since 0.1.0-alpha
 */
 $args = apply_filters( 'custom_ptt_taxonomy_args', $args, $taxonomy_slug, $taxonomy_data );
```

`custom_ptt_registered_taxonomies` action fires after the taxonomies are registrered
```php 
/**
* Fires after the taxonomies are registered.
*
* @param array $taxonomies The taxonomies that were registered.
* @since 0.1.0-alpha
*/
do_action( 'custom_ptt_registered_taxonomies', $taxonomies );
```

#### Post Types

`custom_ptt_post_type_args` filter is available to modify the default arguments used when registering a post type.
```php 
/**
* Filters the arguments used when registering a post type.
*
* @param array $args The arguments used when registering a post type.
 * @param string $post_type_key The post type slug.
* @param array $post_type_data The post type data.
*
* @since 0.1.0-alpha
*/
$args = apply_filters( 'custom_ptt_post_type_args', $args, $post_type_key, $post_type_data );
```

`custom_ptt_registered_post_types` action fires after the post types are registrered
```php 
/**
* Fires after the post types are registered.
*
* @param array $post_types The post types that were registered.
*
* @since 0.1.0-alpha
*/
do_action( 'custom_ptt_registered_post_types', $post_types );
```

## Installation

Composer install is all you need to get started.
```
composer install
```

## Contributing

Anyone is welcome to contribute to Custom PTT. Please follow our guidelines, which are specified on the [phpcs.xml.dist](phpcs.xml.dist) file.
PR's should be raised to the `develop` branch.

## License

This project is licensed under the GNU General Public License v2.0 - see the [LICENSE](LICENSE) file for details.
