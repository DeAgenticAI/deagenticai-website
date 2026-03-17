<?php
/**
 * Marketing page helpers for structured content templates.
 *
 * @package Aura
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get supported marketing page templates.
 *
 * @return array<string,string>
 */
function aura_get_marketing_page_templates() {
    return array(
        'page-glossary.php' => 'glossary-hub',
        'page-glossary-entry.php' => 'glossary-entry',
        'page-solutions.php' => 'solutions-hub',
        'page-solution-entry.php' => 'solution-entry',
        'page-learn-faq.php' => 'learn-faq-hub',
        'page-learn-faq-topic.php' => 'learn-faq-topic',
    );
}

/**
 * Resolve the marketing template type for a page.
 *
 * @param int $page_id Page ID.
 * @return string
 */
function aura_get_marketing_page_type($page_id = 0) {
    $page_id = $page_id ? (int) $page_id : get_the_ID();
    if ($page_id <= 0) {
        return '';
    }

    $template = get_page_template_slug($page_id);
    $templates = aura_get_marketing_page_templates();

    return isset($templates[$template]) ? $templates[$template] : '';
}

/**
 * Get trimmed lead copy for a page.
 *
 * @param int $page_id Page ID.
 * @param int $fallback_words Fallback word count.
 * @return string
 */
function aura_get_marketing_page_excerpt($page_id, $fallback_words = 34) {
    $page_id = (int) $page_id;
    if ($page_id <= 0) {
        return '';
    }

    $excerpt = trim((string) get_post_field('post_excerpt', $page_id));
    if ('' !== $excerpt) {
        return $excerpt;
    }

    $content = wp_strip_all_tags((string) get_post_field('post_content', $page_id));
    return trim((string) wp_trim_words($content, max(12, (int) $fallback_words), '...'));
}

/**
 * Get normalized scalar page meta.
 *
 * @param int    $page_id Page ID.
 * @param string $meta_key Meta key.
 * @param string $default Default value.
 * @return string
 */
function aura_get_marketing_page_meta($page_id, $meta_key, $default = '') {
    $value = get_post_meta((int) $page_id, (string) $meta_key, true);
    if (!is_string($value)) {
        return (string) $default;
    }

    $value = trim($value);
    return '' !== $value ? $value : (string) $default;
}

/**
 * Get normalized repeater rows from page meta.
 *
 * @param int      $page_id Page ID.
 * @param string   $meta_key Meta key.
 * @param string[] $fields Expected field keys.
 * @return array<int,array<string,string>>
 */
function aura_get_marketing_page_rows($page_id, $meta_key, $fields) {
    $rows = get_post_meta((int) $page_id, (string) $meta_key, true);
    if (!is_array($rows)) {
        return array();
    }

    $normalized = array();
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $clean_row = array();
        $has_value = false;
        foreach ($fields as $field) {
            $value = isset($row[$field]) ? trim((string) $row[$field]) : '';
            if ('' !== $value) {
                $has_value = true;
            }
            $clean_row[$field] = $value;
        }

        if ($has_value) {
            $normalized[] = $clean_row;
        }
    }

    return $normalized;
}

/**
 * Get child pages for a marketing hub page.
 *
 * @param int    $page_id Parent page ID.
 * @param string $template Optional child template slug.
 * @return WP_Post[]
 */
function aura_get_marketing_child_pages($page_id, $template = '') {
    $args = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post_parent' => (int) $page_id,
        'orderby' => array(
            'menu_order' => 'ASC',
            'title' => 'ASC',
        ),
    );

    if ('' !== trim($template)) {
        $args['meta_query'] = array(
            array(
                'key' => '_wp_page_template',
                'value' => $template,
            ),
        );
    }

    return get_posts($args);
}

/**
 * Resolve internal and external links consistently.
 *
 * @param string $url Raw link.
 * @return string
 */
function aura_marketing_resolve_link($url) {
    $url = trim((string) $url);

    if ('' === $url) {
        return '#';
    }

    if (preg_match('/^(https?:|mailto:|tel:|#)/i', $url)) {
        return $url;
    }

    if ('/' !== substr($url, 0, 1)) {
        $url = '/' . $url;
    }

    return home_url($url);
}

/**
 * Get breadcrumb items for a hierarchical page.
 *
 * @param int $page_id Page ID.
 * @return array<int,array{label:string,url:string}>
 */
function aura_get_marketing_breadcrumb_items($page_id) {
    $page_id = (int) $page_id;
    if ($page_id <= 0) {
        return array();
    }

    $items = array(
        array(
            'label' => __('Home', 'aura'),
            'url' => home_url('/'),
        ),
    );

    $ancestors = array_reverse(get_post_ancestors($page_id));
    foreach ($ancestors as $ancestor_id) {
        $items[] = array(
            'label' => get_the_title($ancestor_id),
            'url' => get_permalink($ancestor_id),
        );
    }

    $items[] = array(
        'label' => get_the_title($page_id),
        'url' => get_permalink($page_id),
    );

    return $items;
}

/**
 * Build glossary template payload.
 *
 * @param int $page_id Page ID.
 * @return array<string,mixed>
 */
function aura_get_glossary_entry_payload($page_id = 0) {
    $page_id = $page_id ? (int) $page_id : get_the_ID();

    $default_why = __('This entity defines a core control-plane mechanism and should be linked from all related implementation and architecture pages.', 'aura');
    $default_how_steps = array(
        array(
            'title' => __('Define the execution boundary', 'aura'),
            'text' => __('Identify where this concept sits in the agent execution path and what decisions it governs.', 'aura'),
        ),
        array(
            'title' => __('Evaluate policy and risk implications', 'aura'),
            'text' => __('Map how this mechanism interacts with authorization, risk scoring, and escalation controls.', 'aura'),
        ),
        array(
            'title' => __('Enforce at signing or execution time', 'aura'),
            'text' => __('Explain how enforcement occurs at runtime and what is blocked when conditions are not met.', 'aura'),
        ),
        array(
            'title' => __('Record auditable outcomes', 'aura'),
            'text' => __('Capture approvals, denials, and context so teams can validate behavior under review.', 'aura'),
        ),
    );
    $default_related = array(
        array('label' => __('Agentic Control Plane', 'aura'), 'url' => home_url('/glossary/agentic-control-plane/')),
        array('label' => __('Intent Sanitization', 'aura'), 'url' => home_url('/glossary/intent-sanitization/')),
        array('label' => __('Policy DSL', 'aura'), 'url' => home_url('/glossary/policy-dsl/')),
    );
    $default_reading = array(
        array('label' => __('Autonomous AI Agents & On-Chain Capital', 'aura'), 'url' => home_url('/learn/pillars/autonomous-ai-agents-on-chain-capital/')),
        array('label' => __('Agentic Policy & Authority Enforcement', 'aura'), 'url' => home_url('/learn/pillars/agentic-policy-authority-enforcement/')),
    );
    $default_faqs = array(
        array(
            'question' => __('Why is this entity important for governed agent execution?', 'aura'),
            'answer' => __('It defines one of the control points that keeps autonomous behavior within explicit authority and risk limits.', 'aura'),
        ),
        array(
            'question' => __('How does this connect to policy and signing?', 'aura'),
            'answer' => __('This concept should map directly to policy evaluation and the final execution boundary where authorization is enforced.', 'aura'),
        ),
        array(
            'question' => __('Where should teams reference this term?', 'aura'),
            'answer' => __('Use it consistently in solution pages, compare pages, and technical guides so the internal knowledge graph remains coherent.', 'aura'),
        ),
    );

    $why_it_matters = aura_get_marketing_page_meta($page_id, '_aura_glossary_why_it_matters');
    $how_steps = aura_get_marketing_page_rows($page_id, '_aura_glossary_how_steps', array('title', 'text'));
    $related_entities = aura_get_marketing_page_rows($page_id, '_aura_glossary_related_entities', array('label', 'url'));
    $further_reading = aura_get_marketing_page_rows($page_id, '_aura_glossary_further_reading', array('label', 'url'));
    $faqs = aura_get_marketing_page_rows($page_id, '_aura_glossary_faqs', array('question', 'answer'));

    return array(
        'definition' => aura_get_marketing_page_excerpt($page_id, 28),
        'why_it_matters' => '' !== trim($why_it_matters) ? $why_it_matters : $default_why,
        'how_steps' => !empty($how_steps) ? $how_steps : $default_how_steps,
        'related_entities' => !empty($related_entities) ? $related_entities : $default_related,
        'further_reading' => !empty($further_reading) ? $further_reading : $default_reading,
        'faqs' => !empty($faqs) ? $faqs : $default_faqs,
    );
}

/**
 * Build solution template payload.
 *
 * @param int $page_id Page ID.
 * @return array<string,mixed>
 */
function aura_get_solution_entry_payload($page_id = 0) {
    $page_id = $page_id ? (int) $page_id : get_the_ID();

    $default_steps = array(
        array(
            'title' => __('Define authority boundaries', 'aura'),
            'text' => __('Map which agent actions are auto-approved, escalated, or blocked for this ICP context.', 'aura'),
        ),
        array(
            'title' => __('Apply policy and risk controls', 'aura'),
            'text' => __('Encode policy constraints and risk checks before any signing path is executed.', 'aura'),
        ),
        array(
            'title' => __('Integrate signing and observability', 'aura'),
            'text' => __('Wire execution to governed signing and monitor approvals, denials, and anomalies.', 'aura'),
        ),
        array(
            'title' => __('Validate and expand safely', 'aura'),
            'text' => __('Run staged rollout checks and expand coverage once audit and control outcomes are stable.', 'aura'),
        ),
    );
    $default_glossary_links = array(
        array('label' => __('Agentic Control Plane', 'aura'), 'url' => home_url('/glossary/agentic-control-plane/')),
        array('label' => __('Policy DSL', 'aura'), 'url' => home_url('/glossary/policy-dsl/')),
        array('label' => __('Intent-Evaluated MPC', 'aura'), 'url' => home_url('/glossary/intent-evaluated-mpc/')),
    );
    $default_resource_links = array(
        array('label' => __('Autonomous AI Agents & On-Chain Capital', 'aura'), 'url' => home_url('/learn/pillars/autonomous-ai-agents-on-chain-capital/')),
        array('label' => __('MPC, Security & Fast-Path Execution', 'aura'), 'url' => home_url('/learn/pillars/mpc-security-fast-path-execution/')),
    );
    $default_compare_links = array(
        array('label' => __('DeAgenticAI vs Privy', 'aura'), 'url' => home_url('/compare/deagentic-vs-privy/')),
        array('label' => __('MPC vs Multisig for AI Agents', 'aura'), 'url' => home_url('/compare/mpc-vs-multisig-for-ai-agents/')),
    );
    $default_faqs = array(
        array(
            'question' => __('How quickly can this solution pattern be piloted?', 'aura'),
            'answer' => __('Most teams start with one bounded workflow, validate controls and telemetry, and then expand to additional execution lanes.', 'aura'),
        ),
        array(
            'question' => __('What should remain human-governed?', 'aura'),
            'answer' => __('Strategic policy changes, emergency overrides, and exception approvals should remain under explicit human governance.', 'aura'),
        ),
        array(
            'question' => __('How does this page connect to compare evaluations?', 'aura'),
            'answer' => __('Use compare pages to validate tradeoffs before implementation and align architecture decisions with risk tolerance.', 'aura'),
        ),
        array(
            'question' => __('What evidence should be captured from day one?', 'aura'),
            'answer' => __('Capture policy decisions, blocked intents, signing outcomes, and escalation events for audit and operational tuning.', 'aura'),
        ),
    );

    $steps = aura_get_marketing_page_rows($page_id, '_aura_solution_steps', array('title', 'text'));
    $glossary_links = aura_get_marketing_page_rows($page_id, '_aura_solution_glossary_links', array('label', 'url'));
    $resource_links = aura_get_marketing_page_rows($page_id, '_aura_solution_resource_links', array('label', 'url'));
    $compare_links = aura_get_marketing_page_rows($page_id, '_aura_solution_compare_links', array('label', 'url'));
    $faqs = aura_get_marketing_page_rows($page_id, '_aura_solution_faqs', array('question', 'answer'));

    return array(
        'eyebrow' => aura_get_marketing_page_meta($page_id, '_aura_solution_eyebrow', __('Solution Page', 'aura')),
        'primary_cta_label' => aura_get_marketing_page_meta($page_id, '_aura_solution_primary_cta_label', __('Join Waitlist', 'aura')),
        'primary_cta_url' => aura_get_marketing_page_meta($page_id, '_aura_solution_primary_cta_url', '#waitlist'),
        'secondary_cta_label' => aura_get_marketing_page_meta($page_id, '_aura_solution_secondary_cta_label', __('View Ecosystem', 'aura')),
        'secondary_cta_url' => aura_get_marketing_page_meta($page_id, '_aura_solution_secondary_cta_url', home_url('/ecosystem')),
        'implementation_steps' => !empty($steps) ? $steps : $default_steps,
        'case_study_title' => aura_get_marketing_page_meta($page_id, '_aura_solution_case_study_title', __('Implementation Case Study', 'aura')),
        'case_study_body' => aura_get_marketing_page_meta($page_id, '_aura_solution_case_study_body', __('Operational outcomes, governance decisions, and measurable impact from a real-world deployment pattern.', 'aura')),
        'glossary_links' => !empty($glossary_links) ? $glossary_links : $default_glossary_links,
        'resource_links' => !empty($resource_links) ? $resource_links : $default_resource_links,
        'compare_links' => !empty($compare_links) ? $compare_links : $default_compare_links,
        'faqs' => !empty($faqs) ? $faqs : $default_faqs,
    );
}

/**
 * Build learn FAQ topic template payload.
 *
 * @param int $page_id Page ID.
 * @return array<string,mixed>
 */
function aura_get_learn_faq_topic_payload($page_id = 0) {
    $page_id = $page_id ? (int) $page_id : get_the_ID();

    $faqs = aura_get_marketing_page_rows($page_id, '_aura_learn_faq_rows', array('question', 'answer'));

    return array(
        'faqs' => $faqs,
    );
}

/**
 * Output shared marketing metabox admin assets.
 */
function aura_marketing_output_metabox_assets() {
    static $did_output = false;

    if ($did_output) {
        return;
    }

    $did_output = true;
    ?>
    <style>
        .aura-marketing-note {
            margin: 0 0 14px;
            color: #646970;
        }
        .aura-marketing-grid {
            display: grid;
            gap: 14px;
        }
        .aura-marketing-two-col {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .aura-marketing-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .aura-marketing-field input,
        .aura-marketing-field textarea {
            width: 100%;
        }
        .aura-marketing-repeater {
            border: 1px solid #dcdcde;
            border-radius: 6px;
            padding: 12px;
            background: #fff;
        }
        .aura-marketing-repeater + .aura-marketing-repeater {
            margin-top: 16px;
        }
        .aura-marketing-repeater-head {
            margin-bottom: 10px;
        }
        .aura-marketing-repeater-row {
            border: 1px solid #dcdcde;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            background: #f6f7f7;
        }
        .aura-marketing-repeater-row:last-child {
            margin-bottom: 0;
        }
        .aura-marketing-repeater-actions {
            display: flex;
            justify-content: flex-end;
        }
        @media (max-width: 782px) {
            .aura-marketing-two-col {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        (function() {
            if (window.auraMarketingRepeatersReady) {
                return;
            }

            window.auraMarketingRepeatersReady = true;

            document.addEventListener('click', function(event) {
                var addTarget = event.target.closest('[data-aura-add-row]');
                if (addTarget) {
                    var repeaterId = addTarget.getAttribute('data-aura-add-row');
                    var repeater = document.getElementById(repeaterId);
                    if (!repeater) {
                        return;
                    }

                    var template = repeater.querySelector('template');
                    var rows = repeater.querySelector('[data-aura-rows]');
                    if (!template || !rows) {
                        return;
                    }

                    rows.appendChild(template.content.cloneNode(true));
                    return;
                }

                var removeTarget = event.target.closest('[data-aura-remove-row]');
                if (!removeTarget) {
                    return;
                }

                var row = removeTarget.closest('.aura-marketing-repeater-row');
                var rowsWrap = removeTarget.closest('[data-aura-rows]');
                if (!row || !rowsWrap) {
                    return;
                }

                var rows = rowsWrap.querySelectorAll('.aura-marketing-repeater-row');
                if (rows.length === 1) {
                    row.querySelectorAll('input, textarea').forEach(function(field) {
                        field.value = '';
                    });
                    return;
                }

                row.remove();
            });
        })();
    </script>
    <?php
}

/**
 * Render a generic repeatable field group.
 *
 * @param string                               $id Group ID.
 * @param string                               $title Section title.
 * @param string                               $description Section description.
 * @param array<int,array<string,string>>      $rows Existing rows.
 * @param string                               $group_name Input group name.
 * @param array<int,array<string,string>>      $fields Field definitions.
 * @param string                               $add_label Add button label.
 */
function aura_marketing_render_repeater_group($id, $title, $description, $rows, $group_name, $fields, $add_label) {
    $rows = !empty($rows) ? $rows : array(array());
    ?>
    <div class="aura-marketing-repeater" id="<?php echo esc_attr($id); ?>">
        <div class="aura-marketing-repeater-head">
            <strong><?php echo esc_html($title); ?></strong>
            <?php if ('' !== trim($description)) : ?>
                <p class="aura-marketing-note"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>

        <div data-aura-rows>
            <?php foreach ($rows as $row) : ?>
                <?php aura_marketing_render_repeater_row($group_name, $fields, $row); ?>
            <?php endforeach; ?>
        </div>

        <template>
            <?php aura_marketing_render_repeater_row($group_name, $fields, array()); ?>
        </template>

        <p style="margin-top: 10px;">
            <button type="button" class="button" data-aura-add-row="<?php echo esc_attr($id); ?>">
                <?php echo esc_html($add_label); ?>
            </button>
        </p>
    </div>
    <?php
}

/**
 * Render a repeatable row.
 *
 * @param string                          $group_name Input group name.
 * @param array<int,array<string,string>> $fields Field definitions.
 * @param array<string,string>            $row Existing row.
 */
function aura_marketing_render_repeater_row($group_name, $fields, $row) {
    ?>
    <div class="aura-marketing-repeater-row">
        <div class="aura-marketing-grid">
            <?php foreach ($fields as $field) : ?>
                <?php
                $key = isset($field['key']) ? (string) $field['key'] : '';
                $label = isset($field['label']) ? (string) $field['label'] : '';
                $type = isset($field['type']) ? (string) $field['type'] : 'text';
                $placeholder = isset($field['placeholder']) ? (string) $field['placeholder'] : '';
                $value = isset($row[$key]) ? (string) $row[$key] : '';
                ?>
                <div class="aura-marketing-field">
                    <label><?php echo esc_html($label); ?></label>
                    <?php if ('textarea' === $type) : ?>
                        <textarea rows="3" name="<?php echo esc_attr($group_name); ?>[<?php echo esc_attr($key); ?>][]" placeholder="<?php echo esc_attr($placeholder); ?>"><?php echo esc_textarea($value); ?></textarea>
                    <?php else : ?>
                        <input type="<?php echo 'url' === $type ? 'url' : 'text'; ?>" name="<?php echo esc_attr($group_name); ?>[<?php echo esc_attr($key); ?>][]" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="aura-marketing-repeater-actions">
            <button type="button" class="button-link-delete" data-aura-remove-row>
                <?php esc_html_e('Remove', 'aura'); ?>
            </button>
        </div>
    </div>
    <?php
}

/**
 * Register page-template-specific metaboxes.
 *
 * @param WP_Post $post Current post.
 */
function aura_register_marketing_page_meta_boxes($post) {
    if (!$post instanceof WP_Post) {
        return;
    }

    $page_type = aura_get_marketing_page_type($post->ID);

    if ('glossary-entry' === $page_type) {
        add_meta_box(
            'aura_glossary_entry_fields',
            __('Glossary Entry Structure', 'aura'),
            'aura_render_glossary_entry_meta_box',
            'page',
            'normal',
            'high'
        );
    }

    if ('solution-entry' === $page_type) {
        add_meta_box(
            'aura_solution_entry_fields',
            __('Solution Page Structure', 'aura'),
            'aura_render_solution_entry_meta_box',
            'page',
            'normal',
            'high'
        );
    }

    if ('learn-faq-topic' === $page_type) {
        add_meta_box(
            'aura_learn_faq_topic_fields',
            __('Learn FAQ Topic Structure', 'aura'),
            'aura_render_learn_faq_topic_meta_box',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes_page', 'aura_register_marketing_page_meta_boxes');

/**
 * Render glossary entry metabox.
 *
 * @param WP_Post $post Current post.
 */
function aura_render_glossary_entry_meta_box($post) {
    aura_marketing_output_metabox_assets();
    wp_nonce_field('aura_save_glossary_entry_fields', 'aura_glossary_entry_nonce');

    $payload = aura_get_glossary_entry_payload($post->ID);
    ?>
    <p class="aura-marketing-note">
        <?php esc_html_e('Use the page excerpt for the one-sentence citable definition. Use the main editor for the two-paragraph explanation and technical context.', 'aura'); ?>
    </p>
    <p class="aura-marketing-note">
        <?php esc_html_e('For GEO/AEO, keep the excerpt concise and ensure the semantic triple appears early in the body copy when relevant.', 'aura'); ?>
    </p>

    <div class="aura-marketing-field" style="margin-bottom: 16px;">
        <label for="aura-glossary-why-it-matters"><?php esc_html_e('Why It Matters', 'aura'); ?></label>
        <textarea id="aura-glossary-why-it-matters" rows="4" name="aura_glossary_why_it_matters"><?php echo esc_textarea($payload['why_it_matters']); ?></textarea>
    </div>

    <?php
    aura_marketing_render_repeater_group(
        'aura-glossary-how-steps',
        __('How It Works', 'aura'),
        __('Add 4 to 6 mechanism steps for the glossary entry.', 'aura'),
        $payload['how_steps'],
        'aura_glossary_how_steps',
        array(
            array(
                'key' => 'title',
                'label' => __('Step Title', 'aura'),
                'type' => 'text',
                'placeholder' => __('Policy Evaluation', 'aura'),
            ),
            array(
                'key' => 'text',
                'label' => __('Step Description', 'aura'),
                'type' => 'textarea',
                'placeholder' => __('Explain what happens in this step.', 'aura'),
            ),
        ),
        __('Add Step', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-glossary-related-entities',
        __('Related Entities', 'aura'),
        __('Add at least three related glossary links mentioned on this page.', 'aura'),
        $payload['related_entities'],
        'aura_glossary_related_entities',
        array(
            array(
                'key' => 'label',
                'label' => __('Link Label', 'aura'),
                'type' => 'text',
                'placeholder' => __('Intent Sanitization', 'aura'),
            ),
            array(
                'key' => 'url',
                'label' => __('Link URL', 'aura'),
                'type' => 'url',
                'placeholder' => __('/glossary/intent-sanitization/', 'aura'),
            ),
        ),
        __('Add Related Entity', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-glossary-further-reading',
        __('Further Reading', 'aura'),
        __('Link to pillar pages, guides, or supporting resources.', 'aura'),
        $payload['further_reading'],
        'aura_glossary_further_reading',
        array(
            array(
                'key' => 'label',
                'label' => __('Resource Label', 'aura'),
                'type' => 'text',
                'placeholder' => __('Agentic Policy & Authority Enforcement', 'aura'),
            ),
            array(
                'key' => 'url',
                'label' => __('Resource URL', 'aura'),
                'type' => 'url',
                'placeholder' => __('/learn/pillars/agentic-policy-authority-enforcement/', 'aura'),
            ),
        ),
        __('Add Resource', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-glossary-faqs',
        __('FAQ Block', 'aura'),
        __('These questions are used for both the visible FAQ section and FAQPage schema.', 'aura'),
        $payload['faqs'],
        'aura_glossary_faqs',
        array(
            array(
                'key' => 'question',
                'label' => __('Question', 'aura'),
                'type' => 'text',
                'placeholder' => __('How is this different from traditional wallet policy?', 'aura'),
            ),
            array(
                'key' => 'answer',
                'label' => __('Answer', 'aura'),
                'type' => 'textarea',
                'placeholder' => __('Answer the follow-up question in 2 to 3 sentences.', 'aura'),
            ),
        ),
        __('Add FAQ', 'aura')
    );
}

/**
 * Render solution entry metabox.
 *
 * @param WP_Post $post Current post.
 */
function aura_render_solution_entry_meta_box($post) {
    aura_marketing_output_metabox_assets();
    wp_nonce_field('aura_save_solution_entry_fields', 'aura_solution_entry_nonce');

    $payload = aura_get_solution_entry_payload($post->ID);
    ?>
    <p class="aura-marketing-note">
        <?php esc_html_e('Use the page title as the pain-led H1. Use the excerpt for the opening summary and include the semantic triple within the first 150 words of the rendered page.', 'aura'); ?>
    </p>

    <div class="aura-marketing-grid" style="margin-bottom: 16px;">
        <div class="aura-marketing-two-col">
            <div class="aura-marketing-field">
                <label for="aura-solution-eyebrow"><?php esc_html_e('Eyebrow Label', 'aura'); ?></label>
                <input id="aura-solution-eyebrow" type="text" name="aura_solution_eyebrow" value="<?php echo esc_attr($payload['eyebrow']); ?>">
            </div>
            <div class="aura-marketing-field">
                <label for="aura-solution-primary-cta-label"><?php esc_html_e('Primary CTA Label', 'aura'); ?></label>
                <input id="aura-solution-primary-cta-label" type="text" name="aura_solution_primary_cta_label" value="<?php echo esc_attr($payload['primary_cta_label']); ?>">
            </div>
        </div>

        <div class="aura-marketing-two-col">
            <div class="aura-marketing-field">
                <label for="aura-solution-primary-cta-url"><?php esc_html_e('Primary CTA URL', 'aura'); ?></label>
                <input id="aura-solution-primary-cta-url" type="url" name="aura_solution_primary_cta_url" value="<?php echo esc_attr($payload['primary_cta_url']); ?>">
            </div>
            <div class="aura-marketing-field">
                <label for="aura-solution-secondary-cta-label"><?php esc_html_e('Secondary CTA Label', 'aura'); ?></label>
                <input id="aura-solution-secondary-cta-label" type="text" name="aura_solution_secondary_cta_label" value="<?php echo esc_attr($payload['secondary_cta_label']); ?>">
            </div>
        </div>

        <div class="aura-marketing-two-col">
            <div class="aura-marketing-field">
                <label for="aura-solution-secondary-cta-url"><?php esc_html_e('Secondary CTA URL', 'aura'); ?></label>
                <input id="aura-solution-secondary-cta-url" type="url" name="aura_solution_secondary_cta_url" value="<?php echo esc_attr($payload['secondary_cta_url']); ?>">
            </div>
            <div class="aura-marketing-field">
                <label for="aura-solution-case-title"><?php esc_html_e('Case Study / Simulation Title', 'aura'); ?></label>
                <input id="aura-solution-case-title" type="text" name="aura_solution_case_study_title" value="<?php echo esc_attr($payload['case_study_title']); ?>">
            </div>
        </div>

        <div class="aura-marketing-field">
            <label for="aura-solution-case-body"><?php esc_html_e('Case Study / Simulation Copy', 'aura'); ?></label>
            <textarea id="aura-solution-case-body" rows="4" name="aura_solution_case_study_body"><?php echo esc_textarea($payload['case_study_body']); ?></textarea>
        </div>
    </div>

    <?php
    aura_marketing_render_repeater_group(
        'aura-solution-steps',
        __('Implementation Steps', 'aura'),
        __('These steps feed the visible implementation block and HowTo schema.', 'aura'),
        $payload['implementation_steps'],
        'aura_solution_steps',
        array(
            array(
                'key' => 'title',
                'label' => __('Step Title', 'aura'),
                'type' => 'text',
                'placeholder' => __('Create a governed wallet profile', 'aura'),
            ),
            array(
                'key' => 'text',
                'label' => __('Step Description', 'aura'),
                'type' => 'textarea',
                'placeholder' => __('Explain what the reader needs to do in this step.', 'aura'),
            ),
        ),
        __('Add Step', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-solution-glossary-links',
        __('Glossary Links', 'aura'),
        __('Link the key terms mentioned on this solution page.', 'aura'),
        $payload['glossary_links'],
        'aura_solution_glossary_links',
        array(
            array(
                'key' => 'label',
                'label' => __('Glossary Term', 'aura'),
                'type' => 'text',
                'placeholder' => __('Policy DSL', 'aura'),
            ),
            array(
                'key' => 'url',
                'label' => __('Term URL', 'aura'),
                'type' => 'url',
                'placeholder' => __('/glossary/policy-dsl/', 'aura'),
            ),
        ),
        __('Add Glossary Link', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-solution-resource-links',
        __('Related Resources', 'aura'),
        __('Link to pillar pages, guides, or docs relevant to this audience.', 'aura'),
        $payload['resource_links'],
        'aura_solution_resource_links',
        array(
            array(
                'key' => 'label',
                'label' => __('Resource Label', 'aura'),
                'type' => 'text',
                'placeholder' => __('Autonomous AI Agents & On-Chain Capital', 'aura'),
            ),
            array(
                'key' => 'url',
                'label' => __('Resource URL', 'aura'),
                'type' => 'url',
                'placeholder' => __('/learn/pillars/autonomous-ai-agents-on-chain-capital/', 'aura'),
            ),
        ),
        __('Add Resource', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-solution-compare-links',
        __('Comparison Links', 'aura'),
        __('Link to evaluation and comparison pages for this buyer profile.', 'aura'),
        $payload['compare_links'],
        'aura_solution_compare_links',
        array(
            array(
                'key' => 'label',
                'label' => __('Comparison Label', 'aura'),
                'type' => 'text',
                'placeholder' => __('DeAgenticAI vs Turnkey', 'aura'),
            ),
            array(
                'key' => 'url',
                'label' => __('Comparison URL', 'aura'),
                'type' => 'url',
                'placeholder' => __('/compare/deagentic-vs-turnkey/', 'aura'),
            ),
        ),
        __('Add Comparison Link', 'aura')
    );

    aura_marketing_render_repeater_group(
        'aura-solution-faqs',
        __('FAQ Block', 'aura'),
        __('These questions are used for both the visible FAQ section and FAQPage schema.', 'aura'),
        $payload['faqs'],
        'aura_solution_faqs',
        array(
            array(
                'key' => 'question',
                'label' => __('Question', 'aura'),
                'type' => 'text',
                'placeholder' => __('How quickly can we integrate this workflow?', 'aura'),
            ),
            array(
                'key' => 'answer',
                'label' => __('Answer', 'aura'),
                'type' => 'textarea',
                'placeholder' => __('Provide a concise operational answer.', 'aura'),
            ),
        ),
        __('Add FAQ', 'aura')
    );
}

/**
 * Render learn FAQ topic metabox.
 *
 * @param WP_Post $post Current post.
 */
function aura_render_learn_faq_topic_meta_box($post) {
    aura_marketing_output_metabox_assets();
    wp_nonce_field('aura_save_learn_faq_topic_fields', 'aura_learn_faq_topic_nonce');

    $payload = aura_get_learn_faq_topic_payload($post->ID);
    ?>
    <p class="aura-marketing-note">
        <?php esc_html_e('Use this template for /learn/faq/[topic]/ pages. Keep answers concise and implementation-oriented so FAQPage schema remains high quality for answer engines.', 'aura'); ?>
    </p>

    <?php
    aura_marketing_render_repeater_group(
        'aura-learn-faq-rows',
        __('FAQ Entries', 'aura'),
        __('These rows power both the visible FAQ list and FAQPage schema for this topic page.', 'aura'),
        isset($payload['faqs']) && is_array($payload['faqs']) ? $payload['faqs'] : array(),
        'aura_learn_faq_rows',
        array(
            array(
                'key' => 'question',
                'label' => __('Question', 'aura'),
                'type' => 'text',
                'placeholder' => __('How do policy limits affect agent execution speed?', 'aura'),
            ),
            array(
                'key' => 'answer',
                'label' => __('Answer', 'aura'),
                'type' => 'textarea',
                'placeholder' => __('Provide a concise answer in 2 to 3 sentences.', 'aura'),
            ),
        ),
        __('Add FAQ', 'aura')
    );
}

/**
 * Sanitize a scalar value by field type.
 *
 * @param mixed  $value Raw value.
 * @param string $type Field type.
 * @return string
 */
function aura_marketing_sanitize_field_value($value, $type) {
    $value = is_scalar($value) ? (string) $value : '';
    $value = wp_unslash($value);

    if ('url' === $type) {
        $value = trim($value);
        return '' !== $value ? esc_url_raw($value) : '';
    }

    if ('textarea' === $type) {
        return sanitize_textarea_field($value);
    }

    return sanitize_text_field($value);
}

/**
 * Collect sanitized repeater rows from POST data.
 *
 * @param mixed                        $group Posted group data.
 * @param array<string,string>         $field_types Field type map.
 * @return array<int,array<string,string>>
 */
function aura_marketing_collect_repeater_rows($group, $field_types) {
    if (!is_array($group) || empty($field_types)) {
        return array();
    }

    $row_total = 0;
    foreach ($field_types as $field_key => $field_type) {
        $field_values = isset($group[$field_key]) && is_array($group[$field_key]) ? $group[$field_key] : array();
        $row_total = max($row_total, count($field_values));
    }

    $rows = array();
    for ($index = 0; $index < $row_total; $index++) {
        $row = array();
        $has_value = false;

        foreach ($field_types as $field_key => $field_type) {
            $field_values = isset($group[$field_key]) && is_array($group[$field_key]) ? $group[$field_key] : array();
            $raw_value = isset($field_values[$index]) ? $field_values[$index] : '';
            $value = aura_marketing_sanitize_field_value($raw_value, $field_type);
            if ('' !== $value) {
                $has_value = true;
            }
            $row[$field_key] = $value;
        }

        if ($has_value) {
            $rows[] = $row;
        }
    }

    return $rows;
}

/**
 * Save or delete page meta based on value.
 *
 * @param int         $post_id Page ID.
 * @param string      $meta_key Meta key.
 * @param string|array $value Sanitized value.
 */
function aura_marketing_update_meta($post_id, $meta_key, $value) {
    if (is_array($value)) {
        if (!empty($value)) {
            update_post_meta((int) $post_id, (string) $meta_key, $value);
            return;
        }

        delete_post_meta((int) $post_id, (string) $meta_key);
        return;
    }

    $value = trim((string) $value);
    if ('' !== $value) {
        update_post_meta((int) $post_id, (string) $meta_key, $value);
        return;
    }

    delete_post_meta((int) $post_id, (string) $meta_key);
}

/**
 * Save marketing page metabox values.
 *
 * @param int $post_id Page ID.
 */
function aura_save_marketing_page_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $page_type = aura_get_marketing_page_type($post_id);

    if ('glossary-entry' === $page_type && isset($_POST['aura_glossary_entry_nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['aura_glossary_entry_nonce']));
        if (!wp_verify_nonce($nonce, 'aura_save_glossary_entry_fields')) {
            return;
        }

        $why_it_matters = isset($_POST['aura_glossary_why_it_matters']) ? aura_marketing_sanitize_field_value($_POST['aura_glossary_why_it_matters'], 'textarea') : '';
        $how_steps = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_glossary_how_steps']) ? $_POST['aura_glossary_how_steps'] : array(),
            array(
                'title' => 'text',
                'text' => 'textarea',
            )
        );
        $related_entities = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_glossary_related_entities']) ? $_POST['aura_glossary_related_entities'] : array(),
            array(
                'label' => 'text',
                'url' => 'url',
            )
        );
        $further_reading = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_glossary_further_reading']) ? $_POST['aura_glossary_further_reading'] : array(),
            array(
                'label' => 'text',
                'url' => 'url',
            )
        );
        $faqs = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_glossary_faqs']) ? $_POST['aura_glossary_faqs'] : array(),
            array(
                'question' => 'text',
                'answer' => 'textarea',
            )
        );

        aura_marketing_update_meta($post_id, '_aura_glossary_why_it_matters', $why_it_matters);
        aura_marketing_update_meta($post_id, '_aura_glossary_how_steps', $how_steps);
        aura_marketing_update_meta($post_id, '_aura_glossary_related_entities', $related_entities);
        aura_marketing_update_meta($post_id, '_aura_glossary_further_reading', $further_reading);
        aura_marketing_update_meta($post_id, '_aura_glossary_faqs', $faqs);

        return;
    }

    if ('solution-entry' === $page_type && isset($_POST['aura_solution_entry_nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['aura_solution_entry_nonce']));
        if (!wp_verify_nonce($nonce, 'aura_save_solution_entry_fields')) {
            return;
        }

        $scalars = array(
            '_aura_solution_eyebrow' => array('field' => 'aura_solution_eyebrow', 'type' => 'text'),
            '_aura_solution_primary_cta_label' => array('field' => 'aura_solution_primary_cta_label', 'type' => 'text'),
            '_aura_solution_primary_cta_url' => array('field' => 'aura_solution_primary_cta_url', 'type' => 'url'),
            '_aura_solution_secondary_cta_label' => array('field' => 'aura_solution_secondary_cta_label', 'type' => 'text'),
            '_aura_solution_secondary_cta_url' => array('field' => 'aura_solution_secondary_cta_url', 'type' => 'url'),
            '_aura_solution_case_study_title' => array('field' => 'aura_solution_case_study_title', 'type' => 'text'),
            '_aura_solution_case_study_body' => array('field' => 'aura_solution_case_study_body', 'type' => 'textarea'),
        );

        foreach ($scalars as $meta_key => $config) {
            $field_name = $config['field'];
            $value = isset($_POST[$field_name]) ? aura_marketing_sanitize_field_value($_POST[$field_name], $config['type']) : '';
            aura_marketing_update_meta($post_id, $meta_key, $value);
        }

        $steps = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_solution_steps']) ? $_POST['aura_solution_steps'] : array(),
            array(
                'title' => 'text',
                'text' => 'textarea',
            )
        );
        $glossary_links = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_solution_glossary_links']) ? $_POST['aura_solution_glossary_links'] : array(),
            array(
                'label' => 'text',
                'url' => 'url',
            )
        );
        $resource_links = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_solution_resource_links']) ? $_POST['aura_solution_resource_links'] : array(),
            array(
                'label' => 'text',
                'url' => 'url',
            )
        );
        $compare_links = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_solution_compare_links']) ? $_POST['aura_solution_compare_links'] : array(),
            array(
                'label' => 'text',
                'url' => 'url',
            )
        );
        $faqs = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_solution_faqs']) ? $_POST['aura_solution_faqs'] : array(),
            array(
                'question' => 'text',
                'answer' => 'textarea',
            )
        );

        aura_marketing_update_meta($post_id, '_aura_solution_steps', $steps);
        aura_marketing_update_meta($post_id, '_aura_solution_glossary_links', $glossary_links);
        aura_marketing_update_meta($post_id, '_aura_solution_resource_links', $resource_links);
        aura_marketing_update_meta($post_id, '_aura_solution_compare_links', $compare_links);
        aura_marketing_update_meta($post_id, '_aura_solution_faqs', $faqs);

        return;
    }

    if ('learn-faq-topic' === $page_type && isset($_POST['aura_learn_faq_topic_nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['aura_learn_faq_topic_nonce']));
        if (!wp_verify_nonce($nonce, 'aura_save_learn_faq_topic_fields')) {
            return;
        }

        $faqs = aura_marketing_collect_repeater_rows(
            isset($_POST['aura_learn_faq_rows']) ? $_POST['aura_learn_faq_rows'] : array(),
            array(
                'question' => 'text',
                'answer' => 'textarea',
            )
        );

        aura_marketing_update_meta($post_id, '_aura_learn_faq_rows', $faqs);
    }
}
add_action('save_post_page', 'aura_save_marketing_page_meta');

/**
 * Build breadcrumb JSON-LD structure.
 *
 * @param int $page_id Page ID.
 * @return array<string,mixed>
 */
function aura_get_marketing_breadcrumb_schema($page_id) {
    $breadcrumbs = aura_get_marketing_breadcrumb_items($page_id);
    $item_list = array();

    foreach ($breadcrumbs as $index => $item) {
        $item_list[] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['label'],
            'item' => $item['url'],
        );
    }

    return array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $item_list,
    );
}

/**
 * Output structured data for marketing page templates.
 */
function aura_output_marketing_page_schema() {
    if (!aura_should_output_theme_seo_tags() || !is_page()) {
        return;
    }

    $page_id = (int) get_queried_object_id();
    if ($page_id <= 0) {
        return;
    }

    $page_type = aura_get_marketing_page_type($page_id);
    if ('' === $page_type) {
        return;
    }

    $schemas = array(
        aura_get_marketing_breadcrumb_schema($page_id),
    );

    if ('glossary-hub' === $page_type) {
        $entries = aura_get_marketing_child_pages($page_id, 'page-glossary-entry.php');
        if (empty($entries)) {
            $entries = aura_get_marketing_child_pages($page_id);
        }

        $defined_terms = array();
        foreach ($entries as $entry) {
            $entry_id = (int) $entry->ID;
            if ($entry_id <= 0) {
                continue;
            }

            $term = array(
                '@type' => 'DefinedTerm',
                'name' => get_the_title($entry_id),
                'url' => get_permalink($entry_id),
            );

            $term_description = aura_get_marketing_page_excerpt($entry_id, 28);
            if ('' !== $term_description) {
                $term['description'] = aura_normalize_meta_text($term_description, 240);
            }

            $defined_terms[] = $term;
        }

        $defined_term_set = array(
            '@context' => 'https://schema.org',
            '@type' => 'DefinedTermSet',
            'name' => get_the_title($page_id),
            'url' => get_permalink($page_id),
        );

        $hub_excerpt = aura_get_marketing_page_excerpt($page_id, 42);
        if ('' !== $hub_excerpt) {
            $defined_term_set['description'] = aura_normalize_meta_text($hub_excerpt, 240);
        }

        if (!empty($defined_terms)) {
            $defined_term_set['hasDefinedTerm'] = $defined_terms;
        }

        $schemas[] = $defined_term_set;
    }

    if ('solutions-hub' === $page_type) {
        $entries = aura_get_marketing_child_pages($page_id, 'page-solution-entry.php');
        if (empty($entries)) {
            $entries = aura_get_marketing_child_pages($page_id);
        }

        $item_list_element = array();
        foreach ($entries as $index => $entry) {
            $entry_id = (int) $entry->ID;
            if ($entry_id <= 0) {
                continue;
            }

            $item_list_element[] = array(
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => get_the_title($entry_id),
                'url' => get_permalink($entry_id),
            );
        }

        $collection_page = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => get_the_title($page_id),
            'url' => get_permalink($page_id),
        );

        $hub_excerpt = aura_get_marketing_page_excerpt($page_id, 44);
        if ('' !== $hub_excerpt) {
            $collection_page['description'] = aura_normalize_meta_text($hub_excerpt, 240);
        }

        if (!empty($item_list_element)) {
            $collection_page['mainEntity'] = array(
                '@type' => 'ItemList',
                'itemListElement' => $item_list_element,
            );
        }

        $schemas[] = $collection_page;
    }

    if ('glossary-entry' === $page_type) {
        $payload = aura_get_glossary_entry_payload($page_id);
        $defined_term = array(
            '@context' => 'https://schema.org',
            '@type' => 'DefinedTerm',
            'name' => get_the_title($page_id),
            'description' => aura_normalize_meta_text($payload['definition'], 320),
            'url' => get_permalink($page_id),
            'inDefinedTermSet' => array(
                '@type' => 'DefinedTermSet',
                'name' => __('DeAgenticAI Glossary', 'aura'),
                'url' => home_url('/glossary/'),
            ),
        );
        $schemas[] = $defined_term;

        if (!empty($payload['faqs'])) {
            $faq_entities = array();
            foreach ($payload['faqs'] as $faq) {
                $faq_entities[] = array(
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ),
                );
            }

            if (!empty($faq_entities)) {
                $schemas[] = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faq_entities,
                );
            }
        }
    }

    if ('solution-entry' === $page_type) {
        $payload = aura_get_solution_entry_payload($page_id);
        if (!empty($payload['implementation_steps'])) {
            $steps = array();
            foreach ($payload['implementation_steps'] as $step) {
                $steps[] = array(
                    '@type' => 'HowToStep',
                    'name' => $step['title'],
                    'text' => $step['text'],
                );
            }

            if (!empty($steps)) {
                $how_to = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'HowTo',
                    'name' => get_the_title($page_id),
                    'description' => aura_normalize_meta_text(aura_get_marketing_page_excerpt($page_id, 40), 220),
                    'step' => $steps,
                    'url' => get_permalink($page_id),
                );

                $featured_image = get_the_post_thumbnail_url($page_id, 'full');
                if ($featured_image) {
                    $how_to['image'] = $featured_image;
                }

                $schemas[] = $how_to;
            }
        }

        if (!empty($payload['faqs'])) {
            $faq_entities = array();
            foreach ($payload['faqs'] as $faq) {
                $faq_entities[] = array(
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ),
                );
            }

            if (!empty($faq_entities)) {
                $schemas[] = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faq_entities,
                );
            }
        }
    }

    if ('learn-faq-topic' === $page_type) {
        $payload = aura_get_learn_faq_topic_payload($page_id);
        if (!empty($payload['faqs'])) {
            $faq_entities = array();
            foreach ($payload['faqs'] as $faq) {
                $faq_entities[] = array(
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ),
                );
            }

            if (!empty($faq_entities)) {
                $schemas[] = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faq_entities,
                );
            }
        }
    }

    foreach ($schemas as $schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'aura_output_marketing_page_schema', 6);

/**
 * Get default child pages to seed for marketing hubs.
 *
 * @return array<string,array<int,array<string,string>>>
 */
function aura_get_marketing_seed_pages() {
    return array(
        'glossary' => array(
            array(
                'slug' => 'agentic-control-plane',
                'title' => 'Agentic Control Plane',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "DeAgenticAI's Agentic Control Plane is a multi-layer infrastructure architecture that enforces cryptographic policy over autonomous AI agent authority -- separating what an agent can do from what it is authorized to do -- in Web3 and enterprise financial environments.",
                'content' => "<p>Expand the definition with two short explanatory paragraphs focused on mechanism, architecture, and why this term matters to the reader's use case.</p>\n<p>Keep named concepts explicit and citable. Add internal links to related glossary terms and the most relevant pillar pages.</p>",
            ),
            array(
                'slug' => 'intent-sanitization',
                'title' => 'Intent Sanitization',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "Intent Sanitization is DeAgenticAI's pre-execution pipeline that validates AI agent proposals against structural, semantic, and contextual integrity checks -- defending against prompt injection before any intent reaches the policy or signing layer.",
                'content' => "<p>Explain the pre-execution validation path, what gets checked, and how sanitization protects downstream policy and signing systems.</p>\n<p>Use this space for two concise paragraphs before relying on the structured sections below.</p>",
            ),
            array(
                'slug' => 'policy-dsl',
                'title' => 'Policy DSL',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "DeAgenticAI's Policy DSL is a declarative domain-specific language for defining AI agent governance rules -- including spending limits, protocol allowlists, risk thresholds, and escalation paths -- enforced cryptographically at signing time.",
                'content' => "<p>Describe how the policy language is authored, versioned, and enforced within the control plane.</p>\n<p>Clarify the difference between application-layer guidance and cryptographic enforcement at the signing boundary.</p>",
            ),
            array(
                'slug' => 'fast-path-execution',
                'title' => 'Fast-Path Execution',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "Fast-Path Execution is DeAgenticAI's policy-governed optimization that delivers sub-100ms signing for pre-authorized, low-risk agent transactions using session-key credentials -- bypassing the full MPC ceremony while remaining within the authorized policy context.",
                'content' => "<p>Explain when Fast-Path is used, how bounded credentials work, and what remains cryptographically enforced during optimization.</p>\n<p>Include the performance context and the conditions under which requests fall back to the full approval path.</p>",
            ),
            array(
                'slug' => 'hardware-hybrid-custody',
                'title' => 'Hardware-Hybrid Custody',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "Hardware-Hybrid Custody is DeAgenticAI's institutional security model in which one MPC key share is stored on a physical hardware device while remaining shares are distributed across the MPC node network -- ensuring no cloud-based attack can unilaterally move funds.",
                'content' => "<p>Explain why one hardware-held share changes the institution-level security model and how it interacts with distributed MPC shares.</p>\n<p>Clarify who controls the device-held share and how operational approvals are coordinated.</p>",
            ),
            array(
                'slug' => 'inheritance-protocol',
                'title' => 'Inheritance Protocol',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "DeAgenticAI's Inheritance Protocol is a time-locked key share release mechanism that provides institutional continuity and crypto-native estate planning, triggering controlled custody transfer to designated beneficiaries if a check-in period elapses without owner confirmation.",
                'content' => "<p>Explain the time-lock, notification, grace period, and controlled release model with enough detail for a technical and operational audience.</p>\n<p>Connect the term to business continuity and key-person-risk mitigation.</p>",
            ),
            array(
                'slug' => 'kya-know-your-agent',
                'title' => 'Know Your Agent (KYA)',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "Know Your Agent (KYA) is the identity and credentialing framework through which DeAgenticAI establishes verifiable, on-chain identities for autonomous AI agents anchored to W3C Decentralized Identifiers and A2A Agent Card verification.",
                'content' => "<p>Explain how verifiable agent identity works, what trust signals matter, and how KYA maps to on-chain operations.</p>\n<p>Use concrete identity components rather than abstract marketing language.</p>",
            ),
            array(
                'slug' => 'intent-evaluated-mpc',
                'title' => 'Intent-Evaluated MPC',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "Intent-Evaluated MPC is DeAgenticAI's core signing primitive in which MPC nodes independently verify the policy authorization hash before contributing partial signatures -- adding a cryptographic verification layer independent of the orchestrator.",
                'content' => "<p>Explain how intent evaluation is performed at the node level and why that matters for reducing orchestrator trust.</p>\n<p>Connect the term directly to policy enforcement and signing guarantees.</p>",
            ),
            array(
                'slug' => 'x402-machine-to-machine-payments',
                'title' => 'x402 Machine-to-Machine Payments',
                'template' => 'page-glossary-entry.php',
                'excerpt' => "x402 is a machine-to-machine payment protocol natively integrated into DeAgenticAI's Chain Abstraction layer, enabling agents to pay for APIs, compute, and data feeds autonomously while remaining subject to the full policy and fraud-detection enforcement stack.",
                'content' => "<p>Explain where x402 fits into the transaction flow and how payment authorization stays bounded by policy and fraud controls.</p>\n<p>Connect the term to AI infrastructure and agent-to-agent commerce use cases.</p>",
            ),
        ),
        'solutions' => array(
            array(
                'slug' => 'dao-treasury',
                'title' => 'DAO Treasury Automation Without Surrendering Governance Control',
                'template' => 'page-solution-entry.php',
                'excerpt' => 'Automate DAO treasury operations under explicit policy guardrails while preserving governance override, audit visibility, and member trust in every agent-initiated action.',
                'content' => "<p>Lead with the governance-latency problem, explain why existing treasury workflows are too slow, and show how policy-bounded automation changes the operating model.</p>\n<p>Keep the semantic triple within the first 150 words of the rendered page and connect the page to the glossary terms it depends on.</p>",
            ),
            array(
                'slug' => 'ai-infrastructure',
                'title' => 'Add a Governed On-Chain Wallet to Any AI Agent Framework in 3 API Calls',
                'template' => 'page-solution-entry.php',
                'excerpt' => 'Add governed on-chain execution to AI agent frameworks without becoming a blockchain team or building key management, signing policy, and compliance controls from scratch.',
                'content' => "<p>Frame this page for AI infrastructure engineers who need zero-knowledge blockchain integration and want a fast path to their first governed transaction.</p>\n<p>Use practical implementation language and connect the workflow to machine-to-machine payments, Fast-Path execution, and the Agentic Control Plane.</p>",
            ),
            array(
                'slug' => 'web3-startups',
                'title' => 'Ship Your AI Agent Product Without Building Key Management From Scratch',
                'template' => 'page-solution-entry.php',
                'excerpt' => 'Launch AI-native Web3 products faster with policy-enforced wallet infrastructure, MPC-backed execution, and a developer-first path that removes custom custody overhead.',
                'content' => "<p>Lead with speed-to-market, platform risk, and why startup teams should not build custody primitives internally unless they need to.</p>\n<p>Connect the page to glossary terms, implementation steps, and the most relevant comparison pages.</p>",
            ),
            array(
                'slug' => 'enterprise',
                'title' => 'Institutional-Grade AI Agent Governance With MiCA-Formatted Audit Trails',
                'template' => 'page-solution-entry.php',
                'excerpt' => 'Give enterprise teams cryptographic AI agent governance, MiCA-aligned auditability, and institutional controls without rebuilding Web3 infrastructure from first principles.',
                'content' => "<p>Translate the system into enterprise control language, including oversight, audit trails, governance boundaries, and integration with existing security and identity models.</p>\n<p>Use concrete operational and compliance framing, not generic Web3 product language.</p>",
            ),
            array(
                'slug' => 'rwa-management',
                'title' => 'Policy-Governed AI Agents for Tokenized RWA Portfolio Operations',
                'template' => 'page-solution-entry.php',
                'excerpt' => 'Operate tokenized RWA portfolios with AI agents that remain bound by policy, audit, continuity controls, and institutional compliance requirements.',
                'content' => "<p>Lead with portfolio operations, policy boundaries, and how institutional teams can use AI agents without weakening control or regulatory posture.</p>\n<p>Connect the page to compliance, Hardware-Hybrid Custody, and Inheritance Protocol concepts.</p>",
            ),
            array(
                'slug' => 'compliance-mica-dora',
                'title' => 'MiCA, DORA, and Travel Rule Compliance Infrastructure for Autonomous Agents',
                'template' => 'page-solution-entry.php',
                'excerpt' => 'Design autonomous agent infrastructure around explicit compliance controls, audit traceability, and human-governed escalation paths for regulated environments.',
                'content' => "<p>Frame the page around what regulated teams actually need: explicit control surfaces, audit proof, escalation paths, and operational resilience.</p>\n<p>Support the page with glossary links, implementation steps, and future case study or simulation evidence.</p>",
            ),
        ),
    );
}

/**
 * Normalize page body content for scaffold comparisons.
 *
 * @param string $content Raw content.
 * @return string
 */
function aura_normalize_marketing_seed_content($content) {
    $plain_text = wp_strip_all_tags((string) $content);
    $plain_text = preg_replace('/\s+/', ' ', $plain_text);
    return is_string($plain_text) ? trim($plain_text) : '';
}

/**
 * Detect whether body content is unchanged editorial scaffold content.
 *
 * @param string $content Raw post content.
 * @return bool
 */
function aura_is_marketing_seed_stub_content($content) {
    $normalized = aura_normalize_marketing_seed_content($content);
    if ('' === $normalized) {
        return false;
    }

    $seed_pages = aura_get_marketing_seed_pages();
    foreach ($seed_pages as $group) {
        if (!is_array($group)) {
            continue;
        }

        foreach ($group as $seed_page) {
            if (!is_array($seed_page) || empty($seed_page['content'])) {
                continue;
            }

            if ($normalized === aura_normalize_marketing_seed_content((string) $seed_page['content'])) {
                return true;
            }
        }
    }

    $legacy_stubs = array(
        'Use this hub for topic-specific FAQ pages under /learn/faq/. Keep each topic page focused, concrete, and schema-ready.',
        'Replace this sample with production FAQ topics. Keep answers concise and implementation-specific.',
    );

    foreach ($legacy_stubs as $stub) {
        if ($normalized === aura_normalize_marketing_seed_content($stub)) {
            return true;
        }
    }

    return false;
}

/**
 * Seed default child pages for a marketing hub.
 *
 * @param int    $parent_id Parent page ID.
 * @param string $hub_key Hub key.
 */
function aura_seed_marketing_child_pages($parent_id, $hub_key) {
    $parent_id = (int) $parent_id;
    $hub_key = sanitize_key((string) $hub_key);
    if ($parent_id <= 0 || '' === $hub_key) {
        return;
    }

    $all_seed_pages = aura_get_marketing_seed_pages();
    if (empty($all_seed_pages[$hub_key]) || !is_array($all_seed_pages[$hub_key])) {
        return;
    }

    $parent = get_post($parent_id);
    if (!$parent instanceof WP_Post || 'page' !== $parent->post_type) {
        return;
    }

    // Canonical /solutions/* pages must be live so legacy redirects do not resolve to 404.
    $seed_post_status = ('solutions' === $hub_key) ? 'publish' : 'draft';

    foreach ($all_seed_pages[$hub_key] as $seed_page) {
        $slug = isset($seed_page['slug']) ? sanitize_title($seed_page['slug']) : '';
        $title = isset($seed_page['title']) ? (string) $seed_page['title'] : '';
        $template = isset($seed_page['template']) ? (string) $seed_page['template'] : '';

        if ('' === $slug || '' === $title || '' === $template) {
            continue;
        }

        $existing_page = get_page_by_path($parent->post_name . '/' . $slug);
        if (!$existing_page) {
            $page_id = wp_insert_post(
                array(
                    'post_title' => $title,
                    'post_name' => $slug,
                    'post_type' => 'page',
                    'post_status' => $seed_post_status,
                    'post_parent' => $parent_id,
                    'post_excerpt' => isset($seed_page['excerpt']) ? (string) $seed_page['excerpt'] : '',
                    'post_content' => isset($seed_page['content']) ? (string) $seed_page['content'] : '',
                )
            );

            if ($page_id) {
                update_post_meta((int) $page_id, '_wp_page_template', $template);
            }

            continue;
        }

        $existing_id = (int) $existing_page->ID;
        $current_template = (string) get_post_meta($existing_id, '_wp_page_template', true);
        if ($current_template !== $template) {
            update_post_meta($existing_id, '_wp_page_template', $template);
        }

        if ((int) $existing_page->post_parent !== $parent_id) {
            wp_update_post(
                array(
                    'ID' => $existing_id,
                    'post_parent' => $parent_id,
                )
            );
        }

        if ('solutions' === $hub_key && 'publish' !== (string) $existing_page->post_status) {
            wp_update_post(
                array(
                    'ID' => $existing_id,
                    'post_status' => 'publish',
                )
            );
        }

        if ('' === trim((string) $existing_page->post_excerpt) && !empty($seed_page['excerpt'])) {
            wp_update_post(
                array(
                    'ID' => $existing_id,
                    'post_excerpt' => (string) $seed_page['excerpt'],
                )
            );
        }

        if ('' === trim((string) $existing_page->post_content) && !empty($seed_page['content'])) {
            wp_update_post(
                array(
                    'ID' => $existing_id,
                    'post_content' => (string) $seed_page['content'],
                )
            );
        }
    }
}

/**
 * Seed /learn/faq structure with a hub and one draft topic sample.
 *
 * @param int $learn_page_id Learn hub page ID.
 */
function aura_seed_learn_faq_pages($learn_page_id) {
    $learn_page_id = (int) $learn_page_id;
    if ($learn_page_id <= 0) {
        return;
    }

    $learn_page = get_post($learn_page_id);
    if (!$learn_page instanceof WP_Post || 'page' !== $learn_page->post_type) {
        return;
    }

    $faq_slug = trim((string) $learn_page->post_name) . '/faq';
    $faq_page = get_page_by_path($faq_slug);

    if (!$faq_page) {
        $faq_page_id = wp_insert_post(array(
            'post_title' => __('FAQ', 'aura'),
            'post_name' => 'faq',
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_parent' => $learn_page_id,
            'post_excerpt' => __('Answer-engine oriented implementation FAQs for governed agent infrastructure topics.', 'aura'),
            'post_content' => '<p>' . esc_html__('Topic-specific implementation FAQs for governed agent infrastructure, organized for clear retrieval and decision support.', 'aura') . '</p>',
        ));

        if ($faq_page_id) {
            update_post_meta((int) $faq_page_id, '_wp_page_template', 'page-learn-faq.php');
            $faq_page = get_post((int) $faq_page_id);
        }
    } else {
        $faq_page_id = (int) $faq_page->ID;
        $current_template = (string) get_post_meta($faq_page_id, '_wp_page_template', true);
        if ('page-learn-faq.php' !== $current_template) {
            update_post_meta($faq_page_id, '_wp_page_template', 'page-learn-faq.php');
        }
    }

    if (!$faq_page instanceof WP_Post) {
        return;
    }

    $sample_slug = trim((string) $learn_page->post_name) . '/faq/agent-wallet-governance';
    $sample_page = get_page_by_path($sample_slug);

    if (!$sample_page) {
        $sample_page_id = wp_insert_post(array(
            'post_title' => __('Agent Wallet Governance FAQ (Sample)', 'aura'),
            'post_name' => 'agent-wallet-governance',
            'post_type' => 'page',
            'post_status' => 'draft',
            'post_parent' => (int) $faq_page->ID,
            'post_excerpt' => __('Sample FAQ topic page for /learn/faq/ structure validation.', 'aura'),
            'post_content' => '<p>' . esc_html__('Replace this sample with production FAQ topics. Keep answers concise and implementation-specific.', 'aura') . '</p>',
        ));

        if ($sample_page_id) {
            update_post_meta((int) $sample_page_id, '_wp_page_template', 'page-learn-faq-topic.php');
            update_post_meta((int) $sample_page_id, '_aura_learn_faq_rows', array(
                array(
                    'question' => __('What belongs on a /learn/faq/topic page?', 'aura'),
                    'answer' => __('Use concise, implementation-focused answers tied to one technical topic and keep the structure suitable for FAQPage schema.', 'aura'),
                ),
                array(
                    'question' => __('How long should answers be?', 'aura'),
                    'answer' => __('Aim for 2 to 3 sentence answers that resolve the question directly before adding optional context.', 'aura'),
                ),
                array(
                    'question' => __('Can these pages link back to glossary and pillars?', 'aura'),
                    'answer' => __('Yes. Topic FAQ pages should connect to glossary entities, pillar pages, and relevant comparison surfaces to preserve internal graph coherence.', 'aura'),
                ),
            ));
        }
    } else {
        $sample_page_id = (int) $sample_page->ID;
        $current_template = (string) get_post_meta($sample_page_id, '_wp_page_template', true);
        if ('page-learn-faq-topic.php' !== $current_template) {
            update_post_meta($sample_page_id, '_wp_page_template', 'page-learn-faq-topic.php');
        }
    }
}