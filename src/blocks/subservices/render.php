<?php
$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-subservices',
]);

// Fetch all services from the "service" custom post type
$services = new WP_Query(array(
	'post_type'      => 'sub_service',
	'posts_per_page' => -1,
	'status'         => 'publish',
	'order'          => 'ASC',
));

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
		</div>
<?php
		return ob_get_clean();
	}
}

?>
<div <?php echo $containerAttributes ?>>
	<?php
	while ($services->have_posts()) : $services->the_post();
		echo evolutio_render_subservice_card($services->post);
	endwhile;
	wp_reset_postdata();
	?>
</div>