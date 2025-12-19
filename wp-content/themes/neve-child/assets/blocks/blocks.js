( function( blocks, element, blockEditor, components, i18n ) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var InspectorControls = blockEditor.InspectorControls;
    var MediaUpload = blockEditor.MediaUpload;
    var MediaUploadCheck = blockEditor.MediaUploadCheck;
    var URLInputButton = blockEditor.URLInputButton;
    var useBlockProps = blockEditor.useBlockProps;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var Button = components.Button;
    var __ = i18n.__;

    blocks.registerBlockType( 'kowboy/cta-two-buttons', {
        title: __( 'CTA: Title, Text, Two Buttons', 'kowboy' ),
        description: __( 'Call-to-action block with title, text, and two buttons.', 'kowboy' ),
        icon: 'megaphone',
        category: 'kowboy',
        supports: {
            align: true,
            anchor: true
        },
        attributes: {
            title: {
                type: 'string',
                source: 'html',
                selector: 'h2'
            },
            text: {
                type: 'string',
                source: 'html',
                selector: '.kowboy-cta-two-buttons__text'
            },
            primaryLabel: {
                type: 'string',
                default: ''
            },
            primaryUrl: {
                type: 'string',
                default: ''
            },
            secondaryLabel: {
                type: 'string',
                default: ''
            },
            secondaryUrl: {
                type: 'string',
                default: ''
            }
        },
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps( { className: 'kowboy-cta-two-buttons' } );

            return [
                el( InspectorControls, { key: 'cta-controls' },
                    el( PanelBody, { title: __( 'Buttons', 'kowboy' ), initialOpen: true },
                        el( TextControl, {
                            label: __( 'Primary Button Label', 'kowboy' ),
                            value: attributes.primaryLabel,
                            onChange: function( value ) {
                                setAttributes( { primaryLabel: value } );
                            }
                        } ),
                        el( URLInputButton, {
                            url: attributes.primaryUrl,
                            onChange: function( url ) {
                                setAttributes( { primaryUrl: url } );
                            }
                        } ),
                        el( TextControl, {
                            label: __( 'Secondary Button Label', 'kowboy' ),
                            value: attributes.secondaryLabel,
                            onChange: function( value ) {
                                setAttributes( { secondaryLabel: value } );
                            }
                        } ),
                        el( URLInputButton, {
                            url: attributes.secondaryUrl,
                            onChange: function( url ) {
                                setAttributes( { secondaryUrl: url } );
                            }
                        } )
                    )
                ),
                el( 'section', blockProps,
                    el( 'div', { className: 'kowboy-cta-two-buttons__inner' },
                        el( RichText, {
                            tagName: 'h2',
                            className: 'kowboy-cta-two-buttons__title',
                            value: attributes.title,
                            placeholder: __( 'Add title...', 'kowboy' ),
                            onChange: function( value ) {
                                setAttributes( { title: value } );
                            }
                        } ),
                        el( RichText, {
                            tagName: 'div',
                            className: 'kowboy-cta-two-buttons__text',
                            value: attributes.text,
                            placeholder: __( 'Add text...', 'kowboy' ),
                            multiline: 'p',
                            onChange: function( value ) {
                                setAttributes( { text: value } );
                            }
                        } ),
                        el( 'div', { className: 'kowboy-cta-two-buttons__actions' },
                            el( Button, { className: 'kowboy-cta-two-buttons__button kowboy-cta-two-buttons__button--primary', isSecondary: true }, attributes.primaryLabel || __( 'Primary', 'kowboy' ) ),
                            el( Button, { className: 'kowboy-cta-two-buttons__button kowboy-cta-two-buttons__button--secondary', isSecondary: true }, attributes.secondaryLabel || __( 'Secondary', 'kowboy' ) )
                        )
                    )
                )
            ];
        },
        save: function( props ) {
            var attributes = props.attributes;
            var blockProps = blockEditor.useBlockProps.save( { className: 'kowboy-cta-two-buttons' } );

            return el( 'section', blockProps,
                el( 'div', { className: 'kowboy-cta-two-buttons__inner' },
                    attributes.title && el( RichText.Content, {
                        tagName: 'h2',
                        className: 'kowboy-cta-two-buttons__title',
                        value: attributes.title
                    } ),
                    attributes.text && el( RichText.Content, {
                        tagName: 'div',
                        className: 'kowboy-cta-two-buttons__text',
                        value: attributes.text
                    } ),
                    ( attributes.primaryLabel || attributes.secondaryLabel ) && el( 'div', { className: 'kowboy-cta-two-buttons__actions' },
                        attributes.primaryLabel && attributes.primaryUrl && el( 'a', {
                            className: 'kowboy-cta-two-buttons__button kowboy-cta-two-buttons__button--primary',
                            href: attributes.primaryUrl
                        }, attributes.primaryLabel ),
                        attributes.secondaryLabel && attributes.secondaryUrl && el( 'a', {
                            className: 'kowboy-cta-two-buttons__button kowboy-cta-two-buttons__button--secondary',
                            href: attributes.secondaryUrl
                        }, attributes.secondaryLabel )
                    )
                )
            );
        }
    } );

    blocks.registerBlockType( 'kowboy/hero-background-buttons', {
        title: __( 'Hero: Background, Heading, Subheading, Two Buttons', 'kowboy' ),
        description: __( 'Hero block with background image, heading, subheading, and two buttons.', 'kowboy' ),
        icon: 'format-image',
        category: 'kowboy',
        supports: {
            align: true,
            anchor: true
        },
        attributes: {
            backgroundUrl: {
                type: 'string',
                default: ''
            },
            backgroundId: {
                type: 'number'
            },
            heading: {
                type: 'string',
                source: 'html',
                selector: 'h1'
            },
            subheading: {
                type: 'string',
                source: 'html',
                selector: '.kowboy-hero-background-buttons__subheading'
            },
            primaryLabel: {
                type: 'string',
                default: ''
            },
            primaryUrl: {
                type: 'string',
                default: ''
            },
            secondaryLabel: {
                type: 'string',
                default: ''
            },
            secondaryUrl: {
                type: 'string',
                default: ''
            }
        },
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var style = attributes.backgroundUrl ? { backgroundImage: 'url(' + attributes.backgroundUrl + ')' } : {};
            var blockProps = useBlockProps( { className: 'kowboy-hero-background-buttons', style: style } );

            return [
                el( InspectorControls, { key: 'hero-controls' },
                    el( PanelBody, { title: __( 'Background Image', 'kowboy' ), initialOpen: true },
                        el( MediaUploadCheck, null,
                            el( MediaUpload, {
                                onSelect: function( media ) {
                                    setAttributes( {
                                        backgroundUrl: media.url,
                                        backgroundId: media.id
                                    } );
                                },
                                allowedTypes: [ 'image' ],
                                value: attributes.backgroundId,
                                render: function( obj ) {
                                    return el( Button, { onClick: obj.open, isSecondary: true },
                                        attributes.backgroundUrl ? __( 'Replace image', 'kowboy' ) : __( 'Select image', 'kowboy' )
                                    );
                                }
                            } )
                        ),
                        attributes.backgroundUrl && el( Button, {
                            isLink: true,
                            isDestructive: true,
                            onClick: function() {
                                setAttributes( { backgroundUrl: '', backgroundId: null } );
                            }
                        }, __( 'Remove image', 'kowboy' ) )
                    ),
                    el( PanelBody, { title: __( 'Buttons', 'kowboy' ), initialOpen: false },
                        el( TextControl, {
                            label: __( 'Primary Button Label', 'kowboy' ),
                            value: attributes.primaryLabel,
                            onChange: function( value ) {
                                setAttributes( { primaryLabel: value } );
                            }
                        } ),
                        el( URLInputButton, {
                            url: attributes.primaryUrl,
                            onChange: function( url ) {
                                setAttributes( { primaryUrl: url } );
                            }
                        } ),
                        el( TextControl, {
                            label: __( 'Secondary Button Label', 'kowboy' ),
                            value: attributes.secondaryLabel,
                            onChange: function( value ) {
                                setAttributes( { secondaryLabel: value } );
                            }
                        } ),
                        el( URLInputButton, {
                            url: attributes.secondaryUrl,
                            onChange: function( url ) {
                                setAttributes( { secondaryUrl: url } );
                            }
                        } )
                    )
                ),
                el( 'section', blockProps,
                    el( 'div', { className: 'kowboy-hero-background-buttons__inner' },
                        el( RichText, {
                            tagName: 'h1',
                            className: 'kowboy-hero-background-buttons__heading',
                            value: attributes.heading,
                            placeholder: __( 'Add heading...', 'kowboy' ),
                            onChange: function( value ) {
                                setAttributes( { heading: value } );
                            }
                        } ),
                        el( RichText, {
                            tagName: 'div',
                            className: 'kowboy-hero-background-buttons__subheading',
                            value: attributes.subheading,
                            placeholder: __( 'Add subheading...', 'kowboy' ),
                            multiline: 'p',
                            onChange: function( value ) {
                                setAttributes( { subheading: value } );
                            }
                        } ),
                        el( 'div', { className: 'kowboy-hero-background-buttons__actions' },
                            el( Button, { className: 'kowboy-hero-background-buttons__button kowboy-hero-background-buttons__button--primary', isSecondary: true }, attributes.primaryLabel || __( 'Primary', 'kowboy' ) ),
                            el( Button, { className: 'kowboy-hero-background-buttons__button kowboy-hero-background-buttons__button--secondary', isSecondary: true }, attributes.secondaryLabel || __( 'Secondary', 'kowboy' ) )
                        )
                    )
                )
            ];
        },
        save: function( props ) {
            var attributes = props.attributes;
            var style = attributes.backgroundUrl ? { backgroundImage: 'url(' + attributes.backgroundUrl + ')' } : {};
            var blockProps = blockEditor.useBlockProps.save( { className: 'kowboy-hero-background-buttons', style: style } );

            return el( 'section', blockProps,
                el( 'div', { className: 'kowboy-hero-background-buttons__inner' },
                    attributes.heading && el( RichText.Content, {
                        tagName: 'h1',
                        className: 'kowboy-hero-background-buttons__heading',
                        value: attributes.heading
                    } ),
                    attributes.subheading && el( RichText.Content, {
                        tagName: 'div',
                        className: 'kowboy-hero-background-buttons__subheading',
                        value: attributes.subheading
                    } ),
                    ( attributes.primaryLabel || attributes.secondaryLabel ) && el( 'div', { className: 'kowboy-hero-background-buttons__actions' },
                        attributes.primaryLabel && attributes.primaryUrl && el( 'a', {
                            className: 'kowboy-hero-background-buttons__button kowboy-hero-background-buttons__button--primary',
                            href: attributes.primaryUrl
                        }, attributes.primaryLabel ),
                        attributes.secondaryLabel && attributes.secondaryUrl && el( 'a', {
                            className: 'kowboy-hero-background-buttons__button kowboy-hero-background-buttons__button--secondary',
                            href: attributes.secondaryUrl
                        }, attributes.secondaryLabel )
                    )
                )
            );
        }
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
