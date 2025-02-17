//@ts-check

import { RichText, useBlockProps } from '@wordpress/block-editor';
import { Pagination, Mousewheel } from 'swiper/modules';
import { TextControl, Button, PanelBody, PanelRow, TextareaControl } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { Fragment } from 'react/jsx-runtime';
import Swiper from 'swiper';

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
 *  {
 *      reviews: {author: string, role: string, content: string}[],
 *      description: string,
 *  }>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const { reviews, description } = attributes;

    new Swiper('.swiper', {
        modules: [Pagination, Mousewheel],
        spaceBetween: 32,
        slidesPerView: "auto",
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: reviews.length > 3
        },
        mousewheel: { forceToAxis: true }
    });

    /** @type {{name: string}[]} */
    const defaultFormats = wp.data.select('core/rich-text').getFormatTypes();
    const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name)

    const addReview = () => {
        setAttributes({ reviews: [...reviews, { author: '', role: '', content: '' }] });
    };

    /**
     * @param {number} index The index of the review to update.
     * @param {"author" | "role" | "content"} key Which property to update.
     * @param {string} value The new value.
     */
    const updateReview = (index, key, value) => {
        const updatedReviews = [...reviews];
        updatedReviews[index][key] = value;
        setAttributes({ reviews: updatedReviews });
    };

    /**
     * @param {number} index The index of the review to remove.
     */
    const removeReview = (index) => {
        const updatedReviews = reviews.filter((_, i) => i !== index);
        setAttributes({ reviews: updatedReviews });
    };

    /**
     * @param {string} value The new block description.
     */
    const updateDescription = (value) => {
        setAttributes({ description: value });
    }

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title="Reviews list" initialOpen={true}>
                    <PanelRow>
                        <Button variant="primary" onClick={addReview}>
                            Add Review
                        </Button>
                    </PanelRow>
                    {reviews.map((link, index) => (
                        <Fragment key={index}>
                            <hr />
                            <TextControl
                                label={`Author ${index + 1}`}
                                value={link.author}
                                onChange={(value) => updateReview(index, "author", value)}
                                maxLength={100}
                            />
                            <TextControl
                                label={`Function ${index + 1}`}
                                value={link.role}
                                onChange={(value) => updateReview(index, "role", value)}
                                maxLength={100}
                            />
                            <TextareaControl
                                label={`Content ${index + 1}`}
                                value={link.content}
                                onChange={(value) => updateReview(index, "content", value)}
                                maxLength={700}
                            />
                            <Button isDestructive onClick={() => removeReview(index)}>
                                Remove
                            </Button>
                        </Fragment>
                    ))}
                </PanelBody>
            </InspectorControls>
            <link
                rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
            />
            <div {...useBlockProps({ className: "evolutio-customer-reviews" })}>
                <h3 className="evolutio-customer-reviews__desktop-title">Ce que vous pensez de nous</h3>
                <h3 className="evolutio-customer-reviews__mobile-title">Vos avis</h3>
                <RichText
                    tagName="p"
                    allowedFormats={allowedFormats}
                    value={description}
                    onChange={updateDescription}
                    placeholder="Enter some text here"
                    className="evolutio-customer-reviews__description"
                />
                <div className="swiper">
                    <div className="swiper-wrapper">
                        {reviews.map((review, index) => (
                            <div key={index} className="swiper-slide">
                                <div className="evolutio-customer-review__role">{review.role || "​"}</div>
                                <div className="evolutio-customer-review__content">{review.content || '?'}</div>
                                <div className="evolutio-customer-review__author">{review.author || 'Anonyme'}</div>
                            </div>
                        ))}
                    </div>
                    <div className="swiper-pagination"></div>
                </div>
            </div>
        </Fragment>
    );
}
