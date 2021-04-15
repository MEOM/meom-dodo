<?php
/**
 * Remove edito settings that we don't use..
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
