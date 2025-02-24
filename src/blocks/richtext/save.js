//@ts-check
import { RichText, useBlockProps } from '@wordpress/block-editor';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
*  {
    * 		text: string,
*  }>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Save({ attributes }) {
    const { text } = attributes;

    return (
        <RichText.Content
            {...useBlockProps.save({ className: "evolutio-richtext" })}
            tagName="div"
            value={text}
        />
    )
}