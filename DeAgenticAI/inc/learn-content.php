<?php
/**
 * Learn and compare content model.
 *
 * @package Aura
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get learn content post type slugs.
 *
 * @return string[]
 */
function aura_get_learn_post_types() {
    return array(
        'aura_pillar',
        'aura_guide',
        'aura_research',
        'aura_update',
        'aura_compare',
        'aura_dataset',
    );
}

/**
 * Register learn and compare post types/taxonomies.
 */
function aura_register_learn_content_model() {
    $supports = array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions');

    register_post_type('aura_pillar', array(
        'labels' => array(
            'name' => __('Pillars', 'aura'),
            'singular_name' => __('Pillar', 'aura'),
            'add_new_item' => __('Add New Pillar', 'aura'),
            'edit_item' => __('Edit Pillar', 'aura'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => $supports,
        'rewrite' => array(
            'slug' => 'learn/pillars',
            'with_front' => false,
        ),
        'has_archive' => 'learn/pillars',
        'menu_icon' => 'dashicons-welcome-learn-more',
        'menu_position' => 21,
    ));

    register_post_type('aura_guide', array(
        'labels' => array(
            'name' => __('Guides', 'aura'),
            'singular_name' => __('Guide', 'aura'),
            'add_new_item' => __('Add New Guide', 'aura'),
            'edit_item' => __('Edit Guide', 'aura'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => $supports,
        'rewrite' => array(
            'slug' => 'learn/guides',
            'with_front' => false,
        ),
        'has_archive' => 'learn/guides',
        'menu_icon' => 'dashicons-media-text',
        'menu_position' => 22,
    ));

    register_post_type('aura_research', array(
        'labels' => array(
            'name' => __('Research', 'aura'),
            'singular_name' => __('Research', 'aura'),
            'add_new_item' => __('Add New Research', 'aura'),
            'edit_item' => __('Edit Research', 'aura'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => $supports,
        'rewrite' => array(
            'slug' => 'learn/research',
            'with_front' => false,
        ),
        'has_archive' => 'learn/research',
        'menu_icon' => 'dashicons-chart-area',
        'menu_position' => 23,
    ));

    register_post_type('aura_update', array(
        'labels' => array(
            'name' => __('Updates', 'aura'),
            'singular_name' => __('Update', 'aura'),
            'add_new_item' => __('Add New Update', 'aura'),
            'edit_item' => __('Edit Update', 'aura'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => $supports,
        'rewrite' => array(
            'slug' => 'learn/updates',
            'with_front' => false,
        ),
        'has_archive' => 'learn/updates',
        'menu_icon' => 'dashicons-megaphone',
        'menu_position' => 24,
    ));

    register_post_type('aura_compare', array(
        'labels' => array(
            'name' => __('Compare Pages', 'aura'),
            'singular_name' => __('Compare Page', 'aura'),
            'add_new_item' => __('Add New Compare Page', 'aura'),
            'edit_item' => __('Edit Compare Page', 'aura'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => $supports,
        'rewrite' => array(
            'slug' => 'compare',
            'with_front' => false,
        ),
        'has_archive' => 'compare',
        'menu_icon' => 'dashicons-chart-bar',
        'menu_position' => 25,
    ));

    register_post_type('aura_dataset', array(
        'labels' => array(
            'name' => __('Datasets', 'aura'),
            'singular_name' => __('Dataset', 'aura'),
            'add_new_item' => __('Add New Dataset', 'aura'),
            'edit_item' => __('Edit Dataset', 'aura'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => $supports,
        'rewrite' => array(
            'slug' => 'learn/datasets',
            'with_front' => false,
        ),
        'has_archive' => 'learn/datasets',
        'menu_icon' => 'dashicons-database',
        'menu_position' => 26,
    ));

    register_taxonomy('aura_learn_topic', array('aura_pillar', 'aura_guide', 'aura_research', 'aura_update', 'aura_compare'), array(
        'labels' => array(
            'name' => __('Learn Topics', 'aura'),
            'singular_name' => __('Learn Topic', 'aura'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'learn/topic',
            'with_front' => false,
        ),
    ));

    register_taxonomy('aura_icp_profile', array('aura_pillar', 'aura_guide', 'aura_research', 'aura_update', 'aura_compare'), array(
        'labels' => array(
            'name' => __('ICP Profiles', 'aura'),
            'singular_name' => __('ICP Profile', 'aura'),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'learn/icp',
            'with_front' => false,
        ),
    ));
}
add_action('init', 'aura_register_learn_content_model', 9);

/**
 * Flush rewrite rules once after learn content model lands.
 */
function aura_flush_learn_rewrites_once() {
    $current_version = (int) get_option('aura_learn_rewrite_version', 0);
    if ($current_version >= 2) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('aura_learn_rewrite_version', 2, false);
}
add_action('init', 'aura_flush_learn_rewrites_once', 40);

/**
 * Get stable archive metadata for learn and compare post types.
 *
 * @param string $post_type Post type slug.
 * @return array<string,string>
 */
function aura_get_learn_archive_meta($post_type) {
    $meta = array(
        'badge' => __('Learn', 'aura'),
        'description' => __('Technical resources covering architecture, controls, and deployment patterns for governed agent systems.', 'aura'),
        'card_label' => __('Resource', 'aura'),
    );

    switch ((string) $post_type) {
        case 'aura_pillar':
            $meta['badge'] = __('Pillar Library', 'aura');
            $meta['description'] = __('Deep foundation pages that define the core architecture and control boundaries for each topic cluster.', 'aura');
            $meta['card_label'] = __('Pillar Page', 'aura');
            break;
        case 'aura_guide':
            $meta['badge'] = __('Technical Guides', 'aura');
            $meta['description'] = __('Hands-on implementation walkthroughs and deep dives for engineering teams.', 'aura');
            $meta['card_label'] = __('Guide', 'aura');
            break;
        case 'aura_research':
            $meta['badge'] = __('Research', 'aura');
            $meta['description'] = __('Original analysis, benchmark findings, and institutional research notes.', 'aura');
            $meta['card_label'] = __('Research', 'aura');
            break;
        case 'aura_update':
            $meta['badge'] = __('Product Updates', 'aura');
            $meta['description'] = __('Release notes, product milestones, and shipping updates.', 'aura');
            $meta['card_label'] = __('Update', 'aura');
            break;
        case 'aura_compare':
            $meta['badge'] = __('Comparison Hub', 'aura');
            $meta['description'] = __('High-intent evaluation pages comparing DeAgenticAI with market alternatives and adjacent approaches.', 'aura');
            $meta['card_label'] = __('Comparison', 'aura');
                break;
            case 'aura_dataset':
                $meta['badge'] = __('Datasets', 'aura');
                $meta['description'] = __('Structured empirical data on autonomous agent behavior, governance outcomes, and security incident analyses.', 'aura');
                $meta['card_label'] = __('Dataset', 'aura');
                break;
            }

    return $meta;
}

/**
 * Get compact excerpt for a learn post.
 *
 * @param int $post_id Post ID.
 * @param int $words Word count fallback.
 * @return string
 */
function aura_get_learn_entry_excerpt($post_id, $words = 32) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return '';
    }

    $excerpt = trim((string) get_post_field('post_excerpt', $post_id));
    if ('' !== $excerpt) {
        return $excerpt;
    }

    $content = wp_strip_all_tags((string) get_post_field('post_content', $post_id));
    return trim((string) wp_trim_words($content, max(16, (int) $words), '...'));
}

/**
 * Sanitize a compare field value.
 *
 * @param mixed  $value Raw value.
 * @param string $type Field type.
 * @return string
 */
function aura_compare_sanitize_field_value($value, $type) {
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
 * Collect sanitized rows from compare repeater POST data.
 *
 * @param mixed                $group Posted group.
 * @param array<string,string> $field_types Field map.
 * @return array<int,array<string,string>>
 */
function aura_compare_collect_rows($group, $field_types) {
    if (!is_array($group) || empty($field_types)) {
        return array();
    }

    $row_total = 0;
    foreach ($field_types as $field_key => $field_type) {
        $values = isset($group[$field_key]) && is_array($group[$field_key]) ? $group[$field_key] : array();
        $row_total = max($row_total, count($values));
    }

    $rows = array();
    for ($index = 0; $index < $row_total; $index++) {
        $row = array();
        $has_value = false;

        foreach ($field_types as $field_key => $field_type) {
            $values = isset($group[$field_key]) && is_array($group[$field_key]) ? $group[$field_key] : array();
            $raw_value = isset($values[$index]) ? $values[$index] : '';
            $value = aura_compare_sanitize_field_value($raw_value, $field_type);
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
 * Save or delete compare meta.
 *
 * @param int               $post_id Post ID.
 * @param string            $meta_key Meta key.
 * @param string|array<int,array<string,string>> $value Meta value.
 */
function aura_compare_update_meta($post_id, $meta_key, $value) {
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
 * Normalize compare repeater rows.
 *
 * @param mixed             $rows Stored rows.
 * @param array<int,string> $fields Required fields.
 * @return array<int,array<string,string>>
 */
function aura_compare_normalize_rows($rows, $fields) {
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
 * Read compare scalar meta with fallback.
 *
 * @param int    $post_id Post ID.
 * @param string $meta_key Meta key.
 * @param string $default Fallback.
 * @return string
 */
function aura_compare_meta_text($post_id, $meta_key, $default = '') {
    $value = get_post_meta((int) $post_id, (string) $meta_key, true);
    if (!is_string($value)) {
        return (string) $default;
    }

    $value = trim($value);
    return '' !== $value ? $value : (string) $default;
}

/**
 * Read compare repeater meta with fallback.
 *
 * @param int               $post_id Post ID.
 * @param string            $meta_key Meta key.
 * @param array<int,string> $fields Required fields.
 * @return array<int,array<string,string>>
 */
function aura_compare_meta_rows($post_id, $meta_key, $fields) {
    return aura_compare_normalize_rows(
        get_post_meta((int) $post_id, (string) $meta_key, true),
        $fields
    );
}

/**
 * Convert compare bullet list array to textarea string.
 *
 * @param mixed $items List items.
 * @return string
 */
function aura_compare_list_to_text($items) {
    if (!is_array($items)) {
        return '';
    }

    $lines = array();
    foreach ($items as $item) {
        $line = trim((string) $item);
        if ('' !== $line) {
            $lines[] = $line;
        }
    }

    return implode("\n", $lines);
}

/**
 * Convert compare textarea list into normalized array.
 *
 * @param string $value Text area value.
 * @return array<int,string>
 */
function aura_compare_text_to_list($value) {
    $value = trim((string) $value);
    if ('' === $value) {
        return array();
    }

    $lines = preg_split('/\r\n|\r|\n/', $value);
    if (!is_array($lines)) {
        return array();
    }

    $items = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        $line = trim((string) preg_replace('/^[-*]\s*/', '', $line));
        if ('' !== $line) {
            $items[] = $line;
        }
    }

    return $items;
}

/**
 * Guess comparison entities from title.
 *
 * @param string $title Post title.
 * @return array{0:string,1:string}
 */
function aura_guess_compare_entities_from_title($title) {
    $title = trim(wp_strip_all_tags((string) $title));
    if ('' === $title) {
        return array('', '');
    }

    if (preg_match('/^(.+?)\s+vs\.?\s+(.+)$/i', $title, $matches)) {
        return array(trim((string) $matches[1]), trim((string) $matches[2]));
    }

    return array('', '');
}

/**
 * Canonical compare defaults by slug.
 *
 * @return array<string,array<string,mixed>>
 */
function aura_get_compare_seed_map() {
    return array(
        'deagentic-vs-privy' => array(
            'entity_left' => 'DeAgenticAI',
            'entity_right' => 'Privy',
            'primary_question' => 'When should a team graduate from embedded wallet auth to policy-governed agent authority?',
            'frame' => 'The whitepaper identifies a migration window as Privy, Dynamic, and Web3Auth undergo acquisition dynamics that can change roadmap continuity and lock-in risk for teams shipping autonomous agents.',
            'quick_verdict' => 'Privy remains strong for embedded wallet onboarding. DeAgenticAI is stronger when agents execute financial actions and must pass policy, risk, and audit controls before every signature.',
            'recommended_for' => array(
                'Teams moving from user-auth-first wallets to autonomous on-chain agent operations.',
                'Organizations needing MPC-enforced policy, behavioral fraud scoring, and institutional audit trails.',
                'Builders who want social login onboarding but also progressive custody upgrades over time.',
            ),
            'not_ideal_for' => array(
                'Products focused only on embedded wallet UX for human users.',
                'Teams not ready to model explicit policy and governance boundaries for machine authority.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Onboarding path for developers',
                    'left' => 'Supports social login onboarding and progressive migration to self-custodied hardware-backed shares.',
                    'right' => 'Optimized for embedded wallet and social login onboarding workflows.',
                    'winner' => 'Context dependent. DeAgenticAI preserves low-friction onboarding while adding policy-governed authority.',
                ),
                array(
                    'criterion' => 'Authority enforcement at signing time',
                    'left' => 'Threshold MPC orchestration verifies sanitization, policy authorization, fraud score, and transaction hash before signing.',
                    'right' => 'Primarily authentication and wallet access controls; policy-aware autonomous signing is typically app-layer work.',
                    'winner' => 'DeAgenticAI for governed machine-to-machine execution.',
                ),
                array(
                    'criterion' => 'Execution-path risk controls',
                    'left' => 'Built-in behavioral fraud detection plus fast-path criteria that automatically fall back to standard MPC when conditions fail.',
                    'right' => 'Equivalent controls generally require additional custom infrastructure around the wallet layer.',
                    'winner' => 'DeAgenticAI when autonomous financial safety is a hard requirement.',
                ),
                array(
                    'criterion' => 'Platform-risk and roadmap control',
                    'left' => 'Purpose-built roadmap for autonomous agent governance infrastructure.',
                    'right' => 'Acquisition dynamics can increase lock-in and roadmap dependency concerns for production teams.',
                    'winner' => 'DeAgenticAI when long-horizon infrastructure sovereignty matters.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Separate human auth from agent authority',
                    'text' => 'Keep existing user login flows stable while you isolate which transactions are initiated by autonomous agents.',
                ),
                array(
                    'title' => 'Translate runtime checks into policy DSL',
                    'text' => 'Convert informal allow/deny logic into explicit policy constraints, limits, and escalation rules.',
                ),
                array(
                    'title' => 'Pilot low-risk routes on fast-path criteria',
                    'text' => 'Allowlist routine operations and verify they revert to standard MPC automatically when risk conditions change.',
                ),
                array(
                    'title' => 'Cut over with dual-run telemetry',
                    'text' => 'Run both stacks briefly and compare authorization outcomes, blocked intents, and incident response signals.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Can we keep social login UX while moving away from Privy for agent signing?',
                    'answer' => 'Yes. The whitepaper positions social login as a first-class onboarding path, with progressive custody upgrades as teams mature their security model.',
                ),
                array(
                    'question' => 'What should trigger migration from embedded wallet infrastructure?',
                    'answer' => 'Migration becomes urgent when agents execute autonomous financial actions that require policy-coupled signing, fraud scoring, and compliance-ready auditability.',
                ),
                array(
                    'question' => 'Is this an all-at-once replacement?',
                    'answer' => 'No. A phased migration is typical: keep front-end auth patterns, move high-risk agent flows first, then expand policy-governed signing across workloads.',
                ),
            ),
        ),
        'deagentic-vs-dynamic' => array(
            'entity_left' => 'DeAgenticAI',
            'entity_right' => 'Dynamic.xyz',
            'primary_question' => 'What is the best Dynamic.xyz alternative for AI agent developers?',
            'frame' => 'Dynamic.xyz helps teams ship embedded wallets quickly. This page evaluates where that model diverges from policy-first AI agent infrastructure.',
            'quick_verdict' => 'DeAgenticAI wins when agent authority boundaries, fraud controls, and machine-signing policies are central. Dynamic.xyz remains suitable for account onboarding and wallet UX acceleration.',
            'recommended_for' => array(
                'Agent products that execute financial actions autonomously.',
                'Teams needing explicit authority segmentation by environment and risk band.',
                'Architectures that prioritize long-term control over vendor abstraction.',
            ),
            'not_ideal_for' => array(
                'Teams seeking only a front-end wallet authentication layer.',
                'Use cases without machine-executed transaction authority.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Control plane depth',
                    'left' => 'Multi-layer policy enforcement across intent, risk, and signing.',
                    'right' => 'Developer tooling focused on wallet and account experience.',
                    'winner' => 'DeAgenticAI for governed autonomous execution.',
                ),
                array(
                    'criterion' => 'Policy explainability',
                    'left' => 'Deterministic rule outputs with audit-oriented policy paths.',
                    'right' => 'Application-level checks left primarily to team implementation.',
                    'winner' => 'DeAgenticAI when compliance traceability is needed.',
                ),
                array(
                    'criterion' => 'Agent signing architecture',
                    'left' => 'Intent-evaluated MPC and fast-path execution options.',
                    'right' => 'General embedded wallet architecture not specialized for AI-agent policy gating.',
                    'winner' => 'DeAgenticAI for autonomous transaction pipelines.',
                ),
                array(
                    'criterion' => 'Time-to-first-wallet',
                    'left' => 'More setup due to policy modeling.',
                    'right' => 'Fast onboarding for user wallet UX.',
                    'winner' => 'Dynamic.xyz for user onboarding-first projects.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Segment user-auth and agent-auth pathways',
                    'text' => 'Treat human onboarding and machine authority as separate controls before migration.',
                ),
                array(
                    'title' => 'Define risk lanes and guardrails',
                    'text' => 'Create low, medium, and high-risk execution lanes for agent actions with explicit limits.',
                ),
                array(
                    'title' => 'Run policy simulation on historical intents',
                    'text' => 'Replay prior agent actions and inspect approval/deny behavior before production cutover.',
                ),
                array(
                    'title' => 'Switch signing authority incrementally',
                    'text' => 'Move one workflow at a time to avoid broad blast radius and maintain operational confidence.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Can we combine Dynamic.xyz and DeAgenticAI?',
                    'answer' => 'Yes. Some teams keep Dynamic.xyz for user-facing wallet UX while routing autonomous agent authority through DeAgenticAI.',
                ),
                array(
                    'question' => 'Does policy modeling slow delivery?',
                    'answer' => 'Initial setup takes longer, but it usually reduces long-term incident response and governance rework.',
                ),
                array(
                    'question' => 'What should be migrated first?',
                    'answer' => 'Start with workflows where incorrect agent decisions have financial or compliance impact.',
                ),
            ),
        ),
        'deagentic-vs-turnkey' => array(
            'entity_left' => 'DeAgenticAI',
            'entity_right' => 'Turnkey',
            'primary_question' => 'How does DeAgenticAI compare with Turnkey on signing latency and policy controls?',
            'frame' => 'Turnkey emphasizes secure key operations with TEE-based primitives. This comparison focuses on latency-sensitive agent execution with governance constraints.',
            'quick_verdict' => 'Turnkey can be compelling for key infrastructure abstractions. DeAgenticAI is stronger when teams need policy-evaluated authority enforcement tied to machine-speed signing decisions.',
            'recommended_for' => array(
                'Teams balancing sub-100ms execution with strict policy gates.',
                'Projects requiring explicit fraud detection and intent checks before signing.',
                'Organizations prioritizing auditable decision trails for agent actions.',
            ),
            'not_ideal_for' => array(
                'Workloads where policy governance is not part of the signing lifecycle.',
                'Teams evaluating only generic wallet key APIs.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Latency tuning model',
                    'left' => 'Fast-path MPC optimized for pre-authorized low-risk intents.',
                    'right' => 'TEE-oriented execution model with different trust and performance assumptions.',
                    'winner' => 'Depends on trust model and policy requirements.',
                ),
                array(
                    'criterion' => 'Policy gating before signature',
                    'left' => 'Intent sanitization and policy evaluation are explicit first-class steps.',
                    'right' => 'Policy logic is generally externalized to application design.',
                    'winner' => 'DeAgenticAI for policy-first stacks.',
                ),
                array(
                    'criterion' => 'Fraud and anomaly controls',
                    'left' => 'Built to include fraud detection stage in the execution path.',
                    'right' => 'Requires additional orchestration layers for equivalent checks.',
                    'winner' => 'DeAgenticAI for integrated guardrails.',
                ),
                array(
                    'criterion' => 'Operational simplicity',
                    'left' => 'Higher modeling effort for richer governance outcomes.',
                    'right' => 'May be simpler for teams focused on pure key operations.',
                    'winner' => 'Turnkey for key-ops-only use cases.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Benchmark end-to-end intent-to-broadcast latency',
                    'text' => 'Measure total pipeline time including policy checks, not only raw signing speed.',
                ),
                array(
                    'title' => 'Classify intents by risk and frequency',
                    'text' => 'Assign execution lanes to determine where fast-path can safely apply.',
                ),
                array(
                    'title' => 'Instrument policy decision telemetry',
                    'text' => 'Track why each intent was approved or blocked to support operational tuning.',
                ),
                array(
                    'title' => 'Choose target trust assumptions explicitly',
                    'text' => 'Document the custody, enclave, and governance assumptions your risk team accepts.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Is lower signing latency always better?',
                    'answer' => 'Only if policy and fraud controls remain intact. For autonomous agents, unsafe speed increases downstream risk.',
                ),
                array(
                    'question' => 'Can both platforms coexist?',
                    'answer' => 'Yes, hybrid architectures are possible, but governance boundaries and escalation paths should be explicit.',
                ),
                array(
                    'question' => 'What metric should teams optimize first?',
                    'answer' => 'Optimize for safe throughput: approved transaction quality over raw signature volume.',
                ),
            ),
        ),
        'mpc-vs-multisig-for-ai-agents' => array(
            'entity_left' => 'MPC custody',
            'entity_right' => 'Multisig custody',
            'primary_question' => 'Is MPC better than multisig for AI agent custody?',
            'frame' => 'The whitepaper frames multisig as human-coordination infrastructure that introduces approval latency, while MPC enforces signing as a cryptographic threshold tied to policy checks.',
            'quick_verdict' => 'For autonomous agent loops, policy-aware MPC is typically stronger because it preserves machine-speed execution without dropping guardrails. Multisig remains valuable for deliberative, human-in-the-loop treasury governance.',
            'recommended_for' => array(
                'MPC: High-frequency autonomous agents with strict timing and policy constraints.',
                'MPC: Teams that need cryptographic enforcement resistant to API-layer bypass scenarios.',
                'Multisig: Lower-frequency treasury actions that intentionally require explicit human signer coordination.',
            ),
            'not_ideal_for' => array(
                'MPC: Teams that are not ready to operationalize threshold signing protocols and governance policy design.',
                'Multisig: Latency-sensitive autonomous loops that cannot wait for manual signer round-trips.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Approval bottleneck',
                    'left' => 'Threshold nodes cooperate after machine policy checks; no per-transaction human coordination required for approved lanes.',
                    'right' => 'Human signers must coordinate each approval, creating governance and operational latency.',
                    'winner' => 'MPC for autonomous execution cadence.',
                ),
                array(
                    'criterion' => 'Bypass resistance model',
                    'left' => 't-of-n threshold cannot be bypassed unless enough independent signing nodes are compromised simultaneously.',
                    'right' => 'Security depends heavily on signer operations and surrounding module/app assumptions.',
                    'winner' => 'MPC for cryptographic enforcement depth.',
                ),
                array(
                    'criterion' => 'Policy-aware signing path',
                    'left' => 'Pre-sign orchestration verifies sanitized intent, explicit authorization, risk score, and transaction hash; nodes re-verify policy hash.',
                    'right' => 'Policy checks are typically external and not part of a unified signature ceremony.',
                    'winner' => 'MPC for deterministic machine governance.',
                ),
                array(
                    'criterion' => 'Latency tuning options',
                    'left' => 'Supports full MPC for high-risk actions and fast-path session-key execution for pre-cleared low-risk actions.',
                    'right' => 'Human signer orchestration generally limits latency optimization for routine autonomous actions.',
                    'winner' => 'MPC for low-latency autonomous lanes.',
                ),
                array(
                    'criterion' => 'Human deliberation fit',
                    'left' => 'Requires explicit policy authoring to represent governance intent.',
                    'right' => 'Naturally aligns with committee-style approvals and social consensus workflows.',
                    'winner' => 'Multisig for deliberate human governance.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Classify workflows by autonomy level',
                    'text' => 'Separate machine-speed operational lanes from governance-critical approvals that should remain human-coordinated.',
                ),
                array(
                    'title' => 'Encode governance boundaries in policy DSL',
                    'text' => 'Define which intents can auto-execute, which require escalation, and which are always blocked.',
                ),
                array(
                    'title' => 'Benchmark full-path and fast-path latency',
                    'text' => 'Measure end-to-end authorize-to-broadcast performance and verify automatic fallback to standard MPC when criteria are not met.',
                ),
                array(
                    'title' => 'Adopt hybrid governance where appropriate',
                    'text' => 'Use MPC for routine autonomous execution and multisig committees for strategic policy updates and emergency controls.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Is fast-path execution a security bypass?',
                    'answer' => 'No. The whitepaper describes fast-path as policy-governed optimization using pre-authorized session credentials, with automatic fallback to standard MPC when any criterion fails.',
                ),
                array(
                    'question' => 'Can multisig still play a role in agent systems?',
                    'answer' => 'Yes. Multisig remains useful for human deliberation, treasury committees, and emergency intervention, especially at governance boundaries.',
                ),
                array(
                    'question' => 'What architecture do most teams end up with?',
                    'answer' => 'A hybrid model is common: MPC handles autonomous operational lanes, while multisig governs policy changes and strategic treasury decisions.',
                ),
            ),
        ),
        'custodial-vs-non-custodial-agent-wallets' => array(
            'entity_left' => 'Custodial agent wallet',
            'entity_right' => 'Non-custodial agent wallet',
            'primary_question' => 'Custodial vs non-custodial wallet: which model fits AI agents best?',
            'frame' => 'Choosing custodial or non-custodial architecture affects regulatory exposure, operational burden, and incident response paths for AI agent deployments.',
            'quick_verdict' => 'Custodial models may accelerate operations and support burden centralization. Non-custodial models improve sovereignty and can reduce concentration risk when governance and policy controls are mature.',
            'recommended_for' => array(
                'Custodial: Teams prioritizing operational simplicity and managed service support.',
                'Custodial: Deployments with strict central controls and short implementation timelines.',
                'Non-custodial: Organizations requiring sovereignty, portability, and reduced platform dependence.',
            ),
            'not_ideal_for' => array(
                'Custodial: Workloads where vendor concentration risk is unacceptable.',
                'Non-custodial: Teams without governance or policy maturity to run distributed controls.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Operational ownership',
                    'left' => 'Provider-managed controls and incident pathways.',
                    'right' => 'Organization-managed controls with greater internal responsibility.',
                    'winner' => 'Context dependent.',
                ),
                array(
                    'criterion' => 'Sovereignty and portability',
                    'left' => 'Lower portability and stronger provider coupling.',
                    'right' => 'Higher sovereignty and clearer migration optionality.',
                    'winner' => 'Non-custodial for long-term infrastructure independence.',
                ),
                array(
                    'criterion' => 'Compliance posture design',
                    'left' => 'Centralized reporting can be simpler initially.',
                    'right' => 'Requires more explicit policy and evidence collection design.',
                    'winner' => 'Custodial for fast baseline compliance setup.',
                ),
                array(
                    'criterion' => 'Agent autonomy fit',
                    'left' => 'May constrain advanced custom policy workflows.',
                    'right' => 'Supports deeper customization of authority boundaries.',
                    'winner' => 'Non-custodial for policy-rich autonomous systems.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Identify your regulatory and audit obligations',
                    'text' => 'Document evidence requirements first, then choose custody that can produce those artifacts reliably.',
                ),
                array(
                    'title' => 'Assess internal security and governance maturity',
                    'text' => 'Non-custodial models require stronger internal control processes and incident playbooks.',
                ),
                array(
                    'title' => 'Define migration and exit criteria upfront',
                    'text' => 'Even if starting custodial, establish portability checkpoints before scaling transaction volume.',
                ),
                array(
                    'title' => 'Map custody choice to agent authority design',
                    'text' => 'Ensure your custody model aligns with how much autonomous discretion agents should receive.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Is custodial always less secure?',
                    'answer' => 'Not necessarily. Security depends on implementation quality, governance, and incident response, not only custody label.',
                ),
                array(
                    'question' => 'Can teams move from custodial to non-custodial later?',
                    'answer' => 'Yes, but migration planning should begin early to avoid operational lock-in.',
                ),
                array(
                    'question' => 'Which model is better for enterprise AI agents?',
                    'answer' => 'It depends on risk appetite, compliance obligations, and internal operational maturity. Many enterprises adopt phased hybrid strategies.',
                ),
            ),
        ),
    );
}

/**
 * Build normalized compare payload from any seed map.
 *
 * @param string                         $slug Post slug.
 * @param string                         $title Post title fallback.
 * @param array<string,array<string,mixed>> $seed_map Seed map.
 * @return array<string,mixed>
 */
function aura_build_compare_seed_payload_from_map($slug, $title, $seed_map) {
    $slug = sanitize_title((string) $slug);

    $payload = array(
        'entity_left' => '',
        'entity_right' => '',
        'primary_question' => '',
        'frame' => '',
        'quick_verdict' => '',
        'recommended_for' => array(),
        'not_ideal_for' => array(),
        'matrix' => array(),
        'steps' => array(),
        'faqs' => array(),
    );

    if (isset($seed_map[$slug]) && is_array($seed_map[$slug])) {
        $payload = array_merge($payload, $seed_map[$slug]);
    }

    if ('' === trim((string) $payload['entity_left']) || '' === trim((string) $payload['entity_right'])) {
        $guessed = aura_guess_compare_entities_from_title($title);
        if ('' === trim((string) $payload['entity_left'])) {
            $payload['entity_left'] = $guessed[0];
        }
        if ('' === trim((string) $payload['entity_right'])) {
            $payload['entity_right'] = $guessed[1];
        }
    }

    $payload['matrix'] = aura_compare_normalize_rows(
        isset($payload['matrix']) ? $payload['matrix'] : array(),
        array('criterion', 'left', 'right', 'winner')
    );
    $payload['steps'] = aura_compare_normalize_rows(
        isset($payload['steps']) ? $payload['steps'] : array(),
        array('title', 'text')
    );
    $payload['faqs'] = aura_compare_normalize_rows(
        isset($payload['faqs']) ? $payload['faqs'] : array(),
        array('question', 'answer')
    );

    return $payload;
}

/**
 * Snapshot of compare seed defaults from v2 for safe upgrades.
 *
 * @return array<string,array<string,mixed>>
 */
function aura_get_compare_seed_legacy_v2_map() {
    return array(
        'deagentic-vs-privy' => array(
            'entity_left' => 'DeAgenticAI',
            'entity_right' => 'Privy',
            'primary_question' => 'What is the best Privy alternative for AI agent wallets?',
            'frame' => 'Privy was acquired by Stripe. This comparison explains what that platform risk means for AI agent teams and what a purpose-built alternative changes.',
            'quick_verdict' => 'DeAgenticAI is stronger for autonomous agent authority controls, policy-governed signing, and infrastructure sovereignty. Privy can fit user-auth-first experiences where programmable agent governance is not the core requirement.',
            'recommended_for' => array(
                'Teams shipping autonomous on-chain AI agents with policy constraints.',
                'Organizations requiring cryptographic controls over agent authority.',
                'Builders who need migration paths that avoid payment-rail lock-in.',
            ),
            'not_ideal_for' => array(
                'Products focused only on embedded wallet UX for human users.',
                'Teams that do not require policy-enforced machine decisioning.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Agent authority governance',
                    'left' => 'Policy DSL and intent-evaluated signing.',
                    'right' => 'Authentication-centric controls optimized for user sessions.',
                    'winner' => 'DeAgenticAI for autonomous agent operations.',
                ),
                array(
                    'criterion' => 'MPC + policy coupling',
                    'left' => 'Policy verification tied directly to signing path.',
                    'right' => 'Wallet infrastructure not designed around policy-at-signing for AI agents.',
                    'winner' => 'DeAgenticAI for machine-to-machine financial workflows.',
                ),
                array(
                    'criterion' => 'Vendor trajectory risk',
                    'left' => 'Purpose-built roadmap for agent infrastructure.',
                    'right' => 'Post-acquisition roadmap uncertainty for teams depending on long-horizon features.',
                    'winner' => 'DeAgenticAI when roadmap sovereignty matters.',
                ),
                array(
                    'criterion' => 'Implementation fit',
                    'left' => 'Best for governance-heavy autonomous execution.',
                    'right' => 'Best for embedded wallet login and account abstraction UX.',
                    'winner' => 'Context dependent.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Inventory identity and signing dependencies',
                    'text' => 'Map where Privy sessions, wallet adapters, and signing calls are used in your AI agent stack.',
                ),
                array(
                    'title' => 'Port authority logic into policy rules',
                    'text' => 'Translate informal runtime checks into explicit policy DSL controls with measurable limits.',
                ),
                array(
                    'title' => 'Validate low-risk flows on fast-path',
                    'text' => 'Benchmark latency for pre-authorized intents before moving high-value routes.',
                ),
                array(
                    'title' => 'Cut over with dual-run observability',
                    'text' => 'Operate both stacks briefly to compare authorization outcomes and signing telemetry.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Is DeAgenticAI only for enterprise teams?',
                    'answer' => 'No. Startup teams can use the same policy enforcement model and scale governance depth as they grow.',
                ),
                array(
                    'question' => 'What is the fastest migration path from Privy?',
                    'answer' => 'Start with a single agent workflow, map auth decisions to policy rules, then expand once signing and audit outcomes are stable.',
                ),
                array(
                    'question' => 'Can we keep existing front-end wallet UX during migration?',
                    'answer' => 'Yes. Most teams migrate backend authority and signing controls first, then refine front-end patterns incrementally.',
                ),
            ),
        ),
        'mpc-vs-multisig-for-ai-agents' => array(
            'entity_left' => 'MPC custody',
            'entity_right' => 'Multisig custody',
            'primary_question' => 'Is MPC better than multisig for AI agent custody?',
            'frame' => 'This page compares MPC and multisig in AI-agent environments where latency, policy enforcement, and failure domains all affect operational safety.',
            'quick_verdict' => 'MPC is usually stronger for high-frequency autonomous execution and reduced key-fragment exposure. Multisig can remain practical for slower governance-heavy operations with clear signer coordination.',
            'recommended_for' => array(
                'MPC: High-frequency autonomous agents with strict execution windows.',
                'MPC: Scenarios requiring cryptographic threshold signing without assembling full keys.',
                'Multisig: Lower-frequency treasury actions with explicit human signer workflows.',
            ),
            'not_ideal_for' => array(
                'MPC: Teams without capacity to manage threshold protocol complexity.',
                'Multisig: Latency-sensitive agent loops requiring rapid autonomous reactions.',
            ),
            'matrix' => array(
                array(
                    'criterion' => 'Signing latency',
                    'left' => 'Can support optimized sub-second and fast-path workflows when architected for it.',
                    'right' => 'Typically slower due to signer coordination and transaction orchestration.',
                    'winner' => 'MPC for time-sensitive autonomous flows.',
                ),
                array(
                    'criterion' => 'Key exposure model',
                    'left' => 'No single full private key reconstruction in normal operation.',
                    'right' => 'Signer key management remains distributed but operationally exposed per signer setup.',
                    'winner' => 'MPC for minimizing centralized key exposure.',
                ),
                array(
                    'criterion' => 'Human governance compatibility',
                    'left' => 'Requires policy tooling to represent governance logic clearly.',
                    'right' => 'Naturally aligned with explicit multi-signer approvals.',
                    'winner' => 'Multisig for deliberate human-in-the-loop governance.',
                ),
                array(
                    'criterion' => 'AI-agent operational fit',
                    'left' => 'Designed to integrate with machine-speed execution pipelines.',
                    'right' => 'Less suited for autonomous loops requiring immediate deterministic responses.',
                    'winner' => 'MPC for autonomous agent operations.',
                ),
            ),
            'steps' => array(
                array(
                    'title' => 'Model execution frequency and time constraints',
                    'text' => 'If agents must react in seconds or milliseconds, prioritize architectures that minimize signer round-trips.',
                ),
                array(
                    'title' => 'Map governance responsibilities to policy controls',
                    'text' => 'Define what remains human-approved versus what agents can execute under bounded authorization.',
                ),
                array(
                    'title' => 'Test failure and recovery scenarios',
                    'text' => 'Simulate signer outage, policy mismatch, and emergency freeze paths before production.',
                ),
                array(
                    'title' => 'Align custody architecture with compliance reporting',
                    'text' => 'Ensure audit logs can explain both cryptographic approval and policy authorization decisions.',
                ),
            ),
            'faqs' => array(
                array(
                    'question' => 'Can multisig secure AI agents sufficiently?',
                    'answer' => 'It can for low-frequency operations, but it often struggles with latency and automation requirements at scale.',
                ),
                array(
                    'question' => 'Does MPC eliminate all custody risk?',
                    'answer' => 'No. MPC reduces key reconstruction risk, but policy, fraud detection, and operational controls are still required.',
                ),
                array(
                    'question' => 'Should organizations run hybrid models?',
                    'answer' => 'Many do. MPC can power autonomous lanes while multisig governs strategic treasury changes.',
                ),
            ),
        ),
    );
}

/**
 * Resolve compare defaults from the legacy v2 map for safe refresh checks.
 *
 * @param string $slug Post slug.
 * @param string $title Post title fallback.
 * @return array<string,mixed>
 */
function aura_get_compare_legacy_seed_payload($slug, $title = '') {
    $legacy_map = aura_get_compare_seed_legacy_v2_map();
    if (!isset($legacy_map[sanitize_title((string) $slug)])) {
        return array();
    }

    return aura_build_compare_seed_payload_from_map($slug, $title, $legacy_map);
}

/**
 * Resolve compare defaults for a slug.
 *
 * @param string $slug Post slug.
 * @param string $title Post title fallback.
 * @return array<string,mixed>
 */
function aura_get_compare_seed_payload($slug, $title = '') {
    return aura_build_compare_seed_payload_from_map($slug, $title, aura_get_compare_seed_map());
}

/**
 * Build compare seed content from a prepared payload.
 *
 * @param array<string,mixed> $payload Compare payload.
 * @return string
 */
function aura_get_compare_seed_content_from_payload($payload) {
    $content = array();

    $frame = trim((string) (isset($payload['frame']) ? $payload['frame'] : ''));
    if ('' !== $frame) {
        $content[] = '<p>' . esc_html($frame) . '</p>';
    }

    $quick_verdict = trim((string) (isset($payload['quick_verdict']) ? $payload['quick_verdict'] : ''));
    if ('' !== $quick_verdict) {
        $content[] = '<p>' . esc_html($quick_verdict) . '</p>';
    }

    $primary_question = trim((string) (isset($payload['primary_question']) ? $payload['primary_question'] : ''));
    if ('' !== $primary_question) {
        $content[] = '<h2>' . esc_html__('Primary Decision Question', 'aura') . '</h2>';
        $content[] = '<p>' . esc_html($primary_question) . '</p>';
    }

    if (!empty($payload['matrix'])) {
        $content[] = '<h2>' . esc_html__('Evaluation Criteria', 'aura') . '</h2>';
        $content[] = '<ul>';
        foreach ($payload['matrix'] as $row) {
            $criterion = isset($row['criterion']) ? trim((string) $row['criterion']) : '';
            $winner = isset($row['winner']) ? trim((string) $row['winner']) : '';
            if ('' === $criterion) {
                continue;
            }

            $line = $criterion;
            if ('' !== $winner) {
                $line .= ' - ' . $winner;
            }

            $content[] = '<li>' . esc_html($line) . '</li>';
        }
        $content[] = '</ul>';
    }

    if (!empty($payload['steps'])) {
        $content[] = '<h2>' . esc_html__('Migration Outline', 'aura') . '</h2>';
        $content[] = '<ol>';
        foreach ($payload['steps'] as $step) {
            $step_title = isset($step['title']) ? trim((string) $step['title']) : '';
            $step_text = isset($step['text']) ? trim((string) $step['text']) : '';
            if ('' === $step_title && '' === $step_text) {
                continue;
            }

            if ('' !== $step_title && '' !== $step_text) {
                $content[] = '<li><strong>' . esc_html($step_title) . '.</strong> ' . esc_html($step_text) . '</li>';
            } elseif ('' !== $step_title) {
                $content[] = '<li>' . esc_html($step_title) . '</li>';
            } else {
                $content[] = '<li>' . esc_html($step_text) . '</li>';
            }
        }
        $content[] = '</ol>';
    }

    $content[] = '<p>' . esc_html__('Use the structured comparison sections below this introduction to publish your final matrix, migration path, and FAQ answers.', 'aura') . '</p>';

    return implode("\n", $content);
}

/**
 * Sample-only long-form appendix for critical compare seed pages.
 *
 * @param string $slug Post slug.
 * @return string
 */
function aura_get_compare_seed_sample_appendix($slug) {
    $slug = sanitize_title((string) $slug);

    if ('deagentic-vs-privy' === $slug) {
        $content = array(
            '<h2>Sample Narrative: Embedded Wallet Layer vs Agent Authority Layer</h2>',
            '<p>This sample section is intentionally lightweight and exists to demonstrate seeded long-form structure, not final editorial voice.</p>',
            '<p>Privy is effective for embedded wallet onboarding and user-auth flows. As teams move into autonomous agent execution, the whitepaper positions policy-governed authority and pre-sign risk controls as the differentiator.</p>',
            '<ul>',
            '<li>Migration trigger: your agent starts making financial decisions instead of only receiving user-approved actions.</li>',
            '<li>Risk trigger: roadmap or lock-in concerns increase after acquisition dynamics in your wallet-provider layer.</li>',
            '<li>Control requirement: you need policy, fraud scoring, and signing enforcement connected in one execution path.</li>',
            '</ul>',
            '<h2>Sample Implementation Notes</h2>',
            '<p>A practical pattern is to keep the existing human onboarding path while migrating autonomous routes first. Teams typically run parallel telemetry briefly to compare allow and block outcomes before full cutover.</p>',
            '<p>Replace this sample narrative with product-specific evidence, benchmarks, and customer proof points before publish.</p>',
        );

        return implode("\n", $content);
    }

    if ('mpc-vs-multisig-for-ai-agents' === $slug) {
        $content = array(
            '<h2>Sample Narrative: Machine-Speed Enforcement vs Human Coordination</h2>',
            '<p>This sample section is intentionally lightweight and exists to demonstrate seeded long-form structure, not final editorial voice.</p>',
            '<p>The whitepaper characterizes multisig as human-coordination infrastructure and MPC as cryptographic threshold enforcement. For agent loops, that changes both latency and operational safety assumptions.</p>',
            '<ul>',
            '<li>MPC strength: pre-sign checks and threshold enforcement support deterministic machine execution lanes.</li>',
            '<li>Multisig strength: explicit committee-style approvals remain strong for strategic treasury governance.</li>',
            '<li>Common target state: hybrid architecture with MPC for routine autonomous flows and multisig for policy-level interventions.</li>',
            '</ul>',
            '<h2>Sample Evaluation Notes</h2>',
            '<p>For implementation planning, benchmark full-path latency and fast-path eligibility behavior, not just signing microbenchmarks. The security question is whether unsafe intents are blocked deterministically under operational stress.</p>',
            '<p>Replace this sample narrative with production metrics, failure test outcomes, and governance evidence before publish.</p>',
        );

        return implode("\n", $content);
    }

    return '';
}

/**
 * Build compare seed content for editor drafts.
 *
 * @param array<string,mixed> $entry Compare entry payload.
 * @return string
 */
function aura_get_compare_seed_content($entry) {
    $slug = isset($entry['slug']) ? (string) $entry['slug'] : '';
    $title = isset($entry['title']) ? (string) $entry['title'] : '';
    $payload = aura_get_compare_seed_payload($slug, $title);
    $content = aura_get_compare_seed_content_from_payload($payload);
    $sample_appendix = aura_get_compare_seed_sample_appendix($slug);

    if ('' !== trim($sample_appendix)) {
        $content .= "\n\n" . $sample_appendix;
    }

    return $content;
}

/**
 * Build legacy compare seed content for v2 compatibility checks.
 *
 * @param string $slug Post slug.
 * @param string $title Post title.
 * @return string
 */
function aura_get_compare_legacy_seed_content($slug, $title = '') {
    $payload = aura_get_compare_legacy_seed_payload($slug, $title);
    if (empty($payload)) {
        return '';
    }

    return aura_get_compare_seed_content_from_payload($payload);
}

/**
 * Get merged compare payload for front-end rendering.
 *
 * @param int $post_id Compare post ID.
 * @return array<string,mixed>
 */
function aura_get_compare_payload($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return array(
            'entity_left' => '',
            'entity_right' => '',
            'primary_question' => '',
            'frame' => '',
            'quick_verdict' => '',
            'recommended_for' => array(),
            'not_ideal_for' => array(),
            'matrix' => array(),
            'steps' => array(),
            'faqs' => array(),
        );
    }

    $post = get_post($post_id);
    $slug = $post instanceof WP_Post ? (string) $post->post_name : '';
    $title = $post instanceof WP_Post ? (string) $post->post_title : '';
    $defaults = aura_get_compare_seed_payload($slug, $title);

    $entity_left = aura_compare_meta_text($post_id, '_aura_compare_entity_left', (string) $defaults['entity_left']);
    $entity_right = aura_compare_meta_text($post_id, '_aura_compare_entity_right', (string) $defaults['entity_right']);
    if ('' === trim($entity_left) || '' === trim($entity_right)) {
        $guessed = aura_guess_compare_entities_from_title($title);
        if ('' === trim($entity_left)) {
            $entity_left = $guessed[0];
        }
        if ('' === trim($entity_right)) {
            $entity_right = $guessed[1];
        }
    }

    $matrix = aura_compare_meta_rows($post_id, '_aura_compare_matrix', array('criterion', 'left', 'right', 'winner'));
    if (empty($matrix)) {
        $matrix = aura_compare_normalize_rows($defaults['matrix'], array('criterion', 'left', 'right', 'winner'));
    }

    $steps = aura_compare_meta_rows($post_id, '_aura_compare_steps', array('title', 'text'));
    if (empty($steps)) {
        $steps = aura_compare_normalize_rows($defaults['steps'], array('title', 'text'));
    }

    $faqs = aura_compare_meta_rows($post_id, '_aura_compare_faqs', array('question', 'answer'));
    if (empty($faqs)) {
        $faqs = aura_compare_normalize_rows($defaults['faqs'], array('question', 'answer'));
    }

    $recommended_for_text = aura_compare_meta_text(
        $post_id,
        '_aura_compare_recommended_for',
        aura_compare_list_to_text(isset($defaults['recommended_for']) ? $defaults['recommended_for'] : array())
    );
    $not_ideal_for_text = aura_compare_meta_text(
        $post_id,
        '_aura_compare_not_ideal_for',
        aura_compare_list_to_text(isset($defaults['not_ideal_for']) ? $defaults['not_ideal_for'] : array())
    );

    return array(
        'entity_left' => $entity_left,
        'entity_right' => $entity_right,
        'primary_question' => aura_compare_meta_text($post_id, '_aura_compare_primary_question', (string) $defaults['primary_question']),
        'frame' => aura_compare_meta_text($post_id, '_aura_compare_frame', (string) $defaults['frame']),
        'quick_verdict' => aura_compare_meta_text($post_id, '_aura_compare_quick_verdict', (string) $defaults['quick_verdict']),
        'recommended_for' => aura_compare_text_to_list($recommended_for_text),
        'not_ideal_for' => aura_compare_text_to_list($not_ideal_for_text),
        'matrix' => $matrix,
        'steps' => $steps,
        'faqs' => $faqs,
    );
}

/**
 * Build compare FAQ schema entities.
 *
 * @param int $post_id Compare post ID.
 * @return array<int,array<string,mixed>>
 */
function aura_get_compare_faq_schema_entities($post_id) {
    $payload = aura_get_compare_payload((int) $post_id);
    $faqs = isset($payload['faqs']) && is_array($payload['faqs']) ? $payload['faqs'] : array();

    $entities = array();
    foreach ($faqs as $faq) {
        if (!is_array($faq)) {
            continue;
        }

        $question = isset($faq['question']) ? trim((string) $faq['question']) : '';
        $answer = isset($faq['answer']) ? trim((string) $faq['answer']) : '';
        if ('' === $question || '' === $answer) {
            continue;
        }

        $entities[] = array(
            '@type' => 'Question',
            'name' => $question,
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $answer,
            ),
        );
    }

    return $entities;
}

/**
 * Determine whether seeded scalar compare meta should be refreshed.
 *
 * @param string $current Current value.
 * @param string $legacy Legacy value.
 * @return bool
 */
function aura_compare_seed_should_refresh_text($current, $legacy = '') {
    $current = trim((string) $current);
    $legacy = trim((string) $legacy);

    if ('' === $current) {
        return true;
    }

    return '' !== $legacy && $current === $legacy;
}

/**
 * Determine whether seeded repeater compare meta should be refreshed.
 *
 * @param array<int,array<string,string>> $current Current rows.
 * @param array<int,array<string,string>> $legacy Legacy rows.
 * @return bool
 */
function aura_compare_seed_should_refresh_rows($current, $legacy = array()) {
    if (empty($current)) {
        return true;
    }

    return !empty($legacy) && $current === $legacy;
}

/**
 * Seed compare post meta if not already populated.
 *
 * @param int                $post_id Compare post ID.
 * @param array<string,mixed> $entry Compare seed entry.
 */
function aura_seed_compare_entry_meta($post_id, $entry) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return;
    }

    $slug = isset($entry['slug']) ? (string) $entry['slug'] : '';
    $title = isset($entry['title']) ? (string) $entry['title'] : '';
    $defaults = aura_get_compare_seed_payload($slug, $title);
    $legacy_defaults = aura_get_compare_legacy_seed_payload($slug, $title);

    $current_entity_left = aura_compare_meta_text($post_id, '_aura_compare_entity_left', '');
    $legacy_entity_left = isset($legacy_defaults['entity_left']) ? (string) $legacy_defaults['entity_left'] : '';
    if (aura_compare_seed_should_refresh_text($current_entity_left, $legacy_entity_left) && '' !== trim((string) $defaults['entity_left'])) {
        update_post_meta($post_id, '_aura_compare_entity_left', (string) $defaults['entity_left']);
    }

    $current_entity_right = aura_compare_meta_text($post_id, '_aura_compare_entity_right', '');
    $legacy_entity_right = isset($legacy_defaults['entity_right']) ? (string) $legacy_defaults['entity_right'] : '';
    if (aura_compare_seed_should_refresh_text($current_entity_right, $legacy_entity_right) && '' !== trim((string) $defaults['entity_right'])) {
        update_post_meta($post_id, '_aura_compare_entity_right', (string) $defaults['entity_right']);
    }

    $current_primary_question = aura_compare_meta_text($post_id, '_aura_compare_primary_question', '');
    $legacy_primary_question = isset($legacy_defaults['primary_question']) ? (string) $legacy_defaults['primary_question'] : '';
    if (aura_compare_seed_should_refresh_text($current_primary_question, $legacy_primary_question) && '' !== trim((string) $defaults['primary_question'])) {
        update_post_meta($post_id, '_aura_compare_primary_question', (string) $defaults['primary_question']);
    }

    $current_frame = aura_compare_meta_text($post_id, '_aura_compare_frame', '');
    $legacy_frame = isset($legacy_defaults['frame']) ? (string) $legacy_defaults['frame'] : '';
    if (aura_compare_seed_should_refresh_text($current_frame, $legacy_frame) && '' !== trim((string) $defaults['frame'])) {
        update_post_meta($post_id, '_aura_compare_frame', (string) $defaults['frame']);
    }

    $current_quick_verdict = aura_compare_meta_text($post_id, '_aura_compare_quick_verdict', '');
    $legacy_quick_verdict = isset($legacy_defaults['quick_verdict']) ? (string) $legacy_defaults['quick_verdict'] : '';
    if (aura_compare_seed_should_refresh_text($current_quick_verdict, $legacy_quick_verdict) && '' !== trim((string) $defaults['quick_verdict'])) {
        update_post_meta($post_id, '_aura_compare_quick_verdict', (string) $defaults['quick_verdict']);
    }

    $recommended_text = aura_compare_meta_text($post_id, '_aura_compare_recommended_for', '');
    $default_recommended_text = aura_compare_list_to_text(isset($defaults['recommended_for']) ? $defaults['recommended_for'] : array());
    $legacy_recommended_text = aura_compare_list_to_text(isset($legacy_defaults['recommended_for']) ? $legacy_defaults['recommended_for'] : array());
    if (aura_compare_seed_should_refresh_text($recommended_text, $legacy_recommended_text) && '' !== trim($default_recommended_text)) {
        update_post_meta($post_id, '_aura_compare_recommended_for', $default_recommended_text);
    }

    $not_ideal_text = aura_compare_meta_text($post_id, '_aura_compare_not_ideal_for', '');
    $default_not_ideal_text = aura_compare_list_to_text(isset($defaults['not_ideal_for']) ? $defaults['not_ideal_for'] : array());
    $legacy_not_ideal_text = aura_compare_list_to_text(isset($legacy_defaults['not_ideal_for']) ? $legacy_defaults['not_ideal_for'] : array());
    if (aura_compare_seed_should_refresh_text($not_ideal_text, $legacy_not_ideal_text) && '' !== trim($default_not_ideal_text)) {
        update_post_meta($post_id, '_aura_compare_not_ideal_for', $default_not_ideal_text);
    }

    $matrix_fields = array('criterion', 'left', 'right', 'winner');
    $current_matrix = aura_compare_meta_rows($post_id, '_aura_compare_matrix', $matrix_fields);
    $default_matrix = aura_compare_normalize_rows($defaults['matrix'], $matrix_fields);
    $legacy_matrix = aura_compare_normalize_rows(isset($legacy_defaults['matrix']) ? $legacy_defaults['matrix'] : array(), $matrix_fields);
    if (aura_compare_seed_should_refresh_rows($current_matrix, $legacy_matrix) && !empty($default_matrix)) {
        update_post_meta($post_id, '_aura_compare_matrix', $default_matrix);
    }

    $step_fields = array('title', 'text');
    $current_steps = aura_compare_meta_rows($post_id, '_aura_compare_steps', $step_fields);
    $default_steps = aura_compare_normalize_rows($defaults['steps'], $step_fields);
    $legacy_steps = aura_compare_normalize_rows(isset($legacy_defaults['steps']) ? $legacy_defaults['steps'] : array(), $step_fields);
    if (aura_compare_seed_should_refresh_rows($current_steps, $legacy_steps) && !empty($default_steps)) {
        update_post_meta($post_id, '_aura_compare_steps', $default_steps);
    }

    $faq_fields = array('question', 'answer');
    $current_faqs = aura_compare_meta_rows($post_id, '_aura_compare_faqs', $faq_fields);
    $default_faqs = aura_compare_normalize_rows($defaults['faqs'], $faq_fields);
    $legacy_faqs = aura_compare_normalize_rows(isset($legacy_defaults['faqs']) ? $legacy_defaults['faqs'] : array(), $faq_fields);
    if (aura_compare_seed_should_refresh_rows($current_faqs, $legacy_faqs) && !empty($default_faqs)) {
        update_post_meta($post_id, '_aura_compare_faqs', $default_faqs);
    }
}

/**
 * Canonical pillar scaffold defaults by slug.
 *
 * @return array<string,array<string,mixed>>
 */
function aura_get_pillar_seed_map() {
    return array(
        'autonomous-ai-agents-on-chain-capital' => array(
            'focus_step' => 1,
            'visual_placeholder' => 'Diagram placeholder: key possession vs policy authority model for autonomous on-chain agents.',
            'cluster_placeholders' => array(
                'What Is an Agentic Control Plane? Definition & Architecture',
                'Why Private Keys Cannot Secure AI Agents (And What Can)',
                'AI Agents vs Automation Scripts: What\'s the Real Difference On-Chain?',
                'How AI Agents Execute DeFi Strategies: A Technical Breakdown',
            ),
        ),
        'agentic-policy-authority-enforcement' => array(
            'focus_step' => 3,
            'visual_placeholder' => 'Diagram placeholder: capability scope vs authorized scope with policy inheritance layers.',
            'cluster_placeholders' => array(
                'Prompt Injection Attacks on AI Agents and Intent Sanitization Defenses',
                'Hierarchical Agent Policies: DAO, Team, and Individual Agent Authority',
                'Policy Engine vs Smart Contract: Which Should Govern Your AI Agent?',
                'Designing Safe Policy Rollouts for Autonomous Financial Agents',
            ),
        ),
        'dao-treasury-automation' => array(
            'focus_step' => 4,
            'visual_placeholder' => 'Diagram placeholder: DAO proposal to policy-enforced execution flow with fraud checkpoints.',
            'cluster_placeholders' => array(
                'Safe Multisig vs AI Agent Control Plane for DAO Treasury',
                'How to Bound Agent Spending With Governance-Linked Policies',
                'Emergency Pause and Human Escalation Patterns for DAO Agents',
                'Treasury Risk Scoring for Autonomous Rebalancing Workflows',
            ),
        ),
        'mpc-security-fast-path-execution' => array(
            'focus_step' => 5,
            'visual_placeholder' => 'Diagram placeholder: latency bars for full MPC, fast-path MPC, and TEE-based alternatives.',
            'cluster_placeholders' => array(
                'MPC vs Multisig vs Smart Contract Wallets for AI Agent Custody',
                'Private Key Reconstruction Risk and Why Threshold Signing Matters',
                'Blind Signing Risk: Why MPC Alone Is Not Enough',
                'Fast-Path Execution Design for Sub-100ms Agent Workflows',
            ),
        ),
        'ai-agent-compliance-regulatory' => array(
            'focus_step' => 6,
            'visual_placeholder' => 'Diagram placeholder: compliance evidence chain from intent logs to signed transaction audit trails.',
            'cluster_placeholders' => array(
                'MiCA, DORA, and Travel Rule Controls for Autonomous Agent Systems',
                'Building Policy Evidence Chains for Regulatory Audits',
                'KYA and Agent Identity Controls for Institutional Deployments',
                'Cross-Jurisdiction Agent Governance Comparison Framework',
            ),
        ),
        'institutional-rwa-management' => array(
            'focus_step' => 4,
            'visual_placeholder' => 'Diagram placeholder: tokenized asset operations lifecycle with valuation and policy checkpoints.',
            'cluster_placeholders' => array(
                'AI Agent Governance for Tokenized Fund Structures',
                'Valuation and Risk Monitoring: AI Agents vs Manual Oversight',
                'Policy-Limited Agent Mandates for RWA Portfolios',
                'Operational Controls for Institutional Tokenized Asset Workflows',
            ),
        ),
        'web2-web3-enterprise-bridge' => array(
            'focus_step' => 2,
            'visual_placeholder' => 'Diagram placeholder: enterprise middleware bridge from Web2 events to sanitized Web3 execution intents.',
            'cluster_placeholders' => array(
                'Self-Hosted MPC vs SaaS Custody for Enterprise Data Sovereignty',
                'Bridging Legacy Approval Systems Into Agent Policy Controls',
                'x402 Machine-to-Machine Payments for Enterprise Agent Workflows',
                'Enterprise Integration Patterns for Web2 to Web3 Agent Infrastructure',
            ),
        ),
    );
}

/**
 * Resolve pillar defaults for a slug.
 *
 * @param string $slug Pillar slug.
 * @return array<string,mixed>
 */
function aura_get_pillar_seed_payload($slug) {
    $slug = sanitize_title((string) $slug);
    $seed_map = aura_get_pillar_seed_map();

    $payload = array(
        'focus_step' => 3,
        'visual_placeholder' => '',
        'cluster_placeholders' => array(),
    );

    if (isset($seed_map[$slug]) && is_array($seed_map[$slug])) {
        $payload = array_merge($payload, $seed_map[$slug]);
    }

    $focus_step = (int) $payload['focus_step'];
    if ($focus_step < 1 || $focus_step > 6) {
        $payload['focus_step'] = 3;
    }

    if (!is_array($payload['cluster_placeholders'])) {
        $payload['cluster_placeholders'] = array();
    }

    return $payload;
}

/**
 * Build pillar seed content for initial drafts.
 *
 * @param array<string,mixed> $entry Pillar entry payload.
 * @return string
 */
function aura_get_pillar_seed_content($entry) {
    $slug = isset($entry['slug']) ? (string) $entry['slug'] : '';
    $payload = aura_get_pillar_seed_payload($slug);

    $semantic_triple = function_exists('aura_get_semantic_triple')
        ? aura_get_semantic_triple()
        : "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do - in Web3 and enterprise financial environments.";

    $step_labels = aura_get_happy_path_step_labels();
    $focus_step = (int) $payload['focus_step'];
    $focus_label = isset($step_labels[$focus_step]) ? (string) $step_labels[$focus_step] : __('Policy Evaluation', 'aura');
    $visual_placeholder = trim((string) (isset($payload['visual_placeholder']) ? $payload['visual_placeholder'] : ''));
    $cluster_placeholders = isset($payload['cluster_placeholders']) && is_array($payload['cluster_placeholders']) ? $payload['cluster_placeholders'] : array();
    $visual_summary = (string) preg_replace('/^Diagram placeholder:\s*/i', '', $visual_placeholder);

    $content = array();
    $content[] = '<p>' . esc_html($semantic_triple) . '</p>';
    $content[] = '<p>' . esc_html__('This pillar defines core concepts, architectural decisions, and control boundaries for the surrounding topic cluster.', 'aura') . '</p>';
    $content[] = '<h2>' . esc_html__('Happy Path Context', 'aura') . '</h2>';
    $content[] = '<p>' . esc_html(sprintf(__('This topic is centered on Step %1$d: %2$s in the execution sequence.', 'aura'), $focus_step, $focus_label)) . '</p>';

    if (!empty($cluster_placeholders)) {
        $content[] = '<h2>' . esc_html__('Related Deep-Dive Topics', 'aura') . '</h2>';
        $content[] = '<ul>';
        foreach ($cluster_placeholders as $placeholder) {
            $label = trim((string) $placeholder);
            if ('' === $label) {
                continue;
            }
            $content[] = '<li>' . esc_html($label) . '</li>';
        }
        $content[] = '</ul>';
    }

    if ('' !== $visual_placeholder) {
        $content[] = '<h2>' . esc_html__('Reference Diagram Context', 'aura') . '</h2>';
        $content[] = '<p>' . esc_html(trim($visual_summary)) . '</p>';
    }

    return implode("\n", $content);
}

/**
 * Seed pillar highlight-step meta from scaffold defaults.
 *
 * @param int                $post_id Pillar post ID.
 * @param array<string,mixed> $entry Pillar seed entry.
 */
function aura_seed_pillar_visual_meta($post_id, $entry) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return;
    }

    $existing = (int) get_post_meta($post_id, '_aura_pillar_highlight_step', true);
    if ($existing >= 1 && $existing <= 6) {
        return;
    }

    $slug = isset($entry['slug']) ? (string) $entry['slug'] : '';
    $payload = aura_get_pillar_seed_payload($slug);
    $focus_step = (int) $payload['focus_step'];
    if ($focus_step < 1 || $focus_step > 6) {
        $focus_step = 3;
    }

    update_post_meta($post_id, '_aura_pillar_highlight_step', $focus_step);
}

/**
 * Get pillar scaffold payload for front-end placeholders.
 *
 * @param int $post_id Pillar post ID.
 * @return array<string,mixed>
 */
function aura_get_pillar_scaffold_payload($post_id) {
    $post = get_post((int) $post_id);
    if (!$post instanceof WP_Post) {
        return array(
            'focus_step' => 3,
            'focus_label' => __('Policy Evaluation', 'aura'),
            'visual_placeholder' => '',
            'cluster_placeholders' => array(),
        );
    }

    $payload = aura_get_pillar_seed_payload((string) $post->post_name);
    $focus_step = (int) $payload['focus_step'];
    $focus_label = __('Policy Evaluation', 'aura');
    $steps = aura_get_happy_path_step_labels();
    if (isset($steps[$focus_step])) {
        $focus_label = (string) $steps[$focus_step];
    }

    return array(
        'focus_step' => $focus_step,
        'focus_label' => $focus_label,
        'visual_placeholder' => isset($payload['visual_placeholder']) ? (string) $payload['visual_placeholder'] : '',
        'cluster_placeholders' => isset($payload['cluster_placeholders']) && is_array($payload['cluster_placeholders']) ? $payload['cluster_placeholders'] : array(),
    );
}

/**
 * Get default FAQ scaffold rows for a pillar page.
 *
 * @param string $title Pillar title.
 * @param string $focus_label Highlighted Happy Path label.
 * @return array<int,array<string,string>>
 */
function aura_get_pillar_default_faq_rows($title, $focus_label) {
    $title = trim((string) $title);
    $focus_label = trim((string) $focus_label);

    $pillar_label = '' !== $title ? $title : __('this pillar', 'aura');
    $focus_text = '' !== $focus_label ? $focus_label : __('Policy Evaluation', 'aura');

    return array(
        array(
            'question' => sprintf(__('What control boundary does %s emphasize?', 'aura'), $pillar_label),
            'answer' => sprintf(__('This pillar centers on %s in the Happy Path and explains how that boundary limits autonomous execution before signing.', 'aura'), $focus_text),
        ),
        array(
            'question' => __('How should teams adopt this layer first?', 'aura'),
            'answer' => __('Start with one bounded workflow, define explicit allow and deny criteria, and expand only after observed decisions match policy intent.', 'aura'),
        ),
        array(
            'question' => __('Which metrics should be tracked for this pillar?', 'aura'),
            'answer' => __('Track approval and denial distribution, escalation frequency, and execution latency so the control layer can be tuned safely over time.', 'aura'),
        ),
    );
}

/**
 * Get FAQ payload for a pillar page.
 *
 * @param int $post_id Pillar post ID.
 * @return array<int,array<string,string>>
 */
function aura_get_pillar_faq_payload($post_id) {
    $post = get_post((int) $post_id);
    if (!$post instanceof WP_Post) {
        return array();
    }

    $rows = aura_compare_meta_rows((int) $post->ID, '_aura_pillar_faqs', array('question', 'answer'));
    if (!empty($rows)) {
        return $rows;
    }

    $scaffold = aura_get_pillar_scaffold_payload((int) $post->ID);
    $focus_label = isset($scaffold['focus_label']) ? (string) $scaffold['focus_label'] : '';
    return aura_get_pillar_default_faq_rows((string) $post->post_title, $focus_label);
}

/**
 * Get happy-path step labels used by pillar visuals.
 *
 * @return array<int,string>
 */
function aura_get_happy_path_step_labels() {
    return array(
        1 => __('Intent Generation', 'aura'),
        2 => __('Intent Sanitization', 'aura'),
        3 => __('Policy Evaluation', 'aura'),
        4 => __('Fraud Detection', 'aura'),
        5 => __('MPC Threshold Signing', 'aura'),
        6 => __('Chain Broadcast', 'aura'),
    );
}

/**
 * Read pillar highlight step from post meta.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function aura_get_pillar_highlight_step($post_id) {
    $step = (int) get_post_meta((int) $post_id, '_aura_pillar_highlight_step', true);
    return ($step >= 1 && $step <= 6) ? $step : 3;
}

/**
 * Register pillar visual meta box.
 */
function aura_register_pillar_visual_meta_box() {
    add_meta_box(
        'aura_pillar_visual_settings',
        __('Pillar Visual Settings', 'aura'),
        'aura_render_pillar_visual_meta_box',
        'aura_pillar',
        'side',
        'default'
    );
}
add_action('add_meta_boxes_aura_pillar', 'aura_register_pillar_visual_meta_box');

/**
 * Register pillar FAQ meta box.
 */
function aura_register_pillar_faq_meta_box() {
    add_meta_box(
        'aura_pillar_faq_fields',
        __('Pillar FAQ Structure', 'aura'),
        'aura_render_pillar_faq_meta_box',
        'aura_pillar',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes_aura_pillar', 'aura_register_pillar_faq_meta_box');

/**
 * Render pillar visual settings meta box.
 *
 * @param WP_Post $post Current post.
 */
function aura_render_pillar_visual_meta_box($post) {
    wp_nonce_field('aura_pillar_visual_save_' . $post->ID, 'aura_pillar_visual_nonce');

    $current_step = aura_get_pillar_highlight_step((int) $post->ID);
    $steps = aura_get_happy_path_step_labels();
    $recommended_step = 0;
    if ($post instanceof WP_Post && function_exists('aura_get_pillar_seed_payload')) {
        $pillar_payload = aura_get_pillar_seed_payload((string) $post->post_name);
        $recommended_step = isset($pillar_payload['focus_step']) ? (int) $pillar_payload['focus_step'] : 0;
        if ($recommended_step < 1 || $recommended_step > 6) {
            $recommended_step = 0;
        }
    }
    ?>
    <p><?php esc_html_e('Select which Happy Path layer should be emphasized for this pillar page.', 'aura'); ?></p>
    <?php if ($recommended_step > 0 && isset($steps[$recommended_step])) : ?>
        <p class="description"><?php echo esc_html(sprintf(__('Recommended default for this pillar: Step %1$d - %2$s.', 'aura'), $recommended_step, $steps[$recommended_step])); ?></p>
    <?php endif; ?>
    <p>
        <label for="aura-pillar-highlight-step" class="screen-reader-text"><?php esc_html_e('Highlighted Happy Path Step', 'aura'); ?></label>
        <select id="aura-pillar-highlight-step" name="aura_pillar_highlight_step" style="width: 100%;">
            <?php foreach ($steps as $index => $label) : ?>
                <option value="<?php echo esc_attr((string) $index); ?>" <?php selected($current_step, $index); ?>>
                    <?php echo esc_html(sprintf(__('Step %1$d - %2$s', 'aura'), $index, $label)); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

/**
 * Render pillar FAQ settings meta box.
 *
 * @param WP_Post $post Current post.
 */
function aura_render_pillar_faq_meta_box($post) {
    if (!$post instanceof WP_Post) {
        return;
    }

    wp_nonce_field('aura_pillar_faq_save_' . $post->ID, 'aura_pillar_faq_nonce');

    if (function_exists('aura_marketing_output_metabox_assets')) {
        aura_marketing_output_metabox_assets();
    }

    $faqs = aura_get_pillar_faq_payload((int) $post->ID);
    ?>
    <p class="aura-marketing-note">
        <?php esc_html_e('These rows power both the visible FAQ section on pillar pages and FAQPage schema output.', 'aura'); ?>
    </p>

    <?php if (function_exists('aura_marketing_render_repeater_group')) : ?>
        <?php
        aura_marketing_render_repeater_group(
            'aura-pillar-faqs',
            __('Pillar FAQ Block', 'aura'),
            __('Add at least 3 FAQ rows for answer-engine coverage.', 'aura'),
            $faqs,
            'aura_pillar_faq_rows',
            array(
                array(
                    'key' => 'question',
                    'label' => __('Question', 'aura'),
                    'type' => 'text',
                    'placeholder' => __('What is the control boundary for this layer?', 'aura'),
                ),
                array(
                    'key' => 'answer',
                    'label' => __('Answer', 'aura'),
                    'type' => 'textarea',
                    'placeholder' => __('Provide a concise implementation-oriented answer.', 'aura'),
                ),
            ),
            __('Add FAQ', 'aura')
        );
        ?>
    <?php endif; ?>
    <?php
}

/**
 * Save pillar visual settings.
 *
 * @param int $post_id Post ID.
 */
function aura_save_pillar_visual_meta_box($post_id) {
    $post_id = (int) $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['aura_pillar_visual_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash((string) $_POST['aura_pillar_visual_nonce']));
    if (!wp_verify_nonce($nonce, 'aura_pillar_visual_save_' . $post_id)) {
        return;
    }

    $step = isset($_POST['aura_pillar_highlight_step']) ? (int) $_POST['aura_pillar_highlight_step'] : 0;
    if ($step < 1 || $step > 6) {
        delete_post_meta($post_id, '_aura_pillar_highlight_step');
        return;
    }

    update_post_meta($post_id, '_aura_pillar_highlight_step', $step);
}
add_action('save_post_aura_pillar', 'aura_save_pillar_visual_meta_box', 10, 1);

/**
 * Save pillar FAQ settings.
 *
 * @param int $post_id Post ID.
 */
function aura_save_pillar_faq_meta_box($post_id) {
    $post_id = (int) $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['aura_pillar_faq_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash((string) $_POST['aura_pillar_faq_nonce']));
    if (!wp_verify_nonce($nonce, 'aura_pillar_faq_save_' . $post_id)) {
        return;
    }

    $faqs = aura_compare_collect_rows(
        isset($_POST['aura_pillar_faq_rows']) ? $_POST['aura_pillar_faq_rows'] : array(),
        array(
            'question' => 'text',
            'answer' => 'textarea',
        )
    );

    aura_compare_update_meta($post_id, '_aura_pillar_faqs', $faqs);
}
add_action('save_post_aura_pillar', 'aura_save_pillar_faq_meta_box', 10, 1);

/**
 * Register compare structured-content meta box.
 */
function aura_register_compare_meta_box() {
    add_meta_box(
        'aura_compare_structured_fields',
        __('Compare Page Structured Fields', 'aura'),
        'aura_render_compare_meta_box',
        'aura_compare',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_aura_compare', 'aura_register_compare_meta_box');

/**
 * Keep dataset editor clean (no compare/pillar structured metaboxes).
 */
function aura_cleanup_dataset_meta_boxes() {
    remove_meta_box('aura_compare_structured_fields', 'aura_dataset', 'normal');
    remove_meta_box('aura_pillar_visual_settings', 'aura_dataset', 'side');
    remove_meta_box('aura_pillar_faq_fields', 'aura_dataset', 'normal');
}
add_action('add_meta_boxes_aura_dataset', 'aura_cleanup_dataset_meta_boxes', 99);

/**
 * Register dataset metadata meta box.
 */
function aura_register_dataset_meta_box() {
    add_meta_box(
        'aura_dataset_metadata_fields',
        __('Dataset Metadata', 'aura'),
        'aura_render_dataset_meta_box',
        'aura_dataset',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_aura_dataset', 'aura_register_dataset_meta_box', 10);

/**
 * Render dataset metadata meta box.
 *
 * @param WP_Post $post Current post object.
 */
function aura_render_dataset_meta_box($post) {
    if (!$post instanceof WP_Post) {
        return;
    }

    $post_id = (int) $post->ID;
    wp_nonce_field('aura_dataset_meta_save_' . $post_id, 'aura_dataset_meta_nonce');

    $source_url = (string) get_post_meta($post_id, '_aura_dataset_source_url', true);
    $download_url = (string) get_post_meta($post_id, '_aura_dataset_download_url', true);
    $license = (string) get_post_meta($post_id, '_aura_dataset_license', true);
    $coverage = (string) get_post_meta($post_id, '_aura_dataset_coverage_period', true);
    $methodology = (string) get_post_meta($post_id, '_aura_dataset_methodology', true);
    ?>
    <p class="aura-marketing-note">
        <?php esc_html_e('Optional metadata for dataset provenance, access, and interpretation context.', 'aura'); ?>
    </p>

    <div class="aura-marketing-two-col">
        <div class="aura-marketing-field">
            <label for="aura-dataset-source-url"><?php esc_html_e('Source URL', 'aura'); ?></label>
            <input type="url" id="aura-dataset-source-url" name="aura_dataset_source_url" value="<?php echo esc_attr($source_url); ?>" placeholder="https://...">
        </div>
        <div class="aura-marketing-field">
            <label for="aura-dataset-download-url"><?php esc_html_e('Download URL', 'aura'); ?></label>
            <input type="url" id="aura-dataset-download-url" name="aura_dataset_download_url" value="<?php echo esc_attr($download_url); ?>" placeholder="https://...">
        </div>
    </div>

    <div class="aura-marketing-two-col" style="margin-top: 12px;">
        <div class="aura-marketing-field">
            <label for="aura-dataset-license"><?php esc_html_e('License', 'aura'); ?></label>
            <input type="text" id="aura-dataset-license" name="aura_dataset_license" value="<?php echo esc_attr($license); ?>" placeholder="CC BY 4.0 / Proprietary / Internal">
        </div>
        <div class="aura-marketing-field">
            <label for="aura-dataset-coverage"><?php esc_html_e('Coverage Period', 'aura'); ?></label>
            <input type="text" id="aura-dataset-coverage" name="aura_dataset_coverage_period" value="<?php echo esc_attr($coverage); ?>" placeholder="2024-01 to 2026-03">
        </div>
    </div>

    <div class="aura-marketing-field" style="margin-top: 12px;">
        <label for="aura-dataset-methodology"><?php esc_html_e('Methodology Notes', 'aura'); ?></label>
        <textarea id="aura-dataset-methodology" name="aura_dataset_methodology" rows="4" placeholder="<?php esc_attr_e('Describe collection process, sampling, and caveats.', 'aura'); ?>"><?php echo esc_textarea($methodology); ?></textarea>
    </div>
    <?php
}

/**
 * Save dataset metadata meta box values.
 *
 * @param int $post_id Post ID.
 */
function aura_save_dataset_meta_box($post_id) {
    $post_id = (int) $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['aura_dataset_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash((string) $_POST['aura_dataset_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'aura_dataset_meta_save_' . $post_id)) {
        return;
    }

    $source_url = isset($_POST['aura_dataset_source_url']) ? esc_url_raw(wp_unslash((string) $_POST['aura_dataset_source_url'])) : '';
    $download_url = isset($_POST['aura_dataset_download_url']) ? esc_url_raw(wp_unslash((string) $_POST['aura_dataset_download_url'])) : '';
    $license = isset($_POST['aura_dataset_license']) ? sanitize_text_field(wp_unslash((string) $_POST['aura_dataset_license'])) : '';
    $coverage = isset($_POST['aura_dataset_coverage_period']) ? sanitize_text_field(wp_unslash((string) $_POST['aura_dataset_coverage_period'])) : '';
    $methodology = isset($_POST['aura_dataset_methodology']) ? sanitize_textarea_field(wp_unslash((string) $_POST['aura_dataset_methodology'])) : '';

    aura_compare_update_meta($post_id, '_aura_dataset_source_url', $source_url);
    aura_compare_update_meta($post_id, '_aura_dataset_download_url', $download_url);
    aura_compare_update_meta($post_id, '_aura_dataset_license', $license);
    aura_compare_update_meta($post_id, '_aura_dataset_coverage_period', $coverage);
    aura_compare_update_meta($post_id, '_aura_dataset_methodology', $methodology);
}
add_action('save_post_aura_dataset', 'aura_save_dataset_meta_box', 10, 1);

/**
 * Render compare structured-content meta box.
 *
 * @param WP_Post $post Current post object.
 */
function aura_render_compare_meta_box($post) {
    if (!$post instanceof WP_Post) {
        return;
    }

    $post_id = (int) $post->ID;
    $payload = aura_get_compare_payload($post_id);

    wp_nonce_field('aura_compare_meta_save_' . $post_id, 'aura_compare_meta_nonce');

    if (function_exists('aura_marketing_output_metabox_assets')) {
        aura_marketing_output_metabox_assets();
    }

    $recommended_text = implode("\n", isset($payload['recommended_for']) && is_array($payload['recommended_for']) ? $payload['recommended_for'] : array());
    $not_ideal_text = implode("\n", isset($payload['not_ideal_for']) && is_array($payload['not_ideal_for']) ? $payload['not_ideal_for'] : array());
    ?>
    <p class="aura-marketing-note">
        <?php esc_html_e('These fields power the comparison matrix, decision framework, migration path, FAQ section, and JSON-LD mentions/FAQPage output.', 'aura'); ?>
    </p>

    <div class="aura-marketing-two-col">
        <div class="aura-marketing-field">
            <label for="aura-compare-entity-left"><?php esc_html_e('Left Entity', 'aura'); ?></label>
            <input type="text" id="aura-compare-entity-left" name="aura_compare_entity_left" value="<?php echo esc_attr(isset($payload['entity_left']) ? (string) $payload['entity_left'] : ''); ?>" placeholder="<?php esc_attr_e('DeAgenticAI', 'aura'); ?>">
        </div>
        <div class="aura-marketing-field">
            <label for="aura-compare-entity-right"><?php esc_html_e('Right Entity', 'aura'); ?></label>
            <input type="text" id="aura-compare-entity-right" name="aura_compare_entity_right" value="<?php echo esc_attr(isset($payload['entity_right']) ? (string) $payload['entity_right'] : ''); ?>" placeholder="<?php esc_attr_e('Privy / Dynamic.xyz / Multisig', 'aura'); ?>">
        </div>
    </div>

    <div class="aura-marketing-field" style="margin-top: 12px;">
        <label for="aura-compare-primary-question"><?php esc_html_e('Primary AEO Question', 'aura'); ?></label>
        <input type="text" id="aura-compare-primary-question" name="aura_compare_primary_question" value="<?php echo esc_attr(isset($payload['primary_question']) ? (string) $payload['primary_question'] : ''); ?>" placeholder="<?php esc_attr_e('What is the best alternative for ...?', 'aura'); ?>">
    </div>

    <div class="aura-marketing-field" style="margin-top: 12px;">
        <label for="aura-compare-frame"><?php esc_html_e('Frame / Angle', 'aura'); ?></label>
        <textarea id="aura-compare-frame" name="aura_compare_frame" rows="3" placeholder="<?php esc_attr_e('Describe the evaluation angle this page takes.', 'aura'); ?>"><?php echo esc_textarea(isset($payload['frame']) ? (string) $payload['frame'] : ''); ?></textarea>
    </div>

    <div class="aura-marketing-field" style="margin-top: 12px;">
        <label for="aura-compare-quick-verdict"><?php esc_html_e('Quick Verdict', 'aura'); ?></label>
        <textarea id="aura-compare-quick-verdict" name="aura_compare_quick_verdict" rows="4" placeholder="<?php esc_attr_e('Summarize who should choose which option and why.', 'aura'); ?>"><?php echo esc_textarea(isset($payload['quick_verdict']) ? (string) $payload['quick_verdict'] : ''); ?></textarea>
    </div>

    <div class="aura-marketing-two-col" style="margin-top: 12px;">
        <div class="aura-marketing-field">
            <label for="aura-compare-recommended"><?php esc_html_e('Recommended For (one line per bullet)', 'aura'); ?></label>
            <textarea id="aura-compare-recommended" name="aura_compare_recommended_for" rows="5" placeholder="<?php esc_attr_e('Teams shipping autonomous on-chain agents', 'aura'); ?>"><?php echo esc_textarea($recommended_text); ?></textarea>
        </div>
        <div class="aura-marketing-field">
            <label for="aura-compare-not-ideal"><?php esc_html_e('Not Ideal For (one line per bullet)', 'aura'); ?></label>
            <textarea id="aura-compare-not-ideal" name="aura_compare_not_ideal_for" rows="5" placeholder="<?php esc_attr_e('Projects that only need wallet login UX', 'aura'); ?>"><?php echo esc_textarea($not_ideal_text); ?></textarea>
        </div>
    </div>

    <?php if (function_exists('aura_marketing_render_repeater_group')) : ?>
        <?php
        aura_marketing_render_repeater_group(
            'aura-compare-matrix',
            __('Comparison Matrix', 'aura'),
            __('Define objective criteria and how each side performs.', 'aura'),
            isset($payload['matrix']) && is_array($payload['matrix']) ? $payload['matrix'] : array(),
            'aura_compare_matrix',
            array(
                array(
                    'key' => 'criterion',
                    'label' => __('Criterion', 'aura'),
                    'type' => 'text',
                    'placeholder' => __('Agent authority governance', 'aura'),
                ),
                array(
                    'key' => 'left',
                    'label' => __('Left Side', 'aura'),
                    'type' => 'textarea',
                    'placeholder' => __('How the left entity handles this criterion.', 'aura'),
                ),
                array(
                    'key' => 'right',
                    'label' => __('Right Side', 'aura'),
                    'type' => 'textarea',
                    'placeholder' => __('How the right entity handles this criterion.', 'aura'),
                ),
                array(
                    'key' => 'winner',
                    'label' => __('Winner / Takeaway', 'aura'),
                    'type' => 'text',
                    'placeholder' => __('Context dependent', 'aura'),
                ),
            ),
            __('Add Matrix Row', 'aura')
        );

        aura_marketing_render_repeater_group(
            'aura-compare-steps',
            __('Migration / Evaluation Steps', 'aura'),
            __('Add 3-6 practical steps teams can execute.', 'aura'),
            isset($payload['steps']) && is_array($payload['steps']) ? $payload['steps'] : array(),
            'aura_compare_steps',
            array(
                array(
                    'key' => 'title',
                    'label' => __('Step Title', 'aura'),
                    'type' => 'text',
                    'placeholder' => __('Inventory identity and signing dependencies', 'aura'),
                ),
                array(
                    'key' => 'text',
                    'label' => __('Step Detail', 'aura'),
                    'type' => 'textarea',
                    'placeholder' => __('Explain what to do and how to validate this step.', 'aura'),
                ),
            ),
            __('Add Step', 'aura')
        );

        aura_marketing_render_repeater_group(
            'aura-compare-faqs',
            __('FAQ Block', 'aura'),
            __('Used for both the visible FAQ section and FAQPage schema.', 'aura'),
            isset($payload['faqs']) && is_array($payload['faqs']) ? $payload['faqs'] : array(),
            'aura_compare_faqs',
            array(
                array(
                    'key' => 'question',
                    'label' => __('Question', 'aura'),
                    'type' => 'text',
                    'placeholder' => __('Is MPC better than multisig for AI agents?', 'aura'),
                ),
                array(
                    'key' => 'answer',
                    'label' => __('Answer', 'aura'),
                    'type' => 'textarea',
                    'placeholder' => __('Provide a concise and evidence-oriented answer.', 'aura'),
                ),
            ),
            __('Add FAQ', 'aura')
        );
        ?>
    <?php endif; ?>
    <?php
}

/**
 * Save compare structured-content meta box values.
 *
 * @param int $post_id Post ID.
 */
function aura_save_compare_meta_box($post_id) {
    $post_id = (int) $post_id;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['aura_compare_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash((string) $_POST['aura_compare_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'aura_compare_meta_save_' . $post_id)) {
        return;
    }

    $entity_left = isset($_POST['aura_compare_entity_left']) ? aura_compare_sanitize_field_value($_POST['aura_compare_entity_left'], 'text') : '';
    $entity_right = isset($_POST['aura_compare_entity_right']) ? aura_compare_sanitize_field_value($_POST['aura_compare_entity_right'], 'text') : '';
    $primary_question = isset($_POST['aura_compare_primary_question']) ? aura_compare_sanitize_field_value($_POST['aura_compare_primary_question'], 'text') : '';
    $frame = isset($_POST['aura_compare_frame']) ? aura_compare_sanitize_field_value($_POST['aura_compare_frame'], 'textarea') : '';
    $quick_verdict = isset($_POST['aura_compare_quick_verdict']) ? aura_compare_sanitize_field_value($_POST['aura_compare_quick_verdict'], 'textarea') : '';
    $recommended_for = isset($_POST['aura_compare_recommended_for']) ? aura_compare_sanitize_field_value($_POST['aura_compare_recommended_for'], 'textarea') : '';
    $not_ideal_for = isset($_POST['aura_compare_not_ideal_for']) ? aura_compare_sanitize_field_value($_POST['aura_compare_not_ideal_for'], 'textarea') : '';

    $matrix = aura_compare_collect_rows(
        isset($_POST['aura_compare_matrix']) ? $_POST['aura_compare_matrix'] : array(),
        array(
            'criterion' => 'text',
            'left' => 'textarea',
            'right' => 'textarea',
            'winner' => 'text',
        )
    );

    $steps = aura_compare_collect_rows(
        isset($_POST['aura_compare_steps']) ? $_POST['aura_compare_steps'] : array(),
        array(
            'title' => 'text',
            'text' => 'textarea',
        )
    );

    $faqs = aura_compare_collect_rows(
        isset($_POST['aura_compare_faqs']) ? $_POST['aura_compare_faqs'] : array(),
        array(
            'question' => 'text',
            'answer' => 'textarea',
        )
    );

    aura_compare_update_meta($post_id, '_aura_compare_entity_left', $entity_left);
    aura_compare_update_meta($post_id, '_aura_compare_entity_right', $entity_right);
    aura_compare_update_meta($post_id, '_aura_compare_primary_question', $primary_question);
    aura_compare_update_meta($post_id, '_aura_compare_frame', $frame);
    aura_compare_update_meta($post_id, '_aura_compare_quick_verdict', $quick_verdict);
    aura_compare_update_meta($post_id, '_aura_compare_recommended_for', $recommended_for);
    aura_compare_update_meta($post_id, '_aura_compare_not_ideal_for', $not_ideal_for);
    aura_compare_update_meta($post_id, '_aura_compare_matrix', $matrix);
    aura_compare_update_meta($post_id, '_aura_compare_steps', $steps);
    aura_compare_update_meta($post_id, '_aura_compare_faqs', $faqs);
}
add_action('save_post_aura_compare', 'aura_save_compare_meta_box', 10, 1);

/**
 * Build HTML list markup for seed content.
 *
 * @param array<int,string> $items Seed list items.
 * @param string            $tag List tag.
 * @return string
 */
function aura_get_learn_seed_html_list($items, $tag = 'ul') {
    $tag = ('ol' === strtolower((string) $tag)) ? 'ol' : 'ul';
    $rows = array();

    if (!is_array($items)) {
        return '';
    }

    foreach ($items as $item) {
        $label = trim((string) $item);
        if ('' === $label) {
            continue;
        }

        $rows[] = '<li>' . esc_html($label) . '</li>';
    }

    if (empty($rows)) {
        return '';
    }

    array_unshift($rows, '<' . $tag . '>');
    $rows[] = '</' . $tag . '>';

    return implode("\n", $rows);
}

/**
 * Canonical guide, research, and update seed defaults.
 *
 * @return array<string,array<string,array<string,mixed>>>
 */
function aura_get_learn_editorial_seed_map() {
    return array(
        'aura_guide' => array(
            'what-is-mpc' => array(
                'title' => 'What Is MPC for AI Agent Custody?',
                'excerpt' => 'Explain how threshold signing changes key exposure, recovery, and operational control assumptions for autonomous agents.',
                'summary' => 'This guide explains why Multi-Party Computation is the baseline custody primitive for autonomous agent systems that must move capital without ever reconstructing a full private key.',
                'question' => 'Which custody risks disappear with threshold signing, and which governance risks remain above the signing layer?',
                'inputs' => array(
                    'Document the chains, wallets, and agent workflows that need signatures.',
                    'List the human escalation paths for high-risk or off-policy actions.',
                    'Capture any latency targets that would force fast-path criteria.',
                ),
                'steps' => array(
                    'Map the current signing boundary: identify where keys exist today, who can approve, and where AI systems can trigger transactions.',
                    'Define the threshold model: choose node distribution, quorum assumptions, and recovery requirements before comparing vendors or protocols.',
                    'Separate cryptographic custody from policy enforcement: note that MPC protects key material, but it does not decide whether an intent should be approved.',
                    'Document fallback and recovery paths: include emergency pause, hardware-backed shares, and operator intervention routes.',
                ),
                'checks' => array(
                    'No architecture diagram shows a full private key being reconstructed or exported.',
                    'Each execution lane has a clear policy owner and escalation owner.',
                    'Latency claims are measured end-to-end, not just at signature generation.',
                ),
                'related' => array(
                    'MPC, Security & Fast-Path Execution',
                    'Intent-Evaluated MPC',
                    'Hardware-Hybrid Custody',
                ),
            ),
            'threshold-signatures' => array(
                'title' => 'Implementing Threshold Signatures for Agent Wallets',
                'excerpt' => 'Draft an implementation walkthrough for distributing key shares, selecting quorum rules, and wiring policy-aware signing into agent execution paths.',
                'summary' => 'Use this guide to turn threshold-signing theory into an implementation plan for production agent wallets.',
                'question' => 'How do you move from a single signing service to distributed threshold signing without breaking developer ergonomics or auditability?',
                'inputs' => array(
                    'Target wallet or account model by chain.',
                    'Expected signer or node topology and failure tolerance.',
                    'Policy engine inputs required before a signature is requested.',
                ),
                'steps' => array(
                    'Design the share distribution model and quorum assumptions.',
                    'Insert policy and fraud evaluation before the signature request is broadcast to the node set.',
                    'Instrument partial-signature coordination, retries, and failure logging.',
                    'Run staged benchmarks for full-path and degraded-node scenarios.',
                ),
                'checks' => array(
                    'Node compromise assumptions are explicit and tested.',
                    'Signature orchestration logs capture policy hash, intent hash, and broadcast result.',
                    'Disaster recovery does not weaken the threshold model.',
                ),
                'related' => array(
                    'MPC vs Multisig for AI Agents',
                    'Policy DSL',
                    'Fast-Path Execution',
                ),
            ),
            'prompt-injection-defenses' => array(
                'title' => 'Prompt Injection Defenses for Autonomous Agent Wallets',
                'excerpt' => 'Structure the pre-execution pipeline that validates agent intents before policy evaluation or signing begins.',
                'summary' => 'This guide frames Intent Sanitization as the security boundary that must operate before any policy or signing logic receives an agent-generated action.',
                'question' => 'Which structural, semantic, and contextual checks should block an adversarial or malformed intent before it reaches the wallet?',
                'inputs' => array(
                    'Agent intent sources and tools.',
                    'Normalization rules for addresses, spend amounts, and protocol identifiers.',
                    'Known escalation triggers and deny conditions.',
                ),
                'steps' => array(
                    'Normalize the raw intent into a canonical schema.',
                    'Verify identity, origin, and context before trusting the request.',
                    'Run semantic validation against allowed action types and account state.',
                    'Only hand sanitized payloads to policy evaluation and downstream signing services.',
                ),
                'checks' => array(
                    'Sanitization failures are logged with precise reject reasons.',
                    'No downstream signer can be called with raw model output.',
                    'Red-team prompts are part of regression testing.',
                ),
                'related' => array(
                    'Intent Sanitization',
                    'Agentic Policy & Authority Enforcement',
                    'Prompt Injection Attacks on AI Agents and Intent Sanitization Defenses',
                ),
            ),
            'safe-policy-rollouts' => array(
                'title' => 'Designing Safe Policy Rollouts for Autonomous Financial Agents',
                'excerpt' => 'Document how to ship new policy rules gradually, audit decision changes, and preserve rollback paths for governed agent execution.',
                'summary' => 'Use this guide to publish operational playbooks for policy changes so new agent permissions are introduced without widening blast radius.',
                'question' => 'How should teams stage policy updates when a rule change can affect spending limits, protocol allowlists, or escalation thresholds?',
                'inputs' => array(
                    'Existing policy versions and hash references.',
                    'Historical intent samples to replay.',
                    'Rollback criteria and approver ownership.',
                ),
                'steps' => array(
                    'Version each policy bundle and label the intended rollout cohort.',
                    'Replay historical intents against the proposed rules before production promotion.',
                    'Start with a narrow lane and compare approvals, denials, and escalations against the prior policy.',
                    'Promote the new policy only after audit and operator review confirm expected behavior.',
                ),
                'checks' => array(
                    'Every published policy has a rollback path.',
                    'Change logs show what limit or allowlist changed and why.',
                    'Operational owners can explain the difference between rejected and escalated intents.',
                ),
                'related' => array(
                    'Policy DSL',
                    'Agentic Policy & Authority Enforcement',
                    'DAO Treasury Automation',
                ),
            ),
        ),
        'aura_research' => array(
            'fast-path-mpc-benchmarks' => array(
                'title' => 'Benchmarking Fast-Path MPC for Sub-100ms Agent Execution',
                'excerpt' => 'Outline the latency, fallback, and safety measurements needed to evaluate fast-path MPC for routine autonomous actions.',
                'summary' => 'This research draft should quantify when fast-path execution improves operational responsiveness without weakening policy or fraud controls.',
                'question' => 'Under what workload, node topology, and policy conditions does fast-path MPC reliably stay below the target latency envelope?',
                'methodology' => array(
                    'Define comparable benchmarks for full MPC, fast-path MPC, and any alternative signing stack.',
                    'Measure end-to-end intent-to-broadcast latency rather than isolated signing time.',
                    'Track fallback rates when a request no longer qualifies for fast-path execution.',
                    'Record failure modes, retry behavior, and policy-decision latency under load.',
                ),
                'observations' => array(
                    'Latency results are only meaningful when policy evaluation and fraud checks are included.',
                    'Fast-path value depends on how often requests remain inside the low-risk lane.',
                    'Fallback behavior may matter more than peak performance for production operators.',
                ),
                'implications' => 'Use the final published version to explain where fast-path should be enabled, where standard MPC remains mandatory, and how operators should monitor both paths.',
                'next_questions' => array(
                    'Which workloads produce the highest safe fast-path hit rate?',
                    'How does node geography affect latency variance?',
                    'What telemetry should trigger automatic reclassification from fast-path to full-path?',
                ),
            ),
            'policy-evidence-chains' => array(
                'title' => 'Policy Evidence Chains for Autonomous Agent Audits',
                'excerpt' => 'Build a research note on the data model and audit artifacts needed to defend policy-controlled agent execution in regulated environments.',
                'summary' => 'This research note should define the evidence chain from sanitized intent to policy hash, fraud score, threshold signature, and final transaction record.',
                'question' => 'What minimum evidence set allows an auditor to reconstruct why an autonomous agent action was approved, escalated, or blocked?',
                'methodology' => array(
                    'Inventory every event emitted across the execution sequence.',
                    'Map each event to the compliance or audit question it answers.',
                    'Test whether an independent reviewer can rebuild the decision path from stored artifacts alone.',
                    'Identify retention, tamper-evidence, and redaction constraints.',
                ),
                'observations' => array(
                    'Logs that omit policy version or intent context create weak audit narratives.',
                    'Evidence quality depends on consistent identifiers across services.',
                    'Human escalations need the same traceability as machine-approved actions.',
                ),
                'implications' => 'The final article should help institutional buyers understand that compliance is an architectural layer, not a reporting overlay added after execution.',
                'next_questions' => array(
                    'Which fields belong in immutable logs versus analyst dashboards?',
                    'How should privacy constraints shape audit exports?',
                    'What evidence should be available in real time versus post-incident?',
                ),
            ),
            'rwa-agent-mandates' => array(
                'title' => 'Policy-Limited Agent Mandates for Tokenized RWA Portfolios',
                'excerpt' => 'Evaluate how institutional teams can grant bounded authority to agents managing tokenized assets without collapsing governance or compliance controls.',
                'summary' => 'This draft explores how tokenized asset operations differ from DeFi-native workflows when agent authority, valuation cadence, and reporting requirements must stay bounded.',
                'question' => 'How should institutions define agent mandates for tokenized asset portfolios so execution remains useful but tightly scoped?',
                'methodology' => array(
                    'Compare recurring portfolio operations against exceptional events that require human review.',
                    'Document mandate boundaries for spend, counterparties, valuation triggers, and time windows.',
                    'Stress-test the policy model against stale data, valuation shocks, and unavailable operators.',
                    'Map every mandate to required oversight artifacts and continuity procedures.',
                ),
                'observations' => array(
                    'RWA workflows usually require narrower authority than speculative trading systems.',
                    'Operational value comes from predictable recurring actions, not broad discretionary power.',
                    'Continuity and beneficiary controls matter more when assets represent off-chain obligations.',
                ),
                'implications' => 'Use the published piece to connect RWA operations with Hardware-Hybrid Custody, Inheritance Protocol, and enterprise policy approval paths.',
                'next_questions' => array(
                    'Which portfolio actions should never be delegated?',
                    'How frequently should mandates be re-approved?',
                    'What signals should force valuation-driven escalation?',
                ),
            ),
            'cross-jurisdiction-governance' => array(
                'title' => 'Cross-Jurisdiction Agent Governance Comparison Framework',
                'excerpt' => 'Compare how different regulatory environments shape policy controls, evidence requirements, and escalation models for autonomous agent deployments.',
                'summary' => 'This research note should compare how governance expectations shift across jurisdictions without reducing the product narrative to generic compliance slogans.',
                'question' => 'Which governance and audit controls stay constant across jurisdictions, and which ones require region-specific policy or evidence adaptations?',
                'methodology' => array(
                    'Choose a small set of target jurisdictions and list the operational obligations most relevant to autonomous financial agents.',
                    'Compare identity, reporting, incident-response, and delegation expectations.',
                    'Map common controls that can be standardized across regions.',
                    'Identify which policy fragments should remain local or institution-specific.',
                ),
                'observations' => array(
                    'The most portable controls are identity, policy versioning, and signed audit trails.',
                    'Local variation often appears in escalation timing and reporting detail.',
                    'A shared control plane still needs regional policy overlays.',
                ),
                'implications' => 'Publish the finished note as a decision aid for enterprise teams planning staged multi-region deployments.',
                'next_questions' => array(
                    'Which controls can be centralized safely?',
                    'Where do local teams need override authority?',
                    'How should cross-border evidence export be designed?',
                ),
            ),
        ),
        'aura_update' => array(
            'execution-visualization-and-control-surfaces' => array(
                'title' => 'Execution Visualization and Control-Surface Improvements',
                'excerpt' => 'Draft release note covering updates to the Happy Path narrative, operator-facing control explanations, and page-level supporting visuals.',
                'summary' => 'Use this update draft to summarize changes that make the execution sequence easier to understand across pillars, ecosystem content, and supporting pages.',
                'changes' => array(
                    'Refined the Happy Path sequence narrative so each enforcement step has a clearer role.',
                    'Improved the language around authority boundaries, policy decisions, and signing checkpoints.',
                    'Added supporting visual context for pages that explain execution flow or control surfaces.',
                ),
                'why' => 'This update matters because buyers need a crisp mental model of where intent validation ends and cryptographic authority enforcement begins.',
                'followups' => array(
                    'Add screenshots or diagrams once the final visual assets are approved.',
                    'Link to the pillar pages that explain the affected steps in more detail.',
                    'Replace placeholder phrasing with exact shipped changes and any measured improvements.',
                ),
                'inputs' => array(
                    'Release date',
                    'Screenshots or SVG diffs',
                    'Affected URLs',
                    'Any copy or UX metrics worth citing',
                ),
            ),
            'dataset-archive-and-knowledge-index' => array(
                'title' => 'Dataset Archive and Knowledge Index Progress',
                'excerpt' => 'Draft release note for the dataset archive, machine-readable knowledge files, and structured-data improvements supporting discovery.',
                'summary' => 'Use this update to document shipping progress around datasets, root-level machine-readable resources, and schema coverage.',
                'changes' => array(
                    'Prepared the dataset archive foundation for structured empirical resources.',
                    'Expanded machine-readable files such as llms.txt, ai.txt, and the knowledge index.',
                    'Aligned schema coverage so learn content types have clearer search-engine and AI-discovery signals.',
                ),
                'why' => 'This work improves how both human readers and retrieval systems understand the site\'s primary entities, supporting resources, and canonical references.',
                'followups' => array(
                    'Confirm live URLs and file contents before publication.',
                    'Add notes on any crawler or sitemap changes once deployed.',
                    'Reference the dataset archive or glossary entries that became newly available.',
                ),
                'inputs' => array(
                    'Deployment date',
                    'Live URLs',
                    'Sitemap or Search Console updates',
                    'Any validator screenshots',
                ),
            ),
            'publishing-workflows-and-editorial-scaffolding' => array(
                'title' => 'Publishing Workflows and Editorial Scaffolding',
                'excerpt' => 'Draft release note covering new publishing scaffolds for guides, research, and updates so content can be edited in wp-admin without custom metabox dependencies.',
                'summary' => 'Use this update to explain how the learn library publishing workflow became easier to manage through draft scaffolding and cleaner editorial defaults.',
                'changes' => array(
                    'Seeded draft entries for technical guides, research notes, and updates so editors can start from structured copy instead of empty screens.',
                    'Preserved the existing compare and pillar workflows while avoiding destructive changes to authored content.',
                    'Kept schema generation in theme code so editorial tooling stays separate from SEO plugin configuration.',
                ),
                'why' => 'This matters because the editorial bottleneck was not rendering or schema, it was the absence of a usable starting point inside wp-admin for certain CPTs.',
                'followups' => array(
                    'Replace the placeholder language with the exact titles that were published.',
                    'Note any taxonomy, internal-linking, or review-process changes that followed.',
                    'Add before-and-after screenshots of the edit experience if helpful.',
                ),
                'inputs' => array(
                    'Affected CPTs',
                    'Seeded draft list',
                    'Any editorial QA notes',
                    'Publication dates',
                ),
            ),
        ),
    );
}

/**
 * Build seed entries for a learn post type.
 *
 * @param string $post_type Post type slug.
 * @return array<int,array<string,string>>
 */
function aura_get_learn_editorial_seed_entries($post_type) {
    $post_type = sanitize_key((string) $post_type);
    $seed_map = aura_get_learn_editorial_seed_map();
    $entries = array();

    if (!isset($seed_map[$post_type]) || !is_array($seed_map[$post_type])) {
        return $entries;
    }

    foreach ($seed_map[$post_type] as $slug => $payload) {
        if (!is_array($payload)) {
            continue;
        }

        $title = isset($payload['title']) ? trim((string) $payload['title']) : '';
        if ('' === $title) {
            continue;
        }

        $entries[] = array(
            'slug' => sanitize_title((string) $slug),
            'title' => $title,
            'excerpt' => isset($payload['excerpt']) ? (string) $payload['excerpt'] : '',
        );
    }

    return $entries;
}

/**
 * Resolve seed payload for a learn post type and slug.
 *
 * @param string $post_type Post type slug.
 * @param string $slug Post slug.
 * @return array<string,mixed>
 */
function aura_get_learn_editorial_seed_payload($post_type, $slug) {
    $post_type = sanitize_key((string) $post_type);
    $slug = sanitize_title((string) $slug);
    $seed_map = aura_get_learn_editorial_seed_map();

    if (isset($seed_map[$post_type][$slug]) && is_array($seed_map[$post_type][$slug])) {
        return $seed_map[$post_type][$slug];
    }

    return array();
}

/**
 * Build editorial seed content for guides, research notes, and updates.
 *
 * @param string              $post_type Post type slug.
 * @param array<string,mixed> $entry Entry payload.
 * @return string
 */
function aura_get_learn_editorial_seed_content($post_type, $entry) {
    $slug = isset($entry['slug']) ? (string) $entry['slug'] : '';
    $payload = aura_get_learn_editorial_seed_payload($post_type, $slug);
    if (empty($payload)) {
        return '';
    }

    $summary = isset($payload['summary']) ? trim((string) $payload['summary']) : '';
    $question = isset($payload['question']) ? trim((string) $payload['question']) : '';
    $content = array();

    if ('' !== $summary) {
        $content[] = '<p>' . esc_html($summary) . '</p>';
    }

    switch ((string) $post_type) {
        case 'aura_guide':
            $content[] = '<p>Use this draft as the implementation version of the surrounding pillar material. Replace the placeholders below with environment-specific commands, screenshots, metrics, and internal links before publication.</p>';

            if ('' !== $question) {
                $content[] = '<h2>Operator Question</h2>';
                $content[] = '<p>' . esc_html($question) . '</p>';
            }

            $inputs_html = aura_get_learn_seed_html_list(isset($payload['inputs']) ? $payload['inputs'] : array(), 'ul');
            if ('' !== $inputs_html) {
                $content[] = '<h2>Recommended Inputs</h2>';
                $content[] = $inputs_html;
            }

            $steps_html = aura_get_learn_seed_html_list(isset($payload['steps']) ? $payload['steps'] : array(), 'ol');
            if ('' !== $steps_html) {
                $content[] = '<h2>Implementation Path</h2>';
                $content[] = $steps_html;
            }

            $checks_html = aura_get_learn_seed_html_list(isset($payload['checks']) ? $payload['checks'] : array(), 'ul');
            if ('' !== $checks_html) {
                $content[] = '<h2>Control Checks</h2>';
                $content[] = $checks_html;
            }

            $related_html = aura_get_learn_seed_html_list(isset($payload['related']) ? $payload['related'] : array(), 'ul');
            if ('' !== $related_html) {
                $content[] = '<h2>Related Reading</h2>';
                $content[] = $related_html;
            }
            break;

        case 'aura_research':
            $content[] = '<p>Use this research scaffold to add benchmark data, citations, methodology detail, and any charts or tables before publication.</p>';

            if ('' !== $question) {
                $content[] = '<h2>Research Question</h2>';
                $content[] = '<p>' . esc_html($question) . '</p>';
            }

            $methodology_html = aura_get_learn_seed_html_list(isset($payload['methodology']) ? $payload['methodology'] : array(), 'ul');
            if ('' !== $methodology_html) {
                $content[] = '<h2>Methodology Outline</h2>';
                $content[] = $methodology_html;
            }

            $observations_html = aura_get_learn_seed_html_list(isset($payload['observations']) ? $payload['observations'] : array(), 'ul');
            if ('' !== $observations_html) {
                $content[] = '<h2>Preliminary Observations</h2>';
                $content[] = $observations_html;
            }

            if (!empty($payload['implications'])) {
                $content[] = '<h2>Implications</h2>';
                $content[] = '<p>' . esc_html((string) $payload['implications']) . '</p>';
            }

            $next_questions_html = aura_get_learn_seed_html_list(isset($payload['next_questions']) ? $payload['next_questions'] : array(), 'ul');
            if ('' !== $next_questions_html) {
                $content[] = '<h2>Next Questions</h2>';
                $content[] = $next_questions_html;
            }
            break;

        case 'aura_update':
            $content[] = '<p>Use this draft to capture shipped work, scope changes, or milestone progress. Replace the placeholders with exact dates, screenshots, links, and operator-facing impact before publication.</p>';

            $changes_html = aura_get_learn_seed_html_list(isset($payload['changes']) ? $payload['changes'] : array(), 'ul');
            if ('' !== $changes_html) {
                $content[] = '<h2>What Changed</h2>';
                $content[] = $changes_html;
            }

            if (!empty($payload['why'])) {
                $content[] = '<h2>Why It Matters</h2>';
                $content[] = '<p>' . esc_html((string) $payload['why']) . '</p>';
            }

            $followups_html = aura_get_learn_seed_html_list(isset($payload['followups']) ? $payload['followups'] : array(), 'ul');
            if ('' !== $followups_html) {
                $content[] = '<h2>Editorial Follow-Ups</h2>';
                $content[] = $followups_html;
            }

            $inputs_html = aura_get_learn_seed_html_list(isset($payload['inputs']) ? $payload['inputs'] : array(), 'ul');
            if ('' !== $inputs_html) {
                $content[] = '<h2>Release Notes Inputs</h2>';
                $content[] = $inputs_html;
            }
            break;
    }

    return implode("\n", $content);
}

/**
 * Seed default learn-library drafts once.
 */
function aura_seed_learn_content() {
    if (!post_type_exists('aura_pillar') || !post_type_exists('aura_compare')) {
        return;
    }

    $seed_version = (int) get_option('aura_learn_seed_version', 0);
    if ($seed_version >= 5) {
        return;
    }

    $pillar_entries = array(
        array(
            'slug' => 'autonomous-ai-agents-on-chain-capital',
            'title' => 'Autonomous AI Agents & On-Chain Capital',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
        array(
            'slug' => 'agentic-policy-authority-enforcement',
            'title' => 'Agentic Policy & Authority Enforcement',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
        array(
            'slug' => 'dao-treasury-automation',
            'title' => 'DAO Treasury Automation',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
        array(
            'slug' => 'mpc-security-fast-path-execution',
            'title' => 'MPC, Security & Fast-Path Execution',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
        array(
            'slug' => 'ai-agent-compliance-regulatory',
            'title' => 'AI Agent Compliance & Regulatory',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
        array(
            'slug' => 'institutional-rwa-management',
            'title' => 'Institutional RWA Management',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
        array(
            'slug' => 'web2-web3-enterprise-bridge',
            'title' => 'Web2-Web3 Enterprise Bridge',
            'excerpt' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do.",
        ),
    );

    foreach ($pillar_entries as $entry) {
        $pillar_content = aura_get_pillar_seed_content($entry);
        $post_id = aura_seed_single_learn_entry('aura_pillar', $entry, $pillar_content);
        aura_seed_pillar_visual_meta($post_id, $entry);
    }

    $compare_entries = array(
        array(
            'slug' => 'deagentic-vs-privy',
            'title' => 'DeAgenticAI vs Privy',
            'excerpt' => 'Comparison page for teams evaluating purpose-built AI agent wallet infrastructure against Privy alternatives.',
        ),
        array(
            'slug' => 'deagentic-vs-dynamic',
            'title' => 'DeAgenticAI vs Dynamic.xyz',
            'excerpt' => 'Comparison page for teams evaluating vendor-independent infrastructure versus Dynamic.xyz alternatives.',
        ),
        array(
            'slug' => 'mpc-vs-multisig-for-ai-agents',
            'title' => 'MPC vs Multisig for AI Agents',
            'excerpt' => 'Technical comparison page for choosing custody and signing architecture for autonomous agent operations.',
        ),
        array(
            'slug' => 'deagentic-vs-turnkey',
            'title' => 'DeAgenticAI vs Turnkey',
            'excerpt' => 'Latency-focused comparison of TEE-based signing models versus policy-enforced fast-path MPC workflows.',
        ),
        array(
            'slug' => 'custodial-vs-non-custodial-agent-wallets',
            'title' => 'Custodial vs Non-Custodial Agent Wallets',
            'excerpt' => 'Decision framework for organizations choosing custody architecture for autonomous AI agent deployments.',
        ),
    );

    foreach ($compare_entries as $entry) {
        $compare_content = aura_get_compare_seed_content($entry);
        $post_id = aura_seed_single_learn_entry('aura_compare', $entry, $compare_content);
        aura_seed_compare_entry_meta($post_id, $entry);
    }

    foreach (array('aura_guide', 'aura_research', 'aura_update') as $editorial_post_type) {
        if (!post_type_exists($editorial_post_type)) {
            continue;
        }

        $editorial_entries = aura_get_learn_editorial_seed_entries($editorial_post_type);
        foreach ($editorial_entries as $entry) {
            $editorial_content = aura_get_learn_editorial_seed_content($editorial_post_type, $entry);
            aura_seed_single_learn_entry($editorial_post_type, $entry, $editorial_content);
        }
    }

    update_option('aura_learn_seed_version', 5, false);
}
add_action('init', 'aura_seed_learn_content', 30);

/**
 * Seed a single learn entry if missing.
 *
 * @param string $post_type Post type.
 * @param array  $entry Entry payload.
 * @param string $fallback_content Fallback content.
 * @return int
 */
function aura_seed_single_learn_entry($post_type, $entry, $fallback_content) {
    $post_type = sanitize_key((string) $post_type);
    $slug = isset($entry['slug']) ? sanitize_title($entry['slug']) : '';
    $title = isset($entry['title']) ? (string) $entry['title'] : '';
    $excerpt = isset($entry['excerpt']) ? (string) $entry['excerpt'] : '';

    if ('' === $post_type || '' === $slug || '' === $title) {
        return 0;
    }

    $existing = get_posts(array(
        'post_type' => $post_type,
        'post_status' => array('publish', 'draft', 'pending', 'future', 'private'),
        'name' => $slug,
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'suppress_filters' => true,
    ));

    if (!empty($existing)) {
        $post_id = (int) $existing[0];
        $post_obj = get_post($post_id);
        if ($post_obj instanceof WP_Post) {
            if ('' === trim((string) $post_obj->post_excerpt) && '' !== trim($excerpt)) {
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_excerpt' => $excerpt,
                ));
            }

            $content = trim((string) $post_obj->post_content);
            $legacy_compare_stub = '<p>Structure this page around objective criteria, implementation implications, and measurable tradeoffs. End with a practical decision framework.</p>';
            $legacy_pillar_stub = '<p>Use this pillar page as the authoritative hub for the topic cluster. Keep the semantic triple in the opening section and link to all relevant glossary entities.</p>';
            $should_refresh_content = ('' === $content);

            if (!$should_refresh_content && 'aura_compare' === $post_type) {
                $should_refresh_content = (trim($legacy_compare_stub) === $content);

                if (!$should_refresh_content) {
                    $legacy_compare_content = aura_get_compare_legacy_seed_content($slug, $title);
                    if ('' !== trim($legacy_compare_content) && trim($legacy_compare_content) === $content) {
                        $should_refresh_content = true;
                    }
                }

                if (!$should_refresh_content) {
                    $sample_appendix = aura_get_compare_seed_sample_appendix($slug);
                    if ('' !== trim($sample_appendix)) {
                        $base_compare_content = aura_get_compare_seed_content_from_payload(
                            aura_get_compare_seed_payload($slug, $title)
                        );
                        if ('' !== trim($base_compare_content) && trim($base_compare_content) === $content) {
                            $should_refresh_content = true;
                        }
                    }
                }
            }

            if (!$should_refresh_content && 'aura_pillar' === $post_type) {
                $should_refresh_content = (trim($legacy_pillar_stub) === $content);
            }

            if ($should_refresh_content && '' !== trim((string) $fallback_content)) {
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_content' => (string) $fallback_content,
                ));
            }
        }

        return $post_id;
    }

    $inserted = wp_insert_post(array(
        'post_type' => $post_type,
        'post_status' => 'draft',
        'post_title' => $title,
        'post_name' => $slug,
        'post_excerpt' => $excerpt,
        'post_content' => (string) $fallback_content,
    ), true);

    if (is_wp_error($inserted)) {
        return 0;
    }

    return (int) $inserted;
}

/**
 * Seed Learn hub page copy when empty.
 *
 * @param int $page_id Learn page ID.
 */
function aura_seed_learn_hub_content($page_id) {
    $page_id = (int) $page_id;
    if ($page_id <= 0) {
        return;
    }

    $post = get_post($page_id);
    if (!$post instanceof WP_Post || 'page' !== $post->post_type) {
        return;
    }

    $updates = array('ID' => $page_id);
    $has_update = false;

    if ('' === trim((string) $post->post_excerpt)) {
        $updates['post_excerpt'] = __('Technical resources, benchmark analysis, and implementation playbooks for autonomous agent infrastructure.', 'aura');
        $has_update = true;
    }

    if ('' === trim((string) $post->post_content)) {
        $updates['post_content'] = '<p>This hub connects pillar pages, implementation guides, research notes, and product updates so readers can navigate topic clusters and decision paths with clarity.</p>';
        $has_update = true;
    }

    if ($has_update) {
        wp_update_post($updates);
    }
}

/**
 * Output schema for Learn and Compare post types.
 */
function aura_output_learn_content_schema() {
    // Article-family schemas (Article, TechArticle, ScholarlyArticle, NewsArticle)
    // are now emitted by inc/schema.php (dea_pillar_schema, dea_guide_schema,
    // dea_research_schema, dea_compare_schema). Only FAQPage blocks remain here.
    if (!aura_should_output_theme_schema_tags()) {
        return;
    }

    if (!is_singular(aura_get_learn_post_types())) {
        return;
    }

    $post_id = (int) get_queried_object_id();
    if ($post_id <= 0) {
        return;
    }

    $post_type = get_post_type($post_id);

    if ('aura_compare' === $post_type) {
        $faq_entities = aura_get_compare_faq_schema_entities($post_id);
        if (!empty($faq_entities)) {
            $faq_schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faq_entities,
            );

            echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }

    if ('aura_pillar' === $post_type) {
        $pillar_faqs = aura_get_pillar_faq_payload($post_id);
        if (!empty($pillar_faqs)) {
            $faq_entities = array();
            foreach ($pillar_faqs as $faq) {
                if (!is_array($faq)) {
                    continue;
                }

                $question = isset($faq['question']) ? trim((string) $faq['question']) : '';
                $answer = isset($faq['answer']) ? trim((string) $faq['answer']) : '';
                if ('' === $question || '' === $answer) {
                    continue;
                }

                $faq_entities[] = array(
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $answer,
                    ),
                );
            }

            if (!empty($faq_entities)) {
                $faq_schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faq_entities,
                );

                echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
            }
        }
    }
}
add_action('wp_head', 'aura_output_learn_content_schema', 7);
