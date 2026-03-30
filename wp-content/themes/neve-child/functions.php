<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        $style_path = get_stylesheet_directory() . '/style.css';
        $style_version = file_exists( $style_path ) ? filemtime( $style_path ) : null;
        wp_enqueue_style(
            'chld_thm_cfg_child',
            trailingslashit( get_stylesheet_directory_uri() ) . 'style.css',
            array( 'neve-style','neve-style' ),
            $style_version
        );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

require_once get_stylesheet_directory() . '/inc/gutenberg/blocks.php';
require_once get_stylesheet_directory() . '/inc/acf/blocks.php';
require_once get_stylesheet_directory() . '/inc/gutenberg/server-blocks.php';

if ( !function_exists( 'kowboy_enqueue_shadow_rounding' ) ) :
    function kowboy_enqueue_shadow_rounding() {
        if ( is_admin() ) {
            return;
        }
        $script_path = get_stylesheet_directory() . '/assets/js/kowboy-shadow-rounded.js';
        $version = file_exists( $script_path ) ? filemtime( $script_path ) : '1.0.0';
        wp_enqueue_script(
            'kowboy-shadow-rounded',
            get_stylesheet_directory_uri() . '/assets/js/kowboy-shadow-rounded.js',
            array(),
            $version,
            true
        );
    }
endif;
add_action( 'wp_enqueue_scripts', 'kowboy_enqueue_shadow_rounding', 20 );

if ( !function_exists( 'kowboy_enqueue_fullscreen_slideshow' ) ) :
    function kowboy_enqueue_fullscreen_slideshow() {
        if ( is_admin() ) {
            return;
        }

        $script_path = get_stylesheet_directory() . '/assets/js/kowboy-fullscreen-slideshow.js';
        $version = file_exists( $script_path ) ? filemtime( $script_path ) : '1.0.0';

        wp_enqueue_script(
            'kowboy-fullscreen-slideshow',
            get_stylesheet_directory_uri() . '/assets/js/kowboy-fullscreen-slideshow.js',
            array(),
            $version,
            true
        );
    }
endif;
add_action( 'wp_enqueue_scripts', 'kowboy_enqueue_fullscreen_slideshow', 20 );

if ( !function_exists( 'kowboy_child_setup_editor_styles' ) ) :
    function kowboy_child_setup_editor_styles() {
        add_theme_support( 'editor-styles' );
        add_editor_style( 'assets/blocks/editor.css' );
    }
endif;
add_action( 'after_setup_theme', 'kowboy_child_setup_editor_styles' );

if ( !function_exists( 'kowboy_force_style_cache_bust' ) ) :
    function kowboy_force_style_cache_bust( $src, $handle ) {
        $handles = array(
            'chld_thm_cfg_child',
            'kowboy-blocks',
            'kowboy-blocks-editor',
            'kowboy-2025-agents-tailwind-editor',
            'kowboy-2025-agents-styles-editor',
            'kowboy-property-list-tailwind-editor',
            'kowboy-property-list-styles-editor',
            'kowboy-property-list-swiper-editor',
            'kowboy-footer-newsletter-tailwind-editor',
            'kowboy-footer-newsletter-styles-editor',
        );

        if ( !in_array( $handle, $handles, true ) ) {
            return $src;
        }

        $ver = time();
        if ( strpos( $src, 'ver=' ) !== false ) {
            $src = preg_replace( '/ver=[^&]+/', 'ver=' . $ver, $src );
        } else {
            $src .= ( strpos( $src, '?' ) === false ? '?' : '&' ) . 'ver=' . $ver;
        }

        return $src;
    }
endif;
add_filter( 'style_loader_src', 'kowboy_force_style_cache_bust', 20, 2 );

if ( !function_exists( 'kowboy_frontend_rounding_inline' ) ) :
    function kowboy_frontend_rounding_inline() {
        if ( is_admin() ) {
            return;
        }

        $css = '
            .kowboy-property-list-wrapper .btn,
            .kowboy-property-list-wrapper .property-filter-btn,
            .kowboy-property-filter .btn,
            .kowboy-property-filter input,
            .kowboy-property-filter select,
            .kowboy-property-filter textarea,
            .contact-form-section input[type="text"],
            .contact-form-section input[type="email"],
            .contact-form-section input[type="tel"],
            .contact-form-section select,
            .contact-form-section textarea,
            .contact-form-section button,
            .agents-list-item,
            .agent-card,
            .testimonial-card,
            .single-area-card,
            .single-office-card,
            .property-list .shadow-lg,
            .kowboy-property-filter button,
            .kowboy-property-filter .status-filter-items button,
            .status-filter-items button,
            .property-filter-btn,
            .load-more-button,
            .search-properties-list button,
            .search-properties-list .btn {
                border-radius: 12px !important;
            }
        ';

        wp_add_inline_style( 'chld_thm_cfg_child', $css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'kowboy_frontend_rounding_inline', 20 );

if ( !function_exists( 'kowboy_frontend_rounding_head' ) ) :
    function kowboy_frontend_rounding_head() {
        if ( is_admin() ) {
            return;
        }
        ?>
        <style id="kowboy-frontend-rounded">
            .kowboy-property-list-wrapper .btn,
            .kowboy-property-list-wrapper .property-filter-btn,
            .kowboy-property-filter .btn,
            .kowboy-property-filter input,
            .kowboy-property-filter select,
            .kowboy-property-filter textarea,
            .contact-form-section input,
            .contact-form-section select,
            .contact-form-section textarea,
            .contact-form-section button,
            .agents-list-item,
            .agent-card,
            .testimonial-card,
            .single-area-card,
            .single-office-card,
            .property-list .shadow-lg,
            .kowboy-property-filter button,
            .kowboy-property-filter .status-filter-items button,
            .status-filter-items button,
            .property-filter-btn,
            .load-more-button,
            .search-properties-list button,
            .search-properties-list .btn {
                border-radius: 12px !important;
            }
        </style>
        <?php
    }
endif;
add_action( 'wp_head', 'kowboy_frontend_rounding_head', 99 );
