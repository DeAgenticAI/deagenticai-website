<?php
/**
 * Template Name: Glossary Entry
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page glossary-entry-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_id = get_the_ID();
        $payload = aura_get_glossary_entry_payload($page_id);
        $breadcrumbs = aura_get_marketing_breadcrumb_items($page_id);
        $featured_image_html = get_the_post_thumbnail(
            $page_id,
            'large',
            array(
                'alt' => the_title_attribute(array('echo' => false, 'post' => $page_id)),
                'loading' => 'eager',
                'decoding' => 'async',
                'fetchpriority' => 'high',
            )
        );
        $semantic_triple = function_exists('aura_get_semantic_triple') ? aura_get_semantic_triple() : '';
        $definition_has_triple = (
            '' !== $semantic_triple &&
            '' !== $payload['definition'] &&
            false !== stripos($payload['definition'], $semantic_triple)
        );
        $overview_content = trim((string) get_post_field('post_content', $page_id));
        $has_overview_content = '' !== $overview_content;
        if ($has_overview_content && function_exists('aura_is_marketing_seed_stub_content')) {
            $has_overview_content = !aura_is_marketing_seed_stub_content($overview_content);
        }
        $has_how_steps = !empty($payload['how_steps']);
        $has_why_it_matters = '' !== $payload['why_it_matters'];
        $has_faqs = !empty($payload['faqs']);
        $has_related_entities = !empty($payload['related_entities']);
        $has_further_reading = !empty($payload['further_reading']);
        $jump_links = array();

        if ($has_overview_content) {
            $jump_links[] = array(
                'id' => 'entry-overview',
                'label' => __('Overview', 'aura'),
            );
        }

        if ($has_how_steps) {
            $jump_links[] = array(
                'id' => 'entry-how-it-works',
                'label' => __('How it works', 'aura'),
            );
        }

        if ($has_why_it_matters) {
            $jump_links[] = array(
                'id' => 'entry-why-it-matters',
                'label' => __('Why it matters', 'aura'),
            );
        }

        if ($has_faqs) {
            $jump_links[] = array(
                'id' => 'entry-faq',
                'label' => __('FAQ', 'aura'),
            );
        }

        if ($has_related_entities) {
            $jump_links[] = array(
                'id' => 'entry-related-entities',
                'label' => __('Related entities', 'aura'),
            );
        }

        if ($has_further_reading) {
            $jump_links[] = array(
                'id' => 'entry-further-reading',
                'label' => __('Further reading', 'aura'),
            );
        }
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
                        <span class="marketing-badge-text"><?php esc_html_e('Glossary Entry', 'aura'); ?></span>
                    </div>

                    <h1 class="marketing-entry-title">
                        <?php printf(esc_html__('What is %s?', 'aura'), get_the_title()); ?>
                    </h1>

                    <?php if ('' !== $payload['definition']) : ?>
                        <p class="marketing-definition"><?php echo esc_html($payload['definition']); ?></p>
                    <?php endif; ?>

                    <?php if ('' !== $semantic_triple && !$definition_has_triple) : ?>
                        <p class="marketing-lead marketing-lead-left"><?php echo esc_html($semantic_triple); ?></p>
                    <?php endif; ?>
                </div>

                <div class="marketing-entry-visual">
                    <?php if ('' !== $featured_image_html) : ?>
                        <figure class="marketing-featured-media">
                            <?php echo wp_kses_post($featured_image_html); ?>
                        </figure>
                    <?php else : ?>
                        <div class="marketing-visual-panel">
                            <div class="marketing-visual-glow"></div>
                            <div class="marketing-visual-chip"><?php esc_html_e('Definition Surface', 'aura'); ?></div>
                            <strong><?php the_title(); ?></strong>
                            <p><?php esc_html_e('Key concepts, control boundaries, and context for this term in the DeAgenticAI architecture.', 'aura'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <?php if (count($jump_links) > 1) : ?>
                <nav class="marketing-page-jump-nav" aria-label="<?php esc_attr_e('On this page', 'aura'); ?>">
                    <p class="marketing-page-jump-title"><?php esc_html_e('On this page', 'aura'); ?></p>
                    <ul class="marketing-page-jump-list">
                        <?php foreach ($jump_links as $item) : ?>
                            <li>
                                <a href="#<?php echo esc_attr($item['id']); ?>"><?php echo esc_html($item['label']); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <div class="marketing-content-layout">
                <div class="marketing-main-column">
                    <?php if ($has_overview_content) : ?>
                        <section id="entry-overview" class="marketing-section-card marketing-prose-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('Overview', 'aura'); ?></h2>
                            </div>
                            <div class="marketing-prose">
                                <?php the_content(); ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_how_steps) : ?>
                        <section id="entry-how-it-works" class="marketing-section-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('How does it work?', 'aura'); ?></h2>
                            </div>

                            <ol class="marketing-step-list">
                                <?php foreach ($payload['how_steps'] as $index => $step) : ?>
                                    <li class="marketing-step-item">
                                        <span class="marketing-step-number"><?php echo esc_html((string) ($index + 1)); ?></span>
                                        <div>
                                            <h3><?php echo esc_html($step['title']); ?></h3>
                                            <p><?php echo esc_html($step['text']); ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_why_it_matters) : ?>
                        <section id="entry-why-it-matters" class="marketing-section-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('Why does this matter?', 'aura'); ?></h2>
                            </div>
                            <p class="marketing-body-copy"><?php echo esc_html($payload['why_it_matters']); ?></p>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_faqs) : ?>
                        <section id="entry-faq" class="marketing-section-card">
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
                    <?php endif; ?>
                </div>

                <aside class="marketing-sidebar-column">
                    <?php if ($has_related_entities) : ?>
                        <section id="entry-related-entities" class="marketing-side-card">
                            <h2><?php esc_html_e('Related Entities', 'aura'); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ($payload['related_entities'] as $item) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(aura_marketing_resolve_link($item['url'])); ?>"><?php echo esc_html($item['label']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_further_reading) : ?>
                        <section id="entry-further-reading" class="marketing-side-card">
                            <h2><?php esc_html_e('Further Reading', 'aura'); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ($payload['further_reading'] as $item) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(aura_marketing_resolve_link($item['url'])); ?>"><?php echo esc_html($item['label']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>