//@ts-check
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";

/**
 * @argument {import("@wordpress/blocks").BlockEditProps<
 * 	{
 * 		image?: {
 *           id: number,
 *           url: string,
 *           alt: string,
 *           srcSet: string,
 *       },
 *      header: string,
 *      title: string,
 *      subtitle: string,
 *	}>} props
 * @return {import("react").ReactElement} Element to render.
 */
export default function Save({ attributes }) {
	const { image, header, title, subtitle } = attributes;

	let containerClassName = "evolutio-membersingle";

	return (
		<div {...useBlockProps.save({ className: containerClassName })}>
			<h3>{header}</h3>
			<div className="evolutio-membersingle__row">
				<div className="evolutio-membersingle__left">
					<img
						src={image?.url}
						srcSet={image?.srcSet}
						alt={image?.alt || "Image"}
						className="evolutio-membersingle__image"
						sizes="(max-width: 782px) 100vw, 50vw"
						decoding="async"
					/>
					<div className="evolutio-membersingle__title">
						{title}
					</div>
					<div className="evolutio-membersingle__subtitle">
						{subtitle}
					</div>
				</div>
				<div className="evolutio-membersingle__content">
					<InnerBlocks.Content />
				</div>
			</div>
		</div>
	);
}
