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
		return "linear-gradient({$backgroundColor}A3 17%, {$backgroundColor}CC 50%), url({$backgroundImage})";
	}
}

$attributes = wp_parse_args($attributes, [
	'minHeight' => '',
	'style' => [],
]);

$minHeight = $attributes['minHeight'];
$backgroundImageStyle = get_background_image_style(
	$attributes['style']['color']['background'] ?? '',
	$attributes['style']['background']['backgroundImage']['url'] ?? ''
);

$styles = wp_style_engine_get_styles($attributes['style'])['css'];
if (!empty($minHeight)) {
	$styles .= "min-height: $minHeight;";
}
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