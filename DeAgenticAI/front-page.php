<?php
/**
 * The front page template
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main front-page">
    
    <!-- Hero Section -->
    <?php get_template_part('template-parts/section', 'hero'); ?>

    <!-- Features Section -->
    <?php get_template_part('template-parts/section', 'features'); ?>

    <!-- Architecture Section -->
    <?php get_template_part('template-parts/section', 'architecture'); ?>

    <!-- Use Cases Section -->
    <?php get_template_part('template-parts/section', 'usecases'); ?>

</main><!-- #primary -->

<?php
get_footer();
