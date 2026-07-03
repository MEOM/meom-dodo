<?php
/**
 * Remove the Kraken.io summary panel from admin screens.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Remove the Kraken.io summary panel.
 *
 * Kraken Image Optimizer prints a ".kraken-summary--media" panel via
 * admin_notices on the Media Library, Add New Media and Plugins screens.
 * We unhook its callback so the markup is never rendered.
 */
function remove_kraken_media_panel() {
    /**
     * Filters whether the Kraken.io media panel removal is enabled.
     *
     * Allows themes to disable this feature, e.g.:
     * add_filter( 'meom_dodo_enable_kraken_media_panel_removal', '__return_false' );
     *
     * @param bool $enabled Whether the feature is enabled. Default true.
     */
    if ( ! \apply_filters( 'meom_dodo_enable_kraken_media_panel_removal', true ) ) {
        return;
    }

    // Bail if Kraken is not active or its summary object is not set up yet.
    if ( ! function_exists( 'kraken_io' ) || ! isset( kraken_io()->summary ) ) {
        return;
    }

    remove_action( 'admin_notices', [ kraken_io()->summary, 'render_media_library_panel' ] );
}
// Runs after Kraken registers the panel on its own init (priority 0) callback,
// and before admin_notices fires.
add_action( 'admin_init', __NAMESPACE__ . '\remove_kraken_media_panel' );
