//@ts-check

import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from "@wordpress/block-editor";
import { Button, PanelBody, PanelRow, TextControl } from "@wordpress/components";
import { Fragment } from "react";

/** @type {import("@wordpress/blocks").TemplateArray} */
const COLUMN_TEMPLATE = [
	["core/paragraph", { placeholder: "Your descriptive text" }]
];

const PLACEHOLDER_IMAGE = "/wp-includes/images/media/default.svg";

/**
 * @argument {import("@wordpress/blocks").BlockEditProps<
 * 	{
 * 		image: {
 *           id: number,
 *           url: string,
 *           alt: string,
 *           srcSet: string,
 *       },
 *       header: string,
 *       title: string,
 *       subtitle: string,
 *       pageUrl: string,
 *	}>} props
 * @return {import("react").ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { image, header, title, subtitle, pageUrl } = attributes;

	let containerClassName = "evolutio-membersingle";

	/**
	 * @param {{id: number} & {[k: string]: unknown}} newImage The new image URL
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
				srcSet: buildSrcSet(sizes)
			}
		});
	};

	/**
	 * @param {string} newHeader The new block header text.
	 */
	const updateHeader = (newHeader) => {
		setAttributes({ header: newHeader });
	};

	/**
	 * @param {string} newTitle The new title text.
	 */
	const updateTitle = (newTitle) => {
		setAttributes({ title: newTitle });
	};

	/**
	 * @param {string} newSubtitle The new subtitle text.
	 */
	const updateSubtitle = (newSubtitle) => {
		setAttributes({ subtitle: newSubtitle });
	};

	/**
	 * @param {string} newPageUrl The new URL to redirect to.
	 */
	const updatePageUrl = (newPageUrl) => {
		setAttributes({ pageUrl: newPageUrl || null });
	};

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title="Block Settings" initialOpen>
					<PanelRow>
						<label>Image</label>
						<MediaUploadCheck>
							<MediaUpload
								onSelect={updateBrandImage}
								allowedTypes={["image"]}
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
						<TextControl
							label="Header text"
							value={header}
							onChange={updateHeader}
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Title"
							value={title}
							onChange={updateTitle}
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Subtitle"
							value={subtitle}
							onChange={updateSubtitle}
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Page URL"
							value={pageUrl}
							onChange={updatePageUrl}
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps({ className: containerClassName })}>
				<h3>{header}</h3>
				<div className="evolutio-membersingle__row">
					<div className="evolutio-membersingle__left">
						<img
							src={image?.url || PLACEHOLDER_IMAGE}
							srcSet={image?.srcSet}
							alt={image?.alt || "Image"}
							className="evolutio-membersingle__image"
							sizes="(max-width: 782px) 100vw, 50vw"
							decoding="async"
						/>
						<div className="evolutio-membersingle__title">
							{title}
						</div>
						<div className="evolutio-membersingle__subtitle">
							{subtitle}
						</div>
					</div>
					<div className="evolutio-membersingle__content">
						<InnerBlocks
							template={COLUMN_TEMPLATE}
							templateLock="all"
						/>
						{pageUrl && <a className="evolutio-membersingle__link evolutio-link">
							Lire la suite
						</a>}
					</div>
				</div>
			</div>
		</Fragment>
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

