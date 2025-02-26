//@ts-check
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit() {
	return (
		<div {...useBlockProps({ className: "evolutio-bloggrid-container" })}>
			<div className="evolutio-bloggrid-search">
				<input
					type="text"
					id="evolutio-bloggrid-search-input"
					placeholder={__('Type to search…', 'text-domain')}
				/>
			</div>

			<div className="evolutio-bloggrid-grid">
			</div>
		</div>
	)
}
