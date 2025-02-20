<?php
$content = $attributes['content'];
$link = $attributes['link'];
$linkText = $attributes['linkText'];

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
<div>
    <div class="evolutio-quote-block">
        <?php echo render_quote_icon('evolutio-quote-svg evolutio-quote-svg-start'); ?>
        <div class="evolutio-quote-content">
            <?php echo $content; ?>
        </div>
        <?php echo render_quote_icon('evolutio-quote-svg evolutio-quote-svg-end'); ?>
    </div>
    <a class="evolutio-quote-link" href="<?php echo esc_url($link); ?>">
        <?php echo esc_html($linkText); ?>
        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
</div>