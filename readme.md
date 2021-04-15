# MEOM Dodo

MEOM Dodo plugin for cleaning up WordPress.

## Requirements

* PHP 7+.
* [Composer](https://getcomposer.org/) for managing PHP dependencies.

## Installation

Use Composer to install the package.

```bash
composer require meom/meom-dodo: "^1.0"
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
