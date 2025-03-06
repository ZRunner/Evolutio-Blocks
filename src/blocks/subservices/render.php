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
				class="evolutio-subservice-card__readmore evolutio-link"
				data-id="<?php echo esc_attr($subservice->ID); ?>"
				data-name="<?php echo esc_attr($name); ?>"
				data-description="<?php echo esc_attr($description); ?>">
				Découvrir
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
	<dialog id="evolutio-subservice-modal" class="evolutio-dialog">
		<div class="evolutio-subservice-modal__content">
			<span class="evolutio-dialog__close">&times;</span>
			<h2 class="evolutio-subservice-modal__title"></h2>
			<div class="evolutio-subservice-modal__description"></div>
		</div>
	</dialog>
</div>