//@ts-check
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit() {
	return (
		<div {...useBlockProps({ className: "evolutio-bloggrid-container" })}>
			<input
				type="text"
				id="evolutio-bloggrid-search-input"
				placeholder="Rechercher un article…"
				maxLength={150}
			/>

			<div className="evolutio-bloggrid-grid">
			</div>
		</div>
	)
}
