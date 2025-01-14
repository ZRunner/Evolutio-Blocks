import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, Button, PanelBody, PanelRow } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { Fragment } from 'react/jsx-runtime';
import { useEffect, useState } from 'react';

export default function Edit({ attributes, setAttributes }) {
    const { links, websiteName, contactUrl } = attributes;
    const [siteIconUrl, setSiteIconUrl] = useState('');

    const siteIconId = wp.data.select('core').getEntityRecord('root', 'site')?.site_icon;
    const siteAttributes = wp.data.select('core').getSite();
    const siteTitle = siteAttributes?.title ?? "";

    useEffect(() => {
        if (!siteIconId) return;
        const unsubscribe = wp.data.subscribe(() => {
            const media = wp.data.select('core').getMedia(siteIconId);
            if (media) {
                setSiteIconUrl(media.source_url);
                unsubscribe(); // Stop subscribing once data is fetched.
            }
        });
    }, [siteIconId]);

    const addLink = () => {
        setAttributes({ links: [...links, { label: '', url: '' }] });
    };

    const updateLink = (index, key, value) => {
        const updatedLinks = [...links];
        updatedLinks[index][key] = value;
        setAttributes({ links: updatedLinks });
    };

    const removeLink = (index) => {
        const updatedLinks = links.filter((_, i) => i !== index);
        setAttributes({ links: updatedLinks });
    };

    const updateWebsiteName = (value) => {
        setAttributes({ websiteName: value || null });
    }

    const updateContactUrl = (value) => {
        setAttributes({ contactUrl: value });
    }

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title="Appbar Settings" initialOpen={true}>
                    <PanelRow>
                        <Button variant="primary" onClick={addLink}>
                            Add Link
                        </Button>
                    </PanelRow>
                    {links.map((link, index) => (
                        <PanelRow key={index}>
                            <TextControl
                                label={`Label ${index + 1}`}
                                value={link.label}
                                onChange={(value) => updateLink(index, 'label', value)}
                            />
                            <TextControl
                                label={`URL ${index + 1}`}
                                value={link.url}
                                onChange={(value) => updateLink(index, 'url', value)}
                            />
                            <Button isDestructive onClick={() => removeLink(index)}>
                                Remove
                            </Button>
                        </PanelRow>
                    ))}
                    <PanelRow>
                        <TextControl
                            label={`Website name`}
                            value={websiteName}
                            onChange={updateWebsiteName}
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label={`Contact page URL`}
                            value={contactUrl}
                            onChange={updateContactUrl}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div {...useBlockProps({ className: "evolutio-appbar-desktop-container" })}>
                <div className="evolutio-appbar-background">
                </div>
                <div className="evolutio-appbar__floating">
                    <nav className="evolutio-appbar__nav">
                        {links.map((link, index) => (
                            <span key={index} href={link.url} className="evolutio-appbar__link">
                                {link.label}
                            </span>
                        ))}
                    </nav>
                    <span className="evolutio-appbar__button" href={contactUrl}>Contact</span>
                </div>
            </div>
            <div className="evolutio-appbar-mobile-container">
                <span className="evolutio-appbar-brand-container" href="/">
                    <img src={siteIconUrl} alt={(websiteName ?? siteTitle) + ' Logo'}
                        width="50" height="50" />
                    <span>{websiteName ?? siteTitle}</span>
                </span>
                <svg width="24" height="24" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="1.43945" y1="1.5" x2="22.068" y2="1.5" stroke="#092747" strokeWidth="2" strokeLinecap="round" />
                    <line x1="1.43945" y1="16.5859" x2="22.068" y2="16.5859" stroke="#092747" strokeWidth="2" strokeLinecap="round" />
                    <line x1="1.43945" y1="9.04297" x2="22.068" y2="9.04297" stroke="#092747" strokeWidth="2" strokeLinecap="round" />
                </svg>
            </div>
        </Fragment>
    );
}
