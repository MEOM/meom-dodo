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

/**
 * Remove layout support, which means removing inline styles.
 *
 * @return void
 */
function remove_layout() {
    // Allow filtering layout support.
    if ( \apply_filters( 'meom_dodo_remove_layout_support', true ) ) {
        remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
    }
}
add_action( 'init', __NAMESPACE__ . '\remove_layout' );

/**
 * Renders utility classes.
 *
 * We need to generate some of the utility classes back since we removed layout support in `remove_layout` filter.
 *
 * See file wp-includes/block-supports/layout.php and `wp_render_layout_support_flag` function.
 *
 * @param string $block_content Rendered block content.
 * @param array  $block         Block object.
 * @return string Filtered block content.
 */
function render_utility_classes( $block_content, $block ) {
    // Allow returning just the block content.
    if ( ! \apply_filters( 'meom_dodo_add_utility_classes', true ) ) {
        return $block_content;
    }

    $class_names = [];

    if ( ! empty( $block['attrs']['layout']['orientation'] ) ) {
        $class_names[] = 'is-' . sanitize_title( $block['attrs']['layout']['orientation'] );
    }

    if ( ! empty( $block['attrs']['layout']['justifyContent'] ) ) {
        $class_names[] = 'is-content-justification-' . sanitize_title( $block['attrs']['layout']['justifyContent'] );
    }

    if ( ! empty( $block['attrs']['layout']['flexWrap'] ) && 'nowrap' === $block['attrs']['layout']['flexWrap'] ) {
        $class_names[] = 'is-nowrap';
    }

    // This assumes the hook only applies to blocks with a single wrapper.
    // I think this is a reasonable limitation for that particular hook.
    $content = preg_replace(
        '/' . preg_quote( 'class="', '/' ) . '/',
        'class="' . esc_attr( implode( ' ', $class_names ) ) . ' ',
        $block_content,
        1
    );

    return $content;
}
add_action( 'render_block', __NAMESPACE__ . '\render_utility_classes', 10, 2 );
