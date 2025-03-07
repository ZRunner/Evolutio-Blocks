//@ts-check
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
* 	{
* 		image?: {
*           id: number,
*           url: string,
*           alt: string,
*           srcSet: string,
*       },
*       desktopDirection: 'row' | 'row-reverse',
*       mobileDirection: 'column' | 'column-reverse',
*       imageFit: 'cover' | 'contain',
*	}>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Save({ attributes }) {
    const { image, desktopDirection, mobileDirection, imageFit } = attributes;

    let containerClassName = "evolutio-imagecolumn";
    containerClassName += ` evolutio-imagecolumn--desktop-${desktopDirection}`;
    containerClassName += ` evolutio-imagecolumn--mobile-${mobileDirection}`;

    return (
        <div {...useBlockProps.save({ className: containerClassName })}>
            <div className="evolutio-imagecolumn__image">
                <img
                    src={image?.url}
                    srcSet={image?.srcSet}
                    alt={image?.alt || "Image"}
                    style={{ objectFit: imageFit }}
                    sizes="(max-width: 782px) 100vw, 50vw"
                    decoding="async"
                />
            </div>
            <div className="evolutio-imagecolumn__content">
                <InnerBlocks.Content />
            </div>
        </div>
    )
}