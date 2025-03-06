//@ts-check
import { RichText, useBlockProps } from '@wordpress/block-editor';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
* 	{
    * 		text: string,
    *       style?: {
    *           typography?: {
    *               textAlign?: import('csstype').Property.TextAlign,
    *           }
    *       }
    *	}>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Save({ attributes }) {
    const { text } = attributes;
    const textAlign = attributes.style?.typography?.textAlign;

    return (
        <RichText.Content
            {...useBlockProps.save({ className: "evolutio-pagedescription", style: { textAlign } })}
            tagName="div"
            value={text}
        />
    )
}