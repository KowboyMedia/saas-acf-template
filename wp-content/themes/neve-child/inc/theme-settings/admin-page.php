<?php
/**
 * Kowboy Theme Settings admin page (Appearance → Kowboy Settings).
 *
 * One-click apply of the bundled header/footer preset, export of the
 * current header/footer, import of an exported package and restore of
 * the backup taken before the last apply/import.
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !defined( 'KOWBOY_TS_PAGE_SLUG' ) ) {
    define( 'KOWBOY_TS_PAGE_SLUG', 'kowboy-theme-settings' );
}

if ( !defined( 'KOWBOY_TS_CAPABILITY' ) ) {
    define( 'KOWBOY_TS_CAPABILITY', 'edit_theme_options' );
}

if ( !function_exists( 'kowboy_ts_register_menu' ) ) :
    function kowboy_ts_register_menu() {
        add_theme_page(
            __( 'Kowboy Theme Settings', 'neve-child' ),
            __( 'Kowboy Settings', 'neve-child' ),
            KOWBOY_TS_CAPABILITY,
            KOWBOY_TS_PAGE_SLUG,
            'kowboy_ts_render_page'
        );
    }
endif;
add_action( 'admin_menu', 'kowboy_ts_register_menu' );

if ( !function_exists( 'kowboy_ts_page_url' ) ) :
    function kowboy_ts_page_url( $args = array() ) {
        $url = admin_url( 'themes.php?page=' . KOWBOY_TS_PAGE_SLUG );
        return empty( $args ) ? $url : add_query_arg( $args, $url );
    }
endif;

if ( !function_exists( 'kowboy_ts_redirect' ) ) :
    /**
     * Store a notice for the next page load and go back to the settings page.
     */
    function kowboy_ts_redirect( $type, $message ) {
        set_transient( 'kowboy_ts_notice_' . get_current_user_id(), array(
            'type'    => $type,
            'message' => $message,
        ), 60 );

        wp_safe_redirect( kowboy_ts_page_url() );
        exit;
    }
endif;

if ( !function_exists( 'kowboy_ts_import_options_from_request' ) ) :
    function kowboy_ts_import_options_from_request() {
        return array(
            'theme_mods' => !empty( $_POST['kowboy_hf_theme_mods'] ),
            'widgets'    => !empty( $_POST['kowboy_hf_widgets'] ),
            'custom_css' => !empty( $_POST['kowboy_hf_custom_css'] ),
            'backup'     => true,
        );
    }
endif;

if ( !function_exists( 'kowboy_ts_report_message' ) ) :
    function kowboy_ts_report_message( $intro, $report ) {
        $parts = array();
        /* translators: %d: number of settings */
        $parts[] = sprintf( _n( '%d builder setting', '%d builder settings', (int) $report['theme_mods'], 'neve-child' ), (int) $report['theme_mods'] );
        /* translators: %d: number of widgets */
        $parts[] = sprintf( _n( '%d footer widget', '%d footer widgets', (int) $report['widgets'], 'neve-child' ), (int) $report['widgets'] );
        $parts[] = $report['custom_css'] ? __( 'Additional CSS updated', 'neve-child' ) : __( 'Additional CSS unchanged', 'neve-child' );

        return $intro . ' ' . implode( ', ', $parts ) . '.';
    }
endif;

/* -------------------------------------------------------------------------
 * Handlers
 * ---------------------------------------------------------------------- */

if ( !function_exists( 'kowboy_ts_handle_apply_preset' ) ) :
    function kowboy_ts_handle_apply_preset() {
        if ( !current_user_can( KOWBOY_TS_CAPABILITY ) ) {
            wp_die( esc_html__( 'You are not allowed to do that.', 'neve-child' ) );
        }
        check_admin_referer( 'kowboy_hf_apply_preset' );

        $preset = kowboy_hf_load_preset();
        if ( is_wp_error( $preset ) ) {
            kowboy_ts_redirect( 'error', $preset->get_error_message() );
        }

        $report = kowboy_hf_import( $preset, kowboy_ts_import_options_from_request() );
        if ( is_wp_error( $report ) ) {
            kowboy_ts_redirect( 'error', $report->get_error_message() );
        }

        kowboy_ts_redirect( 'success', kowboy_ts_report_message( __( 'Kowboy header & footer applied:', 'neve-child' ), $report ) );
    }
endif;
add_action( 'admin_post_kowboy_hf_apply_preset', 'kowboy_ts_handle_apply_preset' );

if ( !function_exists( 'kowboy_ts_handle_export' ) ) :
    function kowboy_ts_handle_export() {
        if ( !current_user_can( KOWBOY_TS_CAPABILITY ) ) {
            wp_die( esc_html__( 'You are not allowed to do that.', 'neve-child' ) );
        }
        check_admin_referer( 'kowboy_hf_export' );

        $package  = kowboy_hf_export();
        $host     = wp_parse_url( home_url(), PHP_URL_HOST );
        $filename = 'kowboy-header-footer-' . sanitize_file_name( (string) $host ) . '-' . gmdate( 'Ymd-His' ) . '.json';

        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        echo wp_json_encode( $package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        exit;
    }
endif;
add_action( 'admin_post_kowboy_hf_export', 'kowboy_ts_handle_export' );

if ( !function_exists( 'kowboy_ts_handle_import_file' ) ) :
    function kowboy_ts_handle_import_file() {
        if ( !current_user_can( KOWBOY_TS_CAPABILITY ) ) {
            wp_die( esc_html__( 'You are not allowed to do that.', 'neve-child' ) );
        }
        check_admin_referer( 'kowboy_hf_import_file' );

        if ( empty( $_FILES['kowboy_hf_file'] ) || !is_array( $_FILES['kowboy_hf_file'] ) ) {
            kowboy_ts_redirect( 'error', __( 'Choose a JSON file to import.', 'neve-child' ) );
        }

        $file = $_FILES['kowboy_hf_file'];

        if ( !empty( $file['error'] ) || empty( $file['tmp_name'] ) || !is_uploaded_file( $file['tmp_name'] ) ) {
            kowboy_ts_redirect( 'error', __( 'The upload failed. Try again.', 'neve-child' ) );
        }

        if ( (int) $file['size'] > 2 * MB_IN_BYTES ) {
            kowboy_ts_redirect( 'error', __( 'The file is larger than 2 MB, which is not a header/footer package.', 'neve-child' ) );
        }

        $raw  = (string) file_get_contents( $file['tmp_name'] );
        $data = json_decode( $raw, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            kowboy_ts_redirect( 'error', __( 'The file is not valid JSON.', 'neve-child' ) );
        }

        $report = kowboy_hf_import( $data, kowboy_ts_import_options_from_request() );
        if ( is_wp_error( $report ) ) {
            kowboy_ts_redirect( 'error', $report->get_error_message() );
        }

        $source = !empty( $data['source'] ) ? esc_url_raw( (string) $data['source'] ) : '';
        $intro  = $source
            /* translators: %s: source site url */
            ? sprintf( __( 'Header & footer imported from %s:', 'neve-child' ), $source )
            : __( 'Header & footer imported:', 'neve-child' );

        kowboy_ts_redirect( 'success', kowboy_ts_report_message( $intro, $report ) );
    }
endif;
add_action( 'admin_post_kowboy_hf_import_file', 'kowboy_ts_handle_import_file' );

if ( !function_exists( 'kowboy_ts_handle_restore' ) ) :
    function kowboy_ts_handle_restore() {
        if ( !current_user_can( KOWBOY_TS_CAPABILITY ) ) {
            wp_die( esc_html__( 'You are not allowed to do that.', 'neve-child' ) );
        }
        check_admin_referer( 'kowboy_hf_restore' );

        $report = kowboy_hf_restore_backup();
        if ( is_wp_error( $report ) ) {
            kowboy_ts_redirect( 'error', $report->get_error_message() );
        }

        kowboy_ts_redirect( 'success', kowboy_ts_report_message( __( 'Previous header & footer restored:', 'neve-child' ), $report ) );
    }
endif;
add_action( 'admin_post_kowboy_hf_restore', 'kowboy_ts_handle_restore' );

/* -------------------------------------------------------------------------
 * Page
 * ---------------------------------------------------------------------- */

if ( !function_exists( 'kowboy_ts_render_include_fields' ) ) :
    function kowboy_ts_render_include_fields( $prefix ) {
        ?>
        <fieldset class="kowboy-ts-includes">
            <legend class="screen-reader-text"><?php esc_html_e( 'What to apply', 'neve-child' ); ?></legend>
            <label><input type="checkbox" name="kowboy_hf_theme_mods" value="1" checked> <?php esc_html_e( 'Header & footer builder settings (layout, colours, sizes, menu style)', 'neve-child' ); ?></label><br>
            <label><input type="checkbox" name="kowboy_hf_widgets" value="1" checked> <?php esc_html_e( 'Footer widgets (lead form, logo, copyright, footer menu, contact details)', 'neve-child' ); ?></label><br>
            <label><input type="checkbox" name="kowboy_hf_custom_css" value="1" checked> <?php esc_html_e( 'Header & footer Additional CSS', 'neve-child' ); ?></label>
        </fieldset>
        <?php
    }
endif;

if ( !function_exists( 'kowboy_ts_render_page' ) ) :
    function kowboy_ts_render_page() {
        if ( !current_user_can( KOWBOY_TS_CAPABILITY ) ) {
            return;
        }

        $notice_key = 'kowboy_ts_notice_' . get_current_user_id();
        $notice     = get_transient( $notice_key );
        if ( $notice ) {
            delete_transient( $notice_key );
        }

        $preset       = kowboy_hf_load_preset();
        $preset_error = is_wp_error( $preset ) ? $preset->get_error_message() : '';
        $backup       = kowboy_hf_get_backup();
        ?>
        <div class="wrap kowboy-ts">
            <h1><?php esc_html_e( 'Kowboy Theme Settings', 'neve-child' ); ?></h1>

            <?php if ( is_array( $notice ) && !empty( $notice['message'] ) ) : ?>
                <div class="notice notice-<?php echo esc_attr( $notice['type'] === 'error' ? 'error' : 'success' ); ?> is-dismissible">
                    <p><?php echo esc_html( $notice['message'] ); ?></p>
                </div>
            <?php endif; ?>

            <div class="kowboy-ts-grid">

                <div class="card">
                    <h2><?php esc_html_e( 'Kowboy header & footer', 'neve-child' ); ?></h2>
                    <p><?php esc_html_e( 'Rebuild the standard Kowboy header and footer on this site: transparent header with logo left and menu right, hamburger menu on mobile, lead form strip above a three column footer with logo, footer menu and contact details.', 'neve-child' ); ?></p>

                    <?php if ( $preset_error ) : ?>
                        <p class="kowboy-ts-error"><?php echo esc_html( $preset_error ); ?></p>
                    <?php else : ?>
                        <?php if ( !empty( $preset['source'] ) ) : ?>
                            <p class="description">
                                <?php
                                /* translators: 1: source site, 2: date */
                                echo esc_html( sprintf( __( 'Preset captured from %1$s on %2$s.', 'neve-child' ), (string) $preset['source'], !empty( $preset['generated_at'] ) ? substr( (string) $preset['generated_at'], 0, 10 ) : '' ) );
                                ?>
                            </p>
                        <?php endif; ?>
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'This replaces the current header and footer settings. A backup is taken first so you can restore. Continue?', 'neve-child' ) ); ?>');">
                            <input type="hidden" name="action" value="kowboy_hf_apply_preset">
                            <?php wp_nonce_field( 'kowboy_hf_apply_preset' ); ?>
                            <?php kowboy_ts_render_include_fields( 'preset' ); ?>
                            <p>
                                <button type="submit" class="button button-primary button-hero"><?php esc_html_e( 'Apply Kowboy header & footer', 'neve-child' ); ?></button>
                            </p>
                        </form>
                        <p class="description">
                            <?php esc_html_e( 'The site logo, menus and admin email are taken from this site. Edit the address in Appearance → Widgets → Footer Three after applying.', 'neve-child' ); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ( $backup ) : ?>
                <div class="card">
                    <h2><?php esc_html_e( 'Restore previous header & footer', 'neve-child' ); ?></h2>
                    <p>
                        <?php
                        /* translators: %s: date time */
                        echo esc_html( sprintf( __( 'A backup was taken on %s before the last apply/import.', 'neve-child' ), (string) $backup['backup_created_at'] ) );
                        ?>
                    </p>
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Restore the header and footer from the backup? The current settings will be replaced.', 'neve-child' ) ); ?>');">
                        <input type="hidden" name="action" value="kowboy_hf_restore">
                        <?php wp_nonce_field( 'kowboy_hf_restore' ); ?>
                        <p><button type="submit" class="button"><?php esc_html_e( 'Restore backup', 'neve-child' ); ?></button></p>
                    </form>
                </div>
                <?php endif; ?>

                <div class="card">
                    <h2><?php esc_html_e( 'Export this site’s header & footer', 'neve-child' ); ?></h2>
                    <p><?php esc_html_e( 'Download the current header/footer builder settings, footer widgets and Additional CSS as a JSON file. Import it on another site running this child theme, or replace the bundled preset file in the theme with it.', 'neve-child' ); ?></p>
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="kowboy_hf_export">
                        <?php wp_nonce_field( 'kowboy_hf_export' ); ?>
                        <p><button type="submit" class="button button-secondary"><?php esc_html_e( 'Download JSON', 'neve-child' ); ?></button></p>
                    </form>
                    <p class="description"><code>inc/theme-settings/presets/kowboy-header-footer.json</code></p>
                </div>

                <div class="card">
                    <h2><?php esc_html_e( 'Import a header & footer file', 'neve-child' ); ?></h2>
                    <p><?php esc_html_e( 'Upload a JSON file exported from another site with Kowboy Theme Settings.', 'neve-child' ); ?></p>
                    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'This replaces the current header and footer settings. A backup is taken first so you can restore. Continue?', 'neve-child' ) ); ?>');">
                        <input type="hidden" name="action" value="kowboy_hf_import_file">
                        <?php wp_nonce_field( 'kowboy_hf_import_file' ); ?>
                        <p><input type="file" name="kowboy_hf_file" accept=".json,application/json" required></p>
                        <?php kowboy_ts_render_include_fields( 'import' ); ?>
                        <p><button type="submit" class="button button-secondary"><?php esc_html_e( 'Import file', 'neve-child' ); ?></button></p>
                    </form>
                </div>

            </div>

            <p>
                <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hfg_header' ) ); ?>"><?php esc_html_e( 'Open the header builder', 'neve-child' ); ?></a> ·
                <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=hfg_footer' ) ); ?>"><?php esc_html_e( 'Open the footer builder', 'neve-child' ); ?></a> ·
                <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Footer widgets', 'neve-child' ); ?></a>
            </p>
        </div>
        <style>
            .kowboy-ts .kowboy-ts-grid { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 16px; }
            .kowboy-ts .card { flex: 1 1 380px; max-width: 560px; margin-top: 0; }
            .kowboy-ts .kowboy-ts-includes { margin: 12px 0; }
            .kowboy-ts .kowboy-ts-includes label { display: inline-block; margin-bottom: 4px; }
            .kowboy-ts .kowboy-ts-error { color: #b32d2e; }
        </style>
        <?php
    }
endif;
