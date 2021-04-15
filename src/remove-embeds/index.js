import { getBlockVariations, unregisterBlockVariation } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

domReady( () => {
    // Allow only listed embeds from array.
    // @link: https://github.com/WordPress/gutenberg/issues/27913#issuecomment-771505654
    const allowedEmbedVariants = ['youtube'];

    getBlockVariations('core/embed').forEach(variant => {
        if(!allowedEmbedVariants.includes(variant.name)) {
            unregisterBlockVariation('core/embed', variant.name);
        }
    });
} );