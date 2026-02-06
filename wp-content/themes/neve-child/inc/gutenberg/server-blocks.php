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
            'attributes' => array(
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
            'attributes' => array(
                'headline' => array( 'type' => 'string', 'default' => '' ),
                'ajax' => array( 'type' => 'boolean', 'default' => false ),
                'template' => array( 'type' => 'string', 'default' => '' ),
                'statuses' => array( 'type' => 'string', 'default' => '' ),
                'showStatusFilter' => array( 'type' => 'boolean', 'default' => false ),
                'ignoreDefaultWrapper' => array( 'type' => 'boolean', 'default' => false ),
                'perPage' => array( 'type' => 'number', 'default' => 10 ),
                'filterTemplate' => array( 'type' => 'string', 'default' => '' ),
            ),
        ) );

        register_block_type( 'kowboy/search-properties-portrait', array(
            'render_callback' => 'kowboy_render_search_properties_portrait_block',
            'attributes' => array(
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
            'attributes' => array(
                'heading' => array( 'type' => 'string', 'default' => 'Har du några frågor?' ),
                'text' => array( 'type' => 'string', 'default' => 'Jag hjälper dig gärna med en kostnadsfri värdering.' ),
                'backgroundImage' => array( 'type' => 'string', 'default' => '' ),
                'leadReceiverId' => array( 'type' => 'string', 'default' => '' ),
                'officeId' => array( 'type' => 'string', 'default' => '' ),
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

        return $output;
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
            'filter_template' => isset( $attributes['filterTemplate'] ) ? $attributes['filterTemplate'] : '',
        );

        $output = $GLOBALS['kowboy_search_shortcode']->search_properties_callback( $atts );

        if ( !empty( $attributes['headline'] ) ) {
            $headline = wp_kses_post( $attributes['headline'] );
            $output = '<h2 class="kowboy-block-headline">' . $headline . '</h2>' . $output;
        }

        return $output;
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

        return '<div class="container kowboy-portrait-section">' . $output . '</div>';
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

        $atts = array(
            'heading' => isset( $attributes['heading'] ) ? $attributes['heading'] : 'Har du några frågor?',
            'text' => isset( $attributes['text'] ) ? $attributes['text'] : 'Jag hjälper dig gärna med en kostnadsfri värdering.',
            'background_image' => isset( $attributes['backgroundImage'] ) ? $attributes['backgroundImage'] : '',
            'lead_receiver_id' => isset( $attributes['leadReceiverId'] ) ? $attributes['leadReceiverId'] : '',
            'office_id' => isset( $attributes['officeId'] ) ? $attributes['officeId'] : '',
        );

        return $GLOBALS['kowboy_2025_template']->render_footer_newsletter_form( $atts );
    }
endif;
