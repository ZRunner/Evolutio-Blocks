<?php
$title = $attributes['title'];
$subtitle = $attributes['subtitle'];

if (!function_exists('get_background_image_style')) {
	function get_background_image_style($backgroundColor, $backgroundImage)
	{
		if (empty($backgroundImage)) {
			return '';
		}
		if (empty($backgroundColor)) {
			return $backgroundImage;
		}
		if ($backgroundColor[0] === '#') {
			$startColor = $backgroundColor . '33';
			$endColor = $backgroundColor . '73';
		} else {
			// else, it's a color name
			$colorVar = 'var(--wp--preset--color--' . $backgroundColor . ')';
			$startColor = "color-mix(in srgb, {$colorVar} 20%, transparent)";
			$endColor = "color-mix(in srgb, {$colorVar} 45%, transparent)";
		}
		return "linear-gradient({$startColor} 17%, {$endColor} 50%), url({$backgroundImage})";
	}
}

$attributes = wp_parse_args($attributes, [
	'minHeight' => '',
	'style' => [],
]);

$minHeight = $attributes['minHeight'];
$backgroundColor = $attributes['backgroundColor']
	?? ($attributes['style']['color']['background'] ?? null);
$backgroundImage = $attributes['style']['background']['backgroundImage']['url'] ?? '';
$backgroundImageStyle = get_background_image_style($backgroundColor, $backgroundImage);

$styles = wp_style_engine_get_styles($attributes['style'])['css'];
$styles .= "background-image: " . $backgroundImageStyle . ";";

$containerAttributes = get_block_wrapper_attributes([
	'style' => $styles,
	'class' => 'evolutio-background-container',
]);

?>
<div <?php echo $containerAttributes ?>>
	<div class="evolutio-text-container">
		<h1 class="evolutio-title">
			<?php echo esc_html($title); ?>
		</h1>
		<h2 class="evolutio-subtitle"><?php echo esc_html($subtitle); ?></h2>
	</div>
</div>
