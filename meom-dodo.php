<?php
/**
 * Plugin name: MEOM Dodo
 * Author: MEOM
 * Author URI: https://www.meom.fi/
 * Description: Clean up WordPress.
 * Version: 1.0.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once dirname( __FILE__ ) . '/inc/enqueue-assets.php';
require_once dirname( __FILE__ ) . '/inc/remove-editor-settings.php';
