<?php
/**
 * Ecosystem item master template part.
 *
 * Expects current global post to be a page.
 *
 * @package Aura
 */

$item = aura_get_ecosystem_item_payload(get_the_ID());

$mode = isset($item['mode']) ? (string) $item['mode'] : 'coming_soon';
$name = isset($item['name']) ? (string) $item['name'] : __('Ecosystem Component', 'aura');
$badge_text = isset($item['badge_text']) ? (string) $item['badge_text'] : __('Ecosystem Component', 'aura');
$one_sentence = isset($item['one_sentence_explanation']) ? (string) $item['one_sentence_explanation'] : '';
$definition = isset($item['definition']) ? (string) $item['definition'] : '';
$coming_soon_text = isset($item['coming_soon_text']) ? (string) $item['coming_soon_text'] : __('This ecosystem component is currently under development. Detailed architectural specifications will be published soon.', 'aura');

$why_cards = isset($item['why_exists']) && is_array($item['why_exists']) ? $item['why_exists'] : array();
$how_it_works = isset($item['how_it_works']) && is_array($item['how_it_works']) ? $item['how_it_works'] : array();
$how_steps = isset($how_it_works['steps']) && is_array($how_it_works['steps']) ? $how_it_works['steps'] : array();
$how_explanation = isset($how_it_works['explanation']) ? (string) $how_it_works['explanation'] : '';

$architecture = isset($item['architecture']) && is_array($item['architecture']) ? $item['architecture'] : array();
$core_components = isset($architecture['components']) && is_array($architecture['components']) ? $architecture['components'] : array();
$trust_model = isset($architecture['trust_model']) && is_array($architecture['trust_model']) ? $architecture['trust_model'] : array();

$security_properties = isset($item['security_properties']) && is_array($item['security_properties']) ? $item['security_properties'] : array();
$comparison_rows = isset($item['comparison']) && is_array($item['comparison']) ? $item['comparison'] : array();
$use_cases = isset($item['use_cases']) && is_array($item['use_cases']) ? $item['use_cases'] : array();

$integration = isset($item['integration']) && is_array($item['integration']) ? $item['integration'] : array();
$integration_model = isset($integration['model']) ? (string) $integration['model'] : '';
$integration_compatibility = isset($integration['compatibility']) && is_array($integration['compatibility']) ? $integration['compatibility'] : array();
$integration_capabilities = isset($integration['capabilities']) && is_array($integration['capabilities']) ? $integration['capabilities'] : array();

$related_content = isset($item['related_content']) && is_array($item['related_content']) ? $item['related_content'] : array();
$related_heading = isset($related_content['heading']) ? (string) $related_content['heading'] : __('Related Guides & Research', 'aura');
$related_description = isset($related_content['description']) ? (string) $related_content['description'] : '';
$related_guides = isset($related_content['guides']) && is_array($related_content['guides']) ? $related_content['guides'] : array();
$related_comparisons = isset($related_content['comparisons']) && is_array($related_content['comparisons']) ? $related_content['comparisons'] : array();
$related_glossary = isset($related_content['glossary']) && is_array($related_content['glossary']) ? $related_content['glossary'] : array();

$faq_items = isset($item['faq']) && is_array($item['faq']) ? $item['faq'] : array();

$ecosystem_context = isset($item['ecosystem_context']) && is_array($item['ecosystem_context']) ? $item['ecosystem_context'] : array();
$context_title = isset($ecosystem_context['title']) ? (string) $ecosystem_context['title'] : __('Part of the DeAgenticAI Ecosystem', 'aura');
$context_description = isset($ecosystem_context['description']) ? (string) $ecosystem_context['description'] : '';
$context_links = isset($ecosystem_context['links']) && is_array($ecosystem_context['links']) ? $ecosystem_context['links'] : array();

$topic_slug = isset($item['topic']) ? sanitize_title((string) $item['topic']) : '';
$related_posts = aura_get_ecosystem_item_related_posts(get_the_ID(), $topic_slug, 3);
$has_related_posts = !empty($related_posts);

$faq_schema = array();
if (!empty($faq_items)) {
    $faq_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array(),
    );

    foreach ($faq_items as $faq_item) {
        if (!is_array($faq_item)) {
            continue;
        }

        $faq_question = isset($faq_item['q']) ? trim((string) $faq_item['q']) : '';
        $faq_answer = isset($faq_item['a']) ? trim((string) $faq_item['a']) : '';

        if ('' === $faq_question || '' === $faq_answer) {
            continue;
        }

        $faq_schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq_question,
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq_answer,
            ),
        );
    }
}

$logo_uri = get_template_directory_uri() . '/images/logo.png';
$icon_allowed = array(
    'svg' => array(
        'viewBox' => true,
        'fill' => true,
        'stroke' => true,
        'stroke-width' => true,
        'stroke-linecap' => true,
        'stroke-linejoin' => true,
    ),
    'path' => array('d' => true),
    'circle' => array('cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true),
    'rect' => array('x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true),
    'polygon' => array('points' => true),
    'line' => array('x1' => true, 'x2' => true, 'y1' => true, 'y2' => true),
    'polyline' => array('points' => true),
);

$why_icon_map = array(
    'shield' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path></svg>',
    'zap' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
    'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 8v4"></path><circle cx="12" cy="16" r="0.8" fill="currentColor" stroke="none"></circle></svg>',
    'lock' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
);

$capability_icon_map = array(
    'code' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.2-3.2a6 6 0 0 1-7.6 7.6l-5.9 5.9a1 1 0 1 1-1.4-1.4l5.9-5.9a6 6 0 0 1 7.6-7.6z"></path></svg>',
    'zap' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
    'cloud' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 17.6a4 4 0 0 0-1-7.9 5 5 0 0 0-9.7-1.5A4 4 0 0 0 6 17.6"></path><path d="M8 17h8"></path></svg>',
    'building' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path><path d="M9 9h6"></path></svg>',
);
?>

<?php if ('live' === $mode && !empty($faq_schema['mainEntity'])) : ?>
    <script type="application/ld+json">
    <?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
<?php endif; ?>

<main id="primary" class="site-main <?php echo 'live' === $mode ? 'eco-service-page' : 'eco-coming-page'; ?>">
    <div class="eco-shell">
        <?php if ('coming_soon' === $mode) : ?>
            <div class="eco-coming-card reveal-scale" style="transition-delay: 0.05s;">
                <h1><?php echo esc_html($name); ?></h1>
                <p><?php echo esc_html($coming_soon_text); ?></p>
                <a href="<?php echo esc_url(home_url('/ecosystem')); ?>" class="eco-btn eco-btn-primary"><?php esc_html_e('Return to Ecosystem', 'aura'); ?></a>
            </div>
        <?php else : ?>
            <section class="eco-section eco-hero">
                <div class="eco-hero-inner reveal-up" style="transition-delay: 0.05s;">
                    <div class="eco-badge">
                        <span class="eco-badge-dot"></span>
                        <span class="eco-badge-text"><?php echo esc_html($badge_text); ?></span>
                    </div>

                    <h1 class="eco-title"><?php echo esc_html($name); ?> <span><em><?php esc_html_e('for Agents', 'aura'); ?></em></span></h1>
                    <?php if ('' !== $one_sentence) : ?>
                        <p class="eco-subtitle"><?php echo esc_html($one_sentence); ?></p>
                    <?php endif; ?>

                    <?php if ('' !== $definition) : ?>
                        <div class="eco-lead-card">
                            <p><?php echo esc_html($definition); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="eco-btn-row">
                        <a href="#architecture" class="eco-btn eco-btn-primary">
                            <?php esc_html_e('View Architecture', 'aura'); ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        </a>
                        <a href="#integration" class="eco-btn eco-btn-outline">
                            <?php esc_html_e('Request Access', 'aura'); ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </section>

            <?php if (!empty($why_cards)) : ?>
                <section class="eco-section">
                    <h2 class="eco-h2 reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html(sprintf(__('Why %s Is Necessary', 'aura'), $name)); ?></h2>
                    <div class="eco-grid-2">
                        <?php foreach ($why_cards as $index => $card) : ?>
                            <?php
                            if (!is_array($card)) {
                                continue;
                            }
                            $card_icon_key = isset($card['icon']) ? (string) $card['icon'] : 'shield';
                            $card_icon_svg = isset($why_icon_map[$card_icon_key]) ? $why_icon_map[$card_icon_key] : $why_icon_map['shield'];
                            ?>
                            <article class="eco-card eco-panel reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.08 + ($index * 0.05)), 2)); ?>s;">
                                <div class="eco-point">
                                    <span class="eco-point-icon"><?php echo wp_kses($card_icon_svg, $icon_allowed); ?></span>
                                    <p>
                                        <strong><?php echo esc_html(isset($card['label']) ? (string) $card['label'] : ''); ?></strong>
                                        <?php echo esc_html(isset($card['content']) ? (string) $card['content'] : ''); ?>
                                    </p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($how_steps) || '' !== $how_explanation) : ?>
                <section class="eco-section reveal-up" style="transition-delay: 0.08s;">
                    <div class="eco-unity">
                        <h2 class="eco-h2 center"><?php esc_html_e('How It Works', 'aura'); ?></h2>

                        <?php if (!empty($how_steps)) : ?>
                            <div class="eco-timeline">
                                <?php foreach ($how_steps as $index => $step) : ?>
                                    <?php if (!is_array($step)) { continue; } ?>
                                    <article class="eco-timeline-step">
                                        <span class="eco-step-dot"><?php echo esc_html((string) ($index + 1)); ?></span>
                                        <h4><?php echo esc_html(isset($step['step']) ? (string) $step['step'] : ''); ?></h4>
                                        <p><?php echo esc_html(isset($step['title']) ? (string) $step['title'] : ''); ?></p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ('' !== $how_explanation) : ?>
                            <p class="eco-center" style="margin-top: 2rem; max-width: 880px; margin-left: auto; margin-right: auto; color: rgba(255,255,255,0.64);">
                                <?php echo esc_html($how_explanation); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section id="architecture" class="eco-section eco-service-arch">
                <div class="reveal-up" style="transition-delay: 0.07s;">
                    <h2 class="eco-h2"><?php esc_html_e('Architecture Overview', 'aura'); ?></h2>

                    <?php if (!empty($core_components)) : ?>
                        <div class="eco-block-card">
                            <h4><?php esc_html_e('Core Components', 'aura'); ?></h4>
                            <ul class="eco-list-rows">
                                <?php foreach ($core_components as $component) : ?>
                                    <li>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 0.95rem; height: 0.95rem; color: var(--primary);"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                                        <span><?php echo esc_html((string) $component); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($trust_model)) : ?>
                        <div class="eco-block-card" style="margin-top: 1rem;">
                            <h4><?php esc_html_e('Trust Model', 'aura'); ?></h4>
                            <ul class="eco-list-rows">
                                <?php foreach ($trust_model as $trust_item) : ?>
                                    <li><span class="dot"></span><span><?php echo esc_html((string) $trust_item); ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="eco-logo-visual reveal-scale" style="transition-delay: 0.12s;">
                    <div class="eco-grid-overlay"></div>
                    <img src="<?php echo esc_url($logo_uri); ?>" alt="DeAgenticAI Logo" loading="lazy" decoding="async" />
                </div>
            </section>

            <?php if (!empty($security_properties)) : ?>
                <section class="eco-section">
                    <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php esc_html_e('Security Properties', 'aura'); ?></h2>
                    <div class="eco-grid-4">
                        <?php foreach ($security_properties as $index => $property) : ?>
                            <article class="eco-card eco-panel eco-center reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.08 + ($index * 0.05)), 2)); ?>s;">
                                <div class="eco-point-icon" style="margin: 0 auto 0.8rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path><path d="m9 12 2 2 4-4"></path></svg>
                                </div>
                                <p style="color: #fff; line-height: 1.6; font-size: 0.9rem;"><?php echo esc_html((string) $property); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($comparison_rows)) : ?>
                <section class="eco-section">
                    <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php esc_html_e('The Agentic Advantage', 'aura'); ?></h2>
                    <div class="eco-table-wrap reveal-up" style="transition-delay: 0.12s;">
                        <table class="eco-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Feature', 'aura'); ?></th>
                                    <th><?php esc_html_e('Traditional Model', 'aura'); ?></th>
                                    <th><?php echo esc_html($name); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comparison_rows as $row) : ?>
                                    <?php if (!is_array($row)) { continue; } ?>
                                    <tr>
                                        <td><?php echo esc_html(isset($row['feature']) ? (string) $row['feature'] : ''); ?></td>
                                        <td><?php echo esc_html(isset($row['traditional']) ? (string) $row['traditional'] : ''); ?></td>
                                        <td><?php echo esc_html(isset($row['service']) ? (string) $row['service'] : ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($use_cases)) : ?>
                <section class="eco-section">
                    <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php esc_html_e('Primary Use Cases', 'aura'); ?></h2>
                    <div class="eco-grid-2">
                        <?php foreach ($use_cases as $index => $use_case) : ?>
                            <?php if (!is_array($use_case)) { continue; } ?>
                            <article class="eco-component-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.08 + ($index * 0.06)), 2)); ?>s;">
                                <h3><?php echo esc_html(isset($use_case['name']) ? (string) $use_case['name'] : ''); ?></h3>
                                <div>
                                    <h4 style="font-family: var(--font-mono); font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.13em; color: rgba(255,255,255,0.35); margin-bottom: 0.3rem;"><?php esc_html_e('Problem', 'aura'); ?></h4>
                                    <p style="margin-bottom: 0.8rem; color: rgba(255,255,255,0.62); font-size: 0.84rem;"><?php echo esc_html(isset($use_case['problem']) ? (string) $use_case['problem'] : ''); ?></p>

                                    <h4 style="font-family: var(--font-mono); font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.13em; color: rgba(255,255,255,0.35); margin-bottom: 0.3rem;"><?php esc_html_e('Solution', 'aura'); ?></h4>
                                    <p style="margin-bottom: 1rem; color: rgba(56,189,248,0.8); font-size: 0.84rem;"><?php echo esc_html(isset($use_case['solution']) ? (string) $use_case['solution'] : ''); ?></p>

                                    <p style="padding-top: 0.8rem; border-top: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.82); font-size: 0.82rem;"><em><?php echo esc_html__('Outcome:', 'aura') . ' ' . esc_html(isset($use_case['outcome']) ? (string) $use_case['outcome'] : ''); ?></em></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ('' !== $integration_model || !empty($integration_compatibility) || !empty($integration_capabilities)) : ?>
                <section id="integration" class="eco-section eco-unity reveal-up" style="transition-delay: 0.08s;">
                    <div class="eco-integration-grid">
                        <div>
                            <h2 class="eco-h2"><?php esc_html_e('Integration Model', 'aura'); ?></h2>
                            <?php if ('' !== $integration_model) : ?>
                                <p><?php echo esc_html($integration_model); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($integration_compatibility)) : ?>
                                <div class="eco-chip-list">
                                    <?php foreach ($integration_compatibility as $chain) : ?>
                                        <span class="eco-chip"><?php echo esc_html((string) $chain); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($integration_capabilities)) : ?>
                            <div class="eco-cap-grid">
                                <?php foreach ($integration_capabilities as $capability) : ?>
                                    <?php
                                    if (!is_array($capability)) {
                                        continue;
                                    }
                                    $cap_icon_key = isset($capability['icon']) ? (string) $capability['icon'] : 'code';
                                    $cap_icon_svg = isset($capability_icon_map[$cap_icon_key]) ? $capability_icon_map[$cap_icon_key] : $capability_icon_map['code'];
                                    ?>
                                    <article class="eco-cap-card">
                                        <?php echo wp_kses($cap_icon_svg, $icon_allowed); ?>
                                        <span><?php echo esc_html(isset($capability['label']) ? (string) $capability['label'] : ''); ?></span>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($related_guides) || !empty($related_comparisons) || !empty($related_glossary) || $has_related_posts) : ?>
                <section class="eco-section">
                    <h2 class="eco-h2 reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html($related_heading); ?></h2>
                    <?php if ('' !== $related_description) : ?>
                        <p class="reveal-up" style="transition-delay: 0.08s; color: rgba(255,255,255,0.6); margin-bottom: 2rem; font-weight: 300;"><?php echo esc_html($related_description); ?></p>
                    <?php endif; ?>

                    <div class="eco-related-grid <?php echo $has_related_posts ? 'eco-related-grid-4' : ''; ?>">
                        <?php if (!empty($related_guides)) : ?>
                            <div class="eco-related-col reveal-up" style="transition-delay: 0.1s;">
                                <h4>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    <?php esc_html_e('Technical Guides', 'aura'); ?>
                                </h4>
                                <?php foreach ($related_guides as $guide) : ?>
                                    <?php if (!is_array($guide)) { continue; } ?>
                                    <a class="eco-guide-card" href="<?php echo esc_url(aura_resolve_content_link(isset($guide['link']) ? (string) $guide['link'] : '')); ?>">
                                        <h5><?php echo esc_html(isset($guide['title']) ? (string) $guide['title'] : ''); ?></h5>
                                        <p><?php echo esc_html(isset($guide['summary']) ? (string) $guide['summary'] : ''); ?></p>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($related_comparisons)) : ?>
                            <div class="eco-related-col reveal-up" style="transition-delay: 0.14s;">
                                <h4>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                                    <?php esc_html_e('Comparisons', 'aura'); ?>
                                </h4>
                                <?php foreach ($related_comparisons as $comparison_link) : ?>
                                    <?php if (!is_array($comparison_link)) { continue; } ?>
                                    <a class="eco-compare-link" href="<?php echo esc_url(aura_resolve_content_link(isset($comparison_link['link']) ? (string) $comparison_link['link'] : '')); ?>"><?php echo esc_html(isset($comparison_link['title']) ? (string) $comparison_link['title'] : ''); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($related_glossary)) : ?>
                            <div class="eco-related-col reveal-up" style="transition-delay: 0.18s;">
                                <h4>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16"></path><path d="M14 2v6h6"></path><path d="M20 8v14a2 2 0 0 1-2 2H6"></path></svg>
                                    <?php esc_html_e('Glossary', 'aura'); ?>
                                </h4>
                                <div class="eco-glossary-wrap">
                                    <?php foreach ($related_glossary as $glossary_item) : ?>
                                        <?php if (!is_array($glossary_item)) { continue; } ?>
                                        <a class="eco-pill" href="<?php echo esc_url(aura_resolve_content_link(isset($glossary_item['link']) ? (string) $glossary_item['link'] : '')); ?>"><?php echo esc_html(isset($glossary_item['term']) ? (string) $glossary_item['term'] : ''); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($has_related_posts) : ?>
                            <div class="eco-related-col eco-related-posts reveal-up" style="transition-delay: 0.22s;">
                                <h4>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16"></path><path d="M4 12h16"></path><path d="M4 18h16"></path></svg>
                                    <?php esc_html_e('Journal Posts', 'aura'); ?>
                                </h4>

                                <?php foreach ($related_posts as $related_post) : ?>
                                    <?php
                                    $related_post_id = (int) $related_post->ID;
                                    $related_post_categories = get_the_category($related_post_id);
                                    $related_post_category = !empty($related_post_categories) ? $related_post_categories[0]->name : __('Journal', 'aura');
                                    ?>
                                    <a class="eco-post-card" href="<?php echo esc_url(get_permalink($related_post_id)); ?>">
                                        <span class="eco-post-card-meta"><?php echo esc_html($related_post_category); ?></span>
                                        <h5><?php echo esc_html(get_the_title($related_post_id)); ?></h5>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($faq_items)) : ?>
                <section class="eco-section eco-faq-wrap">
                    <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php esc_html_e('Frequently Asked Questions', 'aura'); ?></h2>
                    <?php foreach ($faq_items as $index => $faq_item) : ?>
                        <?php
                        if (!is_array($faq_item)) {
                            continue;
                        }
                        $faq_q = isset($faq_item['q']) ? (string) $faq_item['q'] : '';
                        $faq_a = isset($faq_item['a']) ? (string) $faq_item['a'] : '';
                        if ('' === $faq_q || '' === $faq_a) {
                            continue;
                        }
                        ?>
                        <article class="eco-faq-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.1 + ($index * 0.05)), 2)); ?>s;">
                            <h3><?php echo esc_html($faq_q); ?></h3>
                            <p><?php echo esc_html($faq_a); ?></p>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

            <?php if ('' !== $context_description || !empty($context_links)) : ?>
                <section class="eco-context reveal-up" style="transition-delay: 0.08s;">
                    <h2 class="eco-h2 center" style="margin-bottom: 1rem;"><?php echo esc_html($context_title); ?></h2>
                    <?php if ('' !== $context_description) : ?>
                        <p><?php echo esc_html($context_description); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($context_links)) : ?>
                        <div class="eco-btn-row">
                            <?php foreach ($context_links as $context_link) : ?>
                                <?php if (!is_array($context_link)) { continue; } ?>
                                <a class="eco-pill" href="<?php echo esc_url(aura_resolve_content_link(isset($context_link['link']) ? (string) $context_link['link'] : '')); ?>"><?php echo esc_html(isset($context_link['name']) ? (string) $context_link['name'] : ''); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
