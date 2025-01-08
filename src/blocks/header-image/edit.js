//@ts-check
/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 * 
 * @argument {import('@wordpress/blocks').BlockEditProps<{minHeight: string}>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
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

	return (
		<div {...useBlockProps({ style: customStyle })}></div>
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
