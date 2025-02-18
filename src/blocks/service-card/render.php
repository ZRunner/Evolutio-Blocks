<?php
$desktopName = $attributes['desktopName'];
$mobileName = $attributes['mobileName'] ?: $desktopName;
$description = $attributes['description'];
$url = $attributes['url'];
$backgroundImage = $attributes['style']['background']['backgroundImage']['url'] ?? '';
$backgroundSize = $attributes['style']['background']['backgroundSize'] ?? 'inherit';

$containerStyle = 'background-size: ' . esc_attr($backgroundSize) . ';';

if (!function_exists('render_read_more_link')) {
	function render_read_more_link($mobileVersion, $url): bool|string
	{
		$label = $mobileVersion ? "Découvrir" : "En savoir plus";
		ob_start();
		?>
		<a class="evolutio-service-card__readmore" href="<?php echo esc_url($url); ?>">
			<?php echo esc_html($label) ?>
			<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008" stroke="#F5A524" stroke-width="2" stroke-miterlimit="10"
							stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</a>
		<?php
		return ob_get_clean();
	}
}

?>
<div class="evolutio-service-card">
	<img class="evolutio-service-card__image" src="<?php echo esc_attr($backgroundImage) ?>" style="<?php echo $containerStyle ?>" />
	<div class="evolutio-service-card__desktop-content">
		<div class="evolutio-service-card__name"><?php echo esc_html($desktopName) ?></div>
		<div class="evolutio-service-card__description"><?php echo esc_html($description) ?></div>
		<?php echo render_read_more_link(false, $url); ?>
	</div>
	<div class="evolutio-service-card__mobile-content">
		<div class="evolutio-service-card__name"><?php echo esc_html($mobileName) ?></div>
		<?php echo render_read_more_link(true, $url); ?>
	</div>
</div>
