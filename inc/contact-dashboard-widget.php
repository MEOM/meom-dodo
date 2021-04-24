<?php
/**
 * MEOM contact dashboard widget.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

/**
 * Add dashboard widget for MEOM contact info
 *
 * @return void
 */
function add_dashboard_contact_widget() {
    wp_add_dashboard_widget( 'meom_dodo_contact_widget', esc_html__( 'Contact MEOM', 'meom-dodo' ), __NAMESPACE__ . '\contact_content', null, null, 'normal', 'high' );
}
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\add_dashboard_contact_widget' );

/**
 * Contact information.
 *
 * @return void
 */
function contact_content() {
    ?>
    <p>Mikäli kohtaat sivustolla ongelmia, voit ottaa yhteyttä MEOMin tukeen sähköpostilla <a href="mailto:support@meom.fi">support@meom.fi</a>.</p> 
    <p>Kerrothan viestissäsi tarkan kuvauksen ongelmasta ja sen sijainnista sekä käyttämäsi selaimen ja laitteen. Katso lisää ylläpidostamme täältä LINKKI.</p>
    <P>Mikäli tarvitset apua sisällönsyöttöön, markkinointiin, SEO-asioihin tai muuhun vastaavaan, voit ottaa yhteyttä samaan osoitteeseen <a href="mailto:support@meom.fi">support@meom.fi</a>.</p>
    <p>Mikäli haluat sivustolle lisää ominaisuuksia, uutta ilmettä tai mitä vain lisäkehitystä, niin ota yhteyttä <a href="mailto:sales@meom.fi">sales@meom.fi</a>.</p>
    <?php
}
