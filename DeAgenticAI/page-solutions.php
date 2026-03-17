<?php
/**
 * Template Name: Solutions Hub
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page solutions-hub-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_id = get_the_ID();
        $entries = aura_get_marketing_child_pages($page_id, 'page-solution-entry.php');
        if (empty($entries)) {
            $entries = aura_get_marketing_child_pages($page_id);
        }
        $lead = aura_get_marketing_page_excerpt($page_id, 44);
        ?>

        <div class="container marketing-shell">
            <section class="marketing-hero marketing-hero-centered">
                <div class="marketing-badge">
                    <span class="marketing-badge-dot"></span>
                    <span class="marketing-badge-text"><?php esc_html_e('Solutions Hub', 'aura'); ?></span>
                </div>

                <h1 class="marketing-title"><?php the_title(); ?></h1>

                <?php if ('' !== $lead) : ?>
                    <p class="marketing-lead"><?php echo esc_html($lead); ?></p>
                <?php endif; ?>

                <div class="marketing-prose marketing-prose-centered">
                    <?php the_content(); ?>
                </div>
            </section>

            <section class="marketing-grid-section">
                <div class="marketing-section-heading">
                    <h2 id="solutions-audience-pages"><?php esc_html_e('Audience-Specific Solution Pages', 'aura'); ?></h2>
                    <p><?php esc_html_e('Each page is built for a distinct buyer profile with its own pain-led framing, implementation steps, and answer-ready FAQ structure.', 'aura'); ?></p>
                    <div class="marketing-section-meta">
                        <?php
                        printf(
                            esc_html(_n('%s solution page', '%s solution pages', count($entries), 'aura')),
                            esc_html(number_format_i18n(count($entries)))
                        );
                        ?>
                    </div>
                </div>

                <div class="marketing-card-grid marketing-card-grid-solutions">
                    <?php foreach ($entries as $entry) : ?>
                        <?php $entry_lead = aura_get_marketing_page_excerpt($entry->ID, 28); ?>
                        <article class="marketing-card marketing-card-solution">
                            <div class="marketing-card-topline"><?php esc_html_e('Solution Page', 'aura'); ?></div>
                            <h2>
                                <a href="<?php echo esc_url(get_permalink($entry->ID)); ?>">
                                    <?php echo esc_html(get_the_title($entry->ID)); ?>
                                </a>
                            </h2>
                            <?php if ('' !== $entry_lead) : ?>
                                <p><?php echo esc_html($entry_lead); ?></p>
                            <?php endif; ?>
                            <a class="marketing-card-link" href="<?php echo esc_url(get_permalink($entry->ID)); ?>">
                                <?php esc_html_e('Explore Solution', 'aura'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($entries)) : ?>
                    <div class="marketing-empty-state">
                        <p><?php esc_html_e('No solution pages exist yet. Add child pages under this page and assign the "Solution Entry" template.', 'aura'); ?></p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>