//@ts-check

import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, PanelRow, SelectControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import { Fragment } from 'react';


/** @typedef {{
 *  id: number,
 *  title: {
 *   rendered: string,
 *  }
 * }} Service */

/** @typedef {{
 *  id: number,
 *  parent_service_id: number,
 *  sub_service_description: string,
 *  title: {
 *   rendered: string,
 *  }
 *  modified_gmt: string,
 * }} SubService */

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 *  {
*      parentServiceId: number,
*  }>} props
 * @return {import('react').ReactElement} Element to render
 */
export default function Edit({ attributes, setAttributes }) {
	const { parentServiceId } = attributes;

	// Fetch services from the "service" custom post type
	/** @type {Service[]} */
	const services = useEntityRecords('postType', 'service', {
		per_page: -1,
		status: 'publish',
		order: 'asc',
	}).records ?? [];

	// Fetch sub-services from the "sub_service" custom post type
	/** @type {SubService[]} */
	let subServices = useEntityRecords('postType', 'sub_service', {
		per_page: -1,
		status: 'publish',
		order: 'asc',
	}).records ?? [];
	if (parentServiceId) {
		subServices = subServices.filter((service) => service.parent_service_id === parentServiceId);
	}

	/**
	 * @param {string} value The new parent service ID
	 */
	const updateParentServiceId = (value) => {
		const intValue = parseInt(value, 10);
		if (isNaN(intValue)) {
			return
		}
		setAttributes({ parentServiceId: intValue });
	}

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title="Service Settings" initialOpen>
					<PanelRow>
						<SelectControl
							label="Select Parent Service"
							value={parentServiceId?.toString()}
							options={[
								...services.map(service => ({
									value: service.id.toString(),
									label: service.title.rendered
								}))
							]}
							onChange={updateParentServiceId}
							__next40pxDefaultSize
							__nextHasNoMarginBottom
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps({ className: "evolutio-subservices" })}>
				{subServices.map((service) => (
					<SubServiceCard key={service.id} {...service} />
				))}
			</div>
		</Fragment>
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
			<RichText.Content
				tagName="div"
				className="evolutio-subservice-card__description"
				value={sub_service_description}
			/>
		</div>
	)
}
