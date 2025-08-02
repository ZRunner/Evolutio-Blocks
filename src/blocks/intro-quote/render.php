<?php
$content = $attributes['content'];
$link = $attributes['link'];
$linkText = $attributes['linkText'];
$backgroundImage = $attributes['style']['background']['backgroundImage']['url'] ?? '';

$containerAttributes = get_block_wrapper_attributes();

?>
<div <?php echo $containerAttributes ?>>
	<img class="evolutio-quote-bg" src="<?php echo esc_url($backgroundImage); ?>" alt="Evolutio logo">
	<div class="evolutio-quote-block">
		<div class="evolutio-quote-content">
			<?php echo $content; ?>
		</div>
	</div>
	<div class="evolutio-quote-link">
		<a class="evolutio-link" href="<?php echo esc_url($link); ?>">
			<?php echo esc_html($linkText); ?>
		</a>
	</div>
</div>
