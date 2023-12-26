# Custom Post Types and Taxonomies (Custom PTT)

The Custom PTT plugin is a simple yet efficient WordPress Plugin to extending the functionality of WordPress by creating Custom Taxonomies and Custom Post Types.

![Unit Tests](https://github.com/freibergergarcia/custom-post-types-taxonomies/actions/workflows/run-phpunit.yml/badge.svg)
![PHP Code Sniffer](https://github.com/freibergergarcia/custom-post-types-taxonomies/actions/workflows/run-phpcs.yml/badge.svg)

## Why Custom PTT?

Custom PTT is designed to provide creators and developers with a simple and efficient way to extend the built-in functionality.
No need for code knowledge, anyone should be able to install, register new Custom Post Types and Custom Taxonomies and start using them.

## Features

Once the plugin is activate, there is no need to generate any code for you to put in your `theme` or `plugin`. 

**We initialize the Custom Post Types and Custom Taxonomies for you**.

- Easily add Custom Taxonomies
- Easily add Custom Post Types
- Extend with a built-in filter in case you would like to modify the default arguments

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

## Installation

Composer install is suficient
```
composer install
```

## Contributing

Anyone is welcome to contribute to Custom PTT. Please follow our guidelines, which are specified on the [phpcs.xml.dist](phpcs.xml.dist) file.
PR's should be raised to the `develop` branch.

## License

This project is licensed under the GNU General Public License v2.0 - see the [LICENSE](LICENSE) file for details.
