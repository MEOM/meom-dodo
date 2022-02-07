<?php
/**
 * Remove editor settings that we don't use.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Remove drop cap setting from paragraphs.
 *
 * @param array $editor_settings Editor settings.
 *
 * @return mixed
 */
function remove_drop_cap( $editor_settings ) {
    // Allow filtering drop cap.
    if ( \apply_filters( 'meom_dodo_remove_drop_cap', true ) ) {
        $editor_settings['__experimentalFeatures']['defaults']['typography']['dropCap'] = false;
    }

    return $editor_settings;
}
add_filter( 'block_editor_settings_all', __NAMESPACE__ . '\remove_drop_cap' );

/**
 * Remove block directory.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/filters/editor-filters/#block-directory
 */
function block_directory() {
    // Allow filtering block directory.
    if ( \apply_filters( 'meom_dodo_remove_block_directory', true ) ) {
        remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
        remove_action( 'enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory' );
    }
}
add_action( 'admin_init', __NAMESPACE__ . '\block_directory' );
