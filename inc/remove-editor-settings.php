<?php
/**
 * Remove editor settings that we don't use.
 *
 * @package MEOM Dodo
 */

namespace MEOM\dodo;

/**
 * Remove drop cap setting from paragraphs.
 *
 * @param array $editor_settings Editor settings.
 *
 * @return mixed
 */
function remove_drop_cap( $editor_settings ) {
    $editor_settings['__experimentalFeatures']['defaults']['typography']['dropCap'] = false;

    return $editor_settings;
}
add_filter( 'block_editor_settings', __NAMESPACE__ . '\remove_drop_cap' );

/**
 * Remove block directory.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/filters/editor-filters/#block-directory
 */
function block_directory() {
    remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
    remove_action( 'enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory' );
}
add_action( 'admin_init', __NAMESPACE__ . '\block_directory' );
