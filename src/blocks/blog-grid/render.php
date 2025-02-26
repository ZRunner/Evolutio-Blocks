<?php
$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-bloggrid-container',
]);
?>

<div <?php echo $containerAttributes; ?>>
	<div class="evolutio-bloggrid-search">
		<input
			type="text"
			id="evolutio-bloggrid-search-input"
			placeholder="<?php esc_attr_e('Type to search…', 'text-domain'); ?>" />
	</div>

	<div class="evolutio-bloggrid-grid">
	</div>
</div>

<script type="text/javascript">
	if (typeof wpApiSettings === 'undefined') {
		wpApiSettings = {
			restUrl: '<?php echo esc_url(rest_url()); ?>'
		};
	}
</script>