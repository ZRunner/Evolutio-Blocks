//@ts-check

import { useBlockProps } from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';

/** @typedef {{
 *  id: number,
 *  service_mobile_name: string,
 *  service_description: string,
 *  service_url: string,
 *  featured_image_src: string,
 *  title: {
 *   rendered: string,
 *  }
 *  modified_gmt: string,
 * }} Service */

/**
 * @return {import('react').ReactElement} Element to render
 */
export default function Edit() {

	// Fetch services from the "service" custom post type
	/** @type {Service[]} */
	const services = useEntityRecords('postType', 'service', {
		per_page: -1,
		status: 'publish',
		order: 'asc',
	}).records ?? [];

	return (
		<div {...useBlockProps({ className: "evolutio-services" })}>
			<div className="evolutio-services__innercomponents">
				{services.map((service) => (
					<ServiceCard key={service.id} {...service} />
				))}
			</div>
		</div>
	)
}

/**
 * @param {Service} props The service to display
 * @return {import('react').ReactElement} Element to render
 */
function ServiceCard(props) {
	const { title, service_mobile_name, service_description, service_url, featured_image_src } = props;
	const desktopName = title.rendered;

	return (
		<div className="evolutio-service-card">
			<img className="evolutio-service-card__image" src={featured_image_src} />
			<div className="evolutio-service-card__desktop-content">
				<div className="evolutio-service-card__name">{desktopName}</div>
				<div className="evolutio-service-card__description">{service_description}</div>
				<ReadMoreLink url={service_url} />
			</div>
			<div className="evolutio-service-card__mobile-content">
				<div className="evolutio-service-card__name">{service_mobile_name}</div>
				<ReadMoreLink url={service_url} mobileVersion />
			</div>
		</div>
	)
}

/**
 * @param {{url: string, mobileVersion?: boolean}} props
 */
function ReadMoreLink({ url, mobileVersion }) {
	const label = mobileVersion ? "Découvrir" : "En savoir plus";
	return (
		<div className="evolutio-service-card__readmore evolutio-link" title={url}>
			{label}
		</div>
	)
}
