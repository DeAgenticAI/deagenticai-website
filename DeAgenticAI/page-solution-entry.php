<?php
/**
 * Template Name: Solution Entry
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page solution-entry-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $page_id = get_the_ID();
        $payload = aura_get_solution_entry_payload($page_id);
        $breadcrumbs = aura_get_marketing_breadcrumb_items($page_id);
        $lead = aura_get_marketing_page_excerpt($page_id, 40);
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
        $lead_has_triple = (
            '' !== $semantic_triple &&
            '' !== $lead &&
            false !== stripos($lead, $semantic_triple)
        );
        $overview_content = trim((string) get_post_field('post_content', $page_id));
        $has_overview_content = '' !== $overview_content;
        if ($has_overview_content && function_exists('aura_is_marketing_seed_stub_content')) {
            $has_overview_content = !aura_is_marketing_seed_stub_content($overview_content);
        }
        $has_implementation_steps = !empty($payload['implementation_steps']);
        $has_faqs = !empty($payload['faqs']);
        $has_glossary_links = !empty($payload['glossary_links']);
        $has_resource_links = !empty($payload['resource_links']);
        $has_compare_links = !empty($payload['compare_links']);
        $jump_links = array();

        if ($has_overview_content) {
            $jump_links[] = array(
                'id' => 'solution-overview',
                'label' => __('Overview', 'aura'),
            );
        }

        if ($has_implementation_steps) {
            $jump_links[] = array(
                'id' => 'solution-implementation',
                'label' => __('Implementation', 'aura'),
            );
        }

        $jump_links[] = array(
            'id' => 'solution-case-study',
            'label' => __('Case study', 'aura'),
        );

        if ($has_faqs) {
            $jump_links[] = array(
                'id' => 'solution-faq',
                'label' => __('FAQ', 'aura'),
            );
        }

        if ($has_glossary_links) {
            $jump_links[] = array(
                'id' => 'solution-glossary-links',
                'label' => __('Glossary terms', 'aura'),
            );
        }

        if ($has_resource_links) {
            $jump_links[] = array(
                'id' => 'solution-resources',
                'label' => __('Resources', 'aura'),
            );
        }

        if ($has_compare_links) {
            $jump_links[] = array(
                'id' => 'solution-compare-links',
                'label' => __('Compare pages', 'aura'),
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

            <section class="marketing-entry-hero marketing-entry-hero-solution">
                <div class="marketing-entry-copy">
                    <div class="marketing-badge">
                        <span class="marketing-badge-dot"></span>
                        <span class="marketing-badge-text"><?php echo esc_html($payload['eyebrow']); ?></span>
                    </div>

                    <h1 class="marketing-entry-title"><?php the_title(); ?></h1>

                    <?php if ('' !== $lead) : ?>
                        <p class="marketing-lead marketing-lead-left"><?php echo esc_html($lead); ?></p>
                    <?php endif; ?>

                    <?php if ('' !== $semantic_triple && !$lead_has_triple) : ?>
                        <p class="marketing-lead marketing-lead-left"><?php echo esc_html($semantic_triple); ?></p>
                    <?php endif; ?>

                    <div class="marketing-action-row">
                        <a href="<?php echo esc_url(aura_marketing_resolve_link($payload['primary_cta_url'])); ?>" class="marketing-btn marketing-btn-primary">
                            <?php echo esc_html($payload['primary_cta_label']); ?>
                        </a>
                        <a href="<?php echo esc_url(aura_marketing_resolve_link($payload['secondary_cta_url'])); ?>" class="marketing-btn marketing-btn-secondary">
                            <?php echo esc_html($payload['secondary_cta_label']); ?>
                        </a>
                    </div>
                </div>

                <div class="marketing-entry-visual">
                    <?php if ('' !== $featured_image_html) : ?>
                        <figure class="marketing-featured-media">
                            <?php echo wp_kses_post($featured_image_html); ?>
                        </figure>
                    <?php else : ?>
                        <div class="marketing-visual-panel marketing-visual-panel-solution">
                            <div class="marketing-visual-glow"></div>
                            <div class="marketing-visual-chip"><?php esc_html_e('Implementation Path', 'aura'); ?></div>
                            <strong><?php esc_html_e('Intent → Policy → Audit → Execution', 'aura'); ?></strong>
                            <p><?php esc_html_e('A governed workflow from policy design to auditable autonomous execution.', 'aura'); ?></p>
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

            <div class="marketing-content-layout marketing-content-layout-solution">
                <div class="marketing-main-column">
                    <?php if ($has_overview_content) : ?>
                        <section id="solution-overview" class="marketing-section-card marketing-prose-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('Overview', 'aura'); ?></h2>
                            </div>
                            <div class="marketing-prose">
                                <?php the_content(); ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_implementation_steps) : ?>
                        <section id="solution-implementation" class="marketing-section-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('How do you implement this?', 'aura'); ?></h2>
                                <p><?php esc_html_e('A practical sequence from authority design to controlled production rollout.', 'aura'); ?></p>
                            </div>

                            <ol class="marketing-step-list">
                                <?php foreach ($payload['implementation_steps'] as $index => $step) : ?>
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

                    <section id="solution-case-study" class="marketing-section-card marketing-case-study-card">
                        <div class="marketing-section-heading">
                            <h2><?php echo esc_html($payload['case_study_title']); ?></h2>
                        </div>
                        <p class="marketing-body-copy"><?php echo esc_html($payload['case_study_body']); ?></p>
                    </section>

                    <?php if ($has_faqs) : ?>
                        <section id="solution-faq" class="marketing-section-card">
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
                    <?php if ($has_glossary_links) : ?>
                        <section id="solution-glossary-links" class="marketing-side-card">
                            <h2><?php esc_html_e('Glossary Terms', 'aura'); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ($payload['glossary_links'] as $item) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(aura_marketing_resolve_link($item['url'])); ?>"><?php echo esc_html($item['label']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_resource_links) : ?>
                        <section id="solution-resources" class="marketing-side-card">
                            <h2><?php esc_html_e('Related Resources', 'aura'); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ($payload['resource_links'] as $item) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(aura_marketing_resolve_link($item['url'])); ?>"><?php echo esc_html($item['label']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>

                    <?php if ($has_compare_links) : ?>
                        <section id="solution-compare-links" class="marketing-side-card">
                            <h2><?php esc_html_e('Comparison Pages', 'aura'); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ($payload['compare_links'] as $item) : ?>
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