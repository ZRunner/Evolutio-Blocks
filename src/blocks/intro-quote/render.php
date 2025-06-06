<?php
$content = $attributes['content'];
$link = $attributes['link'];
$linkText = $attributes['linkText'];

$containerAttributes = get_block_wrapper_attributes();


if (!function_exists('render_quote_icon')) {
    function render_quote_icon($class_name = '')
    {
        ob_start();
?>
        <svg
            class="<?php echo esc_attr($class_name); ?>"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="10 22 36 10"
            aria-hidden="true">
            <circle cx="18.5" cy="31.5" r="5.5" />
            <path d="M18.5 38a6.5 6.5 0 1 1 .02-13.02A6.5 6.5 0 0 1 18.5 38zm0-11a4.5 4.5 0 1 0 .01 9.01A4.5 4.5 0 0 0 18.5 27z" />
            <circle cx="35.5" cy="31.5" r="5.5" />
            <path d="M35.5 38a6.5 6.5 0 1 1 .02-13.02A6.5 6.5 0 0 1 35.5 38zm0-11a4.5 4.5 0 1 0 .01 9.01A4.5 4.5 0 0 0 35.5 27zM13 32a1 1 0 0 1-1-1c0-7.72 6.28-14 14-14a1 1 0 1 1 0 2c-6.62 0-12 5.38-12 12a1 1 0 0 1-1 1z" />
            <path d="M30 32a1 1 0 0 1-1-1c0-7.72 6.28-14 14-14a1 1 0 1 1 0 2c-6.62 0-12 5.38-12 12a1 1 0 0 1-1 1z" />
        </svg>
<?php
        return ob_get_clean();
    }
}

?>
<div <?php echo $containerAttributes ?>>
    <div class="evolutio-quote-block">
        <?php echo render_quote_icon('evolutio-quote-svg evolutio-quote-svg-start'); ?>
        <div class="evolutio-quote-content">
            <?php echo $content; ?>
        </div>
        <?php echo render_quote_icon('evolutio-quote-svg evolutio-quote-svg-end'); ?>
    </div>
    <div class="evolutio-quote-link">
        <a class="evolutio-link" href="<?php echo esc_url($link); ?>">
            <?php echo esc_html($linkText); ?>
        </a>
    </div>
</div>