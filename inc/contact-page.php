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
