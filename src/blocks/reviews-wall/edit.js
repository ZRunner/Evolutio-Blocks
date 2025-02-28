//@ts-check

import { useBlockProps } from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';

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
 * @return {import('react').ReactElement} Element to render.
 */
export default function Edit() {
    // Fetch reviews from the "review" custom post type
    /** @type {Review[]} */
    const reviews = useEntityRecords('postType', 'review', { per_page: -1, status: 'publish' }).records ?? [];

    return (
        <div {...useBlockProps({ className: "evolutio-reviews-wall" })}>
            <div className="evolutio-reviews-wall__container">
                {reviews.map((review) => (
                    <div key={review.id} className="evolutio-reviews-wall__card">
                        <div className="evolutio-reviews-wall__role">
                            {review.review_job_title || "​"}
                        </div>
                        <div className="evolutio-reviews-wall__content">
                            {review.review_text || '?'}
                        </div>
                        <div className="evolutio-reviews-wall__author">
                            {review.title.rendered || "Anonyme"}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
