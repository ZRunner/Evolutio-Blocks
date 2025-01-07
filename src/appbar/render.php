<?php
$links = $attributes['links'] ?? [];
$websiteName = $attributes['websiteName'] ?? get_bloginfo('name');
$contactUrl = $attributes['contactUrl'];
?>
<div class="evolutio-appbar-desktop-container">
    <div class="evolutio-appbar-background">
    </div>
    <div class="evolutio-appbar__floating">
        <nav class="evolutio-appbar__nav">
            <?php foreach ($links as $link): ?>
                <a href="<?php echo esc_url($link['url']); ?>" class="evolutio-appbar__link">
                    <?php echo esc_html($link['label']); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <a class="evolutio-appbar__button" href=<?php echo esc_attr($contactUrl) ?>>Contact</a>
    </div>
</div>
<div class="evolutio-appbar-mobile-container">
    <a class="evolutio-appbar-brand-container" href="/">
        <img src="<?php echo esc_url(get_site_icon_url()); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?> Logo"
            width="50" height="50" />
        <span><?php echo esc_html($websiteName); ?></span>
    </a>
    <input type="checkbox" id="nav-toggle" class="evolutio-mobile-nav-toggle" hidden />
    <label for="nav-toggle" class="evolutio-burger-icon">
        <svg width="24" height="24" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line x1="1.43945" y1="1.5" x2="22.068" y2="1.5" stroke="#092747" stroke-width="2" stroke-linecap="round" />
            <line x1="1.43945" y1="9.04297" x2="22.068" y2="9.04297" stroke="#092747" stroke-width="2" stroke-linecap="round" />
            <line x1="1.43945" y1="16.5859" x2="22.068" y2="16.5859" stroke="#092747" stroke-width="2" stroke-linecap="round" />
        </svg>
    </label>
    <nav class="evolutio-mobile-nav">
        <ul>
            <?php foreach ($links as $link): ?>
                <li>
                    <a href="<?php echo esc_url($link['url']); ?>" class="evolutio-appbar__link">
                        <?php echo esc_html($link['label']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <a class="evolutio-appbar__button" href=<?php echo esc_attr($contactUrl) ?>>Contactez-nous</a>
        </ul>
    </nav>
</div>