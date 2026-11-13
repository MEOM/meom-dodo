<?php
/**
 * Disable the random content redirect introduced in WordPress 7.2.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Disable the random content redirect.
 *
 * WordPress 7.2 adds a /random/ endpoint which redirects to a random
 * published post (also /random/category/…/, /random/{post-type}/ etc.).
 * Returning false from the core `wp_enable_random_content_redirect` filter
 * removes the rewrite rules, hides the random base field on the permalinks
 * screen and ignores the random query var.
 *
 * The opt-out filter is evaluated when core applies its filter, so themes
 * can still re-enable the feature from functions.php.
 *
 * @see https://make.wordpress.org/core/2026/11/05/your-content-is-going-to-get-more-random/
 *
 * @param bool $enabled Whether the random content redirect is enabled.
 * @return bool Whether the random content redirect is enabled.
 */
function disable_random_content_redirect( $enabled ) {
    /**
     * Filters whether the random content redirect removal is enabled.
     *
     * Allows themes to disable this feature, e.g.:
     * add_filter( 'meom_dodo_enable_random_content_redirect_removal', '__return_false' );
     *
     * @param bool $removal_enabled Whether the feature is enabled. Default true.
     */
    if ( ! \apply_filters( 'meom_dodo_enable_random_content_redirect_removal', true ) ) {
        return $enabled;
    }

    return false;
}
add_filter( 'wp_enable_random_content_redirect', __NAMESPACE__ . '\disable_random_content_redirect' );
