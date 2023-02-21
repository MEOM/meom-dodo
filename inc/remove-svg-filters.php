<?php
/**
 * Remove SVG filters.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Remove SVG filters.
 *
 * @return void
 */
function remove_svg_filters() {
    // Allow filtering svg filters.
    if ( \apply_filters( 'meom_dodo_remove_svg_filters', true ) ) {
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
    }
}
add_action( 'init', __NAMESPACE__ . '\remove_svg_filters' );
