//@ts-check

import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, Button, PanelBody, PanelRow } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { Fragment } from 'react/jsx-runtime';
import { useWpSelector } from '../../wp_selector';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 *  {
 *      links: {label: string, url: string}[],
 *      websiteName: string | undefined,
 *      contactUrl: string
 *  }>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const { links, websiteName, contactUrl } = attributes;

    const siteIconId = useWpSelector(
        (store) => /** @type {{site_icon?: string}} */(store.getEntityRecord('root', 'site'))?.site_icon,
        []
    );
    const siteTitle = useWpSelector((store) => store.getSite(undefined)?.title ?? "", []);
    const siteIconUrl = useWpSelector(
        (store) => store.getMedia(siteIconId)?.source_url,
        [siteIconId]
    )

    const addLink = () => {
        setAttributes({ links: [...links, { label: '', url: '' }] });
    };

    /**
     * @param {number} index The index of the link to update.
     * @param {"label" | "url"} key Which property to update.
     * @param {string} value The new value.
     */
    const updateLink = (index, key, value) => {
        const updatedLinks = [...links];
        updatedLinks[index][key] = value;
        setAttributes({ links: updatedLinks });
    };

    /**
     * @param {number} index The index of the link to remove.
     */
    const removeLink = (index) => {
        const updatedLinks = links.filter((_, i) => i !== index);
        setAttributes({ links: updatedLinks });
    };

    /**
     * @param {string} value The new website name to use in the mobile appbar
     */
    const updateWebsiteName = (value) => {
        setAttributes({ websiteName: value || null });
    }

    /**
     * @param {string} value The new contact URL
     */
    const updateContactUrl = (value) => {
        setAttributes({ contactUrl: value });
    }

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title="Appbar Settings" initialOpen>
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
                            label="Website name"
                            value={websiteName ?? ""}
                            onChange={updateWebsiteName}
                        />
                    </PanelRow>
                    <PanelRow>
                        <TextControl
                            label="Contact page URL"
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
                        {links.map((link) => (
                            <span key={link.label} className="evolutio-appbar__link">
                                {link.label}
                            </span>
                        ))}
                    </nav>
                    <span className="evolutio-appbar__button evolutio-no-hover">Contact</span>
                </div>
            </div>
            <div {...useBlockProps({ className: "evolutio-appbar-mobile-container" })}>
                <span className="evolutio-appbar-brand-container evolutio-no-hover">
                    <img src={siteIconUrl} alt={(websiteName ?? siteTitle) + ' Logo'}
                        width="50" height="50" />
                    <span>{websiteName ?? siteTitle}</span>
                </span>
                <input type="checkbox" id="nav-toggle" hidden />
                <label htmlFor="nav-toggle" id="nav-toggle-label">
                    <div className="top line"></div>
                    <div className="middle line"></div>
                    <div className="bottom line"></div>
                </label>
                <nav className="evolutio-mobile-nav">
                    <ul>
                        {links.map((link) => (
                            <li key={link.label}>
                                <span className="evolutio-appbar__link">
                                    {link.label}
                                </span>
                            </li>
                        ))}
                        <span className="evolutio-appbar__button evolutio-no-hover">Contactez-nous</span>
                    </ul>
                </nav>
            </div>
        </Fragment>
    );
}
