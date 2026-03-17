<?php
/**
 * Template Name: Learn FAQ Hub
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page learn-faq-hub-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_id = get_the_ID();
        $entries = aura_get_marketing_child_pages($page_id, 'page-learn-faq-topic.php');
        if (empty($entries)) {
            $entries = aura_get_marketing_child_pages($page_id);
        }
        $lead = aura_get_marketing_page_excerpt($page_id, 40);
        ?>

        <div class="container marketing-shell">
            <section class="marketing-hero marketing-hero-centered">
                <div class="marketing-badge">
                    <span class="marketing-badge-dot"></span>
                    <span class="marketing-badge-text"><?php esc_html_e('Learn FAQ', 'aura'); ?></span>
                </div>

                <h1 class="marketing-title"><?php the_title(); ?></h1>

                <?php if ('' !== $lead) : ?>
                    <p class="marketing-lead"><?php echo esc_html($lead); ?></p>
                <?php endif; ?>

                <div class="marketing-prose marketing-prose-centered">
                    <?php the_content(); ?>
                </div>
            </section>

            <?php if (!empty($entries)) : ?>
                <section class="marketing-grid-section">
                    <div class="marketing-section-heading">
                        <h2><?php esc_html_e('FAQ Topics', 'aura'); ?></h2>
                        <p><?php esc_html_e('Topic pages in this section are optimized for direct answers, implementation clarity, and FAQPage schema output.', 'aura'); ?></p>
                    </div>

                    <div class="marketing-card-grid marketing-card-grid-solutions">
                        <?php foreach ($entries as $entry) : ?>
                            <?php $entry_lead = aura_get_marketing_page_excerpt($entry->ID, 26); ?>
                            <article class="marketing-card marketing-card-solution">
                                <div class="marketing-card-topline"><?php esc_html_e('FAQ Topic', 'aura'); ?></div>
                                <h2>
                                    <a href="<?php echo esc_url(get_permalink($entry->ID)); ?>">
                                        <?php echo esc_html(get_the_title($entry->ID)); ?>
                                    </a>
                                </h2>
                                <?php if ('' !== $entry_lead) : ?>
                                    <p><?php echo esc_html($entry_lead); ?></p>
                                <?php endif; ?>
                                <a class="marketing-card-link" href="<?php echo esc_url(get_permalink($entry->ID)); ?>">
                                    <?php esc_html_e('Open Topic', 'aura'); ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
