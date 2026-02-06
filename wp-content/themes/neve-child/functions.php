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
