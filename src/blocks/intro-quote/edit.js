//@ts-check

import { InspectorControls, RichText, useBlockProps } from "@wordpress/block-editor";
import { PanelBody, PanelRow, TextControl } from "@wordpress/components";
import { Fragment } from "react/jsx-runtime";
import { useRichtextDefaultFormats } from "../../wp_selector";

const DISABLED_FORMATS = [
	"core/image",
	"core/italic",
	"core/language"
];

/**
 * @argument {import("@wordpress/blocks").BlockEditProps<
 * 	{
 * 		content: string,
 * 		link: string,
 * 		linkText: string,
 *	}>} props
 * @return {import("react").ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { content, link, linkText } = attributes;
	// extract background style from block props
	const props = useBlockProps();
	const backgroundImage = attributes.style.background.backgroundImage.url;

	const customStyle = {
		...props.style,
		backgroundImage: undefined
	};

	const defaultFormats = useRichtextDefaultFormats();
	const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name);

	/**
	 * @param {string} newLink The new quote content
	 */
	const updateLink = (newLink) => {
		setAttributes({ link: newLink });
	};

	/**
	 * @param {string} newLinkText The new link label
	 */
	const updateLinkText = (newLinkText) => {
		setAttributes({ linkText: newLinkText });
	};

	/**
	 * @param {string} newContent The new quote content
	 */
	const updateContent = (newContent) => {
		setAttributes({ content: newContent });
	};

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title="Text Settings" initialOpen>
					<PanelRow>
						<TextControl
							label="Link Label"
							value={linkText}
							onChange={updateLinkText}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label="Link URL"
							value={link}
							onChange={updateLink}
							__nextHasNoMarginBottom
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps({ style: customStyle })}>
				<img className="evolutio-quote-bg" src={backgroundImage} alt="Evolutio logo" />
				<div className="evolutio-quote-block">
					<RichText
						tagName="p"
						allowedFormats={allowedFormats}
						value={content}
						onChange={updateContent}
						placeholder="Enter your quote..."
						className="evolutio-quote-content"
					/>
				</div>
				<div className="evolutio-quote-link">
					<span className="evolutio-link">{linkText}</span>
				</div>
			</div>
		</Fragment>
	);
}
