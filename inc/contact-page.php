<?php
/**
 * MEOM contact and settings page.
 *
 * @package MEOM Dodo
 */

namespace MEOM\dodo;

/**
 * Create the contact page.
 *
 * @return void
 */
function add_contact_admin_menu() {
    add_options_page(
        esc_html__( 'MEOM Contact', 'meom-dodo' ),
        esc_html__( 'MEOM Contact', 'meom-dodo' ),
        'manage_options',
        'meom-dodo-contact-page',
        __NAMESPACE__ . '\meom_contact_page'
    );
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_contact_admin_menu' );

/**
 * Init settings.
 *
 * @return void
 */

function contact_settings_init() {
    register_setting( 'meom_dodo_contact', 'meom_dodo_contact_settings' );

    // Register a new section in the "contact" page.
    add_settings_section(
        'meom_dodo_contact_section',
        esc_html__( 'Contact MEOM details', 'meom-dodo' ),
        __NAMESPACE__ . '\settings_section_callback',
        'meom_dodo_contact'
    );
}
add_action( 'admin_init', __NAMESPACE__ . '\contact_settings_init' );

/**
 * Render page description.
 *
 * @return void
 */
function settings_section_callback() {
    echo sprintf( esc_html__( 'Contact MEOM using email %s.', 'meom-dodo' ), '<a href="mailto:tech@meom.fi">tech@meom.fi</a>' );
}
/**
 * Render the content for the settings page.
 *
 * @return void
 */
function meom_contact_page() {
    // Show error/update messages.
    settings_errors( 'meom_dodo_contact_messages' );
    ?>
    <form action='options.php' method='post'>
        <h1><?php esc_html_e( 'Contact MEOM', 'meom-dodo' ); ?></h1>
        <?php
        settings_fields( 'meom_dodo_contact' );
        do_settings_sections( 'meom_dodo_contact' );
        ?>
    </form>
    <?php
}

/**
 * Adds expire date in user profile.
 *
 * Only user with 'edd_members_edit_user' can edit expire date.
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
                <label for="meom_dodo_allow_all_menu_items"><?php esc_html_e( 'Allow all admin menu items', 'meom-dodo' ); ?></label>

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
 * Only user with 'edd_members_edit_user' or 'manage_shop_settings' can save expire date.
 *
 * @param $user_id int the ID of the current user.
 *
 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
 */
function save_all_admin_menu_items( $user_id ) {
    // Check user cabability.
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
