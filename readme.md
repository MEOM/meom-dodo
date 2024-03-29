# MEOM Dodo

MEOM Dodo plugin for cleaning up WordPress.

![MEOM dodo logo.](assets/images/MEOM-dodo-logo.png)

## Requirements

* PHP 7+.
* [Composer](https://getcomposer.org/) for managing PHP dependencies.

## Installation

Use Composer to install the package.

```bash
composer require meom/meom-dodo
```

Or if living on the edge:

```bash
composer require meom/meom-dodo:dev-main
```

## Filters

### meom_dodo_allowed_embed_variants

Only `youtube` embed is allowed by default. You can modify the allowed array with the filter `meom_dodo_allowed_embed_variants`.

Note! This is already sitting in Kala Stack. 

Example usage:

```php
/**
 * Determine which embeds are allowed.
 * By default only youtube is allowed, defined in MEOM Dodo plugin.
 *
 * @param array  $allowed_embeds List of allowed embeds.
 * @return array $allowed_embeds Modified array of allowed embeds.
 */
function prefix_gutenberg_allowed_embeds( $allowed_embeds ) {
    $allowed_embeds = [
        'youtube',
        'vimeo',
    ];

    return $allowed_embeds;
}
add_filter( 'meom_dodo_allowed_embed_variants','prefix_gutenberg_allowed_embeds' );
```

### meom_dodo_remove_drop_cap

Allow drop cap with the filter `meom_dodo_remove_drop_cap`.

Example usage:

```php
add_filter( 'meom_dodo_remove_drop_cap', '__return_false' );
```

### meom_dodo_remove_block_directory

Allow block directory with the filter `meom_dodo_remove_block_directory`.

Example usage:

```php
add_filter( 'meom_dodo_remove_block_directory', '__return_false' );
```

### meom_dodo_remove_layout_support

Allow layout support with the filter `meom_dodo_remove_layout_support`.

Example usage:

```php
add_filter( 'meom_dodo_remove_layout_support', '__return_false' );
```

### meom_dodo_remove_svg_filters

Allow SVG filters with the filter `meom_dodo_remove_svg_filters`.

Example usage:

```php
add_filter( 'meom_dodo_remove_svg_filters', '__return_false' );
```

### meom_dodo_add_utility_classes

Remove adding utility classes with the filter `meom_dodo_add_utility_classes`.

Example usage:

```php
add_filter( 'meom_dodo_add_utility_classes', '__return_false' );
```

### meom_dodo_show_contact_content

Hide contact content admin widget with the filter `meom_dodo_show_contact_content`.

Example usage:

```php
add_filter( 'meom_dodo_show_contact_content', '__return_false' );
```

### meom_dodo_removed_admin_menu_items

By default some admin menu items are removed. You can modify the list of removed admin menu items with the filter `meom_dodo_removed_admin_menu_items`.

```php
/**
 * Determine which admin menu items are removed.
 *
 * @param array  $removed_admin_menu_items List of removed admin menu items.
 * @return array $removed_admin_menu_items Modified array of removed admin menu items.
 */
function prefix_removed_admin_menu_items( $removed_admin_menu_items ) {
    $removed_admin_menu_items = [
        'plugins.php',
        'edit.php?post_type=acf-field-group',
        'themes.php',
        'users.php',
    ];

    return $removed_admin_menu_items;
}
add_filter( 'meom_dodo_removed_admin_menu_items', 'prefix_removed_admin_menu_items' );
```
