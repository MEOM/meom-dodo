<?php
/**
 * Remove dashboard widgets.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Remove default dashboard widgets.
 */
function remove_dashboard_widgets() {
    /**
     * Filters whether the dashboard widget removal feature is enabled.
     *
     * Allows themes to disable this feature entirely, e.g.:
     * add_filter( 'meom_dodo_enable_dashboard_widgets_removal', '__return_false' );
     *
     * @param bool $enabled Whether the feature is enabled. Default true.
     */
    if ( ! \apply_filters( 'meom_dodo_enable_dashboard_widgets_removal', true ) ) {
        return;
    }

    /**
     * Filters the hidden dashboard widgets.
     *
     * Each item maps a meta box ID to its context ('normal' or 'side').
     *
     * @param array $removed_dashboard_widgets Default hidden dashboard widgets.
     */
    $removed_dashboard_widgets =
    \apply_filters(
        'meom_dodo_removed_dashboard_widgets',
        [
            'dashboard_right_now'   => 'normal',
            'dashboard_activity'    => 'normal',
            'dashboard_site_health' => 'normal',
            'dashboard_quick_press' => 'side',
            'dashboard_primary'     => 'side',
            'kraken_io_summary'     => 'normal',
            'php-warning-widget'    => 'normal',
        ]
    );

    foreach ( $removed_dashboard_widgets as $widget_id => $context ) {
        remove_meta_box( \esc_attr( $widget_id ), 'dashboard', \esc_attr( $context ) );
    }
}
// Priority 999 so this runs after widgets that plugins register from within
// their own wp_dashboard_setup callbacks (e.g. Kraken registers via an init
// hook, so its callback is added later than ours and would otherwise re-add
// the widget after we removed it).
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\remove_dashboard_widgets', 999 );
