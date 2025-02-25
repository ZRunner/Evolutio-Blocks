<?php
$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-subservices',
]);
$parent_service_id = isset($attributes['parentServiceId']) ? intval($attributes['parentServiceId']) : 0;


// Fetch all services from the "service" custom post type
$args = array(
	'post_type'      => 'sub_service',
	'posts_per_page' => -1,
	'status'		 => 'publish',
	'order'			 => 'asc',
	'meta_query'     => array()
);

// If a parent service is selected, filter by meta key "parent_service_id"
if ($parent_service_id) {
	$args['meta_query'][] = array(
		'key'     => 'parent_service_id',
		'value'   => $parent_service_id,
		'compare' => '='
	);
}

$subservices = new WP_Query($args);

if (!function_exists('evolutio_render_subservice_card')) {
	/**
	 * Renders a sub-service card component.
	 *
	 * @param WP_Post $subservice The sub-service post object.
	 * @return string The HTML output.
	 */
	function evolutio_render_subservice_card($subservice)
	{
		$name = esc_html(get_the_title($subservice));
		$description = wp_kses_post(get_post_meta($subservice->ID, 'sub_service_description', true));

		ob_start();
?>
		<div class="evolutio-subservice-card">
			<div class="evolutio-subservice-card__name"><?php echo $name; ?></div>
			<div class="evolutio-subservice-card__description"><?php echo $description; ?></div>
			<a
				href="#<?php echo esc_attr($subservice->ID); ?>"
				class="evolutio-subservice-card__readmore"
				data-id="<?php echo esc_attr($subservice->ID); ?>"
				data-name="<?php echo esc_attr($name); ?>"
				data-description="<?php echo esc_attr($description); ?>">
				Découvrir
				<svg width="22" height="22" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
						stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</a>
		</div>
<?php
		return ob_get_clean();
	}
}

?>
<div <?php echo $containerAttributes ?>>
	<?php
	while ($subservices->have_posts()) : $subservices->the_post();
		echo evolutio_render_subservice_card($subservices->post);
	endwhile;
	wp_reset_postdata();
	?>
	<div id="evolutio-subservice-modal" class="evolutio-subservice-modal">
		<div class="evolutio-subservice-modal__content">
			<span class="evolutio-subservice-modal__close">&times;</span>
			<h2 class="evolutio-subservice-modal__title"></h2>
			<div class="evolutio-subservice-modal__description"></div>
		</div>
	</div>
</div>