//@ts-check

import { useBlockProps } from '@wordpress/block-editor';
import { TextareaControl } from '@wordpress/components';
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
 * @argument {import('@wordpress/blocks').BlockEditProps<
 * 	{
 *      description: string;
 *	}>} props
 * @return {import('react').ReactElement} Element to render
 */
export default function Edit({ attributes, setAttributes, isSelected }) {
	const { description } = attributes;

	// Fetch services from the "service" custom post type
	/** @type {Service[]} */
	const services = useEntityRecords('postType', 'service', {
		per_page: -1,
		status: 'publish',
		order: 'asc',
	}).records ?? [];

	/**
	 * @param {string} value The new block description
	 */
	const updateDescription = (value) => {
		setAttributes({ description: value })
	}

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
				rows={6}
			/>
		)
	}
	return <p className="evolutio-services__description">{description}</p>
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
		<div className="evolutio-service-card__readmore" title={url}>
			{label}
			<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008" stroke="currentColor" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
			</svg>
		</div>
	)
}
