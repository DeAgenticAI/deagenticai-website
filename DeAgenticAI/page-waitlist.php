<?php
/**
 * Template Name: Waitlist
 *
 * @package Aura
 */

get_header();

$waitlist_page_title = function_exists( 'aura_get_content' )
    ? aura_get_content( 'waitlist_page_title', 'Join the Waitlist' )
    : 'Join the Waitlist';
$waitlist_page_description = function_exists( 'aura_get_content' )
    ? aura_get_content( 'waitlist_page_description', 'Get early access updates and product milestones from DeAgenticAI.' )
    : 'Get early access updates and product milestones from DeAgenticAI.';
?>

<main id="primary" class="site-main waitlist-page">
    <section class="about-hero legal-hero" style="padding-bottom: 1.5rem;">
        <div class="container">
            <div class="about-hero-content reveal-up" style="transition-delay: 0.06s;">
                <h1><?php echo esc_html( $waitlist_page_title ); ?></h1>
                <p><?php echo esc_html( $waitlist_page_description ); ?></p>
            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/section', 'waitlist-cta'); ?>
</main>

<?php get_footer();
