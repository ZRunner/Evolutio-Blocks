<?php
$description = $attributes['description'];

$containerAttributes = get_block_wrapper_attributes([
    'class' => 'evolutio-services',
]);

$inner_blocks_html = '';
foreach ($block->inner_blocks as $inner_block) {
    $inner_blocks_html .= $inner_block->render();
}

?>
<div <?php echo $containerAttributes ?>>
    <h3 class="evolutio-services__title">Nos services</h3>
    <p class="evolutio-services__description"><?php echo esc_html($description); ?></p>
    <div class="evolutio-services__innercomponents">
        <?php echo $inner_blocks_html ?>
    </div>
</div>