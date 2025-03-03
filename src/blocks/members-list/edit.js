//@ts-check

import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, PanelRow, SelectControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import { Fragment } from 'react';


/** @typedef {{
 *  id: number,
 *  title: {
 *   rendered: string,
 *  },
 *  status: 'associate' | 'collaborator',
 *  profile_image_src: string,
 * }} Member */

/**
 * @argument {import('@wordpress/blocks').BlockEditProps<
 *  {
*      status: 'associate' | 'collaborator' | null,
*  }>} props
 * @return {import('react').ReactElement} Element to render
 */
export default function Edit({ attributes, setAttributes }) {
	const { status } = attributes;

	// Fetch members from the "team_member" custom post type
	/** @type {Member[]} */
	let members = useEntityRecords('postType', 'team_member', {
		per_page: -1,
		status: 'publish',
		order: 'asc',
		orderby: 'title',
	}).records ?? [];
	if (status) {
		members = members.filter((member) => member.status === status);
	}

	/**
	 * @param {Member['status']} value The member status to filter
	 */
	const updateFilteringStatus = (value) => {
		setAttributes({ status: value });
	}

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title="Members list Settings" initialOpen>
					<PanelRow>
						<SelectControl
							label="Select Status to display"
							value={status}
							options={[
								{ label: 'Associés', value: 'associate' },
								{ label: 'Collaborateurs', value: 'collaborator' },
							]}
							onChange={updateFilteringStatus}
							__next40pxDefaultSize
							__nextHasNoMarginBottom
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps({ className: "evolutio-memberslist" })}>
				{members.map((member) => (
					<MemberCard key={member.id} {...member} />
				))}
			</div>
		</Fragment>
	)
}

/**
 * @param {Member} props The member to display
 * @return {import('react').ReactElement} Element to render
 */
function MemberCard(props) {
	const { title, profile_image_src } = props;
	const memberName = title.rendered;

	return (
		<div className="evolutio-memberslist-card">
			<img className="evolutio-memberslist-card-photo" src={profile_image_src} alt={`Photograph of ${memberName}`} />
			<span className="evolutio-memberslist-card__name">
				{memberName}
				<svg width="22" height="22" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
						stroke="currentColor" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
				</svg>
			</span>
		</div>
	)
}
