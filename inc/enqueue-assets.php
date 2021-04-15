<?php
/**
 * Register needed scripts and styles.
 *
 * @package MEOM Dodo
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

    /**
     * Filters the allowed embeds.
     *
     * @since 1.0.0
     *
     * @param array $allowed_embed_variants Default allowed embeds.
     */
    $allowed_embed_variants = \apply_filters( 'meom_dodo_allowed_embed_variants', [ 'youtube' ] );

    // Data to JS.
    $data_array = [
        'allowedEmbedVariants' => $allowed_embed_variants,
    ];

    wp_localize_script( 'meom-dodo-editor-scripts', 'meomDodoData', $data_array );
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\editor_assets' );
