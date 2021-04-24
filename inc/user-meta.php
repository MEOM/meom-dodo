<?php
/**
 * User meta settings.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Adds allow all menu items checkbox in user profile.
 *
 * @param $user WP_User user object.
 *
 * @return void
 */
function allow_all_admin_menu_items( $user ) {
    // Get user meta for allowing all admin menu items.
    $allow_all_menu_items = get_user_option( '_meom_dodo_allow_all_menu_items', $user->ID );
    ?>
    <h2><?php esc_html_e( 'Show all admin menu items', 'meom-dodo' ); ?></h2>

    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><?php esc_html_e( 'Admin menu items', 'meom-dodo' ); ?></th>
            <td>
                <input type="checkbox" name="meom_dodo_allow_all_menu_items" id="meom_dodo_allow_all_menu_items" <?php \checked( $allow_all_menu_items ); ?>>
                <label for="meom_dodo_allow_all_menu_items"><?php esc_html_e( 'Show all admin menu items', 'meom-dodo' ); ?></label>

                <?php wp_nonce_field( 'meom_dodo_allow_all_nonce_action', 'meom_dodo_allow_all_nonce_field' ); ?>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', __NAMESPACE__ . '\allow_all_admin_menu_items' );
add_action( 'edit_user_profile', __NAMESPACE__ . '\allow_all_admin_menu_items' );

/**
 * Save in user meta.
 *
 * @param $user_id int the ID of the current user.
 *
 * @return bool true on successful update, false on failure.
 */
function save_all_admin_menu_items( $user_id ) {
    // Check user capability.
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // Verify nonce.
    if ( ! isset( $_POST['meom_dodo_allow_all_nonce_field'] )
    || ! wp_verify_nonce( $_POST['meom_dodo_allow_all_nonce_field'], 'meom_dodo_allow_all_nonce_action' )
    ) {
        return false;
    }

    // Get checkbox value.
    $allow_all_menu_items_checkbox = isset( $_POST['meom_dodo_allow_all_menu_items'] ) ? true : false;

    // This will add blog prefix in multisite.
    if ( is_multisite() ) {
        update_user_option( $user_id, '_meom_dodo_allow_all_menu_items', $allow_all_menu_items_checkbox );
    }

    // Update user meta.
    update_user_meta( $user_id, '_meom_dodo_allow_all_menu_items', $allow_all_menu_items_checkbox );
}
add_action( 'personal_options_update', __NAMESPACE__ . '\save_all_admin_menu_items' );
add_action( 'edit_user_profile_update', __NAMESPACE__ . '\save_all_admin_menu_items' );
