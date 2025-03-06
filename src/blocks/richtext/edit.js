//@ts-check

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelRow } from '@wordpress/components';
import { PanelBody } from '@wordpress/components';
import { __experimentalUnitControl as UnitControl } from '@wordpress/components';
import { Fragment } from 'react';


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
*       maxWidth: string,
*	}>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Edit({ attributes, setAttributes }) {
    const { text, maxWidth } = attributes;

    /** @type {{name: string}[]} */
    const defaultFormats = wp.data.select('core/rich-text').getFormatTypes();
    const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name);

    /**
     * @param {string} newText The new paragraph text
     */
    const updateDescription = (newText) => {
        setAttributes({ text: newText });
    }

    /**
     * @param {string} newMaxWidth The new block max width
     */
    const updateMaxWidth = (newMaxWidth) => {
        setAttributes({ maxWidth: newMaxWidth });
    }

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title="RichText Settings" initialOpen>
                    <PanelRow>
                        <UnitControl
                            label="Max width"
                            value={maxWidth}
                            onChange={updateMaxWidth}
                            __next40pxDefaultSize
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div {...useBlockProps({ className: "evolutio-richtext" })}>
                <RichText
                    style={{ maxWidth: maxWidth, letterSpacing: "0.02px" }}
                    tagName="div"
                    allowedFormats={allowedFormats}
                    value={text}
                    onChange={updateDescription}
                    placeholder="Write your text here"
                />
            </div>
        </Fragment>
    );
}
