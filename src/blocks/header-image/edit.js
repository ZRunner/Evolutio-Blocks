//@ts-check

import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl, PanelBody, PanelRow } from '@wordpress/components';
import { Fragment } from 'react/jsx-runtime';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 * 	{
 * 		minHeight: string,
 * 		title: string,
 * 		subtitle: string
 *	}>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle } = attributes;
	// extract background style from block props
	const props = useBlockProps();
	const { backgroundColor, backgroundImage } = props.style;

	// rewrite backgroundImage to get both a linear-gradient and the original image
	const backgroundImageStyle = getBackgroundImageStyle({ backgroundColor, backgroundImage });

	// override component style with new background image
	const customStyle = {
		...props.style,
		minHeight: attributes.minHeight ?? '800px',
		backgroundImage: backgroundImageStyle,
	};

	/**
	 * @param {string} value The new image title
	 */
	const updateTitle = (value) => {
		setAttributes({ title: value });
	};

	/**
	 * @param {string} value The new image subtitle
	 */
	const updateSubtitle = (value) => {
		setAttributes({ subtitle: value });
	}

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title="Text Settings" initialOpen>
					<PanelRow>
						<TextControl
							label="Title"
							value={title}
							onChange={updateTitle}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
					<PanelRow>
						<TextareaControl
							label="Subtitle"
							value={subtitle}
							onChange={updateSubtitle}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps({ style: customStyle, className: "evolutio-background-container" })}>
				<div className="evolutio-text-container">
					<h1 className="evolutio-title">{title}</h1>
					<h2 className="evolutio-subtitle">{subtitle}</h2>
				</div>
			</div>
		</Fragment>
	);
}

/**
 * 
 * @param {{backgroundColor: unknown, backgroundImage: unknown}} params
 * @returns {string}
 */
function getBackgroundImageStyle({ backgroundColor, backgroundImage }) {
	// rewrite backgroundImage to get both a linear-gradient and the original image
	if (typeof backgroundColor !== 'string' || typeof backgroundImage !== 'string') {
		return null;
	}
	if (!backgroundImage) return null; // No image, no style.
	if (!backgroundColor) return backgroundImage; // Only image.

	return `linear-gradient(${backgroundColor}A3 17%, ${backgroundColor}CC 50%), ${backgroundImage}`;
}
