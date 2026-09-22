<?php
/**
 * Kowboy header & footer preset engine.
 *
 * Exports and imports the Neve header/footer builder configuration
 * (theme mods), the footer widget areas and the header/footer related
 * Additional CSS, so the same header and footer can be rebuilt on any
 * site that runs this child theme.
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !defined( 'KOWBOY_HF_FORMAT_VERSION' ) ) {
    define( 'KOWBOY_HF_FORMAT_VERSION', 1 );
}

if ( !defined( 'KOWBOY_HF_BACKUP_OPTION' ) ) {
    define( 'KOWBOY_HF_BACKUP_OPTION', 'kowboy_hf_backup' );
}

if ( !function_exists( 'kowboy_hf_preset_path' ) ) :
    function kowboy_hf_preset_path() {
        return get_stylesheet_directory() . '/inc/theme-settings/presets/kowboy-header-footer.json';
    }
endif;

if ( !function_exists( 'kowboy_hf_footer_sidebars' ) ) :
    /**
     * Neve footer widget areas handled by the preset.
     */
    function kowboy_hf_footer_sidebars() {
        return array(
            'footer-one-widgets',
            'footer-two-widgets',
            'footer-three-widgets',
            'footer-four-widgets',
        );
    }
endif;

if ( !function_exists( 'kowboy_hf_mod_prefixes' ) ) :
    /**
     * Theme mod prefixes that belong to the Neve header/footer builder.
     */
    function kowboy_hf_mod_prefixes() {
        return array(
            'hfg_header_layout',
            'hfg_footer_layout',
            'logo_',
            'primary-menu_',
            'secondary-menu_',
            'nav-icon_',
            'header_menu_icon_',
            'header_search',
            'header_cart_icon',
            'header_palette_switch',
            'button_base',
            'custom_html',
            'footer-menu_',
            'footer_copyright',
            'footer-one-widgets_',
            'footer-two-widgets_',
            'footer-three-widgets_',
            'footer-four-widgets_',
        );
    }
endif;

if ( !function_exists( 'kowboy_hf_mod_exact_keys' ) ) :
    /**
     * Global theme mods that the header/footer depend on.
     */
    function kowboy_hf_mod_exact_keys() {
        return array(
            'neve_migrated_builders',
            'neve_container_width',
        );
    }
endif;

if ( !function_exists( 'kowboy_hf_site_specific_keys' ) ) :
    /**
     * Mods that hold attachment or menu IDs and must never be copied between sites.
     */
    function kowboy_hf_site_specific_keys() {
        return array(
            'custom_logo',
            'logo_logo',
            'nav_menu_locations',
            'header_menu_icon_svg_menu_icon',
        );
    }
endif;

if ( !function_exists( 'kowboy_hf_is_hf_mod' ) ) :
    function kowboy_hf_is_hf_mod( $key ) {
        $key = (string) $key;

        if ( in_array( $key, kowboy_hf_site_specific_keys(), true ) ) {
            return false;
        }

        if ( in_array( $key, kowboy_hf_mod_exact_keys(), true ) ) {
            return true;
        }

        foreach ( kowboy_hf_mod_prefixes() as $prefix ) {
            if ( strpos( $key, $prefix ) === 0 ) {
                return true;
            }
        }

        return false;
    }
endif;

if ( !function_exists( 'kowboy_hf_css_markers' ) ) :
    function kowboy_hf_css_markers() {
        return array(
            'start' => '/* kowboy-header-footer:start */',
            'end'   => '/* kowboy-header-footer:end */',
        );
    }
endif;

/* -------------------------------------------------------------------------
 * Export
 * ---------------------------------------------------------------------- */

if ( !function_exists( 'kowboy_hf_export' ) ) :
    /**
     * Build the portable header/footer package for the current site.
     *
     * @return array
     */
    function kowboy_hf_export() {
        $mods     = get_theme_mods();
        $out_mods = array();

        if ( is_array( $mods ) ) {
            foreach ( $mods as $key => $value ) {
                if ( kowboy_hf_is_hf_mod( $key ) ) {
                    $out_mods[ $key ] = $value;
                }
            }
        }
        ksort( $out_mods );

        $neve = wp_get_theme( 'neve' );

        return array(
            'kowboy_hf_version' => KOWBOY_HF_FORMAT_VERSION,
            'generated_at'      => gmdate( 'c' ),
            'source'            => home_url( '/' ),
            'neve_version'      => $neve->exists() ? $neve->get( 'Version' ) : '',
            'theme_mods'        => $out_mods,
            'widgets'           => kowboy_hf_export_widgets(),
            'custom_css'        => (string) wp_get_custom_css(),
        );
    }
endif;

if ( !function_exists( 'kowboy_hf_export_widgets' ) ) :
    /**
     * Export the widgets placed in the Neve footer widget areas.
     *
     * Nav menu widgets are exported by menu location / slug instead of ID so
     * they can be resolved again on the target site.
     */
    function kowboy_hf_export_widgets() {
        $sidebars  = wp_get_sidebars_widgets();
        $locations = get_nav_menu_locations();
        $out       = array();

        foreach ( kowboy_hf_footer_sidebars() as $sidebar ) {
            $out[ $sidebar ] = array();

            if ( empty( $sidebars[ $sidebar ] ) || !is_array( $sidebars[ $sidebar ] ) ) {
                continue;
            }

            foreach ( $sidebars[ $sidebar ] as $widget_id ) {
                if ( !preg_match( '/^(.+)-(\d+)$/', (string) $widget_id, $m ) ) {
                    continue;
                }

                $base   = $m[1];
                $number = (int) $m[2];
                $stored = get_option( 'widget_' . $base );

                if ( !is_array( $stored ) || !isset( $stored[ $number ] ) || !is_array( $stored[ $number ] ) ) {
                    continue;
                }

                $entry = array(
                    'type'     => $base,
                    'settings' => $stored[ $number ],
                );

                if ( $base === 'nav_menu' && !empty( $entry['settings']['nav_menu'] ) ) {
                    $menu_id  = (int) $entry['settings']['nav_menu'];
                    $location = array_search( $menu_id, array_map( 'intval', (array) $locations ), true );
                    $menu     = wp_get_nav_menu_object( $menu_id );

                    if ( $location !== false ) {
                        $entry['menu_location'] = $location;
                    }
                    if ( $menu ) {
                        $entry['menu_slug'] = $menu->slug;
                    }
                    unset( $entry['settings']['nav_menu'] );
                }

                $out[ $sidebar ][] = $entry;
            }
        }

        return $out;
    }
endif;

/* -------------------------------------------------------------------------
 * Import
 * ---------------------------------------------------------------------- */

if ( !function_exists( 'kowboy_hf_validate_package' ) ) :
    /**
     * @param mixed $data Decoded JSON.
     * @return true|WP_Error
     */
    function kowboy_hf_validate_package( $data ) {
        if ( !is_array( $data ) ) {
            return new WP_Error( 'kowboy_hf_invalid', __( 'The file is not a valid header/footer package.', 'neve-child' ) );
        }

        if ( empty( $data['kowboy_hf_version'] ) ) {
            return new WP_Error( 'kowboy_hf_invalid', __( 'The file was not exported by Kowboy Theme Settings.', 'neve-child' ) );
        }

        if ( (int) $data['kowboy_hf_version'] > KOWBOY_HF_FORMAT_VERSION ) {
            return new WP_Error( 'kowboy_hf_version', __( 'The package was created by a newer version of the child theme. Update the theme first.', 'neve-child' ) );
        }

        if ( empty( $data['theme_mods'] ) && empty( $data['widgets'] ) && empty( $data['custom_css'] ) ) {
            return new WP_Error( 'kowboy_hf_empty', __( 'The package does not contain any header/footer settings.', 'neve-child' ) );
        }

        return true;
    }
endif;

if ( !function_exists( 'kowboy_hf_load_preset' ) ) :
    /**
     * Read the preset bundled with the child theme.
     *
     * @return array|WP_Error
     */
    function kowboy_hf_load_preset() {
        $path = kowboy_hf_preset_path();

        if ( !file_exists( $path ) || !is_readable( $path ) ) {
            return new WP_Error( 'kowboy_hf_missing', __( 'The bundled preset file is missing from the child theme.', 'neve-child' ) );
        }

        $data = json_decode( (string) file_get_contents( $path ), true );
        $ok   = kowboy_hf_validate_package( $data );

        if ( is_wp_error( $ok ) ) {
            return $ok;
        }

        return $data;
    }
endif;

if ( !function_exists( 'kowboy_hf_import' ) ) :
    /**
     * Apply a header/footer package to the current site.
     *
     * @param array $data    Package (see kowboy_hf_export()).
     * @param array $options theme_mods|widgets|custom_css|backup booleans.
     * @return array|WP_Error Report with counts.
     */
    function kowboy_hf_import( $data, $options = array() ) {
        $ok = kowboy_hf_validate_package( $data );
        if ( is_wp_error( $ok ) ) {
            return $ok;
        }

        $options = wp_parse_args( $options, array(
            'theme_mods' => true,
            'widgets'    => true,
            'custom_css' => true,
            'backup'     => true,
        ) );

        if ( $options['backup'] ) {
            kowboy_hf_store_backup();
        }

        $report = array(
            'theme_mods' => 0,
            'widgets'    => 0,
            'custom_css' => false,
        );

        if ( $options['theme_mods'] && !empty( $data['theme_mods'] ) && is_array( $data['theme_mods'] ) ) {
            foreach ( $data['theme_mods'] as $key => $value ) {
                if ( !kowboy_hf_is_hf_mod( $key ) ) {
                    continue;
                }
                set_theme_mod( $key, $value );
                $report['theme_mods']++;
            }
        }

        if ( $options['widgets'] && !empty( $data['widgets'] ) && is_array( $data['widgets'] ) ) {
            $report['widgets'] = kowboy_hf_import_widgets( $data['widgets'] );
        }

        if ( $options['custom_css'] && isset( $data['custom_css'] ) && trim( (string) $data['custom_css'] ) !== '' ) {
            $report['custom_css'] = kowboy_hf_import_custom_css( (string) $data['custom_css'] );
        }

        do_action( 'kowboy_hf_after_import', $data, $report );

        return $report;
    }
endif;

if ( !function_exists( 'kowboy_hf_import_widgets' ) ) :
    /**
     * Replace the content of the footer widget areas.
     *
     * Existing widgets are moved to "Inactive widgets" so nothing is lost.
     * Sidebars not mentioned in the package are left untouched.
     *
     * @return int Number of widgets created.
     */
    function kowboy_hf_import_widgets( $widgets ) {
        $sidebars = wp_get_sidebars_widgets();
        if ( !is_array( $sidebars ) ) {
            $sidebars = array();
        }
        if ( empty( $sidebars['wp_inactive_widgets'] ) || !is_array( $sidebars['wp_inactive_widgets'] ) ) {
            $sidebars['wp_inactive_widgets'] = array();
        }

        $count = 0;

        foreach ( kowboy_hf_footer_sidebars() as $sidebar ) {
            if ( !array_key_exists( $sidebar, $widgets ) ) {
                continue;
            }

            if ( !empty( $sidebars[ $sidebar ] ) && is_array( $sidebars[ $sidebar ] ) ) {
                $sidebars['wp_inactive_widgets'] = array_values( array_unique( array_merge(
                    $sidebars['wp_inactive_widgets'],
                    $sidebars[ $sidebar ]
                ) ) );
            }
            $sidebars[ $sidebar ] = array();

            foreach ( (array) $widgets[ $sidebar ] as $entry ) {
                if ( !is_array( $entry ) ) {
                    continue;
                }
                $widget_id = kowboy_hf_create_widget( $entry );
                if ( $widget_id ) {
                    $sidebars[ $sidebar ][] = $widget_id;
                    $count++;
                }
            }
        }

        wp_set_sidebars_widgets( $sidebars );

        return $count;
    }
endif;

if ( !function_exists( 'kowboy_hf_create_widget' ) ) :
    /**
     * Store a widget instance and return its widget id (e.g. block-7).
     *
     * @return string|false
     */
    function kowboy_hf_create_widget( $entry ) {
        $base = isset( $entry['type'] ) ? sanitize_key( $entry['type'] ) : '';
        if ( $base === '' ) {
            return false;
        }

        $settings = ( isset( $entry['settings'] ) && is_array( $entry['settings'] ) ) ? $entry['settings'] : array();

        if ( $base === 'nav_menu' ) {
            $menu_id = kowboy_hf_resolve_menu( $entry );
            if ( !$menu_id ) {
                return false;
            }
            $settings['nav_menu'] = $menu_id;
        }

        if ( $base === 'block' && isset( $settings['content'] ) ) {
            $settings['content'] = kowboy_hf_replace_tokens( (string) $settings['content'] );
        }

        $option_name = 'widget_' . $base;
        $stored      = get_option( $option_name, array() );
        if ( !is_array( $stored ) ) {
            $stored = array();
        }

        $numbers = array_filter( array_keys( $stored ), 'is_int' );
        $next    = empty( $numbers ) ? 2 : ( max( $numbers ) + 1 );

        $stored[ $next ]         = $settings;
        $stored['_multiwidget'] = 1;

        update_option( $option_name, $stored );

        return $base . '-' . $next;
    }
endif;

if ( !function_exists( 'kowboy_hf_resolve_menu' ) ) :
    /**
     * Find a menu on this site for an exported nav_menu widget.
     *
     * Order: same menu location, same menu slug, the "footer" location,
     * the "primary" location, the first menu that exists.
     *
     * @return int Menu term id or 0.
     */
    function kowboy_hf_resolve_menu( $entry ) {
        $locations = get_nav_menu_locations();

        if ( !empty( $entry['menu_location'] ) && !empty( $locations[ $entry['menu_location'] ] ) ) {
            if ( wp_get_nav_menu_object( (int) $locations[ $entry['menu_location'] ] ) ) {
                return (int) $locations[ $entry['menu_location'] ];
            }
        }

        if ( !empty( $entry['menu_slug'] ) ) {
            $menu = wp_get_nav_menu_object( sanitize_title( $entry['menu_slug'] ) );
            if ( $menu ) {
                return (int) $menu->term_id;
            }
        }

        foreach ( array( 'footer', 'primary' ) as $location ) {
            if ( !empty( $locations[ $location ] ) && wp_get_nav_menu_object( (int) $locations[ $location ] ) ) {
                return (int) $locations[ $location ];
            }
        }

        $menus = wp_get_nav_menus();
        if ( !empty( $menus ) && !is_wp_error( $menus ) ) {
            return (int) $menus[0]->term_id;
        }

        return 0;
    }
endif;

if ( !function_exists( 'kowboy_hf_replace_tokens' ) ) :
    /**
     * Replace site placeholders inside widget content.
     */
    function kowboy_hf_replace_tokens( $content ) {
        $tokens = array(
            '{{year}}'        => gmdate( 'Y' ),
            '{{admin_email}}' => (string) get_option( 'admin_email' ),
            '{{site_name}}'   => (string) get_bloginfo( 'name' ),
            '{{home_url}}'    => home_url( '/' ),
        );

        $tokens = apply_filters( 'kowboy_hf_tokens', $tokens );

        return strtr( $content, $tokens );
    }
endif;

if ( !function_exists( 'kowboy_hf_import_custom_css' ) ) :
    /**
     * Write the package CSS into Additional CSS, inside marker comments,
     * replacing a previous Kowboy block if there is one.
     *
     * @return bool
     */
    function kowboy_hf_import_custom_css( $css ) {
        $markers  = kowboy_hf_css_markers();
        $existing = (string) wp_get_custom_css();
        $css      = kowboy_hf_strip_css_markers( trim( $css ) );
        $block    = $markers['start'] . "\n" . $css . "\n" . $markers['end'];

        $pattern = '/' . preg_quote( $markers['start'], '/' ) . '.*?' . preg_quote( $markers['end'], '/' ) . '/s';

        if ( preg_match( $pattern, $existing ) ) {
            $new = preg_replace_callback( $pattern, function () use ( $block ) {
                return $block;
            }, $existing );
        } else {
            $new = rtrim( $existing );
            $new = ( $new === '' ) ? $block : $new . "\n\n" . $block;
        }

        $result = wp_update_custom_css_post( $new );

        return !is_wp_error( $result );
    }
endif;

if ( !function_exists( 'kowboy_hf_strip_css_markers' ) ) :
    /**
     * Remove marker comments from CSS so blocks never nest when a package
     * exported from one site is imported into another.
     */
    function kowboy_hf_strip_css_markers( $css ) {
        $markers = kowboy_hf_css_markers();
        return trim( str_replace( array( $markers['start'], $markers['end'] ), '', $css ) );
    }
endif;

/* -------------------------------------------------------------------------
 * Backup / restore
 * ---------------------------------------------------------------------- */

if ( !function_exists( 'kowboy_hf_store_backup' ) ) :
    function kowboy_hf_store_backup() {
        $backup                      = kowboy_hf_export();
        $backup['backup_created_at'] = current_time( 'mysql' );
        update_option( KOWBOY_HF_BACKUP_OPTION, $backup, false );
    }
endif;

if ( !function_exists( 'kowboy_hf_get_backup' ) ) :
    function kowboy_hf_get_backup() {
        $backup = get_option( KOWBOY_HF_BACKUP_OPTION );
        return is_array( $backup ) ? $backup : null;
    }
endif;

if ( !function_exists( 'kowboy_hf_restore_backup' ) ) :
    /**
     * Put back the header/footer captured before the last import.
     *
     * @return array|WP_Error
     */
    function kowboy_hf_restore_backup() {
        $backup = kowboy_hf_get_backup();
        if ( !$backup ) {
            return new WP_Error( 'kowboy_hf_no_backup', __( 'There is no backup to restore.', 'neve-child' ) );
        }

        // Mods that exist now but did not exist in the backup must be removed,
        // otherwise leftovers from the preset would survive the restore.
        $current = get_theme_mods();
        if ( is_array( $current ) ) {
            foreach ( $current as $key => $value ) {
                if ( kowboy_hf_is_hf_mod( $key ) && !array_key_exists( $key, (array) $backup['theme_mods'] ) ) {
                    remove_theme_mod( $key );
                }
            }
        }

        $report = kowboy_hf_import( $backup, array( 'backup' => false ) );
        if ( is_wp_error( $report ) ) {
            return $report;
        }

        // The site had no Additional CSS before the import: drop the Kowboy block.
        if ( trim( (string) ( isset( $backup['custom_css'] ) ? $backup['custom_css'] : '' ) ) === '' ) {
            kowboy_hf_remove_custom_css_block();
        }

        delete_option( KOWBOY_HF_BACKUP_OPTION );

        return $report;
    }
endif;

if ( !function_exists( 'kowboy_hf_remove_custom_css_block' ) ) :
    /**
     * Remove the marker-wrapped Kowboy block from Additional CSS.
     */
    function kowboy_hf_remove_custom_css_block() {
        $markers  = kowboy_hf_css_markers();
        $existing = (string) wp_get_custom_css();
        $pattern  = '/\s*' . preg_quote( $markers['start'], '/' ) . '.*?' . preg_quote( $markers['end'], '/' ) . '\s*/s';

        if ( !preg_match( $pattern, $existing ) ) {
            return;
        }

        $new = trim( (string) preg_replace( $pattern, "\n", $existing ) );
        wp_update_custom_css_post( $new );
    }
endif;
