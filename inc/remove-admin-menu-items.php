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
     * Check has user "Show all admin menu items" checked in their profile.
     */
    $allow_all_menu_items = get_user_option( '_meom_dodo_allow_all_menu_items', get_current_user_id() );

    if ( $allow_all_menu_items ) {
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
