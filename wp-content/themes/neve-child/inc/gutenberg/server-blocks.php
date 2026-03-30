<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'kowboy_register_dynamic_blocks' ) ) :
    function kowboy_register_dynamic_blocks() {
        if ( !function_exists( 'register_block_type' ) ) {
            return;
        }

        register_block_type( 'kowboy/agents-list', array(
            'render_callback' => 'kowboy_render_agents_list_block',
            'supports' => array(
                'align' => true,
                'anchor' => true,
                'spacing' => array(
                    'margin' => true,
                    'padding' => true,
                ),
            ),
            'attributes' => array(
                'style' => array( 'type' => 'object' ),
                'headline' => array( 'type' => 'string', 'default' => '' ),
                'offices' => array( 'type' => 'string', 'default' => '' ),
                'remoteids' => array( 'type' => 'string', 'default' => '' ),
                'template' => array( 'type' => 'string', 'default' => '' ),
                'ajax' => array( 'type' => 'boolean', 'default' => false ),
                'ignoreDefaultWrapper' => array( 'type' => 'boolean', 'default' => false ),
            ),
        ) );

        register_block_type( 'kowboy/search-properties', array(
            'render_callback' => 'kowboy_render_search_properties_block',
            'supports' => array(
                'align' => true,
                'anchor' => true,
                'spacing' => array(
                    'margin' => true,
                    'padding' => true,
                ),
            ),
            'attributes' => array(
                'style' => array( 'type' => 'object' ),
                'headline' => array( 'type' => 'string', 'default' => '' ),
                'ajax' => array( 'type' => 'boolean', 'default' => false ),
                'template' => array( 'type' => 'string', 'default' => '' ),
                'statuses' => array( 'type' => 'string', 'default' => '' ),
                'showStatusFilter' => array( 'type' => 'boolean', 'default' => false ),
                'ignoreDefaultWrapper' => array( 'type' => 'boolean', 'default' => false ),
                'perPage' => array( 'type' => 'number', 'default' => 10 ),
                'filterTemplate' => array( 'type' => 'string', 'default' => 'templates/filters/property-search-filter.php' ),
            ),
        ) );

        register_block_type( 'kowboy/search-properties-portrait', array(
            'render_callback' => 'kowboy_render_search_properties_portrait_block',
            'supports' => array(
                'align' => true,
                'anchor' => true,
                'spacing' => array(
                    'margin' => true,
                    'padding' => true,
                ),
            ),
            'attributes' => array(
                'style' => array( 'type' => 'object' ),
                'headline' => array( 'type' => 'string', 'default' => '' ),
                'ajax' => array( 'type' => 'boolean', 'default' => false ),
                'template' => array( 'type' => 'string', 'default' => 'templates/2025/list-item/property-list-item-portrait.php' ),
                'statuses' => array( 'type' => 'string', 'default' => '' ),
                'showStatusFilter' => array( 'type' => 'boolean', 'default' => false ),
                'ignoreDefaultWrapper' => array( 'type' => 'boolean', 'default' => false ),
                'perPage' => array( 'type' => 'number', 'default' => 10 ),
                'filterTemplate' => array( 'type' => 'string', 'default' => '' ),
            ),
        ) );

        register_block_type( 'kowboy/footer-newsletter-form', array(
            'render_callback' => 'kowboy_render_footer_newsletter_block',
            'supports' => array(
                'align' => true,
                'anchor' => true,
                'spacing' => array(
                    'margin' => true,
                    'padding' => true,
                ),
            ),
            'attributes' => array(
                'style' => array( 'type' => 'object' ),
                'heading' => array( 'type' => 'string', 'default' => 'Har du några frågor?' ),
                'text' => array( 'type' => 'string', 'default' => 'Jag hjälper dig gärna med en kostnadsfri värdering.' ),
                'backgroundImage' => array( 'type' => 'string', 'default' => '' ),
                'leadReceiverId' => array( 'type' => 'string', 'default' => '' ),
                'officeId' => array( 'type' => 'string', 'default' => '' ),
                'roundedInputs' => array( 'type' => 'boolean', 'default' => true ),
                'showOverlay' => array( 'type' => 'boolean', 'default' => true ),
                'textColor' => array( 'type' => 'string', 'default' => 'white' ),
            ),
        ) );
    }
endif;
add_action( 'init', 'kowboy_register_dynamic_blocks' );

if ( !function_exists( 'kowboy_render_agents_list_block' ) ) :
    function kowboy_render_agents_list_block( $attributes ) {
        if ( empty( $GLOBALS['kowboy_agents_list_shortcode'] ) && class_exists( 'Kowboy_Agents_List_Shortcode' ) ) {
            $GLOBALS['kowboy_agents_list_shortcode'] = new Kowboy_Agents_List_Shortcode();
        }

        if ( empty( $GLOBALS['kowboy_agents_list_shortcode'] ) ) {
            return '';
        }

        $atts = array(
            'remoteids' => isset( $attributes['remoteids'] ) ? $attributes['remoteids'] : '',
            'offices' => isset( $attributes['offices'] ) ? $attributes['offices'] : '',
            'template' => isset( $attributes['template'] ) ? $attributes['template'] : '',
            'ajax' => !empty( $attributes['ajax'] ),
            'ignore-default-wrapper' => !empty( $attributes['ignoreDefaultWrapper'] ),
        );

        $output = $GLOBALS['kowboy_agents_list_shortcode']->agents_list_callback( $atts );

        if ( !empty( $attributes['headline'] ) ) {
            $headline = wp_kses_post( $attributes['headline'] );
            $output = '<h2 class="kowboy-block-headline">' . $headline . '</h2>' . $output;
        }

        $wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
        return '<div ' . $wrapper . '>' . $output . '</div>';
    }
endif;

if ( !function_exists( 'kowboy_render_search_properties_block' ) ) :
    function kowboy_render_search_properties_block( $attributes ) {
        if ( empty( $GLOBALS['kowboy_search_shortcode'] ) && class_exists( 'Kowboy_Search_Shortcode' ) ) {
            $GLOBALS['kowboy_search_shortcode'] = new Kowboy_Search_Shortcode();
        }

        if ( empty( $GLOBALS['kowboy_search_shortcode'] ) ) {
            return '';
        }

        $atts = array(
            'ajax' => !empty( $attributes['ajax'] ) ? 'true' : false,
            'template' => isset( $attributes['template'] ) ? $attributes['template'] : '',
            'statuses' => isset( $attributes['statuses'] ) ? $attributes['statuses'] : '',
            'show_status_filter' => !empty( $attributes['showStatusFilter'] ),
            'ignore-default-wrapper' => !empty( $attributes['ignoreDefaultWrapper'] ),
            'per_page' => isset( $attributes['perPage'] ) ? intval( $attributes['perPage'] ) : 10,
            'filter_template' => isset( $attributes['filterTemplate'] ) ? $attributes['filterTemplate'] : 'templates/filters/property-search-filter.php',
        );

        $output = $GLOBALS['kowboy_search_shortcode']->search_properties_callback( $atts );

        if ( !empty( $attributes['headline'] ) ) {
            $headline = wp_kses_post( $attributes['headline'] );
            $output = '<h2 class="kowboy-block-headline">' . $headline . '</h2>' . $output;
        }

        $wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
        return '<div ' . $wrapper . '>' . $output . '</div>';
    }
endif;

if ( !function_exists( 'kowboy_render_search_properties_portrait_block' ) ) :
    function kowboy_render_search_properties_portrait_block( $attributes ) {
        if ( empty( $GLOBALS['kowboy_search_shortcode'] ) && class_exists( 'Kowboy_Search_Shortcode' ) ) {
            $GLOBALS['kowboy_search_shortcode'] = new Kowboy_Search_Shortcode();
        }

        if ( empty( $GLOBALS['kowboy_search_shortcode'] ) ) {
            return '';
        }

        $template = isset( $attributes['template'] ) && $attributes['template'] !== ''
            ? $attributes['template']
            : 'templates/2025/list-item/property-list-item-portrait.php';

        $atts = array(
            'ajax' => !empty( $attributes['ajax'] ) ? 'true' : false,
            'template' => $template,
            'statuses' => isset( $attributes['statuses'] ) ? $attributes['statuses'] : '',
            'show_status_filter' => !empty( $attributes['showStatusFilter'] ),
            'ignore-default-wrapper' => !empty( $attributes['ignoreDefaultWrapper'] ),
            'per_page' => isset( $attributes['perPage'] ) ? intval( $attributes['perPage'] ) : 10,
            'filter_template' => isset( $attributes['filterTemplate'] ) ? $attributes['filterTemplate'] : '',
        );

        $output = $GLOBALS['kowboy_search_shortcode']->search_properties_callback( $atts );

        if ( !empty( $attributes['headline'] ) ) {
            $headline = wp_kses_post( $attributes['headline'] );
            $output = '<h2 class="kowboy-block-headline">' . $headline . '</h2>' . $output;
        }

        $wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
        return '<div ' . $wrapper . '><div class="container kowboy-portrait-section">' . $output . '</div></div>';
    }
endif;

if ( !function_exists( 'kowboy_render_footer_newsletter_block' ) ) :
    function kowboy_render_footer_newsletter_block( $attributes ) {
        if ( empty( $GLOBALS['kowboy_2025_template'] ) && class_exists( 'Kowboy_2025' ) ) {
            $GLOBALS['kowboy_2025_template'] = new Kowboy_2025();
        }

        if ( empty( $GLOBALS['kowboy_2025_template'] ) ) {
            return '';
        }

        $heading = isset( $attributes['heading'] ) ? $attributes['heading'] : 'Har du några frågor?';
        $text = isset( $attributes['text'] ) ? $attributes['text'] : 'Jag hjälper dig gärna med en kostnadsfri värdering.';
        $background_image = isset( $attributes['backgroundImage'] ) ? $attributes['backgroundImage'] : '';
        $office_id = isset( $attributes['officeId'] ) ? $attributes['officeId'] : '';
        $lead_receiver_id = isset( $attributes['leadReceiverId'] ) ? $attributes['leadReceiverId'] : '';
        $rounded_inputs = !empty( $attributes['roundedInputs'] );
        $show_overlay = !array_key_exists( 'showOverlay', $attributes ) || !empty( $attributes['showOverlay'] );
        $text_color = isset( $attributes['textColor'] ) && $attributes['textColor'] === 'black' ? 'black' : 'white';

        $is_editor_preview = is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );

        if ( $is_editor_preview && defined( 'KOWBOY_PLUGIN_DIR' ) ) {
            $template_path = KOWBOY_PLUGIN_DIR . 'templates/2025/template-parts/contact-form.php';
            if ( is_file( $template_path ) ) {
                $attr = array(
                    'office_id' => $office_id,
                    'lead_receiver_id' => $lead_receiver_id,
                    'background_image' => $background_image,
                    'heading' => $heading,
                    'text' => $text,
                    'overlay' => $show_overlay ? 'true' : 'false',
                    'text_color' => $text_color,
                );
                $kowboy_style_options = get_option( 'kowboy_options_colors' );
                ob_start();
                include $template_path;
                $preview_html = ob_get_clean();

                if ( $rounded_inputs ) {
                    $preview_html = '<div class="kowboy-footer-newsletter-form--rounded">' . $preview_html . '</div>';
                }

                $wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
                return '<div ' . $wrapper . '>' . $preview_html . '</div>';
            }
        }

        if ( method_exists( $GLOBALS['kowboy_2025_template'], 'render_footer_newsletter_form' ) ) {
            $atts = array(
                'heading' => $heading,
                'text' => $text,
                'background_image' => $background_image,
                'lead_receiver_id' => $lead_receiver_id,
                'office_id' => $office_id,
                'overlay' => $show_overlay ? 'true' : 'false',
                'text_color' => $text_color,
            );
            $html = $GLOBALS['kowboy_2025_template']->render_footer_newsletter_form( $atts );
            if ( $rounded_inputs ) {
                $html = '<div class="kowboy-footer-newsletter-form--rounded">' . $html . '</div>';
            }
            $wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
            return '<div ' . $wrapper . '>' . $html . '</div>';
        }

        if ( method_exists( $GLOBALS['kowboy_2025_template'], 'render_lead_form' ) ) {
            $atts = array(
                'office_id' => $office_id,
                'background_image' => $background_image,
                'heading' => $heading,
                'sub_heading' => $text,
                'overlay' => $show_overlay ? 'true' : 'false',
                'text_color' => $text_color,
            );
            $html = $GLOBALS['kowboy_2025_template']->render_lead_form( $atts );
            if ( $rounded_inputs ) {
                $html = '<div class="kowboy-footer-newsletter-form--rounded">' . $html . '</div>';
            }
            $wrapper = function_exists( 'get_block_wrapper_attributes' ) ? get_block_wrapper_attributes() : '';
            return '<div ' . $wrapper . '>' . $html . '</div>';
        }

        return '';
    }
endif;
