<?php
/**
 * Learn/compare single content layout.
 *
 * @package Aura
 */
?>

<main id="primary" class="site-main marketing-page learn-single-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        $archive_link = get_post_type_archive_link($post_type);
        $archive_obj = get_post_type_object($post_type);
        $archive_label = ($archive_obj && !empty($archive_obj->labels->name)) ? (string) $archive_obj->labels->name : __('Learn', 'aura');
        $archive_meta = function_exists('aura_get_learn_archive_meta') ? aura_get_learn_archive_meta($post_type) : array();
        $lead = function_exists('aura_get_learn_entry_excerpt') ? aura_get_learn_entry_excerpt($post_id, 48) : get_the_excerpt();
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
        $highlight_step = function_exists('aura_get_pillar_highlight_step') ? aura_get_pillar_highlight_step($post_id) : 3;

        $compare_payload = ('aura_compare' === $post_type && function_exists('aura_get_compare_payload')) ? aura_get_compare_payload($post_id) : array();
        $compare_left = isset($compare_payload['entity_left']) ? trim((string) $compare_payload['entity_left']) : '';
        $compare_right = isset($compare_payload['entity_right']) ? trim((string) $compare_payload['entity_right']) : '';
        $compare_primary_question = isset($compare_payload['primary_question']) ? trim((string) $compare_payload['primary_question']) : '';
        $compare_frame = isset($compare_payload['frame']) ? trim((string) $compare_payload['frame']) : '';
        $compare_quick_verdict = isset($compare_payload['quick_verdict']) ? trim((string) $compare_payload['quick_verdict']) : '';
        $compare_matrix = isset($compare_payload['matrix']) && is_array($compare_payload['matrix']) ? $compare_payload['matrix'] : array();
        $compare_steps = isset($compare_payload['steps']) && is_array($compare_payload['steps']) ? $compare_payload['steps'] : array();
        $compare_faqs = isset($compare_payload['faqs']) && is_array($compare_payload['faqs']) ? $compare_payload['faqs'] : array();
        $compare_recommended = isset($compare_payload['recommended_for']) && is_array($compare_payload['recommended_for']) ? $compare_payload['recommended_for'] : array();
        $compare_not_ideal = isset($compare_payload['not_ideal_for']) && is_array($compare_payload['not_ideal_for']) ? $compare_payload['not_ideal_for'] : array();

        $pillar_scaffold = ('aura_pillar' === $post_type && function_exists('aura_get_pillar_scaffold_payload')) ? aura_get_pillar_scaffold_payload($post_id) : array();
        $pillar_faqs = ('aura_pillar' === $post_type && function_exists('aura_get_pillar_faq_payload')) ? aura_get_pillar_faq_payload($post_id) : array();
        $pillar_focus_step = isset($pillar_scaffold['focus_step']) ? (int) $pillar_scaffold['focus_step'] : 0;
        $pillar_focus_label = isset($pillar_scaffold['focus_label']) ? trim((string) $pillar_scaffold['focus_label']) : '';
        $pillar_visual_placeholder = isset($pillar_scaffold['visual_placeholder']) ? trim((string) $pillar_scaffold['visual_placeholder']) : '';
        $pillar_cluster_placeholders = isset($pillar_scaffold['cluster_placeholders']) && is_array($pillar_scaffold['cluster_placeholders']) ? $pillar_scaffold['cluster_placeholders'] : array();

        $related = get_posts(array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
        ));
        ?>

        <div class="container marketing-shell">
            <nav class="marketing-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'aura'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aura'); ?></a>
                <?php if ($archive_link) : ?>
                    <span>></span>
                    <a href="<?php echo esc_url($archive_link); ?>"><?php echo esc_html($archive_label); ?></a>
                <?php endif; ?>
                <span>></span>
                <span><?php the_title(); ?></span>
            </nav>

            <?php if ('aura_pillar' === $post_type) : ?>
                <section class="learn-agentic-lifecycle">
                    <?php
                    if (function_exists('aura_render_agentic_lifecycle_dock')) {
                        echo aura_render_agentic_lifecycle_dock(array(
                            'title' => __('Happy Path Execution Sequence', 'aura'),
                            'status' => __('Happy Path Protocol Active', 'aura'),
                            'active_step' => $highlight_step,
                        ));
                    }
                    ?>
                </section>
            <?php endif; ?>

            <section class="marketing-entry-hero">
                <div class="marketing-entry-copy">
                    <div class="marketing-badge">
                        <span class="marketing-badge-dot"></span>
                        <span class="marketing-badge-text"><?php echo esc_html(isset($archive_meta['card_label']) ? $archive_meta['card_label'] : __('Resource', 'aura')); ?></span>
                    </div>

                    <h1 class="marketing-entry-title"><?php the_title(); ?></h1>

                    <?php if ('' !== trim((string) $lead)) : ?>
                        <p class="marketing-lead marketing-lead-left"><?php echo esc_html($lead); ?></p>
                    <?php endif; ?>
                </div>

                <div class="marketing-entry-visual">
                    <?php if ($featured_image) : ?>
                        <figure class="marketing-featured-media">
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>">
                        </figure>
                    <?php else : ?>
                        <?php if ('aura_compare' === $post_type) : ?>
                            <div class="marketing-visual-panel compare-visual-panel">
                                <div class="marketing-visual-glow"></div>
                                <div class="marketing-visual-chip"><?php esc_html_e('Comparison', 'aura'); ?></div>
                                <strong>
                                    <?php
                                    if ('' !== $compare_left && '' !== $compare_right) {
                                        echo esc_html($compare_left . ' vs ' . $compare_right);
                                    } else {
                                        the_title();
                                    }
                                    ?>
                                </strong>
                                <?php if ('' !== $compare_primary_question) : ?>
                                    <p><?php echo esc_html($compare_primary_question); ?></p>
                                <?php else : ?>
                                    <p><?php esc_html_e('Use this page to present objective criteria, migration implications, and a practical decision framework.', 'aura'); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div class="marketing-visual-panel">
                                <div class="marketing-visual-glow"></div>
                                <div class="marketing-visual-chip"><?php esc_html_e('Learn Resource', 'aura'); ?></div>
                                <strong><?php echo esc_html($archive_label); ?></strong>
                                <p><?php esc_html_e('Set a featured image to add a hero visual or architecture diagram for this entry.', 'aura'); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </section>

            <div class="marketing-content-layout">
                <div class="marketing-main-column">
                    <section class="marketing-section-card marketing-prose-card">
                        <div class="marketing-prose">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <?php if ('aura_pillar' === $post_type && (!empty($pillar_cluster_placeholders) || '' !== $pillar_visual_placeholder || '' !== $pillar_focus_label)) : ?>
                        <section class="marketing-section-card pillar-scaffold-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('Pillar Scaffold', 'aura'); ?></h2>
                                <p><?php esc_html_e('Authoring placeholders used to keep the full pillar cluster and visual system consistent while content production is in progress.', 'aura'); ?></p>
                            </div>

                            <?php if ('' !== $pillar_focus_label) : ?>
                                <p class="compare-primary-question">
                                    <?php
                                    if ($pillar_focus_step > 0) {
                                        echo esc_html(sprintf(__('Happy Path focus: Step %1$d - %2$s', 'aura'), $pillar_focus_step, $pillar_focus_label));
                                    } else {
                                        echo esc_html(sprintf(__('Happy Path focus: %s', 'aura'), $pillar_focus_label));
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>

                            <?php if ('' !== $pillar_visual_placeholder) : ?>
                                <article class="compare-decision-column">
                                    <h3><?php esc_html_e('Visual Placeholder', 'aura'); ?></h3>
                                    <p class="marketing-body-copy"><?php echo esc_html($pillar_visual_placeholder); ?></p>
                                </article>
                            <?php endif; ?>

                            <?php if (!empty($pillar_cluster_placeholders)) : ?>
                                <article class="compare-decision-column" style="margin-top: 1rem;">
                                    <h3><?php esc_html_e('Planned Cluster Articles', 'aura'); ?></h3>
                                    <ul class="compare-bullet-list">
                                        <?php foreach ($pillar_cluster_placeholders as $placeholder) : ?>
                                            <?php if ('' === trim((string) $placeholder)) { continue; } ?>
                                            <li><?php echo esc_html((string) $placeholder); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </article>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>

                    <?php if ('aura_pillar' === $post_type && !empty($pillar_faqs)) : ?>
                        <section class="marketing-section-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e('Frequently Asked Questions', 'aura'); ?></h2>
                                <p><?php esc_html_e('Use these implementation-focused answers to reinforce key controls in this pillar.', 'aura'); ?></p>
                            </div>

                            <div class="marketing-faq-list">
                                <?php foreach ($pillar_faqs as $faq) : ?>
                                    <?php
                                    $question = isset($faq['question']) ? trim((string) $faq['question']) : '';
                                    $answer = isset($faq['answer']) ? trim((string) $faq['answer']) : '';
                                    if ('' === $question || '' === $answer) {
                                        continue;
                                    }
                                    ?>
                                    <article class="marketing-faq-item">
                                        <h3><?php echo esc_html($question); ?></h3>
                                        <p><?php echo esc_html($answer); ?></p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if ('aura_compare' === $post_type) : ?>
                        <?php if ('' !== $compare_frame || '' !== $compare_primary_question || '' !== $compare_quick_verdict) : ?>
                            <section class="marketing-section-card compare-snapshot-card">
                                <div class="marketing-section-heading">
                                    <h2><?php esc_html_e('Evaluation Snapshot', 'aura'); ?></h2>
                                    <?php if ('' !== $compare_frame) : ?>
                                        <p><?php echo esc_html($compare_frame); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if ('' !== $compare_primary_question) : ?>
                                    <p class="compare-primary-question"><?php echo esc_html($compare_primary_question); ?></p>
                                <?php endif; ?>

                                <?php if ('' !== $compare_quick_verdict) : ?>
                                    <p class="marketing-body-copy"><?php echo esc_html($compare_quick_verdict); ?></p>
                                <?php endif; ?>
                            </section>
                        <?php endif; ?>

                        <?php if (!empty($compare_matrix)) : ?>
                            <section class="marketing-section-card compare-matrix-card">
                                <div class="marketing-section-heading">
                                    <h2><?php esc_html_e('Comparison Matrix', 'aura'); ?></h2>
                                    <p><?php esc_html_e('Evaluate each option across governance, security, execution, and operational fit.', 'aura'); ?></p>
                                </div>

                                <div class="compare-table-wrap">
                                    <table class="compare-table">
                                        <thead>
                                            <tr>
                                                <th><?php esc_html_e('Criterion', 'aura'); ?></th>
                                                <th><?php echo esc_html('' !== $compare_left ? $compare_left : __('Left', 'aura')); ?></th>
                                                <th><?php echo esc_html('' !== $compare_right ? $compare_right : __('Right', 'aura')); ?></th>
                                                <th><?php esc_html_e('Winner / Takeaway', 'aura'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($compare_matrix as $row) : ?>
                                                <?php
                                                $criterion = isset($row['criterion']) ? trim((string) $row['criterion']) : '';
                                                $left = isset($row['left']) ? trim((string) $row['left']) : '';
                                                $right = isset($row['right']) ? trim((string) $row['right']) : '';
                                                $winner = isset($row['winner']) ? trim((string) $row['winner']) : '';
                                                if ('' === $criterion && '' === $left && '' === $right && '' === $winner) {
                                                    continue;
                                                }
                                                ?>
                                                <tr>
                                                    <th><?php echo esc_html($criterion); ?></th>
                                                    <td><?php echo esc_html($left); ?></td>
                                                    <td><?php echo esc_html($right); ?></td>
                                                    <td><?php echo esc_html($winner); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php if (!empty($compare_recommended) || !empty($compare_not_ideal)) : ?>
                            <section class="marketing-section-card compare-decision-card">
                                <div class="marketing-section-heading">
                                    <h2><?php esc_html_e('Decision Framework', 'aura'); ?></h2>
                                    <p><?php esc_html_e('Use these criteria to match architecture choice with your team profile and execution constraints.', 'aura'); ?></p>
                                </div>

                                <div class="compare-decision-grid">
                                    <?php if (!empty($compare_recommended)) : ?>
                                        <article class="compare-decision-column">
                                            <h3><?php esc_html_e('Best Fit For', 'aura'); ?></h3>
                                            <ul class="compare-bullet-list">
                                                <?php foreach ($compare_recommended as $item) : ?>
                                                    <?php if ('' === trim((string) $item)) { continue; } ?>
                                                    <li><?php echo esc_html((string) $item); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </article>
                                    <?php endif; ?>

                                    <?php if (!empty($compare_not_ideal)) : ?>
                                        <article class="compare-decision-column">
                                            <h3><?php esc_html_e('Tradeoff Watchouts', 'aura'); ?></h3>
                                            <ul class="compare-bullet-list">
                                                <?php foreach ($compare_not_ideal as $item) : ?>
                                                    <?php if ('' === trim((string) $item)) { continue; } ?>
                                                    <li><?php echo esc_html((string) $item); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </article>
                                    <?php endif; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <?php if (!empty($compare_steps)) : ?>
                            <section class="marketing-section-card compare-steps-card">
                                <div class="marketing-section-heading">
                                    <h2><?php esc_html_e('Migration / Evaluation Path', 'aura'); ?></h2>
                                    <p><?php esc_html_e('A practical sequence to benchmark, validate, and transition without disrupting production.', 'aura'); ?></p>
                                </div>

                                <ol class="marketing-step-list">
                                    <?php foreach ($compare_steps as $index => $step) : ?>
                                        <?php
                                        $step_title = isset($step['title']) ? trim((string) $step['title']) : '';
                                        $step_text = isset($step['text']) ? trim((string) $step['text']) : '';
                                        if ('' === $step_title && '' === $step_text) {
                                            continue;
                                        }
                                        ?>
                                        <li class="marketing-step-item">
                                            <span class="marketing-step-number"><?php echo esc_html(sprintf('%02d', ((int) $index) + 1)); ?></span>
                                            <div>
                                                <?php if ('' !== $step_title) : ?>
                                                    <h3><?php echo esc_html($step_title); ?></h3>
                                                <?php endif; ?>
                                                <?php if ('' !== $step_text) : ?>
                                                    <p><?php echo esc_html($step_text); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ol>
                            </section>
                        <?php endif; ?>

                        <?php if (!empty($compare_faqs)) : ?>
                            <section class="marketing-section-card compare-faq-card">
                                <div class="marketing-section-heading">
                                    <h2><?php esc_html_e('Comparison FAQs', 'aura'); ?></h2>
                                    <p><?php esc_html_e('Answer-ready follow-up questions for human readers and AI retrieval systems.', 'aura'); ?></p>
                                </div>

                                <div class="marketing-faq-list">
                                    <?php foreach ($compare_faqs as $faq) : ?>
                                        <?php
                                        $question = isset($faq['question']) ? trim((string) $faq['question']) : '';
                                        $answer = isset($faq['answer']) ? trim((string) $faq['answer']) : '';
                                        if ('' === $question || '' === $answer) {
                                            continue;
                                        }
                                        ?>
                                        <article class="marketing-faq-item">
                                            <h3><?php echo esc_html($question); ?></h3>
                                            <p><?php echo esc_html($answer); ?></p>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <aside class="marketing-sidebar-column">
                    <section class="marketing-side-card">
                        <h2><?php esc_html_e('Publishing Details', 'aura'); ?></h2>
                        <p><?php echo esc_html(get_the_date('F j, Y', $post_id)); ?></p>
                        <p><?php echo esc_html(get_the_modified_date('F j, Y', $post_id)); ?></p>
                    </section>

                    <?php if ('aura_compare' === $post_type && ('' !== $compare_left || '' !== $compare_right || '' !== $compare_primary_question)) : ?>
                        <section class="marketing-side-card">
                            <h2><?php esc_html_e('Compared Entities', 'aura'); ?></h2>
                            <?php if ('' !== $compare_left) : ?>
                                <p><strong><?php esc_html_e('Left', 'aura'); ?>:</strong> <?php echo esc_html($compare_left); ?></p>
                            <?php endif; ?>
                            <?php if ('' !== $compare_right) : ?>
                                <p><strong><?php esc_html_e('Right', 'aura'); ?>:</strong> <?php echo esc_html($compare_right); ?></p>
                            <?php endif; ?>
                            <?php if ('' !== $compare_primary_question) : ?>
                                <p><?php echo esc_html($compare_primary_question); ?></p>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>

                    <?php if (!empty($related)) : ?>
                        <section class="marketing-side-card">
                            <h2><?php esc_html_e('Related Entries', 'aura'); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ($related as $item) : ?>
                                    <li><a href="<?php echo esc_url(get_permalink($item->ID)); ?>"><?php echo esc_html(get_the_title($item->ID)); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    <?php endwhile; ?>
</main>
