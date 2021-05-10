<?php
/**
 * MEOM contact dashboard widget.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Add dashboard widget for MEOM contact info
 *
 * @return void
 */
function add_dashboard_contact_widget() {
    // Allow filtering contact content.
    if ( \apply_filters( 'meom_dodo_show_contact_content', true ) ) {
        wp_add_dashboard_widget( 'meom_dodo_contact_widget', esc_html__( 'Contact MEOM', 'meom-dodo' ), __NAMESPACE__ . '\contact_content', null, null, 'normal', 'high' );
    }
}
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\add_dashboard_contact_widget' );

/**
 * Contact information.
 *
 * @return void
 */
function contact_content() {
    ?>
    <p><?php printf( esc_html__( 'In case you encounter some problems on the site, you can contact MEOM support by email %s.', 'meom-dodo' ), '<a href="mailto:support@meom.fi">support@meom.fi</a>' ); ?></p> 
    <p><?php esc_html_e( 'Please provide a detailed description of the problem, including its location and the device and the browser you are using.', 'meom-dodo' ); ?></p>
    <p><?php printf( esc_html__( 'In case you need some help with content editing, marketing, SEO-related tasks or something similar, you can take contact to the same address %s.', 'meom-dodo' ), '<a href="mailto:support@meom.fi">support@meom.fi</a>' ); ?></p>
    <p><?php printf( esc_html__( 'In case you wish to have more features, a new look or any further development for the site, please contact %s.', 'meom-dodo' ), '<a href="mailto:sales@meom.fi">sales@meom.fi</a>' ); ?></p>
    <?php
}
