/**
 * * This disables the newly introduced WP Core "Stretchy" variations for paragraphs and headings.
 */
import { unregisterBlockVariation } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

domReady( () => {
    // Unregister Stretchy Paragraph
    unregisterBlockVariation( 'core/paragraph', 'stretchy-paragraph' );

    // Unregister Stretchy Heading
    unregisterBlockVariation( 'core/heading', 'stretchy-heading' );
} );
