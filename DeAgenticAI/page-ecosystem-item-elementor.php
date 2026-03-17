<?php
/**
 * Legacy ecosystem item page template slug.
 *
 * @package Aura
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/ecosystem-item-master'); ?>
<?php endwhile; ?>

<?php get_footer(); ?>
