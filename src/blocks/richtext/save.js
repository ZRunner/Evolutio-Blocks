//@ts-check
import { RichText, useBlockProps } from '@wordpress/block-editor';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
*  {
    * 		text: string,
    *       maxWidth: string,
*  }>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Save({ attributes }) {
    const { text, maxWidth } = attributes;

    return (
        <div {...useBlockProps.save({ className: "evolutio-richtext" })}>
            <RichText.Content
                style={{ maxWidth, letterSpacing: "0.02px" }}
                tagName="div"
                value={text}
            />
        </div>
    )
}