//@ts-check

import { MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { TextControl, SelectControl, PanelBody, PanelRow, Button } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { Fragment } from 'react/jsx-runtime';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 *  {
 *      internalLinks: {label: string, url: string}[],
 *      externalLinks: {label: string, icon: "hammer" | "linkedin", url: string}[],
 *      contactUrl: string,
 *      brandImage: {id: number, url: string,} | null,
 *  }>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const { internalLinks, externalLinks, contactUrl, brandImage } = attributes;

    const addExternalLink = () => {
        setAttributes({ externalLinks: [...externalLinks, { label: '', icon: 'hammer', url: '' }] });
    };

    const addInternalLink = () => {
        setAttributes({ internalLinks: [...internalLinks, { label: '', url: '' }] });
    };

    /**
     * @param {number} index The index of the internal link to update.
     * @param {"label" | "url"} key Which property to update.
     * @param {string} value The new value.
     */
    const updateInternalLink = (index, key, value) => {
        const updatedIntLinks = [...internalLinks];
        updatedIntLinks[index][key] = value;
        setAttributes({ internalLinks: updatedIntLinks });
    };

    /**
     * @param {number} index The index of the internal link to remove.
     */
    const removeInternalLink = (index) => {
        const updatedIntLinks = internalLinks.filter((_, i) => i !== index);
        setAttributes({ internalLinks: updatedIntLinks });
    };

    /**
     * @param {number} index The index of the ext. link to update.
     * @param {"label" | "url" | "icon" } key Which property to update.
     * @param {string} value The new value.
     */
    const updateExternalLink = (index, key, value) => {
        const updatedExtLinks = [...externalLinks];
        if (key === "icon") {
            if (value === "linkedin" || value === "hammer") {
                updatedExtLinks[index][key] = value;
            }
        } else {
            updatedExtLinks[index][key] = value;
        }
        setAttributes({ externalLinks: updatedExtLinks });
    };

    /**
     * @param {number} index The index of the ext. link to remove.
     */
    const removeExternalLink = (index) => {
        const updatedExtLinks = externalLinks.filter((_, i) => i !== index);
        setAttributes({ externalLinks: updatedExtLinks });
    };

    /**
     * @param {string} value The new contact URL
     */
    const updateContactUrl = (value) => {
        setAttributes({ contactUrl: value });
    }

    /**
     * @param {{id: number} & {[k: string]: unknown}} image The new contact URL
     */
    const updateBrandImage = (image) => {
        if (!image.url || typeof image.url !== "string") {
            console.error("Invalid image URL", image);
            return;
        }
        setAttributes({ brandImage: { id: image.id, url: image.url } });
    };

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title="Footer Settings" initialOpen>
                    <PanelRow>
                        <TextControl
                            label="Contact page URL"
                            value={contactUrl}
                            onChange={updateContactUrl}
                            __nextHasNoMarginBottom
                        />
                    </PanelRow>
                    <PanelRow>
                        <label>Custom Logo</label>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={updateBrandImage}
                                allowedTypes={['image']}
                                value={brandImage?.id}
                                render={({ open }) => (
                                    <Button onClick={open}>
                                        {brandImage ? 'Change Image' : 'Upload Image'}
                                    </Button>
                                )}
                            />
                        </MediaUploadCheck>
                    </PanelRow>
                </PanelBody>
                <PanelBody title="Internal Links" initialOpen>
                    <PanelRow>
                        <Button variant="primary" onClick={addInternalLink}>
                            Add Link
                        </Button>
                    </PanelRow>
                    {internalLinks.map((link, index) => (
                        <Fragment key={index}>
                            <hr />
                            <TextControl
                                label={`Label ${index + 1}`}
                                value={link.label}
                                onChange={(value) => updateInternalLink(index, 'label', value)}
                                __nextHasNoMarginBottom
                            />
                            <TextControl
                                label={`URL ${index + 1}`}
                                value={link.url}
                                onChange={(value) => updateInternalLink(index, 'url', value)}
                                __nextHasNoMarginBottom
                            />
                            <Button isDestructive onClick={() => removeInternalLink(index)}>
                                Remove
                            </Button>
                        </Fragment>
                    ))}
                </PanelBody>
                <PanelBody title="External Links" initialOpen>
                    <PanelRow>
                        <Button variant="primary" onClick={addExternalLink}>
                            Add Link
                        </Button>
                    </PanelRow>
                    {externalLinks.map((link, index) => (
                        <Fragment key={index}>
                            <hr />
                            <TextControl
                                label={`Label ${index + 1}`}
                                value={link.label}
                                onChange={(value) => updateExternalLink(index, 'label', value)}
                                __nextHasNoMarginBottom
                            />
                            <TextControl
                                label={`URL ${index + 1}`}
                                value={link.url}
                                onChange={(value) => updateExternalLink(index, 'url', value)}
                                __nextHasNoMarginBottom
                            />
                            <PanelRow>
                                <SelectControl
                                    label={`Icon ${index + 1}`}
                                    value={link.icon}
                                    options={[
                                        { label: 'Hammer', value: 'hammer' },
                                        { label: 'LinkedIn', value: 'linkedin' },
                                    ]}
                                    onChange={(value) => updateExternalLink(index, 'icon', value)}
                                    __nextHasNoMarginBottom
                                />
                                <Button isDestructive onClick={() => removeExternalLink(index)}>
                                    Remove
                                </Button>
                            </PanelRow>
                        </Fragment>
                    ))}
                </PanelBody>
            </InspectorControls>
            <div {...useBlockProps({ className: "evolutio-footer" })}>
                <div className="evolutio-footer__top">
                    <div className="evolutio-footer__contactbox">
                        <div className="evolutio-footer__contacttext" >Prenez directement rendez-vous !</div>
                        <span title={contactUrl} className="evolutio-footer__contactbutton">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fillRule="evenodd" clipRule="evenodd" d="M12.3744 13.8375C17.6931 19.1548 18.8997 13.0033 22.2862 16.3874C25.5511 19.6514 27.4275 20.3053 23.291 24.4406C22.7729 24.857 19.4808 29.8667 7.9115 18.3006C-3.65927 6.73302 1.34748 3.43761 1.76399 2.91962C5.91052 -1.22719 6.55318 0.660193 9.81802 3.92412C13.2045 7.30968 7.05557 8.52023 12.3744 13.8375Z" fill="#F9F8F9" />
                            </svg>
                            Prenez rendez-vous
                        </span>
                    </div>
                </div>
                <div className="evolutio-footer__bottom">
                    <div className="evolutio-footer__flexrow">
                        <span className="evolutio-footer__brandcontainer">
                            <img src={brandImage?.url} alt="Evolutio Avocats Logo" width="60" height="auto" />
                            <span>Evolutio</span>
                        </span>
                        <div className="evolutio-footer__lefthalf">
                            <div className="evolutio-footer__navigation">
                                {internalLinks.map(link => (
                                    <div key={link.url} title={link.url} className="evolutio-footer__innerlink">
                                        {link.label}
                                    </div>
                                ))}
                            </div>
                        </div>
                        <hr />
                        <div className="evolutio-footer__righthalf">
                            <div className="evolutio-footer__outerlinks">
                                {externalLinks.map(link => (
                                    <ExternalLink key={link.url} link={link} />
                                ))}
                            </div>
                            <LegalLinks className="evolutio-footer__desktop" />
                        </div>
                    </div>
                    <div className="evolutio-footer__mobile-legals">
                        <LegalLinks />
                    </div>
                </div>
            </div>
        </Fragment>
    );
}

/**
 * @param {{ link: { label: string, url: string, icon: "hammer" | "linkedin" } }} props
 */
function ExternalLink({ link }) {
    return (
        <div className="evolutio-footer__outerlink" title={link.url}>
            {link.icon === "hammer" ? <HammerIcon /> : <LinkedInIcon />}
            {link.label}
        </div>
    );
}

function HammerIcon() {
    return (
        <svg aria-hidden="true" data-prefix="fa" data-icon="gavel" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
            <path fill="currentColor" d="M504.971 199.362l-22.627-22.627c-9.373-9.373-24.569-9.373-33.941 0l-5.657 5.657L329.608 69.255l5.657-5.657c9.373-9.373 9.373-24.569 0-33.941L312.638 7.029c-9.373-9.373-24.569-9.373-33.941 0L154.246 131.48c-9.373 9.373-9.373 24.569 0 33.941l22.627 22.627c9.373 9.373 24.569 9.373 33.941 0l5.657-5.657 39.598 39.598-81.04 81.04-5.657-5.657c-12.497-12.497-32.758-12.497-45.255 0L9.373 412.118c-12.497 12.497-12.497 32.758 0 45.255l45.255 45.255c12.497 12.497 32.758 12.497 45.255 0l114.745-114.745c12.497-12.497 12.497-32.758 0-45.255l-5.657-5.657 81.04-81.04 39.598 39.598-5.657 5.657c-9.373 9.373-9.373 24.569 0 33.941l22.627 22.627c9.373 9.373 24.569 9.373 33.941 0l124.451-124.451c9.372-9.372 9.372-24.568 0-33.941z" />
        </svg>
    )
}

function LinkedInIcon() {
    return (
        <svg aria-hidden="true" data-prefix="fab" data-icon="linkedin-in" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
            <path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z" />
        </svg>
    )
}

/**
 * @param {{ className?: string }} props
 */
function LegalLinks({ className }) {
    return (
        <Fragment>
            <div className={"evolutio-footer__legal " + (className ?? "")} title="/politique-de-confidentialite">
                Mentions légales et politique de confidentialité
            </div>
            <div className={"evolutio-footer__legal " + (className ?? "")}>
                Evolutio Avocats - 2024
            </div>
        </Fragment>
    )
}
