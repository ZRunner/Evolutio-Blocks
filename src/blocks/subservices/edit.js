//@ts-check

import { useBlockProps } from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';

/** @typedef {{
 *  id: number,
 *  parent_post_id: string,
 *  sub_service_description: string,
 *  title: {
 *   rendered: string,
 *  }
 *  modified_gmt: string,
 * }} SubService */

/**
 * @return {import('react').ReactElement} Element to render
 */
export default function Edit() {

	// Fetch services from the "service" custom post type
	/** @type {SubService[]} */
	const services = useEntityRecords('postType', 'sub_service', {
		per_page: -1,
		status: 'publish',
		order: 'asc',
	}).records ?? [];

	return (
		<div {...useBlockProps({ className: "evolutio-subservices" })}>
			{services.map((service) => (
				<SubServiceCard key={service.id} {...service} />
			))}
		</div>
	)
}

/**
 * @param {SubService} props The service to display
 * @return {import('react').ReactElement} Element to render
 */
function SubServiceCard(props) {
	const { title, sub_service_description } = props;
	const serviceName = title.rendered;

	return (
		<div className="evolutio-subservice-card">
			<div className="evolutio-subservice-card__name">{serviceName}</div>
			<div className="evolutio-subservice-card__description">{sub_service_description}</div>
		</div>
	)
}
