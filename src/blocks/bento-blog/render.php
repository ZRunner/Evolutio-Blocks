<?php
$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-bentoblog',
]);

$query = new WP_Query([
	'post_type'      => 'post',
	'posts_per_page' => 5,
	'post_status'    => 'publish',
]);

if (!function_exists('evolutio_render_post_card')) {
	/**
	 * Renders a post card component.
	 * 
	 * @param WP_Post $post The post object.
	 * @param int $index The index of the post in the loop.
	 * @return string The HTML output.
	 */
	function evolutio_render_post_card($post, $index)
	{
		$title = esc_html(get_the_title($post));
		$date = esc_html(get_the_date('', $post));
		$excerpt = esc_html(get_the_excerpt($post));
		$permalink = esc_url(get_permalink($post));
		// Assign classes dynamically based on index
		$position_class = "evolutio-bentoblog-item-$index";

		ob_start();
?>
		<article class="evolutio-bentoblog-card <?php echo esc_attr($position_class); ?>">
			<h3 class="evolutio-bentoblog-card-title"><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h3>
			<p class="evolutio-bentoblog-card-date"><?php echo $date; ?></p>
			<div class="evolutio-bentoblog-card-excerpt">
				<p><?php echo wp_trim_words($excerpt, 80); ?></p>
			</div>
			<a href="<?php echo $permalink; ?>" class="evolutio-bentoblog-card-readmore">
				Lire la suite
				<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
						stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</a>
		</article>
<?php
		return ob_get_clean();
	}
}

$index = 0;
?>

<div <?php echo $containerAttributes; ?>>
	<h3 class="evolutio-bentoblog-title">Nos récents articles</h3>
	<p class="evolutio-bentoblog-description">
		Ici nous décryptons les dernières évolutions législatives, partageons des analyses approfondies et offrons des conseils pratiques pour naviguer dans le monde complexe du droit des affaires.
	</p>
	<a href="/blog" class="evolutio-bentoblog-readmore evolutio-link">
		Découvrez Le Blog et ses articles
	</a>
	<div class="evolutio-bentoblog-grid">
		<?php if ($query->have_posts()) : ?>
			<?php while ($query->have_posts()) : $query->the_post(); ?>
				<?php echo evolutio_render_post_card(get_post(), $index); ?>
				<?php $index++; ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</div>