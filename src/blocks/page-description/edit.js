//@ts-check

import { useBlockProps, RichText } from '@wordpress/block-editor';

/**
 * @type {{
*   data: typeof import('@wordpress/data'),
* }}
*/
// @ts-ignore
const wp = window.wp;

const DISABLED_FORMATS = [
    "core/image",
    "core/language",
]

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
* 	{
* 		text: string,
*	}>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Edit({ attributes, setAttributes }) {
    const { text } = attributes;

    /** @type {{name: string}[]} */
    const defaultFormats = wp.data.select('core/rich-text').getFormatTypes();
    const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name);

    /**
     * @param {string} newText The new paragraph text
     */
    const updateDescription = (newText) => {
        setAttributes({ text: newText });
    }

    return (
        <RichText
            {...useBlockProps({ className: "evolutio-pagedescription" })}
            tagName="div"
            allowedFormats={allowedFormats}
            value={text}
            onChange={updateDescription}
            placeholder="Write your text here"
        />
    );
}
