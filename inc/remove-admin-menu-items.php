<?php
/**
 * Remove admin menu pages.
 *
 * @package MEOM Dodo
 */

namespace MEOM\dodo;

/**
 * Remove admin menu items.
 */
function remove_admin_menu_items() {
    /**
     * Filters the hidden admin menu items.
     *
     * @since 1.0.3
     *
     * @param array $removed_admin_menu_items Default hidden admin menu items.
     */
    $removed_admin_menu_items =
    \apply_filters(
        'meom_dodo_removed_admin_menu_items',
        [
            'plugins.php',
            'edit.php?post_type=acf-field-group',
        ]
    );

    foreach ( $removed_admin_menu_items as $removed_admin_menu_item ) {
        remove_menu_page( \esc_attr( $removed_admin_menu_item ) );
    }
}
add_action( 'admin_init', __NAMESPACE__ . '\remove_admin_menu_items' );
