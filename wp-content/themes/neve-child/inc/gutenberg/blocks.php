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
            array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components' ),
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
