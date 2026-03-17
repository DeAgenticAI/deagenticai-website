<?php
/**
 * Template Name: Learn Hub
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page learn-hub-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_id = get_the_ID();
        $lead = function_exists('aura_get_marketing_page_excerpt')
            ? aura_get_marketing_page_excerpt($page_id, 44)
            : get_the_excerpt();

        $sections = array();
        foreach (array('aura_pillar', 'aura_guide', 'aura_research', 'aura_update') as $learn_post_type) {
            if (!post_type_exists($learn_post_type)) {
                continue;
            }

            $sections[] = array(
                'post_type' => $learn_post_type,
                'meta' => function_exists('aura_get_learn_archive_meta') ? aura_get_learn_archive_meta($learn_post_type) : array(),
                'archive_url' => get_post_type_archive_link($learn_post_type),
                'items' => get_posts(array(
                    'post_type' => $learn_post_type,
                    'post_status' => 'publish',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'no_found_rows' => true,
                )),
            );
        }

        $compare_archive = post_type_exists('aura_compare') ? get_post_type_archive_link('aura_compare') : '';
        $faq_hub_page = get_page_by_path('learn/faq');
        $faq_hub_url = ($faq_hub_page instanceof WP_Post) ? get_permalink($faq_hub_page->ID) : '';
        ?>

        <div class="container marketing-shell">
            <section class="marketing-hero marketing-hero-centered">
                <div class="marketing-badge">
                    <span class="marketing-badge-dot"></span>
                    <span class="marketing-badge-text"><?php esc_html_e('Learn Hub', 'aura'); ?></span>
                </div>

                <h1 class="marketing-title"><?php the_title(); ?></h1>

                <?php if ('' !== trim((string) $lead)) : ?>
                    <p class="marketing-lead"><?php echo esc_html($lead); ?></p>
                <?php endif; ?>

                <div class="marketing-prose marketing-prose-centered">
                    <?php the_content(); ?>
                </div>
            </section>

            <section class="marketing-grid-section" style="margin-bottom: 2rem;">
                <div class="marketing-section-heading">
                    <h2><?php esc_html_e('Explore Content Tracks', 'aura'); ?></h2>
                    <p><?php esc_html_e('Use these tracks to navigate authoritative pillar pages, implementation guides, research notes, and release updates.', 'aura'); ?></p>
                </div>

                <div class="marketing-card-grid marketing-card-grid-solutions">
                    <?php foreach ($sections as $section) : ?>
                        <?php if (empty($section['archive_url'])) { continue; } ?>
                        <article class="marketing-card marketing-card-solution">
                            <div class="marketing-card-topline"><?php echo esc_html(isset($section['meta']['badge']) ? $section['meta']['badge'] : __('Learn', 'aura')); ?></div>
                            <h2><a href="<?php echo esc_url($section['archive_url']); ?>"><?php echo esc_html(get_post_type_object($section['post_type'])->labels->name); ?></a></h2>
                            <p><?php echo esc_html(isset($section['meta']['description']) ? $section['meta']['description'] : ''); ?></p>
                            <a class="marketing-card-link" href="<?php echo esc_url($section['archive_url']); ?>">
                                <?php esc_html_e('Browse Collection', 'aura'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                        </article>
                    <?php endforeach; ?>

                    <?php if ($compare_archive) : ?>
                        <article class="marketing-card marketing-card-solution">
                            <div class="marketing-card-topline"><?php esc_html_e('Comparison Hub', 'aura'); ?></div>
                            <h2><a href="<?php echo esc_url($compare_archive); ?>"><?php esc_html_e('Compare', 'aura'); ?></a></h2>
                            <p><?php esc_html_e('Evaluation pages for teams comparing DeAgenticAI against alternatives and adjacent architecture choices.', 'aura'); ?></p>
                            <a class="marketing-card-link" href="<?php echo esc_url($compare_archive); ?>">
                                <?php esc_html_e('View Comparisons', 'aura'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                        </article>
                    <?php endif; ?>

                    <?php if ($faq_hub_url) : ?>
                        <article class="marketing-card marketing-card-solution">
                            <div class="marketing-card-topline"><?php esc_html_e('AEO FAQ', 'aura'); ?></div>
                            <h2><a href="<?php echo esc_url($faq_hub_url); ?>"><?php esc_html_e('Learn FAQ', 'aura'); ?></a></h2>
                            <p><?php esc_html_e('Topic-specific FAQ surfaces designed for high-intent implementation questions and FAQPage schema coverage.', 'aura'); ?></p>
                            <a class="marketing-card-link" href="<?php echo esc_url($faq_hub_url); ?>">
                                <?php esc_html_e('Browse FAQ Topics', 'aura'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                        </article>
                    <?php endif; ?>
                </div>
            </section>

            <?php foreach ($sections as $section) : ?>
                <section class="marketing-grid-section" style="margin-bottom: 2rem;">
                    <div class="marketing-section-heading">
                        <h2><?php echo esc_html(get_post_type_object($section['post_type'])->labels->name); ?></h2>
                        <p><?php echo esc_html(isset($section['meta']['description']) ? $section['meta']['description'] : ''); ?></p>
                    </div>

                    <div class="marketing-card-grid marketing-card-grid-solutions">
                        <?php foreach ($section['items'] as $item) : ?>
                            <?php $entry_lead = function_exists('aura_get_learn_entry_excerpt') ? aura_get_learn_entry_excerpt($item->ID, 30) : get_the_excerpt($item->ID); ?>
                            <article class="marketing-card marketing-card-solution">
                                <div class="marketing-card-topline"><?php echo esc_html(isset($section['meta']['card_label']) ? $section['meta']['card_label'] : __('Resource', 'aura')); ?></div>
                                <h2><a href="<?php echo esc_url(get_permalink($item->ID)); ?>"><?php echo esc_html(get_the_title($item->ID)); ?></a></h2>
                                <?php if ('' !== trim((string) $entry_lead)) : ?>
                                    <p><?php echo esc_html($entry_lead); ?></p>
                                <?php endif; ?>
                                <a class="marketing-card-link" href="<?php echo esc_url(get_permalink($item->ID)); ?>">
                                    <?php esc_html_e('Read', 'aura'); ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php if (empty($section['items'])) : ?>
                        <div class="marketing-empty-state">
                            <p><?php esc_html_e('No published entries yet. Publish the seeded drafts from wp-admin to populate this section.', 'aura'); ?></p>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer();
