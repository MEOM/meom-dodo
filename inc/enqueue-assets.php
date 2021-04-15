<?php
/**
 * Register needed scripts and styles.
 *
 * @package MEOM Dodp
 */

namespace MEOM\dodo;

/**
 * Register editor assets
 */
function editor_assets() {
    // Add editor scripts.
    $script_path       = plugin_dir_path( __DIR__ ) . '/build/index.js';
    $script_asset_path = plugin_dir_path( __DIR__ ) . '/build/index.asset.php';
    $script_asset      = file_exists( $script_asset_path )
        ? require $script_asset_path
        : [ 'dependencies' => [], 'version' => filemtime( $script_path ) ];

    $script_url = plugins_url( '/build/index.js', dirname( __FILE__ ) );

    wp_enqueue_script(
        'meom-dodo-editor-scripts',
        $script_url,
        $script_asset['dependencies'],
        $script_asset['version'],
        true
    );
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\editor_assets' );
