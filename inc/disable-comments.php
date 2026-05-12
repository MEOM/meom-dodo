<?php
/**
 * Disable all commenting functionality site-wide.
 *
 * This site does not use comments. Remove comment UI, endpoints,
 * admin pages and post type support across the board.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Register all comment-disabling hooks unless opted out.
 *
 * Themes can re-enable comments by adding:
 * add_filter( 'meom_dodo_disable_comments', '__return_false' );
 *
 * @return void
 */
function disable_comments_init() {
    /**
     * Filters whether comments should be disabled site-wide.
     *
     * @param bool $disabled Whether comments are disabled. Default true.
     */
    if ( ! \apply_filters( 'meom_dodo_disable_comments', true ) ) {
        return;
    }

    add_action( 'init', __NAMESPACE__ . '\disable_comments_post_type_support' );
    add_filter( 'comments_open', __NAMESPACE__ . '\disable_comments_status', 20 );
    add_filter( 'pings_open', __NAMESPACE__ . '\disable_comments_status', 20 );
    add_filter( 'comments_array', __NAMESPACE__ . '\disable_comments_hide_existing', 10 );
    add_action( 'admin_menu', __NAMESPACE__ . '\disable_comments_admin_menu' );
    add_action( 'admin_init', __NAMESPACE__ . '\disable_comments_admin_menu_redirect' );
    add_action( 'admin_menu', __NAMESPACE__ . '\disable_comments_discussion_settings_menu' );
    add_action( 'wp_before_admin_bar_render', __NAMESPACE__ . '\disable_comments_admin_bar' );
    add_filter( 'rest_endpoints', __NAMESPACE__ . '\disable_comments_rest_api' );
    add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\disable_comments_scripts' );
    add_filter( 'feed_links_show_comments_feed', '__return_false' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\disable_comments_init' );

/**
 * Remove comment and trackback support from all post types.
 *
 * @return void
 */
function disable_comments_post_type_support() {
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
        }

        if ( post_type_supports( $post_type, 'trackbacks' ) ) {
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}

/**
 * Force comments and pings closed on the frontend.
 *
 * @return bool
 */
function disable_comments_status() {
    return false;
}

/**
 * Hide any existing comments from the frontend.
 *
 * @return array
 */
function disable_comments_hide_existing() {
    return [];
}

/**
 * Remove the Comments admin menu page.
 *
 * @return void
 */
function disable_comments_admin_menu() {
    remove_menu_page( 'edit-comments.php' );
}

/**
 * Redirect any user attempting to access the comments admin page.
 *
 * @return void
 */
function disable_comments_admin_menu_redirect() {
    global $pagenow;

    if ( 'edit-comments.php' === $pagenow || 'comment.php' === $pagenow || 'options-discussion.php' === $pagenow ) {
        wp_safe_redirect( admin_url() );
        exit;
    }
}

/**
 * Remove the Discussion settings submenu under Settings.
 *
 * @return void
 */
function disable_comments_discussion_settings_menu() {
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}

/**
 * Remove the Comments icon from the admin bar.
 *
 * @return void
 */
function disable_comments_admin_bar() {
    global $wp_admin_bar;

    if ( $wp_admin_bar instanceof \WP_Admin_Bar ) {
        $wp_admin_bar->remove_node( 'comments' );
    }
}

/**
 * Remove comment endpoints from the REST API.
 *
 * @param array $endpoints REST API endpoints.
 * @return array
 */
function disable_comments_rest_api( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/comments'] ) ) {
        unset( $endpoints['/wp/v2/comments'] );
    }

    if ( isset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] );
    }

    return $endpoints;
}

/**
 * Remove the comment-reply script so it never enqueues.
 *
 * @return void
 */
function disable_comments_scripts() {
    wp_deregister_script( 'comment-reply' );
}
