<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'kowboy_build_shortcode_from_attrs' ) ) :
    function kowboy_build_shortcode_from_attrs( $shortcode_tag, $attrs_string ) {
        $attrs_string = is_string( $attrs_string ) ? trim( $attrs_string ) : '';

        if ( $attrs_string === '' ) {
            return '[' . $shortcode_tag . ']';
        }

        if ( strpos( $attrs_string, '[' ) === 0 ) {
            return $attrs_string;
        }

        return '[' . $shortcode_tag . ' ' . $attrs_string . ']';
    }
endif;

if ( !function_exists( 'kowboy_render_shortcode_block' ) ) :
    function kowboy_render_shortcode_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
        $shortcode_tag = function_exists( 'get_field' ) ? get_field( 'shortcode_tag' ) : '';
        $attrs_string = function_exists( 'get_field' ) ? get_field( 'shortcode_attributes' ) : '';

        if ( empty( $shortcode_tag ) && isset( $block['name'] ) ) {
            $map = array(
                'acf/kowboy-agents-list' => 'kowboy_agents_list',
                'acf/kowboy-search-properties' => 'kowboy_search_properties',
                'acf/kowboy-footer-newsletter-form' => 'footer_newsletter_form',
            );
            $shortcode_tag = isset( $map[ $block['name'] ] ) ? $map[ $block['name'] ] : '';
        }

        if ( empty( $shortcode_tag ) ) {
            return;
        }

        echo do_shortcode( kowboy_build_shortcode_from_attrs( $shortcode_tag, $attrs_string ) );
    }
endif;

if ( !function_exists( 'kowboy_register_acf_shortcode_blocks' ) ) :
    function kowboy_register_acf_shortcode_blocks() {
        if ( !function_exists( 'acf_register_block_type' ) ) {
            return;
        }

        $blocks = array(
            array(
                'name' => 'kowboy-agents-list',
                'title' => __( 'Agents List (Shortcode)', 'kowboy' ),
                'description' => __( 'Renders the kowboy_agents_list shortcode.', 'kowboy' ),
                'shortcode_tag' => 'kowboy_agents_list',
            ),
            array(
                'name' => 'kowboy-search-properties',
                'title' => __( 'Search Properties (Shortcode)', 'kowboy' ),
                'description' => __( 'Renders the kowboy_search_properties shortcode.', 'kowboy' ),
                'shortcode_tag' => 'kowboy_search_properties',
            ),
            array(
                'name' => 'kowboy-footer-newsletter-form',
                'title' => __( 'Footer Newsletter Form (Shortcode)', 'kowboy' ),
                'description' => __( 'Renders the footer_newsletter_form shortcode.', 'kowboy' ),
                'shortcode_tag' => 'footer_newsletter_form',
            ),
        );

        foreach ( $blocks as $block ) {
            acf_register_block_type( array(
                'name' => $block['name'],
                'title' => $block['title'],
                'description' => $block['description'],
                'category' => 'kowboy',
                'icon' => 'shortcode',
                'keywords' => array( 'kowboy', 'shortcode' ),
                'mode' => 'preview',
                'supports' => array(
                    'align' => true,
                    'anchor' => true,
                ),
                'render_callback' => 'kowboy_render_shortcode_block',
                'example' => array(
                    'attributes' => array(
                        'mode' => 'preview',
                        'data' => array(
                            'shortcode_tag' => $block['shortcode_tag'],
                        ),
                    ),
                ),
            ) );
        }
    }
endif;
add_action( 'acf/init', 'kowboy_register_acf_shortcode_blocks' );

if ( !function_exists( 'kowboy_register_acf_shortcode_block_fields' ) ) :
    function kowboy_register_acf_shortcode_block_fields() {
        if ( !function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        $groups = array(
            array(
                'key' => 'group_kowboy_agents_list_shortcode',
                'title' => __( 'Agents List Shortcode', 'kowboy' ),
                'block' => 'acf/kowboy-agents-list',
                'shortcode_tag' => 'kowboy_agents_list',
            ),
            array(
                'key' => 'group_kowboy_search_properties_shortcode',
                'title' => __( 'Search Properties Shortcode', 'kowboy' ),
                'block' => 'acf/kowboy-search-properties',
                'shortcode_tag' => 'kowboy_search_properties',
            ),
            array(
                'key' => 'group_kowboy_footer_newsletter_shortcode',
                'title' => __( 'Footer Newsletter Form Shortcode', 'kowboy' ),
                'block' => 'acf/kowboy-footer-newsletter-form',
                'shortcode_tag' => 'footer_newsletter_form',
            ),
        );

        foreach ( $groups as $group ) {
            acf_add_local_field_group( array(
                'key' => $group['key'],
                'title' => $group['title'],
                'fields' => array(
                    array(
                        'key' => $group['key'] . '_shortcode_tag',
                        'label' => __( 'Shortcode Tag', 'kowboy' ),
                        'name' => 'shortcode_tag',
                        'type' => 'text',
                        'default_value' => $group['shortcode_tag'],
                        'readonly' => 1,
                        'instructions' => __( 'This is fixed for the block.', 'kowboy' ),
                    ),
                    array(
                        'key' => $group['key'] . '_shortcode_attributes',
                        'label' => __( 'Shortcode Attributes', 'kowboy' ),
                        'name' => 'shortcode_attributes',
                        'type' => 'textarea',
                        'rows' => 3,
                        'instructions' => __( 'Optional. Example: office=\"Stockholm\" per_page=\"12\". You can also paste a full shortcode like [kowboy_search_properties].', 'kowboy' ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => $group['block'],
                        ),
                    ),
                ),
                'style' => 'seamless',
                'position' => 'normal',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'active' => true,
            ) );
        }
    }
endif;
add_action( 'acf/init', 'kowboy_register_acf_shortcode_block_fields' );
