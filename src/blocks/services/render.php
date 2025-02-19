<?php
$description = $attributes['description'];

$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-services',
]);

// Fetch all services from the "service" custom post type
$services = new WP_Query(array(
	'post_type'      => 'service',
	'posts_per_page' => -1,
	'status'         => 'publish',
	'order'          => 'ASC',
));

if (!function_exists('evolutio_render_service_card')) {
	/**
	 * Renders a service card component.
	 *
	 * @param WP_Post $service The service post object.
	 * @return string The HTML output.
	 */
	function evolutio_render_service_card($service)
	{
		$desktop_name = esc_html(get_the_title($service));
		$mobile_name = esc_html(get_post_meta($service->ID, 'service_mobile_name', true));
		$description = esc_html(get_post_meta($service->ID, 'service_description', true));
		$service_url = esc_url(get_post_meta($service->ID, 'service_url', true));
		$featured_image_src = '';

		if (has_post_thumbnail($service)) {
			$featured_image_src = esc_url(get_the_post_thumbnail_url($service, 'large'));
		}

		ob_start();
?>
		<div class="evolutio-service-card">
			<?php if ($featured_image_src) : ?>
				<img class="evolutio-service-card__image" src="<?php echo $featured_image_src; ?>" alt="<?php echo $desktop_name; ?>">
			<?php endif; ?>
			<div class="evolutio-service-card__desktop-content">
				<div class="evolutio-service-card__name"><?php echo $desktop_name; ?></div>
				<div class="evolutio-service-card__description"><?php echo $description; ?></div>
				<?php echo evolutio_render_readmore_link($service_url); ?>
			</div>
			<div class="evolutio-service-card__mobile-content">
				<div class="evolutio-service-card__name"><?php echo $mobile_name; ?></div>
				<?php echo evolutio_render_readmore_link($service_url, true); ?>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}
}

if (!function_exists('evolutio_render_readmore_link')) {
	/**
	 * Renders the "Read More" link.
	 *
	 * @param string $url The URL to link to.
	 * @param bool $mobileVersion Whether to use the mobile label.
	 * @return string The HTML output.
	 */
	function evolutio_render_readmore_link($url, $mobileVersion = false)
	{
		if (empty($url)) {
			return '';
		}

		$label = $mobileVersion ? 'Découvrir' : 'En savoir plus';
		ob_start();
	?>
		<a href="<?php echo $url; ?>" class="evolutio-service-card__readmore">
			<?php echo esc_html($label); ?>
			<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
					stroke="#F5A524" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</a>
<?php
		return ob_get_clean();
	}
}

?>
<div <?php echo $containerAttributes ?>>
	<h3 class="evolutio-services__title">Nos services</h3>
	<p class="evolutio-services__description"><?php echo esc_html($description); ?></p>
	<div class="evolutio-services__innercomponents">
		<?php
		while ($services->have_posts()) : $services->the_post();
			echo evolutio_render_service_card($services->post);
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</div>