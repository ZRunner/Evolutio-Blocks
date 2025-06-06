//@ts-check
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';

/**
 * @typedef {Object} Post
 * @property {number} id
 * @property {string} date
 * @property {string} link
 * @property {{ rendered: string }} title
 * @property {{ rendered: string }} excerpt
 */

export default function Edit() {
	const blockProps = useBlockProps({ className: 'evolutio-bentoblog' });

	// Fetch the latest 5 posts using the WordPress data store
	/** @type {Post[]} */
	const posts = useEntityRecords('postType', 'post', {
		per_page: 5,
		status: 'publish',
	}).records ?? [];

	return (
		<div {...blockProps}>
			<h3 className="evolutio-bentoblog-title">Nos récents articles</h3>
			<p className="evolutio-bentoblog-description">
				Ici nous décryptons les dernières évolutions législatives, partageons des analyses approfondies et offrons des conseils pratiques pour naviguer dans le monde complexe du droit des affaires.
			</p>
			<span title="/blog" className="evolutio-bentoblog-readmore evolutio-link">
				Découvrez Le Blog et ses articles
			</span>
			<div className="evolutio-bentoblog-grid">
				{posts.map((post, index) => <PostCard key={post.id} post={post} index={index} />)}
			</div>
		</div>
	);
}

/**
 * Strip HTML tags from a string
 * @param {string} html
 * @returns {string}
 */
function stripHtml(html) {
	const doc = new DOMParser().parseFromString(html, 'text/html');
	return doc.body.textContent || "";
}

/**
 * Post Card Component
 * @param {{ post: Post, index: number }} props
 * @returns {JSX.Element}
 */
function PostCard({ post, index }) {
	const title = stripHtml(post.title.rendered);
	const content = stripHtml(post.excerpt.rendered);

	return (
		<article className={`evolutio-bentoblog-card evolutio-bentoblog-item-${index}`}>
			<h3 className="evolutio-bentoblog-card-title">
				<span title={post.link}>{title}</span>
			</h3>
			<p className="evolutio-bentoblog-card-date">{post.date}</p>
			<div className="evolutio-bentoblog-card-excerpt">
				<p>{content}</p>
			</div>
			<span title={post.link} className="evolutio-bentoblog-card-readmore evolutio-link">
				Lire la suite
			</span>
		</article>
	);
}
