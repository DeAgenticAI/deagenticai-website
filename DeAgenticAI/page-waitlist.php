<?php
/**
 * Template Name: Waitlist
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main waitlist-page">
    <section class="about-hero legal-hero" style="padding-bottom: 1.5rem;">
        <div class="container">
            <div class="about-hero-content reveal-up" style="transition-delay: 0.06s;">
                <h1><?php esc_html_e('Join the Waitlist', 'aura'); ?></h1>
                <p><?php esc_html_e('Get early access updates and product milestones from DeAgenticAI.', 'aura'); ?></p>
            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/section', 'waitlist-cta'); ?>
</main>

<?php get_footer();
