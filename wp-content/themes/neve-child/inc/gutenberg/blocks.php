<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'kowboy_register_gutenberg_assets' ) ) :
    function kowboy_register_gutenberg_assets() {
        $script_path = get_stylesheet_directory() . '/assets/blocks/blocks.js';
        $script_version = file_exists( $script_path ) ? filemtime( $script_path ) : '1.0.0';

        wp_register_script(
            'kowboy-blocks',
            get_stylesheet_directory_uri() . '/assets/blocks/blocks.js',
            array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
            $script_version,
            true
        );

        wp_enqueue_script( 'kowboy-blocks' );
    }
endif;
add_action( 'enqueue_block_editor_assets', 'kowboy_register_gutenberg_assets' );

if ( !function_exists( 'kowboy_enqueue_gutenberg_styles' ) ) :
    function kowboy_enqueue_gutenberg_styles() {
        $style_path = get_stylesheet_directory() . '/assets/blocks/blocks.css';
        $editor_style_path = get_stylesheet_directory() . '/assets/blocks/editor.css';
        $style_version = file_exists( $style_path ) ? filemtime( $style_path ) : '1.0.1';
        $editor_style_version = file_exists( $editor_style_path ) ? filemtime( $editor_style_path ) : '1.0.0';

        wp_register_style(
            'kowboy-blocks',
            get_stylesheet_directory_uri() . '/assets/blocks/blocks.css',
            array(),
            $style_version
        );

        wp_register_style(
            'kowboy-blocks-editor',
            get_stylesheet_directory_uri() . '/assets/blocks/editor.css',
            array(),
            $editor_style_version
        );

        wp_enqueue_style( 'kowboy-blocks' );

        if ( is_admin() ) {
            wp_enqueue_style( 'kowboy-blocks-editor' );
        }
    }
endif;
add_action( 'enqueue_block_assets', 'kowboy_enqueue_gutenberg_styles' );

if ( !function_exists( 'kowboy_register_block_category' ) ) :
    function kowboy_register_block_category( $categories, $post ) {
        array_unshift( $categories, array(
            'slug' => 'kowboy',
            'title' => __( 'Kowboy', 'kowboy' ),
        ) );

        return $categories;
    }
endif;
add_filter( 'block_categories_all', 'kowboy_register_block_category', 10, 2 );

if ( !function_exists( 'kowboy_inline_editor_rounding_styles' ) ) :
    function kowboy_inline_editor_rounding_styles() {
        if ( !is_admin() ) {
            return;
        }

        $css = '
            .editor-styles-wrapper .kowboy-dynamic-block {
                border-radius: 12px !important;
                overflow: hidden;
            }
            .editor-styles-wrapper .kowboy-dynamic-block button,
            .editor-styles-wrapper .kowboy-dynamic-block .button,
            .editor-styles-wrapper .kowboy-dynamic-block input,
            .editor-styles-wrapper .kowboy-dynamic-block select,
            .editor-styles-wrapper .kowboy-dynamic-block textarea,
            .editor-styles-wrapper .kowboy-dynamic-block .card,
            .editor-styles-wrapper .kowboy-dynamic-block [class*=\"card\"],
            .editor-styles-wrapper .kowboy-dynamic-block [class*=\"Card\"] {
                border-radius: 12px !important;
            }
            .editor-styles-wrapper .kowboy-cta-two-buttons__button,
            .editor-styles-wrapper .kowboy-hero-background-buttons__button {
                border-radius: 9999px !important;
            }
        ';

        wp_add_inline_style( 'kowboy-blocks-editor', $css );
    }
endif;
add_action( 'enqueue_block_editor_assets', 'kowboy_inline_editor_rounding_styles', 20 );

if ( !function_exists( 'kowboy_enqueue_kowboy_block_editor_assets' ) ) :
    function kowboy_enqueue_kowboy_block_editor_assets() {
        if ( !is_admin() ) {
            return;
        }

        if ( !defined( 'KOWBOY_PLUGIN_DIR_URL' ) ) {
            return;
        }

        $ver = defined( 'KOWBOY_ASSETS_VERSION' ) ? KOWBOY_ASSETS_VERSION : '1.0';

        // Agents list (2025) styles
        wp_enqueue_style(
            'kowboy-2025-agents-tailwind-editor',
            KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/css/tailwind.css',
            array(),
            $ver
        );
        wp_enqueue_style(
            'kowboy-2025-agents-styles-editor',
            KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/css/agents-list-styles.css',
            array( 'kowboy-2025-agents-tailwind-editor' ),
            $ver
        );

        // Property list styles
        wp_enqueue_style(
            'kowboy-property-list-tailwind-editor',
            KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/css/tailwind.css',
            array(),
            $ver
        );
        wp_enqueue_style(
            'kowboy-property-list-styles-editor',
            KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/css/property-list-styles.css',
            array( 'kowboy-property-list-tailwind-editor' ),
            $ver
        );
        wp_enqueue_style(
            'kowboy-property-list-swiper-editor',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            $ver
        );

        // Footer newsletter styles
        wp_enqueue_style(
            'kowboy-footer-newsletter-tailwind-editor',
            KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/css/tailwind.css',
            array(),
            $ver
        );
        wp_enqueue_style(
            'kowboy-footer-newsletter-styles-editor',
            KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/css/single-property-styles.css',
            array( 'kowboy-footer-newsletter-tailwind-editor' ),
            $ver
        );
    }
endif;
add_action( 'enqueue_block_editor_assets', 'kowboy_enqueue_kowboy_block_editor_assets' );
