<?php
/**
 * Template Name: Learn FAQ Topic
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page learn-faq-topic-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_id = get_the_ID();
        $payload = aura_get_learn_faq_topic_payload($page_id);
        $breadcrumbs = aura_get_marketing_breadcrumb_items($page_id);
        $lead = aura_get_marketing_page_excerpt($page_id, 36);
        $featured_image = get_the_post_thumbnail_url($page_id, 'large');
        ?>

        <div class="container marketing-shell">
            <nav class="marketing-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'aura'); ?>">
                <?php foreach ($breadcrumbs as $index => $item) : ?>
                    <?php if ($index > 0) : ?>
                        <span>›</span>
                    <?php endif; ?>
                    <?php if ($index === count($breadcrumbs) - 1) : ?>
                        <span><?php echo esc_html($item['label']); ?></span>
                    <?php else : ?>
                        <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <section class="marketing-entry-hero">
                <div class="marketing-entry-copy">
                    <div class="marketing-badge">
                        <span class="marketing-badge-dot"></span>
                        <span class="marketing-badge-text"><?php esc_html_e('Learn FAQ Topic', 'aura'); ?></span>
                    </div>

                    <h1 class="marketing-entry-title"><?php the_title(); ?></h1>

                    <?php if ('' !== $lead) : ?>
                        <p class="marketing-lead marketing-lead-left"><?php echo esc_html($lead); ?></p>
                    <?php endif; ?>
                </div>

                <div class="marketing-entry-visual">
                    <?php if ($featured_image) : ?>
                        <figure class="marketing-featured-media">
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>">
                        </figure>
                    <?php else : ?>
                        <div class="marketing-visual-panel">
                            <div class="marketing-visual-glow"></div>
                            <div class="marketing-visual-chip"><?php esc_html_e('AEO Surface', 'aura'); ?></div>
                            <strong><?php esc_html_e('Structured answers for high-intent implementation questions', 'aura'); ?></strong>
                            <p><?php esc_html_e('Use this template for concise technical answers with FAQPage schema output.', 'aura'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <div class="marketing-main-column">
                <section class="marketing-section-card marketing-prose-card">
                    <div class="marketing-prose">
                        <?php the_content(); ?>
                    </div>
                </section>

                <?php if (!empty($payload['faqs'])) : ?>
                    <section class="marketing-section-card">
                        <div class="marketing-section-heading">
                            <h2><?php esc_html_e('Frequently Asked Questions', 'aura'); ?></h2>
                        </div>

                        <div class="marketing-faq-list">
                            <?php foreach ($payload['faqs'] as $faq) : ?>
                                <article class="marketing-faq-item">
                                    <h3><?php echo esc_html($faq['question']); ?></h3>
                                    <p><?php echo esc_html($faq['answer']); ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php else : ?>
                    <section class="marketing-section-card">
                        <div class="marketing-empty-state" style="margin-top: 0;">
                            <p><?php esc_html_e('Add at least 3 FAQ rows in the page settings metabox to activate FAQPage schema output for this topic.', 'aura'); ?></p>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
