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
    var ServerSideRender = window.wp.serverSideRender || ( components && components.ServerSideRender );
    var TextareaControl = components.TextareaControl || TextControl;
    var NumberControl = components.NumberControl || TextControl;

    blocks.registerBlockType( 'kowboy/cta-two-buttons', {
        title: __( 'CTA: Title, Text, Two Buttons', 'kowboy' ),
        description: __( 'Call-to-action block with title, text, and two buttons.', 'kowboy' ),
        icon: 'megaphone',
        category: 'kowboy',
        supports: {
            align: true,
            anchor: true,
            spacing: {
                margin: true,
                padding: true
            }
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
            anchor: true,
            spacing: {
                margin: true,
                padding: true
            }
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
            },
            transparentHeader: {
                type: 'boolean',
                default: false
            }
        },
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var style = attributes.backgroundUrl ? { backgroundImage: 'url(' + attributes.backgroundUrl + ')' } : {};
            var className = 'kowboy-hero-background-buttons';
            if ( attributes.transparentHeader ) {
                className += ' kowboy-hero-background-buttons--transparent-header';
            }
            var blockProps = useBlockProps( { className: className, style: style } );

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
                    ),
                    el( PanelBody, { title: __( 'Header', 'kowboy' ), initialOpen: false },
                        el( components.ToggleControl, {
                            label: __( 'Transparent Header Mode', 'kowboy' ),
                            checked: attributes.transparentHeader,
                            onChange: function( value ) {
                                setAttributes( { transparentHeader: value } );
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
            var className = 'kowboy-hero-background-buttons';
            if ( attributes.transparentHeader ) {
                className += ' kowboy-hero-background-buttons--transparent-header';
            }
            var blockProps = blockEditor.useBlockProps.save( { className: className, style: style } );

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

    function registerAgentsListBlock() {
        blocks.registerBlockType( 'kowboy/agents-list', {
            title: __( 'Agents List', 'kowboy' ),
            description: __( 'Displays the agents list.', 'kowboy' ),
            icon: 'groups',
            category: 'kowboy',
            supports: {
                align: true,
                anchor: true,
                spacing: {
                    margin: true,
                    padding: true
                }
            },
            attributes: {
                headline: { type: 'string', default: '' },
                offices: { type: 'string', default: '' },
                remoteids: { type: 'string', default: '' },
                template: { type: 'string', default: '' },
                ajax: { type: 'boolean', default: false },
                ignoreDefaultWrapper: { type: 'boolean', default: false }
            },
            edit: function( props ) {
                var attributes = props.attributes;
                var setAttributes = props.setAttributes;
                var blockProps = useBlockProps( { className: 'kowboy-dynamic-block' } );

                return [
                    el( InspectorControls, { key: 'agents-list-controls' },
                        el( PanelBody, { title: __( 'Agents List Settings', 'kowboy' ), initialOpen: true },
                            el( TextControl, {
                                label: __( 'Headline', 'kowboy' ),
                                value: attributes.headline,
                                onChange: function( value ) {
                                    setAttributes( { headline: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Offices (comma-separated)', 'kowboy' ),
                                value: attributes.offices,
                                onChange: function( value ) {
                                    setAttributes( { offices: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Remote IDs (comma-separated)', 'kowboy' ),
                                value: attributes.remoteids,
                                onChange: function( value ) {
                                    setAttributes( { remoteids: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Template Path', 'kowboy' ),
                                value: attributes.template,
                                onChange: function( value ) {
                                    setAttributes( { template: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Use AJAX', 'kowboy' ),
                                checked: attributes.ajax,
                                onChange: function( value ) {
                                    setAttributes( { ajax: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Ignore Default Wrapper', 'kowboy' ),
                                checked: attributes.ignoreDefaultWrapper,
                                onChange: function( value ) {
                                    setAttributes( { ignoreDefaultWrapper: value } );
                                }
                            } )
                        )
                    ),
                        el( 'section', blockProps,
                        el( 'p', { className: 'kowboy-dynamic-block__label' }, __( 'Agents List Block', 'kowboy' ) ),
                        ServerSideRender ? el( ServerSideRender, {
                            block: 'kowboy/agents-list',
                            attributes: attributes
                        } ) : el( 'p', { className: 'kowboy-dynamic-block__hint' }, __( 'Preview unavailable in this editor.', 'kowboy' ) )
                    )
                ];
            },
            save: function() {
                return null;
            }
        } );
    }

    function registerSearchPropertiesBlock() {
        blocks.registerBlockType( 'kowboy/search-properties', {
            title: __( 'Search Properties', 'kowboy' ),
            description: __( 'Displays the property search results.', 'kowboy' ),
            icon: 'search',
            category: 'kowboy',
            supports: {
                align: true,
                anchor: true,
                spacing: {
                    margin: true,
                    padding: true
                }
            },
            attributes: {
                headline: { type: 'string', default: '' },
                ajax: { type: 'boolean', default: false },
                template: { type: 'string', default: '' },
                statuses: { type: 'string', default: '' },
                showStatusFilter: { type: 'boolean', default: false },
                ignoreDefaultWrapper: { type: 'boolean', default: false },
                perPage: { type: 'number', default: 10 },
                filterTemplate: { type: 'string', default: '' }
            },
            edit: function( props ) {
                var attributes = props.attributes;
                var setAttributes = props.setAttributes;
                var blockProps = useBlockProps( { className: 'kowboy-dynamic-block' } );

                return [
                    el( InspectorControls, { key: 'search-properties-controls' },
                        el( PanelBody, { title: __( 'Search Properties Settings', 'kowboy' ), initialOpen: true },
                            el( TextControl, {
                                label: __( 'Headline', 'kowboy' ),
                                value: attributes.headline,
                                onChange: function( value ) {
                                    setAttributes( { headline: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Use AJAX', 'kowboy' ),
                                checked: attributes.ajax,
                                onChange: function( value ) {
                                    setAttributes( { ajax: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Template Path', 'kowboy' ),
                                value: attributes.template,
                                onChange: function( value ) {
                                    setAttributes( { template: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Statuses (comma-separated)', 'kowboy' ),
                                value: attributes.statuses,
                                onChange: function( value ) {
                                    setAttributes( { statuses: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Show Status Filter', 'kowboy' ),
                                checked: attributes.showStatusFilter,
                                onChange: function( value ) {
                                    setAttributes( { showStatusFilter: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Ignore Default Wrapper', 'kowboy' ),
                                checked: attributes.ignoreDefaultWrapper,
                                onChange: function( value ) {
                                    setAttributes( { ignoreDefaultWrapper: value } );
                                }
                            } ),
                            el( NumberControl, {
                                label: __( 'Per Page', 'kowboy' ),
                                min: 1,
                                value: attributes.perPage,
                                onChange: function( value ) {
                                    setAttributes( { perPage: parseInt( value, 10 ) || 10 } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Filter Template Path', 'kowboy' ),
                                value: attributes.filterTemplate,
                                onChange: function( value ) {
                                    setAttributes( { filterTemplate: value } );
                                }
                            } )
                        )
                    ),
                    el( 'section', blockProps,
                        el( 'p', { className: 'kowboy-dynamic-block__label' }, __( 'Search Properties Block', 'kowboy' ) ),
                        ServerSideRender ? el( ServerSideRender, {
                            block: 'kowboy/search-properties',
                            attributes: attributes
                        } ) : el( 'p', { className: 'kowboy-dynamic-block__hint' }, __( 'Preview unavailable in this editor.', 'kowboy' ) )
                    )
                ];
            },
            save: function() {
                return null;
            }
        } );
    }

    function registerSearchPropertiesPortraitBlock() {
        blocks.registerBlockType( 'kowboy/search-properties-portrait', {
            title: __( 'Search Properties (Portrait)', 'kowboy' ),
            description: __( 'Displays the property search results with portrait images.', 'kowboy' ),
            icon: 'format-image',
            category: 'kowboy',
            supports: {
                align: true,
                anchor: true,
                spacing: {
                    margin: true,
                    padding: true
                }
            },
            attributes: {
                headline: { type: 'string', default: '' },
                ajax: { type: 'boolean', default: false },
                template: { type: 'string', default: 'templates/2025/list-item/property-list-item-portrait.php' },
                statuses: { type: 'string', default: '' },
                showStatusFilter: { type: 'boolean', default: false },
                ignoreDefaultWrapper: { type: 'boolean', default: false },
                perPage: { type: 'number', default: 10 },
                filterTemplate: { type: 'string', default: '' }
            },
            edit: function( props ) {
                var attributes = props.attributes;
                var setAttributes = props.setAttributes;
                var blockProps = useBlockProps( { className: 'kowboy-dynamic-block' } );

                return [
                    el( InspectorControls, { key: 'search-properties-portrait-controls' },
                        el( PanelBody, { title: __( 'Search Properties (Portrait) Settings', 'kowboy' ), initialOpen: true },
                            el( TextControl, {
                                label: __( 'Headline', 'kowboy' ),
                                value: attributes.headline,
                                onChange: function( value ) {
                                    setAttributes( { headline: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Use AJAX', 'kowboy' ),
                                checked: attributes.ajax,
                                onChange: function( value ) {
                                    setAttributes( { ajax: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Template Path', 'kowboy' ),
                                value: attributes.template,
                                onChange: function( value ) {
                                    setAttributes( { template: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Statuses (comma-separated)', 'kowboy' ),
                                value: attributes.statuses,
                                onChange: function( value ) {
                                    setAttributes( { statuses: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Show Status Filter', 'kowboy' ),
                                checked: attributes.showStatusFilter,
                                onChange: function( value ) {
                                    setAttributes( { showStatusFilter: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Ignore Default Wrapper', 'kowboy' ),
                                checked: attributes.ignoreDefaultWrapper,
                                onChange: function( value ) {
                                    setAttributes( { ignoreDefaultWrapper: value } );
                                }
                            } ),
                            el( NumberControl, {
                                label: __( 'Per Page', 'kowboy' ),
                                min: 1,
                                value: attributes.perPage,
                                onChange: function( value ) {
                                    setAttributes( { perPage: parseInt( value, 10 ) || 10 } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Filter Template Path', 'kowboy' ),
                                value: attributes.filterTemplate,
                                onChange: function( value ) {
                                    setAttributes( { filterTemplate: value } );
                                }
                            } )
                        )
                    ),
                    el( 'section', blockProps,
                        el( 'p', { className: 'kowboy-dynamic-block__label' }, __( 'Search Properties (Portrait) Block', 'kowboy' ) ),
                        ServerSideRender ? el( ServerSideRender, {
                            block: 'kowboy/search-properties-portrait',
                            attributes: attributes
                        } ) : el( 'p', { className: 'kowboy-dynamic-block__hint' }, __( 'Preview unavailable in this editor.', 'kowboy' ) )
                    )
                ];
            },
            save: function() {
                return null;
            }
        } );
    }

    function registerFooterNewsletterBlock() {
        blocks.registerBlockType( 'kowboy/footer-newsletter-form', {
            title: __( 'Footer Newsletter Form', 'kowboy' ),
            description: __( 'Displays the footer newsletter form.', 'kowboy' ),
            icon: 'email',
            category: 'kowboy',
            supports: {
                align: true,
                anchor: true,
                spacing: {
                    margin: true,
                    padding: true
                }
            },
            attributes: {
                heading: { type: 'string', default: 'Har du några frågor?' },
                text: { type: 'string', default: 'Jag hjälper dig gärna med en kostnadsfri värdering.' },
                backgroundImage: { type: 'string', default: '' },
                leadReceiverId: { type: 'string', default: '' },
                officeId: { type: 'string', default: '' },
                roundedInputs: { type: 'boolean', default: true }
            },
            edit: function( props ) {
                var attributes = props.attributes;
                var setAttributes = props.setAttributes;
                var blockProps = useBlockProps( { className: 'kowboy-dynamic-block' } );

                return [
                    el( InspectorControls, { key: 'footer-newsletter-controls' },
                        el( PanelBody, { title: __( 'Newsletter Settings', 'kowboy' ), initialOpen: true },
                            el( TextControl, {
                                label: __( 'Heading', 'kowboy' ),
                                value: attributes.heading,
                                onChange: function( value ) {
                                    setAttributes( { heading: value } );
                                }
                            } ),
                            el( TextareaControl, {
                                label: __( 'Text', 'kowboy' ),
                                value: attributes.text,
                                onChange: function( value ) {
                                    setAttributes( { text: value } );
                                }
                            } ),
                            el( MediaUploadCheck, null,
                                el( MediaUpload, {
                                    onSelect: function( media ) {
                                        setAttributes( { backgroundImage: media.url } );
                                    },
                                    allowedTypes: [ 'image' ],
                                    value: attributes.backgroundImage,
                                    render: function( obj ) {
                                        return el( Button, { onClick: obj.open, isSecondary: true },
                                            attributes.backgroundImage ? __( 'Replace image', 'kowboy' ) : __( 'Select image', 'kowboy' )
                                        );
                                    }
                                } )
                            ),
                            attributes.backgroundImage && el( Button, {
                                isLink: true,
                                isDestructive: true,
                                onClick: function() {
                                    setAttributes( { backgroundImage: '' } );
                                }
                            }, __( 'Remove image', 'kowboy' ) ),
                            el( TextControl, {
                                label: __( 'Lead Receiver ID', 'kowboy' ),
                                value: attributes.leadReceiverId,
                                onChange: function( value ) {
                                    setAttributes( { leadReceiverId: value } );
                                }
                            } ),
                            el( TextControl, {
                                label: __( 'Office ID', 'kowboy' ),
                                value: attributes.officeId,
                                onChange: function( value ) {
                                    setAttributes( { officeId: value } );
                                }
                            } ),
                            el( components.ToggleControl, {
                                label: __( 'Rounded Inputs', 'kowboy' ),
                                checked: attributes.roundedInputs,
                                onChange: function( value ) {
                                    setAttributes( { roundedInputs: value } );
                                }
                            } )
                        )
                    ),
                    el( 'section', blockProps,
                        el( 'p', { className: 'kowboy-dynamic-block__label' }, __( 'Footer Newsletter Form Block', 'kowboy' ) ),
                        ServerSideRender ? el( ServerSideRender, {
                            block: 'kowboy/footer-newsletter-form',
                            attributes: attributes
                        } ) : el( 'p', { className: 'kowboy-dynamic-block__hint' }, __( 'Preview unavailable in this editor.', 'kowboy' ) )
                    )
                ];
            },
            save: function() {
                return null;
            }
        } );
    }

    blocks.registerBlockType( 'kowboy/location-contact', {
        title: __( 'Location + Contact', 'kowboy' ),
        description: __( 'Two-column block with map and contact details.', 'kowboy' ),
        icon: 'location-alt',
        category: 'kowboy',
        supports: {
            align: true,
            anchor: true,
            spacing: {
                margin: true,
                padding: true
            }
        },
        attributes: {
            heading: { type: 'string', source: 'html', selector: 'h2' },
            body: { type: 'string', source: 'html', selector: '.kowboy-location-contact__body' },
            mapUrl: { type: 'string', default: '' },
            emailLabel: { type: 'string', default: 'E-post' },
            emailValue: { type: 'string', default: '' },
            phoneLabel: { type: 'string', default: 'Mobil' },
            phoneValue: { type: 'string', default: '' }
        },
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps( { className: 'kowboy-location-contact' } );
            var hasMap = !!( attributes.mapUrl && attributes.mapUrl.trim() );
            var hasEmail = !!( attributes.emailValue && attributes.emailValue.trim() );
            var hasPhone = !!( attributes.phoneValue && attributes.phoneValue.trim() );
            var hasContacts = hasEmail || hasPhone;
            var gridClassName = hasMap
                ? 'kowboy-location-contact__grid'
                : 'kowboy-location-contact__grid kowboy-location-contact__grid--no-map';

            return [
                el( InspectorControls, { key: 'location-contact-controls' },
                    el( PanelBody, { title: __( 'Map', 'kowboy' ), initialOpen: true },
                        el( TextControl, {
                            label: __( 'Google Maps Embed URL', 'kowboy' ),
                            help: __( 'Paste the full embed URL (src from Google Maps iframe).', 'kowboy' ),
                            value: attributes.mapUrl,
                            onChange: function( value ) {
                                setAttributes( { mapUrl: value } );
                            }
                        } )
                    ),
                    el( PanelBody, { title: __( 'Contact', 'kowboy' ), initialOpen: false },
                        el( TextControl, {
                            label: __( 'Email Label', 'kowboy' ),
                            value: attributes.emailLabel,
                            onChange: function( value ) {
                                setAttributes( { emailLabel: value } );
                            }
                        } ),
                        el( TextControl, {
                            label: __( 'Email', 'kowboy' ),
                            value: attributes.emailValue,
                            onChange: function( value ) {
                                setAttributes( { emailValue: value } );
                            }
                        } ),
                        el( TextControl, {
                            label: __( 'Phone Label', 'kowboy' ),
                            value: attributes.phoneLabel,
                            onChange: function( value ) {
                                setAttributes( { phoneLabel: value } );
                            }
                        } ),
                        el( TextControl, {
                            label: __( 'Phone', 'kowboy' ),
                            value: attributes.phoneValue,
                            onChange: function( value ) {
                                setAttributes( { phoneValue: value } );
                            }
                        } )
                    )
                ),
                el( 'section', blockProps,
                    el( 'div', { className: gridClassName },
                        hasMap && el( 'div', { className: 'kowboy-location-contact__map' },
                            el( 'iframe', {
                                src: attributes.mapUrl,
                                title: __( 'Map', 'kowboy' ),
                                allowFullScreen: true,
                                loading: 'lazy'
                            } )
                        ),
                        el( 'div', { className: 'kowboy-location-contact__content' },
                            el( RichText, {
                                tagName: 'h2',
                                value: attributes.heading,
                                placeholder: __( 'Add heading...', 'kowboy' ),
                                onChange: function( value ) {
                                    setAttributes( { heading: value } );
                                }
                            } ),
                            el( RichText, {
                                tagName: 'div',
                                className: 'kowboy-location-contact__body',
                                value: attributes.body,
                                placeholder: __( 'Add description...', 'kowboy' ),
                                multiline: 'p',
                                onChange: function( value ) {
                                    setAttributes( { body: value } );
                                }
                            } ),
                            hasContacts && el( 'div', { className: 'kowboy-location-contact__contacts' },
                                hasEmail && el( 'div', { className: 'kowboy-location-contact__contact' },
                                    el( 'div', { className: 'kowboy-location-contact__label' }, attributes.emailLabel || __( 'E-post', 'kowboy' ) ),
                                    el( 'a', { href: 'mailto:' + attributes.emailValue }, attributes.emailValue )
                                ),
                                hasPhone && el( 'div', { className: 'kowboy-location-contact__contact' },
                                    el( 'div', { className: 'kowboy-location-contact__label' }, attributes.phoneLabel || __( 'Mobil', 'kowboy' ) ),
                                    el( 'a', { href: 'tel:' + attributes.phoneValue }, attributes.phoneValue )
                                )
                            )
                        )
                    )
                )
            ];
        },
        save: function( props ) {
            var attributes = props.attributes;
            var hasMap = !!( attributes.mapUrl && attributes.mapUrl.trim() );
            var hasEmail = !!( attributes.emailValue && attributes.emailValue.trim() );
            var hasPhone = !!( attributes.phoneValue && attributes.phoneValue.trim() );
            var hasContacts = hasEmail || hasPhone;
            var hasHeading = !!attributes.heading;
            var hasBody = !!attributes.body;

            if ( !hasMap && !hasContacts && !hasHeading && !hasBody ) {
                return null;
            }

            var blockProps = blockEditor.useBlockProps.save( { className: 'kowboy-location-contact' } );
            var gridClassName = hasMap
                ? 'kowboy-location-contact__grid'
                : 'kowboy-location-contact__grid kowboy-location-contact__grid--no-map';

            return el( 'section', blockProps,
                el( 'div', { className: gridClassName },
                    hasMap && el( 'div', { className: 'kowboy-location-contact__map' },
                        el( 'iframe', {
                            src: attributes.mapUrl,
                            title: __( 'Map', 'kowboy' ),
                            allowFullScreen: true,
                            loading: 'lazy'
                        } )
                    ),
                    el( 'div', { className: 'kowboy-location-contact__content' },
                        attributes.heading && el( RichText.Content, {
                            tagName: 'h2',
                            value: attributes.heading
                        } ),
                        attributes.body && el( RichText.Content, {
                            tagName: 'div',
                            className: 'kowboy-location-contact__body',
                            value: attributes.body
                        } ),
                        hasContacts && el( 'div', { className: 'kowboy-location-contact__contacts' },
                            hasEmail && el( 'div', { className: 'kowboy-location-contact__contact' },
                                el( 'div', { className: 'kowboy-location-contact__label' }, attributes.emailLabel || __( 'E-post', 'kowboy' ) ),
                                el( 'a', { href: 'mailto:' + attributes.emailValue }, attributes.emailValue )
                            ),
                            hasPhone && el( 'div', { className: 'kowboy-location-contact__contact' },
                                el( 'div', { className: 'kowboy-location-contact__label' }, attributes.phoneLabel || __( 'Mobil', 'kowboy' ) ),
                                el( 'a', { href: 'tel:' + attributes.phoneValue }, attributes.phoneValue )
                            )
                        )
                    )
                )
            );
        }
    } );

    registerAgentsListBlock();
    registerSearchPropertiesBlock();
    registerSearchPropertiesPortraitBlock();
    registerFooterNewsletterBlock();
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
