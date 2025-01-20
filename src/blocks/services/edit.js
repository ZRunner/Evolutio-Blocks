//@ts-check

import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { TextareaControl } from '@wordpress/components';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 * 	{
 *      description: string;
 *	}>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes, isSelected }) {
    const { description } = attributes;

    /**
     * @param {string} value The new block description
     */
    const updateDescription = (value) => {
        setAttributes({ description: value })
    }

    return (
        <div {...useBlockProps({className: "evolutio-services"})}>
            <h3 className="evolutio-services__title">Nos services</h3>
            <Description description={description} updateDescription={updateDescription} isSelected={isSelected} />
            <InnerBlocks />
        </div>
    )
}

/**
 * 
 * @param {{description: string, updateDescription: (description: string) => void, isSelected: boolean}} props
 */
function Description({ description, updateDescription, isSelected }) {
    if (isSelected) {
        return (
            <TextareaControl
                __nextHasNoMarginBottom
                className="evolutio-services__description"
                label="Description"
                placeholder="Enter a description"
                hideLabelFromVision
                value={description}
                onChange={updateDescription}
            />
        )
    }
    return <p className="evolutio-services__description">{description}</p>
}
