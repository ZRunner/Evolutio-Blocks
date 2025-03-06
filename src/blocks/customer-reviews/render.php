<?php
$description = isset($attributes['description']) ? wp_kses_post($attributes['description']) : '';

$containerAttributes = get_block_wrapper_attributes([
    'class' => 'evolutio-customer-reviews',
]);

// Fetch all reviews from the "review" custom post type
$reviews = new WP_Query(array(
    'post_type'      => 'review',
    'posts_per_page' => -1,
    'status'         => 'publish',
));
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<div <?php echo $containerAttributes ?>>
    <h3 class="evolutio-customer-reviews__desktop-title">Ce que vous pensez de nous</h3>
    <h3 class="evolutio-customer-reviews__mobile-title">Vos avis</h3>

    <p class="evolutio-customer-reviews__description">
        <?php echo $description; ?>
    </p>

    <a href="/avis" class="evolutio-customer-reviews__readmore">
        Explorez nos avis
        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.41016 19.9201L15.9302 13.4001C16.7002 12.6301 16.7002 11.3701 15.9302 10.6001L9.41016 4.08008"
                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>

    <div class="swiper">
        <div class="swiper-wrapper">
            <?php if ($reviews->have_posts()) :
                while ($reviews->have_posts()) : $reviews->the_post();
                    $name = get_the_title() ?: 'Anonyme';
                    $role = get_post_meta(get_the_ID(), 'review_job_title', true) ?: '​';
                    $content = get_post_meta(get_the_ID(), 'review_text', true) ?: '?';
            ?>
                    <div class="swiper-slide">
                        <div class="evolutio-customer-review__role"> <?php echo esc_html($role); ?> </div>
                        <div class="evolutio-customer-review__content"> <?php echo esc_html($content); ?> </div>
                        <div class="evolutio-customer-review__author"> <?php echo esc_html($name); ?> </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <p>Aucun avis disponible.</p>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>