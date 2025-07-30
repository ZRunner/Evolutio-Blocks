<?php
$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-memberslist',
]);
$status = $attributes['status'] ?? '';


// Fetch all members from the "team_member" custom post type
$args = array(
	'post_type'      => 'team_member',
	'posts_per_page' => -1,
	'status'		 => 'publish',
	'order'			 => 'asc',
	'orderby'        => 'title',
	'meta_query'     => array()
);

// If a status is selected, filter by meta key "status"
if ($status) {
	$args['meta_query'][] = array(
		'key'     => 'team_member_status',
		'value'   => $status,
		'compare' => '='
	);
}
$members = new WP_Query($args);

$title = match ($status) {
	'associate' => 'Nos associés',
	'collaborator' => 'Nos collaborateurs',
	default => 'Notre équipe',
};

if (!function_exists('evolutio_render_member_card')) {
	/**
	 * Renders a team member card component.
	 *
	 * @param WP_Post $member The team member post object.
	 * @return string The HTML output.
	 */
	function evolutio_render_member_card(WP_Post $member): string
	{
		$name = esc_html(get_the_title($member));
		$profile_image_src = '';

		if (has_post_thumbnail($member)) {
			$profile_image_src = esc_url(get_the_post_thumbnail_url($member, 'medium'));
		}

		ob_start();
?>
		<a href="#<?php echo esc_attr($member->ID); ?>"
			data-id="<?php echo esc_attr($member->ID); ?>"
			class="evolutio-memberslist-card">
			<img class="evolutio-memberslist-card-photo" src="<?php echo $profile_image_src ?>" alt="Photograph of <?php echo $name ?>" />
			<span class="evolutio-memberslist-card__name">
				<?php echo $name; ?>
				<svg width="22" height="22" viewBox="0 0 25 24" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
						stroke="currentColor" fill="none" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</span>
		</a>
<?php
		return ob_get_clean();
	}
}

?>
<div <?php echo $containerAttributes ?>>
	<h3><?php echo esc_html($title) ?></h3>
	<div class="evolutio-memberslist-container">
		<?php
		while ($members->have_posts()) : $members->the_post();
			echo evolutio_render_member_card($members->post);
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<dialog id="evolutio-memberslist-modal" class="evolutio-dialog">
		<div class="evolutio-memberslist-modal__content">
			<span class="evolutio-dialog__close">&times;</span>
			<div class="evolutio-memberslist-modal__header">
				<img class="evolutio-memberslist-modal__photo" src="" alt="Photograph of" />
				<div class="evolutio-memberslist-modal__info">
					<div class="evolutio-memberslist-modal__title"></div>
					<div class="evolutio-memberslist-modal__status"></div>
				</div>
				<div class="evolutio-memberslist-modal__contact">
					<div>
						<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path d="M16.5562 12.9062L16.1007 13.359C16.1007 13.359 15.0181 14.4355 12.0631 11.4972C9.10812 8.55901 10.1907 7.48257 10.1907 7.48257L10.4775 7.19738C11.1841 6.49484 11.2507 5.36691 10.6342 4.54348L9.37326 2.85908C8.61028 1.83992 7.13596 1.70529 6.26145 2.57483L4.69185 4.13552C4.25823 4.56668 3.96765 5.12559 4.00289 5.74561C4.09304 7.33182 4.81071 10.7447 8.81536 14.7266C13.0621 18.9492 17.0468 19.117 18.6763 18.9651C19.1917 18.9171 19.6399 18.6546 20.0011 18.2954L21.4217 16.883C22.3806 15.9295 22.1102 14.2949 20.8833 13.628L18.9728 12.5894C18.1672 12.1515 17.1858 12.2801 16.5562 12.9062Z" fill="currentColor" />
						</svg>
						<a class="evolutio-memberslist-modal__phone"></a>
					</div>
					<div>
						<svg viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
							<path d="M1920 428.266v1189.54l-464.16-580.146-88.203 70.585 468.679 585.904H83.684l468.679-585.904-88.202-70.585L0 1617.805V428.265l959.944 832.441L1920 428.266ZM1919.932 226v52.627l-959.943 832.44L.045 278.628V226h1919.887Z" fill-rule="evenodd" fill="currentColor" />
						</svg>
						<a class="evolutio-memberslist-modal__email"></a>
					</div>
					<div>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72">
							<g fill-rule="evenodd">
								<path d="M8,72 L64,72 C68.418278,72 72,68.418278 72,64 L72,8 C72,3.581722 68.418278,-8.11624501e-16 64,0 L8,0 C3.581722,8.11624501e-16 -5.41083001e-16,3.581722 0,8 L0,64 C5.41083001e-16,68.418278 3.581722,72 8,72 Z" fill="#007EBB" />
								<path d="M62,62 L51.315625,62 L51.315625,43.8021149 C51.315625,38.8127542 49.4197917,36.0245323 45.4707031,36.0245323 C41.1746094,36.0245323 38.9300781,38.9261103 38.9300781,43.8021149 L38.9300781,62 L28.6333333,62 L28.6333333,27.3333333 L38.9300781,27.3333333 L38.9300781,32.0029283 C38.9300781,32.0029283 42.0260417,26.2742151 49.3825521,26.2742151 C56.7356771,26.2742151 62,30.7644705 62,40.051212 L62,62 Z M16.349349,22.7940133 C12.8420573,22.7940133 10,19.9296567 10,16.3970067 C10,12.8643566 12.8420573,10 16.349349,10 C19.8566406,10 22.6970052,12.8643566 22.6970052,16.3970067 C22.6970052,19.9296567 19.8566406,22.7940133 16.349349,22.7940133 Z M11.0325521,62 L21.769401,62 L21.769401,27.3333333 L11.0325521,27.3333333 L11.0325521,62 Z" fill="#FFF" />
							</g>
						</svg>
						<a class="evolutio-memberslist-modal__linkedin" target="_blank">LinkedIn</a>
					</div>
				</div>
			</div>
			<div class="evolutio-memberslist-modal__description"></div>
			<div>
				<span class="evolutio-memberslist-modal__boldlabel">Expérience : </span>
				<span class="evolutio-memberslist-modal__experience"></span>
			</div>
			<div>
				<span class="evolutio-memberslist-modal__boldlabel">Diplômes : </span>
				<p class="evolutio-memberslist-modal__diplomas"></p>
			</div>
			<div>
				<span class="evolutio-memberslist-modal__boldlabel">Domaines d'expertise : </span>
				<p class="evolutio-memberslist-modal__expertise"></p>
			</div>
		</div>
	</dialog>
</div>
