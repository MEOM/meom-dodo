<?php
/**
 * Plugin name: MEOM Dodo
 * Author: MEOM
 * Author URI: https://www.meom.fi/
 * Description: Clean up WordPress.
 * Version: 1.4
 * License: GPL2 or later.
 * Text Domain: meom-dodo
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load text domain.
function meom_dodo_i18n() {
    load_muplugin_textdomain( 'meom-dodo', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
}
add_action( 'plugins_loaded', 'meom_dodo_i18n', 2 );

require_once __DIR__ . '/inc/contact-dashboard-widget.php';
require_once __DIR__ . '/inc/enqueue-assets.php';
require_once __DIR__ . '/inc/remove-admin-menu-items.php';
require_once __DIR__ . '/inc/remove-editor-settings.php';
require_once __DIR__ . '/inc/remove-svg-filters.php';
require_once __DIR__ . '/inc/security.php';
require_once __DIR__ . '/inc/user-meta.php';
