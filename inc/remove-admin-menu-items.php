<?php
/**
 * Remove admin menu pages.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Remove admin menu items.
 */
function remove_admin_menu_items() {
    /**
     * Filters when to show all admin menu items.
     *
     * By default show everything when not on production.
     *
     * @param array $show_all_admin_menu_items When to show all admin menu items.
     */
    $show_all_admin_menu_items =
    \apply_filters(
        'meom_dodo_show_all_admin_menu_items',
        function_exists( 'getenv' ) && getenv( 'WP_ENV' ) !== 'production'
    );

    // Bail based on when to show all admin menu items.
    if ( $show_all_admin_menu_items ) {
        return;
    }

    /**
     * Filters the hidden admin menu items.
     *
     * @param array $removed_admin_menu_items Default hidden admin menu items.
     */
    $removed_admin_menu_items =
    \apply_filters(
        'meom_dodo_removed_admin_menu_items',
        [
            'plugins.php',
            'edit.php?post_type=acf-field-group',
            'edit-comments.php',
            'themes.php',
        ]
    );

    foreach ( $removed_admin_menu_items as $removed_admin_menu_item ) {
        remove_menu_page( \esc_attr( $removed_admin_menu_item ) );
    }

    // Add nav back as top level menu item.
    add_menu_page( \esc_html__( 'Menus', 'meom-dodo' ), \esc_html__( 'Menus', 'meom-dodo' ), 'manage_options', 'nav-menus.php', '', 'dashicons-menu', 25 );
}
add_action( 'admin_menu', __NAMESPACE__ . '\remove_admin_menu_items' );
