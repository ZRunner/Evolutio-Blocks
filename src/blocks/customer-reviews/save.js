//@ts-check
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { Fragment } from 'react/jsx-runtime';

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
*  {
*      reviews: {author: string, role: string, content: string}[],
*      description: string,
*  }>} props
* @return {import('react').ReactElement} Element to render.
*/
export default function Save({ attributes }) {
    const { reviews, description } = attributes;

    return (
        <Fragment>
            <link
                rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
            />
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" />
            <div {...useBlockProps.save({ className: "evolutio-customer-reviews" })}>
                <h3 className="evolutio-customer-reviews__desktop-title">Ce que vous pensez de nous</h3>
                <h3 className="evolutio-customer-reviews__mobile-title">Vos avis</h3>
                <p className="evolutio-customer-reviews__description">
                    <RichText.Content value={description} />
                </p>
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
    )
}
