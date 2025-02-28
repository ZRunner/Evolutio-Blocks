<?php
$containerAttributes = get_block_wrapper_attributes([
    'class' => 'evolutio-reviews-wall',
]);

// Fetch all reviews from the "review" custom post type
$reviews = new WP_Query(array(
    'post_type'      => 'review',
    'posts_per_page' => -1,
    'status'         => 'publish',
));
?>

<div <?php echo $containerAttributes ?>>
    <div class="evolutio-reviews-wall__container">
        <?php if ($reviews->have_posts()) :
            while ($reviews->have_posts()) : $reviews->the_post();
                $name = get_the_title() ?: 'Anonyme';
                $role = get_post_meta(get_the_ID(), 'review_job_title', true) ?: '​';
                $content = get_post_meta(get_the_ID(), 'review_text', true) ?: '?';
        ?>
                <div class="evolutio-reviews-wall__card">
                    <div class="evolutio-reviews-wall__role"> <?php echo esc_html($role); ?> </div>
                    <div class="evolutio-reviews-wall__content"> <?php echo esc_html($content); ?> </div>
                    <div class="evolutio-reviews-wall__author"> <?php echo esc_html($name); ?> </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p>Aucun avis disponible.</p>
        <?php endif; ?>
    </div>
</div>