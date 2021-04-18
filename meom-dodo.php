<?php
/**
 * Plugin name: MEOM Dodo
 * Author: MEOM
 * Author URI: https://www.meom.fi/
 * Description: Clean up WordPress.
 * License: GPL2 or later.
 * Text Domain: meom-dodo
 * Domain Path: /languages
 * Version: 1.0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load text domain.
function meom_dodo_i18n() {
    load_plugin_textdomain( 'meom-dodo', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
}
add_action( 'plugins_loaded', 'meom_dodo_i18n', 2 );

require_once dirname( __FILE__ ) . '/inc/contact-page.php';
require_once dirname( __FILE__ ) . '/inc/enqueue-assets.php';
require_once dirname( __FILE__ ) . '/inc/remove-editor-settings.php';
require_once dirname( __FILE__ ) . '/inc/user-meta.php';
