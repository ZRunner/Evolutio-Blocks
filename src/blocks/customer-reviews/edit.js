//@ts-check

import { RichText, useBlockProps } from '@wordpress/block-editor';
import { Pagination, Mousewheel } from 'swiper/modules';
import { Fragment } from 'react/jsx-runtime';
import Swiper from 'swiper';
import { useEntityRecords } from '@wordpress/core-data';
import { useEffect } from 'react';

/** @typedef {{
 *  id: number,
 *  review_job_title: string,
 *  review_text: string,
 *  title: {
 *   rendered: string,
 *  }
 *  modified_gmt: string,
 * }} Review */

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
 *      description: string,
 *  }>} props
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const { description } = attributes;

    // Fetch reviews from the "review" custom post type
    /** @type {Review[]} */
    const reviews = useEntityRecords('postType', 'review', { per_page: -1, status: 'publish' }).records ?? [];

    /** @type {{name: string}[]} */
    const defaultFormats = wp.data.select('core/rich-text').getFormatTypes();
    const allowedFormats = defaultFormats.filter(format => !DISABLED_FORMATS.includes(format.name)).map(format => format.name)

    /**
     * @param {string} value The new block description.
     */
    const updateDescription = (value) => {
        setAttributes({ description: value });
    }

    useEffect(() => {
        const swiperInstance = new Swiper('.swiper', {
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

        return () => swiperInstance.destroy(true, true);
    }, [reviews]);

    return (
        <Fragment>
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
                <span title="/avis" className="evolutio-customer-reviews__readmore">
                    Explorez nos avis
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
                            stroke="currentColor" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                </span>
                <div className="swiper">
                    <div className="swiper-wrapper">
                        {reviews.length > 0 ? (
                            reviews.map((review) => (
                                <div key={review.id} className="swiper-slide">
                                    <div className="evolutio-customer-review__role">
                                        {review.review_job_title || "​"}
                                    </div>
                                    <div className="evolutio-customer-review__content">
                                        {review.review_text || '?'}
                                    </div>
                                    <div className="evolutio-customer-review__author">
                                        {review.title.rendered || "Anonyme"}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <p>Aucun avis disponible.</p>
                        )}
                    </div>
                    <div className="swiper-pagination"></div>
                </div>
            </div>
        </Fragment>
    );
}
