//@ts-check

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, TextControl, TextareaControl } from '@wordpress/components';
import { Fragment } from 'react/jsx-runtime';

/**
 * @type {{
*   data: typeof import('@wordpress/data'),
* }}
*/
// @ts-ignore
const wp = window.wp;

const DISABLED_FORMATS = [
    "core/image",
    "core/italic",
    "core/language",
]

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
* 	{
* 		content: string,
* 		link: string,
* 		linkText: string,
*	}>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Edit({ attributes, setAttributes }) {
    const { content, link, linkText } = attributes;

    /** @type {{name: string}[]} */
    const defaultFormats = wp.data.select('core/rich-text').getFormatTypes();
    const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name);

    /**
     * @param {string} newLink The new quote content
     */
    const updateLink = (newLink) => {
        setAttributes({ link: newLink });
    }

    /**
     * @param {string} newLinkText The new link label
     */
    const updateLinkText = (newLinkText) => {
        setAttributes({ linkText: newLinkText });
    }

    /**
     * @param {string} newContent The new quote content
     */
    const updateContent = (newContent) => {
        setAttributes({ content: newContent });
    }

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
            <div {...useBlockProps()}>
                <div className="evolutio-quote-block">
                    <QuoteIcon className="evolutio-quote-svg evolutio-quote-svg-start" />
                    <RichText
                        tagName="p"
                        allowedFormats={allowedFormats}
                        value={content}
                        onChange={updateContent}
                        placeholder="Enter your quote..."
                        className="evolutio-quote-content"
                    />
                    <QuoteIcon className="evolutio-quote-svg evolutio-quote-svg-end" />
                </div>
                <div className="evolutio-quote-link">
                    {linkText}
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008" stroke="#F5A524" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                </div>
            </div>
        </Fragment>
    );
}

/**
 * @argument {{
 *     className: string
 * }} props
 */
function QuoteIcon(props) {
    return (
        <svg
            className={props.className}
            xmlns="http://www.w3.org/2000/svg"
            viewBox="10 22 36 10"
            aria-hidden="true"
        >
            <circle cx="18.5" cy="31.5" r="5.5" />
            <path d="M18.5 38a6.5 6.5 0 1 1 .02-13.02A6.5 6.5 0 0 1 18.5 38zm0-11a4.5 4.5 0 1 0 .01 9.01A4.5 4.5 0 0 0 18.5 27z" />
            <circle cx="35.5" cy="31.5" r="5.5" />
            <path d="M35.5 38a6.5 6.5 0 1 1 .02-13.02A6.5 6.5 0 0 1 35.5 38zm0-11a4.5 4.5 0 1 0 .01 9.01A4.5 4.5 0 0 0 35.5 27zM13 32a1 1 0 0 1-1-1c0-7.72 6.28-14 14-14a1 1 0 1 1 0 2c-6.62 0-12 5.38-12 12a1 1 0 0 1-1 1z" />
            <path d="M30 32a1 1 0 0 1-1-1c0-7.72 6.28-14 14-14a1 1 0 1 1 0 2c-6.62 0-12 5.38-12 12a1 1 0 0 1-1 1z" />
        </svg>
    )
}
