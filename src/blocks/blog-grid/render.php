<?php
$containerAttributes = get_block_wrapper_attributes([
	'class' => 'evolutio-bloggrid-container',
]);
?>

<div <?php echo $containerAttributes; ?>>
	<input
		type="text"
		id="evolutio-bloggrid-search-input"
		placeholder="Rechercher un article…"
		maxlength="150"
	/>

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