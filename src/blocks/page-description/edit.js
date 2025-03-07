//@ts-check

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelRow, PanelBody, Button } from '@wordpress/components';
import { Fragment } from 'react';
import { useRichtextDefaultFormats } from '../../wp_selector';

const DISABLED_FORMATS = [
    "core/image",
    "core/language",
]

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
export default function Edit({ attributes, setAttributes }) {
    const { text } = attributes;
    const textAlign = attributes.style?.typography?.textAlign;

    const defaultFormats = useRichtextDefaultFormats();
    const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name);

    /**
     * @param {string} newText The new paragraph text
     */
    const updateDescription = (newText) => {
        setAttributes({ text: newText });
    }

    /**
     * Reset the text alignment
     */
    const resetTextAlign = () => {
        setAttributes({ style: undefined })
    }

    return (
        <Fragment>
            <InspectorControls group="styles">
                <PanelBody title="Typography">
                    <PanelRow>
                        <Button variant="primary" onClick={resetTextAlign} disabled={!textAlign}>
                            Reset text alignment
                        </Button>
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <RichText
                {...useBlockProps({ className: "evolutio-pagedescription", style: { textAlign } })}
                tagName="div"
                allowedFormats={allowedFormats}
                value={text}
                onChange={updateDescription}
                placeholder="Write your text here"
            />
        </Fragment>
    );
}
