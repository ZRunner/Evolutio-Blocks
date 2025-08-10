<?php
$internalLinks = $attributes['internalLinks'] ?? [];
$externalLinks = $attributes['externalLinks'] ?? [];
$contactUrl = $attributes['contactUrl'] ?? '';
$brandImageUrl = $attributes['brandImage']['url'] ?? '';

$containerAttributes = get_block_wrapper_attributes([
    'class' => 'evolutio-footer',
]);

if (! function_exists('evolutio_render_external_link')) {
    function evolutio_render_external_link($link)
    {
        ob_start();
?>
        <a class="evolutio-footer__outerlink" href="<?php echo esc_url($link['url']); ?>">
            <?php if ($link['icon'] === 'hammer') : ?>
                <?php echo evolutio_render_hammer_icon(); ?>
            <?php elseif ($link['icon'] === 'linkedin') : ?>
                <?php echo evolutio_render_linkedin_icon(); ?>
            <?php endif; ?>
            <?php echo esc_html($link['label']); ?>
        </a>
    <?php
        return ob_get_clean();
    }
}

if (! function_exists('evolutio_render_hammer_icon')) {
    function evolutio_render_hammer_icon()
    {
        return '<svg aria-hidden="true" data-prefix="fa" data-icon="gavel" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504.971 199.362l-22.627-22.627c-9.373-9.373-24.569-9.373-33.941 0l-5.657 5.657L329.608 69.255l5.657-5.657c9.373-9.373 9.373-24.569 0-33.941L312.638 7.029c-9.373-9.373-24.569-9.373-33.941 0L154.246 131.48c-9.373 9.373-9.373 24.569 0 33.941l22.627 22.627c9.373 9.373 24.569 9.373 33.941 0l5.657-5.657 39.598 39.598-81.04 81.04-5.657-5.657c-12.497-12.497-32.758-12.497-45.255 0L9.373 412.118c-12.497 12.497-12.497 32.758 0 45.255l45.255 45.255c12.497 12.497 32.758 12.497 45.255 0l114.745-114.745c12.497-12.497 12.497-32.758 0-45.255l-5.657-5.657 81.04-81.04 39.598 39.598-5.657 5.657c-9.373 9.373-9.373 24.569 0 33.941l22.627 22.627c9.373 9.373 24.569 9.373 33.941 0l124.451-124.451c9.372-9.372 9.372-24.568 0-33.941z"></path></svg>';
    }
}

if (! function_exists('evolutio_render_linkedin_icon')) {
    function evolutio_render_linkedin_icon()
    {
        return '<svg aria-hidden="true" data-prefix="fab" data-icon="linkedin-in" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path></svg>';
    }
}

if (! function_exists('evolutio_render_legal_links')) {
    function evolutio_render_legal_links($className = '')
    {
        ob_start();
    ?>
        <a href="/politique-de-confidentialite" class="evolutio-footer__legal <?php echo esc_attr($className); ?>">
            Mentions légales et politique de confidentialité
        </a>
        <div class="evolutio-footer__legal <?php echo esc_attr($className); ?>">
            © Evolutio Avocats - 2025
        </div>
<?php
        return ob_get_clean();
    }
}
?>

<div <?php echo $containerAttributes ?>>
    <div class="evolutio-footer__top">
        <div class="evolutio-footer__contactbox">
            <div class="evolutio-footer__contacttext">Prenez directement rendez-vous !</div>
            <a href="<?php echo esc_url($contactUrl); ?>" class="evolutio-footer__contactbutton evolutio-no-hover">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.3744 13.8375C17.6931 19.1548 18.8997 13.0033 22.2862 16.3874C25.5511 19.6514 27.4275 20.3053 23.291 24.4406C22.7729 24.857 19.4808 29.8667 7.9115 18.3006C-3.65927 6.73302 1.34748 3.43761 1.76399 2.91962C5.91052 -1.22719 6.55318 0.660193 9.81802 3.92412C13.2045 7.30968 7.05557 8.52023 12.3744 13.8375Z" fill="currentColor" />
                </svg>
                Prenez rendez-vous
            </a>
        </div>
    </div>
    <div class="evolutio-footer__bottom">
        <div class="evolutio-footer__flexrow">
            <span class="evolutio-footer__brandcontainer">
                <img src="<?php echo esc_url($brandImageUrl); ?>" alt="Evolutio Avocats Logo" width="60" height="auto" />
                <span>Evolutio</span>
            </span>
            <div class="evolutio-footer__lefthalf">
                <div class="evolutio-footer__navigation">
                    <?php foreach ($internalLinks as $link) : ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="evolutio-footer__innerlink">
                            <?php echo esc_html($link['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <hr />
            <div class="evolutio-footer__righthalf">
                <div class="evolutio-footer__outerlinks">
                    <?php foreach ($externalLinks as $link) : ?>
                        <?php echo evolutio_render_external_link($link); ?>
                    <?php endforeach; ?>
                </div>
                <?php echo evolutio_render_legal_links('evolutio-footer__desktop'); ?>
            </div>
        </div>
        <div class="evolutio-footer__mobile-legals">
            <?php echo evolutio_render_legal_links(); ?>
        </div>
    </div>
</div>
