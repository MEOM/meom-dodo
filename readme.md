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

### meom_dodo_disable_comments

All commenting functionality is disabled by default (UI, endpoints, admin pages, and post type support). You can re-enable comments with the filter `meom_dodo_disable_comments`.

Example usage:

```php
add_filter( 'meom_dodo_disable_comments', '__return_false' );
```

### meom_dodo_enable_dashboard_widgets_removal

Default dashboard widgets are removed by default. You can disable this feature entirely with the filter `meom_dodo_enable_dashboard_widgets_removal`.

Example usage:

```php
add_filter( 'meom_dodo_enable_dashboard_widgets_removal', '__return_false' );
```

### meom_dodo_removed_dashboard_widgets

By default some dashboard widgets are removed, including the third-party Kraken.io and Seravo PHP warning widgets. Each item maps a meta box ID to its context (`normal` or `side`). You can modify the list of removed dashboard widgets with the filter `meom_dodo_removed_dashboard_widgets`.

```php
/**
 * Determine which dashboard widgets are removed.
 *
 * @param array  $removed_dashboard_widgets List of removed dashboard widgets ( id => context ).
 * @return array $removed_dashboard_widgets Modified array of removed dashboard widgets.
 */
function prefix_removed_dashboard_widgets( $removed_dashboard_widgets ) {
    $removed_dashboard_widgets = [
        'dashboard_right_now'   => 'normal',
        'dashboard_activity'    => 'normal',
        'dashboard_site_health' => 'normal',
        'dashboard_quick_press' => 'side',
        'dashboard_primary'     => 'side',
    ];

    return $removed_dashboard_widgets;
}
add_filter( 'meom_dodo_removed_dashboard_widgets', 'prefix_removed_dashboard_widgets' );
```

### meom_dodo_enable_kraken_media_panel_removal

The Kraken.io summary panel (`.kraken-summary--media`) that the Kraken Image Optimizer prints on the Media Library, Add New Media and Plugins screens is removed by default. You can disable this feature with the filter `meom_dodo_enable_kraken_media_panel_removal`.

Example usage:

```php
add_filter( 'meom_dodo_enable_kraken_media_panel_removal', '__return_false' );
```

### meom_dodo_enable_random_content_redirect_removal

The random content redirect introduced in WordPress 7.2 (`/random/` endpoint) is disabled by default. You can re-enable it with the filter `meom_dodo_enable_random_content_redirect_removal`.

Example usage:

```php
add_filter( 'meom_dodo_enable_random_content_redirect_removal', '__return_false' );
```

Note! Re-save permalinks (or run `wp rewrite flush`) after changing this, so the rewrite rules are regenerated.

### meom_dodo_enable_llms_txt

Site admins can manage the `/llms.txt` content in Markdown under **Tools > llms.txt**. In multisite every sub-site has its own content. Empty content returns the normal 404. Disable the feature with the filter `meom_dodo_enable_llms_txt`.

Example usage:

```php
add_filter( 'meom_dodo_enable_llms_txt', '__return_false' );
```

Note! A physical `htdocs/llms.txt` file or a `/llms.txt` rule in `nginx/*.conf` (e.g. `llms.conf` or `php.conf`) is served by nginx before WordPress, so it always wins. The admin page shows a warning in that case. To move an existing file to the admin, run in production (with `--url=<domain>` per sub-site in multisite):

```bash
wp option update meom_llms_txt "$(cat htdocs/llms.txt)" --autoload=no
```

Check the result with `curl`, and only then remove the file, `htdocs/llms/` and the `/llms.txt` nginx rule from git.

If llms.txt is turned on in Yoast SEO, Yoast writes its own `llms.txt` file to the site root and rewrites it weekly, so it wins too and comes back if only the file is deleted. The admin page shows a warning with a link to the Yoast settings. Turn the feature off in Yoast SEO, which also removes Yoast's file.

#### Who can manage llms.txt

The page requires the capability `meom_dodo_manage_llms_txt`. Everyone with `manage_options` (administrators) has it automatically. Give it to other roles in the theme, e.g. client admins (editors) with [MEOM Roles](https://github.com/MEOM/meom-roles):

```php
add_filter( 'meom_roles_caps', function ( array $caps ): array {
    $caps['editor']['meom_dodo_manage_llms_txt'] = true;

    return $caps;
} );
```

Without MEOM Roles, grant it with a `user_has_cap` filter or `add_cap()`.
