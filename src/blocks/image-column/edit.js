//@ts-check

import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck, InnerBlocks } from '@wordpress/block-editor';
import { Button, PanelBody, PanelRow, SelectControl } from '@wordpress/components';
import { Fragment } from 'react';

/** @type {import('@wordpress/blocks').TemplateArray} */
const COLUMN_TEMPLATE = [
    ['core/heading', { fontSize: "x-large", placeholder: 'Section title' }],
    ['core/paragraph', { placeholder: 'Your descriptive text' }],
];

const PLACEHOLDER_IMAGE = "/wp-includes/images/media/default.svg";

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
* 	{
* 		image: {
*           id: number,
*           url: string,
*           alt: string,
*           srcSet: string,
*       },
*       desktopDirection: 'row' | 'row-reverse',
*       mobileDirection: 'column' | 'column-reverse',
*       imageFit: 'cover' | 'contain',
*	}>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Edit({ attributes, setAttributes }) {
    const { image, desktopDirection, mobileDirection, imageFit } = attributes;

    let containerClassName = "evolutio-imagecolumn";
    containerClassName += ` evolutio-imagecolumn--desktop-${desktopDirection}`;
    containerClassName += ` evolutio-imagecolumn--mobile-${mobileDirection}`;

    /**
    * @param {{id: number} & {[k: string]: unknown}} newImage The new contact URL
    */
    const updateBrandImage = (newImage) => {
        if (!newImage.url || typeof newImage.url !== "string") {
            console.error("Invalid image URL", newImage);
            return;
        }
        if (typeof newImage.alt !== "string") {
            console.error("Invalid image alt", newImage);
            return;
        }
        if (!newImage.alt) {
            console.warn("Missing image alt");
        }
        const sizes = /** @type {Record<string, {url: string, width: number}>} */ (newImage.sizes) || {};
        setAttributes({
            image: {
                id: newImage.id,
                url: sizes.large?.url || newImage.url,
                alt: newImage.alt,
                srcSet: buildSrcSet(sizes),
            }
        });
    };

    /**
     * @param {'cover' | 'contain'} newFit The new image fit.
     */
    const updateImageFit = (newFit) => {
        setAttributes({ imageFit: newFit });
    }

    /**
     * @param {'row' | 'row-reverse'} newDirection The new flex direction on large screens.
     */
    const updateDesktopDirection = (newDirection) => {
        setAttributes({ desktopDirection: newDirection });
    }

    /**
     * @param {'column' | 'column-reverse'} newDirection The new flex direction on small screens.
     */
    const updateMobileDirection = (newDirection) => {
        setAttributes({ mobileDirection: newDirection });
    }

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title="Column Settings" initialOpen>
                    <PanelRow>
                        <label>Image</label>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={updateBrandImage}
                                allowedTypes={['image']}
                                value={image?.id}
                                render={({ open }) => (
                                    <Button onClick={open}>
                                        {image?.url ? (
                                            <img src={image.url} alt="Selected image" style={{ maxWidth: 50, maxHeight: 50 }} />
                                        ) : (
                                            "Upload Image"
                                        )}
                                    </Button>
                                )}
                            />
                        </MediaUploadCheck>
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label="Image fit"
                            value={imageFit}
                            options={[
                                { label: 'Cover', value: 'cover' },
                                { label: 'contains', value: 'contain' },
                            ]}
                            onChange={updateImageFit}
                            __nextHasNoMarginBottom
                        />
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label="Desktop Direction"
                            value={desktopDirection}
                            options={[
                                { label: 'Left to right', value: 'row' },
                                { label: 'Right to left', value: 'row-reverse' },
                            ]}
                            onChange={updateDesktopDirection}
                            __nextHasNoMarginBottom
                        />
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label="Mobile Direction"
                            value={mobileDirection}
                            options={[
                                { label: 'Top to bottom', value: 'column' },
                                { label: 'Bottom to top', value: 'column-reverse' },
                            ]}
                            onChange={updateMobileDirection}
                            __nextHasNoMarginBottom
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div {...useBlockProps({ className: containerClassName })}>
                <div className="evolutio-imagecolumn__image">
                    <img
                        src={image?.url || PLACEHOLDER_IMAGE}
                        srcSet={image?.srcSet}
                        alt={image?.alt || "Image"}
                        style={{ objectFit: imageFit }}
                        sizes="(max-width: 782px) 100vw, 50vw"
                        decoding="async"
                    />
                </div>
                <div className="evolutio-imagecolumn__content">
                    <InnerBlocks
                        template={COLUMN_TEMPLATE}
                        templateLock="all"
                    />
                </div>
            </div>
        </Fragment >
    );
}

/**
 * @param {Record<string, {
* url: string,
* width: number,
* }>} sizes The media sizes array.
* @returns {string} The srcset attribute value.
*/
function buildSrcSet(sizes) {
    return Object.values(sizes)
        .map(({ url, width }) => `${url} ${width}w`)
        .join(", ");
}

