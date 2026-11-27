<?php
/**
 * Manage llms.txt content from the admin (Tools > llms.txt).
 *
 * The content is stored per site (also per sub-site in multisite) in the
 * `meom_llms_txt` option and served at /llms.txt as plain text.
 *
 * A physical htdocs/llms.txt file or an nginx rule always wins, because nginx
 * serves them before WordPress is loaded. The admin page warns about this.
 *
 * @package MEOM Dodo
 */

namespace MEOM\Dodo;

const LLMS_TXT_OPTION = 'meom_llms_txt';

/**
 * Capability required to manage llms.txt.
 *
 * Users with manage_options get it automatically. Themes give it to other
 * roles, e.g. with MEOM Roles:
 * $caps['editor']['meom_dodo_manage_llms_txt'] = true;
 */
const LLMS_TXT_CAPABILITY = 'meom_dodo_manage_llms_txt';

/**
 * Whether the llms.txt feature is enabled.
 *
 * Evaluated inside hooks so that themes can still disable it from functions.php.
 *
 * @return bool
 */
function llms_txt_enabled() {
    /**
     * Filters whether the llms.txt feature is enabled.
     *
     * Allows themes to disable this feature, e.g.:
     * add_filter( 'meom_dodo_enable_llms_txt', '__return_false' );
     *
     * @param bool $enabled Whether the feature is enabled. Default true.
     */
    return (bool) \apply_filters( 'meom_dodo_enable_llms_txt', true );
}

/**
 * Give the llms.txt capability to everyone who has manage_options.
 *
 * @param array $allcaps The user's capabilities.
 * @return array
 */
function grant_llms_txt_capability( $allcaps ) {
    if ( is_array( $allcaps ) && ! empty( $allcaps['manage_options'] ) ) {
        $allcaps[ LLMS_TXT_CAPABILITY ] = true;
    }

    return $allcaps;
}
\add_filter( 'user_has_cap', __NAMESPACE__ . '\grant_llms_txt_capability' );

/**
 * Serve /llms.txt from the option.
 *
 * Empty content falls through to the normal WordPress 404.
 */
function serve_llms_txt() {
    if ( ! llms_txt_enabled() || empty( $_SERVER['REQUEST_URI'] ) ) {
        return;
    }

    $request_path = (string) \wp_parse_url( \wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $home_path    = (string) \wp_parse_url( \home_url( '/' ), PHP_URL_PATH );

    if ( \trailingslashit( $home_path ) . 'llms.txt' !== $request_path ) {
        return;
    }

    $content = (string) \get_option( LLMS_TXT_OPTION, '' );

    if ( '' === trim( $content ) ) {
        return;
    }

    \status_header( 200 );
    header( 'Content-Type: text/plain; charset=utf-8' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'Cache-Control: public, max-age=300' );
    echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Served as text/plain with nosniff.
    exit;
}
add_action( 'parse_request', __NAMESPACE__ . '\serve_llms_txt', 0 );

/**
 * Add Tools > llms.txt page.
 */
function add_llms_txt_page() {
    if ( ! llms_txt_enabled() ) {
        return;
    }

    \add_management_page(
        'llms.txt',
        'llms.txt',
        LLMS_TXT_CAPABILITY,
        'meom-llms-txt',
        __NAMESPACE__ . '\render_llms_txt_page'
    );
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_llms_txt_page' );

/**
 * Save the llms.txt content.
 */
function save_llms_txt() {
    if ( ! llms_txt_enabled() || ! \current_user_can( LLMS_TXT_CAPABILITY ) ) {
        \wp_die( \esc_html__( 'You are not allowed to do this.', 'meom-dodo' ), 403 );
    }

    \check_admin_referer( 'meom_llms_txt_save' );

    // Raw markdown: no kses, the content is only ever served as text/plain.
    $content = isset( $_POST['meom_llms_txt'] ) ? (string) \wp_unslash( $_POST['meom_llms_txt'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $content = str_replace( [ "\r\n", "\r" ], "\n", $content );

    // Not autoloaded: only needed on /llms.txt and the admin page.
    \update_option( LLMS_TXT_OPTION, $content, false );

    \wp_safe_redirect( \add_query_arg( 'updated', '1', \admin_url( 'tools.php?page=meom-llms-txt' ) ) );
    exit;
}
add_action( 'admin_post_meom_llms_txt_save', __NAMESPACE__ . '\save_llms_txt' );

/**
 * Whether nginx serves /llms.txt before WordPress.
 *
 * Checks for a physical htdocs/llms.txt and an active /llms.txt rule
 * in the project's nginx/*.conf files (Seravo layout).
 *
 * @param string $doc_root Web root without trailing slash.
 * @return bool
 */
function llms_txt_served_by_code( $doc_root ) {
    if ( file_exists( $doc_root . '/llms.txt' ) ) {
        return true;
    }

    // An htdocs/llms/ folder is served only through an nginx rule, checked below.

    foreach ( (array) glob( dirname( $doc_root ) . '/nginx/*.conf' ) as $conf ) {
        // Only active (non-commented) location rules count.
        if ( is_readable( $conf ) && preg_match( '/^\s*location\s[^#\n]*\/llms\\\\?\.txt/m', (string) file_get_contents( $conf ) ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            return true;
        }
    }

    return false;
}

/**
 * Whether Yoast SEO's own llms.txt feature is enabled.
 *
 * Yoast writes a physical llms.txt to the site root and rewrites it weekly,
 * so it wins over the content saved here and comes back if only the file
 * is deleted. Turning the feature off in Yoast removes the file.
 *
 * @return bool
 */
function yoast_llms_txt_enabled() {
    $wpseo = \get_option( 'wpseo' );

    return is_array( $wpseo ) && ! empty( $wpseo['enable_llms_txt'] );
}

/**
 * Render Tools > llms.txt page.
 */
function render_llms_txt_page() {
    $content  = (string) \get_option( LLMS_TXT_OPTION, '' );
    $doc_root = isset( $_SERVER['DOCUMENT_ROOT'] ) ? \untrailingslashit( (string) \wp_unslash( $_SERVER['DOCUMENT_ROOT'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $overridden = $doc_root && llms_txt_served_by_code( $doc_root );
    $public_url = \home_url( '/llms.txt' );
    ?>
    <div class="wrap">
        <h1>llms.txt</h1>

        <?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
            <div class="notice notice-success is-dismissible"><p><?php \esc_html_e( 'llms.txt saved.', 'meom-dodo' ); ?></p></div>
        <?php endif; ?>

        <?php if ( yoast_llms_txt_enabled() ) : ?>
            <div class="notice notice-warning">
                <p>
                    <?php \esc_html_e( 'llms.txt is turned on in Yoast SEO, so its file is shown instead of the content saved here. Turn it off in the Yoast SEO settings, and Yoast removes its file.', 'meom-dodo' ); ?>
                    <?php if ( \current_user_can( 'wpseo_manage_options' ) ) : ?>
                        <a href="<?php echo \esc_url( \admin_url( 'admin.php?page=wpseo_page_settings#/site-features#card-wpseo-enable_llms_txt' ) ); ?>"><?php \esc_html_e( 'Yoast SEO settings', 'meom-dodo' ); ?></a>
                    <?php endif; ?>
                </p>
            </div>
        <?php elseif ( $overridden ) : ?>
            <div class="notice notice-warning">
                <p><?php \esc_html_e( 'This site serves llms.txt from a file in the code. Changes saved here are not visible until MEOM removes the old file.', 'meom-dodo' ); ?></p>
            </div>
        <?php endif; ?>

        <p>
            <?php \esc_html_e( 'Add the llms.txt content in Markdown format. Leave empty to disable.', 'meom-dodo' ); ?>
            <a href="https://llmstxt.org/" target="_blank" rel="noopener noreferrer"><?php \esc_html_e( 'Format guide', 'meom-dodo' ); ?></a>
        </p>
        <p>
            <?php \esc_html_e( 'Public address:', 'meom-dodo' ); ?>
            <a href="<?php echo \esc_url( $public_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo \esc_html( $public_url ); ?></a>
        </p>

        <form method="post" action="<?php echo \esc_url( \admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="meom_llms_txt_save">
            <?php \wp_nonce_field( 'meom_llms_txt_save' ); ?>
            <label for="meom-llms-txt" class="screen-reader-text">llms.txt</label>
            <textarea id="meom-llms-txt" name="meom_llms_txt" class="large-text code" rows="30" spellcheck="false"><?php echo \esc_textarea( $content ); ?></textarea>
            <?php \submit_button(); ?>
        </form>
    </div>
    <?php
}
