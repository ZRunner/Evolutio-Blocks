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
        <a class="evolutio-appbar__button evolutio-no-hover" href=<?php echo esc_attr($contactUrl) ?>>Contact</a>
    </div>
</div>
<div class="evolutio-appbar-mobile-container">
    <a class="evolutio-appbar-brand-container evolutio-no-hover" href="/">
        <img src="<?php echo esc_url(get_site_icon_url()); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?> Logo"
            width="50" height="50" />
        <span><?php echo esc_html($websiteName); ?></span>
    </a>
    <input type="checkbox" id="nav-toggle" hidden />
    <label for="nav-toggle" id="nav-toggle-label">
        <div class="top line"></div>
        <div class="middle line"></div>
        <div class="bottom line"></div>
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
            <a class="evolutio-appbar__button evolutio-no-hover" href=<?php echo esc_attr($contactUrl) ?>>Contactez-nous</a>
        </ul>
    </nav>
</div>