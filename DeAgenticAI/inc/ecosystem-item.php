<?php
/**
 * Ecosystem item master template helpers.
 *
 * @package Aura
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Catalog for ecosystem item routes/components.
 *
 * @return array<string,array{name:string,path:string,topic:string}>
 */
function aura_get_ecosystem_component_catalog() {
    return array(
        'mpc-wallet' => array(
            'name' => 'MPC Wallet Infrastructure',
            'path' => '/ecosystem/mpc-wallet',
            'topic' => 'mpc-wallet',
        ),
        'policy-engine' => array(
            'name' => 'Policy Engine',
            'path' => '/ecosystem/policy-engine',
            'topic' => 'policy-engine',
        ),
        'delegation' => array(
            'name' => 'Delegation & Session Authority',
            'path' => '/ecosystem/delegation',
            'topic' => 'delegation',
        ),
        'integrations' => array(
            'name' => 'MCP & Integration Layer',
            'path' => '/ecosystem/integrations',
            'topic' => 'integrations',
        ),
        'compliance' => array(
            'name' => 'Compliance & Audit Framework',
            'path' => '/ecosystem/compliance',
            'topic' => 'compliance',
        ),
    );
}

/**
 * Ensure canonical ecosystem component child pages exist and are publicly accessible.
 *
 * @param int $ecosystem_page_id Parent ecosystem page ID.
 */
function aura_seed_ecosystem_component_pages($ecosystem_page_id) {
    $ecosystem_page_id = (int) $ecosystem_page_id;
    if ($ecosystem_page_id <= 0) {
        return;
    }

    $ecosystem_page = get_post($ecosystem_page_id);
    if (!$ecosystem_page instanceof WP_Post || 'page' !== $ecosystem_page->post_type) {
        return;
    }

    $catalog = aura_get_ecosystem_component_catalog();
    if (empty($catalog)) {
        return;
    }

    $parent_slug = trim((string) $ecosystem_page->post_name);
    if ('' === $parent_slug) {
        return;
    }

    foreach ($catalog as $slug => $component) {
        $slug = sanitize_title((string) $slug);
        if ('' === $slug) {
            continue;
        }

        $title = isset($component['name']) ? trim((string) $component['name']) : '';
        if ('' === $title) {
            $title = ucwords(str_replace('-', ' ', $slug));
        }

        $child_path = $parent_slug . '/' . $slug;
        $existing_page = get_page_by_path($child_path);

        if (!$existing_page) {
            $page_id = wp_insert_post(
                array(
                    'post_title' => $title,
                    'post_name' => $slug,
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_parent' => $ecosystem_page_id,
                )
            );

            if ($page_id) {
                update_post_meta((int) $page_id, '_wp_page_template', 'page-ecosystem-item-acf.php');
            }

            continue;
        }

        $existing_id = (int) $existing_page->ID;
        $update_payload = array('ID' => $existing_id);
        $requires_update = false;

        if ((int) $existing_page->post_parent !== $ecosystem_page_id) {
            $update_payload['post_parent'] = $ecosystem_page_id;
            $requires_update = true;
        }

        if ('publish' !== (string) $existing_page->post_status) {
            $update_payload['post_status'] = 'publish';
            $requires_update = true;
        }

        if ($requires_update) {
            wp_update_post($update_payload);
        }

        $current_template = (string) get_post_meta($existing_id, '_wp_page_template', true);
        if ('page-ecosystem-item-acf.php' !== $current_template && 'page-ecosystem-item-elementor.php' !== $current_template) {
            update_post_meta($existing_id, '_wp_page_template', 'page-ecosystem-item-acf.php');
        }
    }
}

/**
 * Compat helper for array_is_list.
 *
 * @param mixed $value Value to check.
 * @return bool
 */
function aura_is_list_array($value) {
    if (!is_array($value)) {
        return false;
    }

    if (function_exists('array_is_list')) {
        return array_is_list($value);
    }

    $expected_key = 0;
    foreach (array_keys($value) as $key) {
        if ($key !== $expected_key) {
            return false;
        }
        $expected_key++;
    }

    return true;
}

/**
 * Recursive merge where list-arrays are replaced, not merged.
 *
 * @param array $defaults Defaults.
 * @param array $overrides Overrides.
 * @return array
 */
function aura_merge_assoc_recursive(array $defaults, array $overrides) {
    foreach ($overrides as $key => $override_value) {
        if (
            isset($defaults[$key]) &&
            is_array($defaults[$key]) &&
            is_array($override_value) &&
            !aura_is_list_array($defaults[$key]) &&
            !aura_is_list_array($override_value)
        ) {
            $defaults[$key] = aura_merge_assoc_recursive($defaults[$key], $override_value);
            continue;
        }

        $defaults[$key] = $override_value;
    }

    return $defaults;
}

/**
 * Build default links for ecosystem context block.
 *
 * @param string $current_slug Current item slug.
 * @return array<int,array{name:string,link:string}>
 */
function aura_get_ecosystem_context_links($current_slug) {
    $catalog = aura_get_ecosystem_component_catalog();
    $links = array();

    foreach ($catalog as $slug => $item) {
        if ($slug === $current_slug) {
            continue;
        }

        $links[] = array(
            'name' => $item['name'],
            'link' => $item['path'],
        );
    }

    return $links;
}

/**
 * Default data for ecosystem item page.
 *
 * @param string $slug Slug.
 * @param string $fallback_title Page title fallback.
 * @return array<string,mixed>
 */
function aura_get_ecosystem_item_default_data($slug, $fallback_title = '') {
    $catalog = aura_get_ecosystem_component_catalog();
    $name = isset($catalog[$slug]['name']) ? $catalog[$slug]['name'] : $fallback_title;
    $name = $name ? $name : __('Ecosystem Component', 'aura');
    $topic = isset($catalog[$slug]['topic']) ? $catalog[$slug]['topic'] : sanitize_title($slug);

    $default = array(
        'mode' => 'coming_soon',
        'topic' => $topic,
        'badge_text' => 'Ecosystem Component',
        'name' => $name,
        'one_sentence_explanation' => '',
        'definition' => '',
        'coming_soon_text' => 'This ecosystem component is currently under development. Detailed architectural specifications will be published soon.',
        'why_exists' => array(),
        'how_it_works' => array(
            'steps' => array(),
            'explanation' => '',
        ),
        'architecture' => array(
            'components' => array(),
            'trust_model' => array(),
        ),
        'security_properties' => array(),
        'comparison' => array(),
        'use_cases' => array(),
        'integration' => array(
            'model' => '',
            'compatibility' => array(),
            'capabilities' => array(),
        ),
        'related_content' => array(
            'heading' => 'Related Guides & Research',
            'description' => '',
            'guides' => array(),
            'comparisons' => array(),
            'glossary' => array(),
        ),
        'faq' => array(),
        'ecosystem_context' => array(
            'title' => 'Part of the DeAgenticAI Ecosystem',
            'description' => $name . ' integrates natively with the broader control plane infrastructure, working in sync with our Policy Engine, Delegation layer, and Audit framework.',
            'links' => aura_get_ecosystem_context_links($slug),
        ),
    );

    if ('mpc-wallet' === $slug) {
        $default = aura_merge_assoc_recursive(
            $default,
            array(
                'mode' => 'live',
                'topic' => 'mpc-wallet',
                'name' => 'MPC Wallet Infrastructure',
                'one_sentence_explanation' => 'Enterprise-grade distributed key management for autonomous agent wallets.',
                'definition' => 'MPC Wallet Infrastructure is a decentralized custody solution that uses Multi-Party Computation to shard private keys across a distributed network of nodes. It ensures that autonomous AI agents can execute on-chain transactions without ever having access to a complete private key, eliminating single points of failure and unauthorized signature risks.',
                'why_exists' => array(
                    array(
                        'label' => 'Market Assumption',
                        'content' => 'Traditional wallets assume a single human owner who can safely manage a mnemonic or private key in a secure environment.',
                        'icon' => 'shield',
                    ),
                    array(
                        'label' => 'Risk Vector',
                        'content' => 'AI agents require high-frequency signing, making local key storage a massive attack surface for extraction and unauthorized execution.',
                        'icon' => 'zap',
                    ),
                    array(
                        'label' => 'Capability Gap',
                        'content' => 'Standard wallets lack the ability to shard authority or enforce cryptographic constraints before a signature is even generated.',
                        'icon' => 'info',
                    ),
                    array(
                        'label' => 'Enforcement Layer',
                        'content' => 'By moving custody to a distributed MPC layer, enforcement can occur at the shard validation level rather than just the application layer.',
                        'icon' => 'lock',
                    ),
                ),
                'how_it_works' => array(
                    'steps' => array(
                        array('step' => 'Trigger', 'title' => 'Agent initiates a signing request via API or SDK.'),
                        array('step' => 'Evaluation', 'title' => 'The request is verified against pre-defined cryptographic policies.'),
                        array('step' => 'Authorization', 'title' => 'MPC nodes validate the request and authorize their specific key shards.'),
                        array('step' => 'Signature', 'title' => 'Nodes collaborate to generate a valid threshold signature (TSS).'),
                        array('step' => 'Audit', 'title' => 'The entire signing lifecycle is logged to an immutable audit trail.'),
                    ),
                    'explanation' => 'Our MPC infrastructure utilizes a (t, n) threshold signature scheme. When an agent requests a signature, the intent is first validated by our policy engine. Once cleared, a subset of distributed nodes (the threshold) perform a secure computation to produce a standard blockchain signature. At no point is the full private key reconstituted or exposed.',
                ),
                'architecture' => array(
                    'components' => array(
                        'Distributed Key Generation (DKG) Protocol',
                        'Threshold Signature Scheme (TSS) Engine',
                        'Node Orchestration Layer',
                        'Cryptographic Shard Management',
                        'Agent Signing SDK',
                    ),
                    'trust_model' => array(
                        'Authority is distributed across non-colluding node operators.',
                        'No single node or the agent itself ever holds the complete private key.',
                        'The system remains secure as long as the threshold of nodes is not compromised.',
                    ),
                ),
                'security_properties' => array(
                    'Distributed Control: Key shards are never co-located.',
                    'Zero-Knowledge Interaction: Nodes sign without seeing each other\'s shards.',
                    'Policy-Gated Access: Signatures require pre-validation by the policy engine.',
                    'Dynamic Refresh: Shards can be rotated without changing the public address.',
                ),
                'comparison' => array(
                    array('feature' => 'Key Storage', 'traditional' => 'Single Seed / Mnemonic', 'service' => 'Distributed Shards'),
                    array('feature' => 'Recovery Model', 'traditional' => 'Mnemonic Recovery', 'service' => 'Social/Distributed Resharding'),
                    array('feature' => 'Attack Surface', 'traditional' => 'Single Point of Failure', 'service' => 'Distributed Multi-Node'),
                    array('feature' => 'Enforcement', 'traditional' => 'Application Layer Only', 'service' => 'Protocol Layer Cryptography'),
                ),
                'use_cases' => array(
                    array(
                        'name' => 'Autonomous DeFi Trading',
                        'problem' => 'Local keys can be stolen if the agent\'s server is compromised.',
                        'solution' => 'Agent uses MPC shards; server breach yields zero usable key material.',
                        'outcome' => 'Secure, high-frequency autonomous trading.',
                    ),
                    array(
                        'name' => 'DAO Treasury Operations',
                        'problem' => 'Multi-sig overhead is too high for rapid AI-driven rebalancing.',
                        'solution' => 'MPC allows threshold signing at machine speed with DAO-enforced policies.',
                        'outcome' => 'Agile, secure treasury management.',
                    ),
                ),
                'integration' => array(
                    'model' => 'Available via our high-performance Signing SDK and REST API. Compatible with both cloud-hosted and self-hosted node configurations.',
                    'compatibility' => array(
                        'Ethereum',
                        'Solana',
                        'L2s (Arbitrum, Optimism, Base)',
                        'Polygon',
                        'Cosmos',
                    ),
                    'capabilities' => array(
                        array('label' => 'Developer SDK', 'icon' => 'code'),
                        array('label' => 'REST API', 'icon' => 'zap'),
                        array('label' => 'Cloud Hosted', 'icon' => 'cloud'),
                        array('label' => 'Enterprise On-Prem', 'icon' => 'building'),
                    ),
                ),
                'related_content' => array(
                    'heading' => 'Related Guides & Research',
                    'description' => 'The following resources provide deeper technical and conceptual background related to MPC Wallet Infrastructure.',
                    'guides' => array(
                        array(
                            'title' => 'What Is MPC?',
                            'link' => '/guides/what-is-mpc',
                            'summary' => 'A deep dive into Multi-Party Computation and its role in modern cryptography.',
                        ),
                        array(
                            'title' => 'Implementing Threshold Signatures',
                            'link' => '/guides/threshold-signatures',
                            'summary' => 'A technical guide to implementing TSS in agentic workflows.',
                        ),
                    ),
                    'comparisons' => array(
                        array(
                            'title' => 'MPC vs Multisig',
                            'link' => '/ecosystem/comparisons/mpc-vs-multisig',
                        ),
                        array(
                            'title' => 'Policy Wallet vs Smart Contract Wallet',
                            'link' => '/ecosystem/comparisons/policy-vs-scw',
                        ),
                    ),
                    'glossary' => array(
                        array('term' => 'MPC', 'link' => '/glossary/mpc'),
                        array('term' => 'Threshold Signature', 'link' => '/glossary/threshold-signature'),
                        array('term' => 'DKG', 'link' => '/glossary/dkg'),
                    ),
                ),
                'faq' => array(
                    array(
                        'q' => 'What is the DeAgenticAI Ecosystem?',
                        'a' => 'The DeAgenticAI Ecosystem is a decentralized control plane infrastructure designed for autonomous AI agents in Web3. It provides a modular framework combining distributed MPC custody, cryptographic policy enforcement, and policy-governed delegation with session-based authority scoping to ensure secure, verifiable, and programmable agent operations.',
                    ),
                    array(
                        'q' => 'How does MPC improve AI wallet security?',
                        'a' => 'Multi-Party Computation (MPC) enhances security by sharding private keys across multiple nodes. This ensures that no single entity or point of failure can access the full key, protecting autonomous agents from unauthorized signature attempts and external breaches.',
                    ),
                ),
                'ecosystem_context' => array(
                    'description' => 'MPC Wallet Infrastructure integrates natively with the broader control plane infrastructure, working in sync with our Policy Engine, Delegation layer, and Audit framework.',
                ),
            )
        );
    }

    return $default;
}

/**
 * Resolve a content link (internal/external).
 *
 * @param string $link Link value.
 * @return string
 */
function aura_resolve_content_link($link) {
    $link = trim((string) $link);

    if ('' === $link) {
        return '#';
    }

    if (preg_match('/^(https?:|mailto:|tel:|#)/i', $link)) {
        return $link;
    }

    if ('/' !== substr($link, 0, 1)) {
        $link = '/' . $link;
    }

    return home_url($link);
}

/**
 * Resolve a field value from ACF (if available) with post-meta fallback.
 *
 * @param int    $page_id    Page ID.
 * @param string $field_name Field name.
 * @return mixed
 */
function aura_get_ecosystem_field_value($page_id, $field_name) {
    $page_id = (int) $page_id;
    $field_name = (string) $field_name;

    if (function_exists('get_field')) {
        $acf_value = get_field($field_name, $page_id);
        if (null !== $acf_value && false !== $acf_value && '' !== $acf_value) {
            return $acf_value;
        }
    }

    return get_post_meta($page_id, $field_name, true);
}

/**
 * Parse multiline text into a list of non-empty values.
 *
 * @param mixed $raw_value Raw field value.
 * @return array<int,string>
 */
function aura_parse_multiline_values($raw_value) {
    if (!is_string($raw_value)) {
        return array();
    }

    $lines = preg_split('/\r\n|\r|\n/', $raw_value);
    if (!is_array($lines)) {
        return array();
    }

    $values = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ('' !== $line) {
            $values[] = $line;
        }
    }

    return $values;
}

/**
 * Parse line-separated rows where columns are pipe-delimited.
 *
 * @param mixed    $raw_value Raw field value.
 * @param string[] $keys      Associative keys per row.
 * @return array<int,array<string,string>>
 */
function aura_parse_pipe_rows($raw_value, $keys) {
    if (!is_string($raw_value) || !is_array($keys) || empty($keys)) {
        return array();
    }

    $lines = preg_split('/\r\n|\r|\n/', $raw_value);
    if (!is_array($lines)) {
        return array();
    }

    $rows = array();
    $column_count = count($keys);

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ('' === $line) {
            continue;
        }

        $columns = array_map(
            'trim',
            explode('|', $line, $column_count)
        );

        while (count($columns) < $column_count) {
            $columns[] = '';
        }

        $row = array();
        foreach ($keys as $index => $key) {
            $row[$key] = isset($columns[$index]) ? (string) $columns[$index] : '';
        }

        $is_empty = true;
        foreach ($row as $value) {
            if ('' !== trim((string) $value)) {
                $is_empty = false;
                break;
            }
        }

        if (!$is_empty) {
            $rows[] = $row;
        }
    }

    return $rows;
}

/**
 * Get full ecosystem item payload for a page.
 *
 * @param int $page_id Optional page ID.
 * @return array<string,mixed>
 */
function aura_get_ecosystem_item_payload($page_id = 0) {
    $page_id = $page_id ? (int) $page_id : get_the_ID();
    $post = get_post($page_id);

    if (!$post || 'page' !== $post->post_type) {
        return aura_get_ecosystem_item_default_data('', '');
    }

    $slug = (string) $post->post_name;
    $defaults = aura_get_ecosystem_item_default_data($slug, get_the_title($page_id));
    $default_context_links = aura_get_ecosystem_context_links($slug);
    if (empty($defaults['ecosystem_context']['links'])) {
        $defaults['ecosystem_context']['links'] = $default_context_links;
    }

    $payload = $defaults;

    $mode = sanitize_key((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_mode'));
    if (in_array($mode, array('live', 'coming_soon'), true)) {
        $payload['mode'] = $mode;
    }

    $topic = sanitize_title((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_topic_slug'));
    if ('' === $topic) {
        // Legacy fallback.
        $topic = sanitize_title((string) get_post_meta($page_id, '_aura_eco_topic', true));
    }
    if ('' !== $topic) {
        $payload['topic'] = $topic;
    }

    $badge_text = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_badge_text'));
    if ('' !== $badge_text) {
        $payload['badge_text'] = $badge_text;
    }

    $name = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_name'));
    if ('' !== $name) {
        $payload['name'] = $name;
    }

    $one_sentence = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_one_sentence'));
    if ('' !== $one_sentence) {
        $payload['one_sentence_explanation'] = $one_sentence;
    }

    $definition = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_definition'));
    if ('' !== $definition) {
        $payload['definition'] = $definition;
    }

    $coming_soon_text = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_coming_soon_text'));
    if ('' !== $coming_soon_text) {
        $payload['coming_soon_text'] = $coming_soon_text;
    }

    $why_exists_rows = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_why_exists_rows'),
        array('label', 'content', 'icon')
    );
    if (!empty($why_exists_rows)) {
        $payload['why_exists'] = $why_exists_rows;
    }

    $how_steps_rows = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_how_steps_rows'),
        array('step', 'title')
    );
    if (!empty($how_steps_rows)) {
        $payload['how_it_works']['steps'] = $how_steps_rows;
    }

    $how_explanation = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_how_explanation'));
    if ('' !== $how_explanation) {
        $payload['how_it_works']['explanation'] = $how_explanation;
    }

    $architecture_components = aura_parse_multiline_values(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_architecture_components')
    );
    if (!empty($architecture_components)) {
        $payload['architecture']['components'] = $architecture_components;
    }

    $trust_model = aura_parse_multiline_values(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_architecture_trust_model')
    );
    if (!empty($trust_model)) {
        $payload['architecture']['trust_model'] = $trust_model;
    }

    $security_properties = aura_parse_multiline_values(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_security_properties')
    );
    if (!empty($security_properties)) {
        $payload['security_properties'] = $security_properties;
    }

    $comparison_rows = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_comparison_rows'),
        array('feature', 'traditional', 'service')
    );
    if (!empty($comparison_rows)) {
        $payload['comparison'] = $comparison_rows;
    }

    $use_case_rows = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_use_cases_rows'),
        array('name', 'problem', 'solution', 'outcome')
    );
    if (!empty($use_case_rows)) {
        $payload['use_cases'] = $use_case_rows;
    }

    $integration_model = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_integration_model'));
    if ('' !== $integration_model) {
        $payload['integration']['model'] = $integration_model;
    }

    $integration_compatibility = aura_parse_multiline_values(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_integration_compatibility')
    );
    if (!empty($integration_compatibility)) {
        $payload['integration']['compatibility'] = $integration_compatibility;
    }

    $integration_capabilities = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_integration_capabilities_rows'),
        array('label', 'icon')
    );
    if (!empty($integration_capabilities)) {
        $payload['integration']['capabilities'] = $integration_capabilities;
    }

    $related_heading = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_related_heading'));
    if ('' !== $related_heading) {
        $payload['related_content']['heading'] = $related_heading;
    }

    $related_description = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_related_description'));
    if ('' !== $related_description) {
        $payload['related_content']['description'] = $related_description;
    }

    $related_guides = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_related_guides_rows'),
        array('title', 'link', 'summary')
    );
    if (!empty($related_guides)) {
        $payload['related_content']['guides'] = $related_guides;
    }

    $related_comparisons = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_related_comparisons_rows'),
        array('title', 'link')
    );
    if (!empty($related_comparisons)) {
        $payload['related_content']['comparisons'] = $related_comparisons;
    }

    $related_glossary = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_related_glossary_rows'),
        array('term', 'link')
    );
    if (!empty($related_glossary)) {
        $payload['related_content']['glossary'] = $related_glossary;
    }

    $faq_rows = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_faq_rows'),
        array('q', 'a')
    );
    if (!empty($faq_rows)) {
        $payload['faq'] = $faq_rows;
    }

    $context_title = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_context_title'));
    if ('' !== $context_title) {
        $payload['ecosystem_context']['title'] = $context_title;
    }

    $context_description = trim((string) aura_get_ecosystem_field_value($page_id, 'aura_eco_context_description'));
    if ('' !== $context_description) {
        $payload['ecosystem_context']['description'] = $context_description;
    }

    $context_links = aura_parse_pipe_rows(
        aura_get_ecosystem_field_value($page_id, 'aura_eco_context_links_rows'),
        array('name', 'link')
    );
    if (!empty($context_links)) {
        $payload['ecosystem_context']['links'] = $context_links;
    }

    if (empty($payload['name'])) {
        $payload['name'] = get_the_title($page_id);
    }

    if (empty($payload['ecosystem_context']['links'])) {
        $payload['ecosystem_context']['links'] = $default_context_links;
    }

    return $payload;
}

/**
 * Get manually selected related post IDs for ecosystem page.
 *
 * @param int $page_id Page ID.
 * @return int[]
 */
function aura_get_ecosystem_item_manual_related_post_ids($page_id) {
    $raw_csv = aura_get_ecosystem_field_value((int) $page_id, 'aura_eco_manual_related_post_ids');
    if (is_string($raw_csv) && '' !== trim($raw_csv)) {
        $parts = preg_split('/[\s,]+/', trim($raw_csv));
        if (is_array($parts)) {
            $ids_from_csv = array_map('absint', $parts);
            $ids_from_csv = array_filter(
                $ids_from_csv,
                function ($post_id) {
                    return $post_id > 0;
                }
            );
            if (!empty($ids_from_csv)) {
                return array_values(array_unique($ids_from_csv));
            }
        }
    }

    // Legacy fallback from previous custom metabox.
    $stored_ids = get_post_meta((int) $page_id, '_aura_eco_manual_related_posts', true);
    if (!is_array($stored_ids)) {
        return array();
    }

    $stored_ids = array_map('absint', $stored_ids);
    $stored_ids = array_filter(
        $stored_ids,
        function ($post_id) {
            return $post_id > 0;
        }
    );

    return array_values(array_unique($stored_ids));
}

/**
 * Query related posts for an ecosystem topic.
 *
 * @param string $topic_slug Topic slug.
 * @param int    $limit      Number of posts.
 * @return WP_Post[]
 */
function aura_get_ecosystem_topic_posts($topic_slug, $limit = 3) {
    $topic_slug = sanitize_title((string) $topic_slug);
    $limit = max(1, (int) $limit);

    if ('' === $topic_slug) {
        return array();
    }

    return get_posts(
        array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'ecosystem_topic',
                    'field' => 'slug',
                    'terms' => array($topic_slug),
                ),
            ),
        )
    );
}

/**
 * Resolve related posts for ecosystem item page.
 *
 * Prefer manual selection from page settings, then fallback to topic-based query.
 *
 * @param int    $page_id    Page ID.
 * @param string $topic_slug Topic slug for fallback.
 * @param int    $limit      Number of posts for fallback query.
 * @return WP_Post[]
 */
function aura_get_ecosystem_item_related_posts($page_id, $topic_slug = '', $limit = 3) {
    $manual_post_ids = aura_get_ecosystem_item_manual_related_post_ids($page_id);
    if (!empty($manual_post_ids)) {
        $manual_posts = get_posts(
            array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => count($manual_post_ids),
                'post__in' => $manual_post_ids,
                'orderby' => 'post__in',
            )
        );

        if (!empty($manual_posts)) {
            return $manual_posts;
        }
    }

    return aura_get_ecosystem_topic_posts($topic_slug, $limit);
}

/**
 * Register ecosystem topic taxonomy for posts.
 */
function aura_register_ecosystem_topic_taxonomy() {
    register_taxonomy(
        'ecosystem_topic',
        array('post'),
        array(
            'labels' => array(
                'name' => __('Ecosystem Topics', 'aura'),
                'singular_name' => __('Ecosystem Topic', 'aura'),
                'search_items' => __('Search Ecosystem Topics', 'aura'),
                'all_items' => __('All Ecosystem Topics', 'aura'),
                'edit_item' => __('Edit Ecosystem Topic', 'aura'),
                'update_item' => __('Update Ecosystem Topic', 'aura'),
                'add_new_item' => __('Add New Ecosystem Topic', 'aura'),
                'new_item_name' => __('New Ecosystem Topic', 'aura'),
                'menu_name' => __('Ecosystem Topics', 'aura'),
            ),
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'hierarchical' => false,
            'rewrite' => array(
                'slug' => 'ecosystem-topic',
                'with_front' => false,
            ),
        )
    );
}
add_action('init', 'aura_register_ecosystem_topic_taxonomy');

/**
 * Register ACF Free field group for ecosystem item pages.
 */
function aura_register_ecosystem_acf_field_group() {
    if (!function_exists('acf_add_local_field_group')) {
        return false;
    }

    acf_add_local_field_group(
        array(
            'key' => 'group_aura_ecosystem_item',
            'title' => 'Ecosystem Item (ACF Free)',
            'fields' => array(
                array(
                    'key' => 'field_aura_eco_mode',
                    'label' => 'Mode',
                    'name' => 'aura_eco_mode',
                    'type' => 'select',
                    'choices' => array(
                        'live' => 'Live',
                        'coming_soon' => 'Coming Soon',
                    ),
                    'default_value' => 'live',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_aura_eco_topic_slug',
                    'label' => 'Topic Slug',
                    'name' => 'aura_eco_topic_slug',
                    'type' => 'text',
                    'instructions' => 'Used for related-post taxonomy fallback (example: mpc-wallet).',
                ),
                array(
                    'key' => 'field_aura_eco_manual_related_post_ids',
                    'label' => 'Manual Related Post IDs',
                    'name' => 'aura_eco_manual_related_post_ids',
                    'type' => 'text',
                    'instructions' => 'Optional comma-separated blog post IDs (example: 12,34,56).',
                ),
                array(
                    'key' => 'field_aura_eco_badge_text',
                    'label' => 'Badge Text',
                    'name' => 'aura_eco_badge_text',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_aura_eco_name',
                    'label' => 'Component Name',
                    'name' => 'aura_eco_name',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_aura_eco_one_sentence',
                    'label' => 'One Sentence Explanation',
                    'name' => 'aura_eco_one_sentence',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_aura_eco_definition',
                    'label' => 'Definition',
                    'name' => 'aura_eco_definition',
                    'type' => 'textarea',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_coming_soon_text',
                    'label' => 'Coming Soon Text',
                    'name' => 'aura_eco_coming_soon_text',
                    'type' => 'textarea',
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_aura_eco_why_exists_rows',
                    'label' => 'Why Exists Rows',
                    'name' => 'aura_eco_why_exists_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Label|Content|IconKey (shield/zap/info/lock)',
                    'rows' => 6,
                ),
                array(
                    'key' => 'field_aura_eco_how_steps_rows',
                    'label' => 'How It Works Steps',
                    'name' => 'aura_eco_how_steps_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: StepName|StepDescription',
                    'rows' => 6,
                ),
                array(
                    'key' => 'field_aura_eco_how_explanation',
                    'label' => 'How It Works Explanation',
                    'name' => 'aura_eco_how_explanation',
                    'type' => 'textarea',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_architecture_components',
                    'label' => 'Architecture Components',
                    'name' => 'aura_eco_architecture_components',
                    'type' => 'textarea',
                    'instructions' => 'One component per line.',
                    'rows' => 5,
                ),
                array(
                    'key' => 'field_aura_eco_architecture_trust_model',
                    'label' => 'Architecture Trust Model',
                    'name' => 'aura_eco_architecture_trust_model',
                    'type' => 'textarea',
                    'instructions' => 'One trust-model point per line.',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_security_properties',
                    'label' => 'Security Properties',
                    'name' => 'aura_eco_security_properties',
                    'type' => 'textarea',
                    'instructions' => 'One property per line.',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_comparison_rows',
                    'label' => 'Comparison Rows',
                    'name' => 'aura_eco_comparison_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Feature|Traditional|Service',
                    'rows' => 6,
                ),
                array(
                    'key' => 'field_aura_eco_use_cases_rows',
                    'label' => 'Use Cases',
                    'name' => 'aura_eco_use_cases_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Name|Problem|Solution|Outcome',
                    'rows' => 6,
                ),
                array(
                    'key' => 'field_aura_eco_integration_model',
                    'label' => 'Integration Model',
                    'name' => 'aura_eco_integration_model',
                    'type' => 'textarea',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_integration_compatibility',
                    'label' => 'Integration Compatibility',
                    'name' => 'aura_eco_integration_compatibility',
                    'type' => 'textarea',
                    'instructions' => 'One chain/platform per line.',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_integration_capabilities_rows',
                    'label' => 'Integration Capabilities',
                    'name' => 'aura_eco_integration_capabilities_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Label|IconKey (code/zap/cloud/building)',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_related_heading',
                    'label' => 'Related Content Heading',
                    'name' => 'aura_eco_related_heading',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_aura_eco_related_description',
                    'label' => 'Related Content Description',
                    'name' => 'aura_eco_related_description',
                    'type' => 'textarea',
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_aura_eco_related_guides_rows',
                    'label' => 'Related Guides',
                    'name' => 'aura_eco_related_guides_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Title|Link|Summary',
                    'rows' => 6,
                ),
                array(
                    'key' => 'field_aura_eco_related_comparisons_rows',
                    'label' => 'Related Comparisons',
                    'name' => 'aura_eco_related_comparisons_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Title|Link',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_related_glossary_rows',
                    'label' => 'Related Glossary',
                    'name' => 'aura_eco_related_glossary_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Term|Link',
                    'rows' => 4,
                ),
                array(
                    'key' => 'field_aura_eco_faq_rows',
                    'label' => 'FAQ Rows',
                    'name' => 'aura_eco_faq_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: Question|Answer',
                    'rows' => 6,
                ),
                array(
                    'key' => 'field_aura_eco_context_title',
                    'label' => 'Context Title',
                    'name' => 'aura_eco_context_title',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_aura_eco_context_description',
                    'label' => 'Context Description',
                    'name' => 'aura_eco_context_description',
                    'type' => 'textarea',
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_aura_eco_context_links_rows',
                    'label' => 'Context Links',
                    'name' => 'aura_eco_context_links_rows',
                    'type' => 'textarea',
                    'instructions' => 'One row per line: LinkText|LinkURL',
                    'rows' => 5,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'page-ecosystem-item-acf.php',
                    ),
                ),
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'page-ecosystem-item-elementor.php',
                    ),
                ),
            ),
            'position' => 'normal',
            'style' => 'default',
            'active' => true,
        )
    );
}
add_action('acf/init', 'aura_register_ecosystem_acf_field_group');

/**
 * Get ACF field name -> field key map for ecosystem group.
 *
 * @return array<string,string>
 */
function aura_get_ecosystem_acf_field_key_map() {
    return array(
        'aura_eco_mode' => 'field_aura_eco_mode',
        'aura_eco_topic_slug' => 'field_aura_eco_topic_slug',
        'aura_eco_manual_related_post_ids' => 'field_aura_eco_manual_related_post_ids',
        'aura_eco_badge_text' => 'field_aura_eco_badge_text',
        'aura_eco_name' => 'field_aura_eco_name',
        'aura_eco_one_sentence' => 'field_aura_eco_one_sentence',
        'aura_eco_definition' => 'field_aura_eco_definition',
        'aura_eco_coming_soon_text' => 'field_aura_eco_coming_soon_text',
        'aura_eco_why_exists_rows' => 'field_aura_eco_why_exists_rows',
        'aura_eco_how_steps_rows' => 'field_aura_eco_how_steps_rows',
        'aura_eco_how_explanation' => 'field_aura_eco_how_explanation',
        'aura_eco_architecture_components' => 'field_aura_eco_architecture_components',
        'aura_eco_architecture_trust_model' => 'field_aura_eco_architecture_trust_model',
        'aura_eco_security_properties' => 'field_aura_eco_security_properties',
        'aura_eco_comparison_rows' => 'field_aura_eco_comparison_rows',
        'aura_eco_use_cases_rows' => 'field_aura_eco_use_cases_rows',
        'aura_eco_integration_model' => 'field_aura_eco_integration_model',
        'aura_eco_integration_compatibility' => 'field_aura_eco_integration_compatibility',
        'aura_eco_integration_capabilities_rows' => 'field_aura_eco_integration_capabilities_rows',
        'aura_eco_related_heading' => 'field_aura_eco_related_heading',
        'aura_eco_related_description' => 'field_aura_eco_related_description',
        'aura_eco_related_guides_rows' => 'field_aura_eco_related_guides_rows',
        'aura_eco_related_comparisons_rows' => 'field_aura_eco_related_comparisons_rows',
        'aura_eco_related_glossary_rows' => 'field_aura_eco_related_glossary_rows',
        'aura_eco_faq_rows' => 'field_aura_eco_faq_rows',
        'aura_eco_context_title' => 'field_aura_eco_context_title',
        'aura_eco_context_description' => 'field_aura_eco_context_description',
        'aura_eco_context_links_rows' => 'field_aura_eco_context_links_rows',
    );
}

/**
 * Check if migration value is empty.
 *
 * @param mixed $value Value.
 * @return bool
 */
function aura_eco_is_empty_migration_value($value) {
    if (is_array($value)) {
        return empty($value);
    }

    if (null === $value || false === $value) {
        return true;
    }

    return '' === trim((string) $value);
}

/**
 * Convert scalar/list values into newline-separated string.
 *
 * @param mixed $values Values.
 * @return string
 */
function aura_eco_serialize_lines($values) {
    if (!is_array($values)) {
        return '';
    }

    $lines = array();
    foreach ($values as $value) {
        $line = trim((string) $value);
        if ('' !== $line) {
            $lines[] = $line;
        }
    }

    return implode("\n", $lines);
}

/**
 * Convert structured rows into pipe-delimited multiline string.
 *
 * @param mixed    $rows Row array.
 * @param string[] $keys Keys to map.
 * @return string
 */
function aura_eco_serialize_pipe_rows($rows, $keys) {
    if (!is_array($rows) || empty($keys)) {
        return '';
    }

    $lines = array();
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $columns = array();
        $has_value = false;
        foreach ($keys as $key) {
            $value = isset($row[$key]) ? trim((string) $row[$key]) : '';
            if ('' !== $value) {
                $has_value = true;
            }
            $columns[] = $value;
        }

        if ($has_value) {
            $lines[] = implode('|', $columns);
        }
    }

    return implode("\n", $lines);
}

/**
 * Extract ACF-field-compatible values from legacy JSON override payload.
 *
 * @param string $json Legacy JSON string.
 * @return array<string,string>
 */
function aura_eco_extract_values_from_legacy_json($json) {
    $json = trim((string) $json);
    if ('' === $json) {
        return array();
    }

    $decoded = json_decode($json, true);
    if (!is_array($decoded)) {
        return array();
    }

    $values = array();

    if (isset($decoded['mode'])) {
        $mode = sanitize_key((string) $decoded['mode']);
        if (in_array($mode, array('live', 'coming_soon'), true)) {
            $values['aura_eco_mode'] = $mode;
        }
    }

    if (isset($decoded['topic'])) {
        $topic = sanitize_title((string) $decoded['topic']);
        if ('' !== $topic) {
            $values['aura_eco_topic_slug'] = $topic;
        }
    }

    $simple_map = array(
        'badge_text' => 'aura_eco_badge_text',
        'name' => 'aura_eco_name',
        'one_sentence_explanation' => 'aura_eco_one_sentence',
        'definition' => 'aura_eco_definition',
        'coming_soon_text' => 'aura_eco_coming_soon_text',
    );
    foreach ($simple_map as $legacy_key => $target_field) {
        if (isset($decoded[$legacy_key])) {
            $value = trim((string) $decoded[$legacy_key]);
            if ('' !== $value) {
                $values[$target_field] = $value;
            }
        }
    }

    if (isset($decoded['why_exists'])) {
        $serialized = aura_eco_serialize_pipe_rows($decoded['why_exists'], array('label', 'content', 'icon'));
        if ('' !== $serialized) {
            $values['aura_eco_why_exists_rows'] = $serialized;
        }
    }

    if (isset($decoded['how_it_works']) && is_array($decoded['how_it_works'])) {
        if (isset($decoded['how_it_works']['steps'])) {
            $serialized = aura_eco_serialize_pipe_rows($decoded['how_it_works']['steps'], array('step', 'title'));
            if ('' !== $serialized) {
                $values['aura_eco_how_steps_rows'] = $serialized;
            }
        }
        if (isset($decoded['how_it_works']['explanation'])) {
            $explanation = trim((string) $decoded['how_it_works']['explanation']);
            if ('' !== $explanation) {
                $values['aura_eco_how_explanation'] = $explanation;
            }
        }
    }

    if (isset($decoded['architecture']) && is_array($decoded['architecture'])) {
        $components = isset($decoded['architecture']['components']) ? aura_eco_serialize_lines($decoded['architecture']['components']) : '';
        if ('' !== $components) {
            $values['aura_eco_architecture_components'] = $components;
        }

        $trust_model = isset($decoded['architecture']['trust_model']) ? aura_eco_serialize_lines($decoded['architecture']['trust_model']) : '';
        if ('' !== $trust_model) {
            $values['aura_eco_architecture_trust_model'] = $trust_model;
        }
    }

    if (isset($decoded['security_properties'])) {
        $serialized = aura_eco_serialize_lines($decoded['security_properties']);
        if ('' !== $serialized) {
            $values['aura_eco_security_properties'] = $serialized;
        }
    }

    if (isset($decoded['comparison'])) {
        $serialized = aura_eco_serialize_pipe_rows($decoded['comparison'], array('feature', 'traditional', 'service'));
        if ('' !== $serialized) {
            $values['aura_eco_comparison_rows'] = $serialized;
        }
    }

    if (isset($decoded['use_cases'])) {
        $serialized = aura_eco_serialize_pipe_rows($decoded['use_cases'], array('name', 'problem', 'solution', 'outcome'));
        if ('' !== $serialized) {
            $values['aura_eco_use_cases_rows'] = $serialized;
        }
    }

    if (isset($decoded['integration']) && is_array($decoded['integration'])) {
        if (isset($decoded['integration']['model'])) {
            $model = trim((string) $decoded['integration']['model']);
            if ('' !== $model) {
                $values['aura_eco_integration_model'] = $model;
            }
        }

        if (isset($decoded['integration']['compatibility'])) {
            $serialized = aura_eco_serialize_lines($decoded['integration']['compatibility']);
            if ('' !== $serialized) {
                $values['aura_eco_integration_compatibility'] = $serialized;
            }
        }

        if (isset($decoded['integration']['capabilities'])) {
            $serialized = aura_eco_serialize_pipe_rows($decoded['integration']['capabilities'], array('label', 'icon'));
            if ('' !== $serialized) {
                $values['aura_eco_integration_capabilities_rows'] = $serialized;
            }
        }
    }

    if (isset($decoded['related_content']) && is_array($decoded['related_content'])) {
        if (isset($decoded['related_content']['heading'])) {
            $heading = trim((string) $decoded['related_content']['heading']);
            if ('' !== $heading) {
                $values['aura_eco_related_heading'] = $heading;
            }
        }

        if (isset($decoded['related_content']['description'])) {
            $description = trim((string) $decoded['related_content']['description']);
            if ('' !== $description) {
                $values['aura_eco_related_description'] = $description;
            }
        }

        if (isset($decoded['related_content']['guides'])) {
            $serialized = aura_eco_serialize_pipe_rows($decoded['related_content']['guides'], array('title', 'link', 'summary'));
            if ('' !== $serialized) {
                $values['aura_eco_related_guides_rows'] = $serialized;
            }
        }

        if (isset($decoded['related_content']['comparisons'])) {
            $serialized = aura_eco_serialize_pipe_rows($decoded['related_content']['comparisons'], array('title', 'link'));
            if ('' !== $serialized) {
                $values['aura_eco_related_comparisons_rows'] = $serialized;
            }
        }

        if (isset($decoded['related_content']['glossary'])) {
            $serialized = aura_eco_serialize_pipe_rows($decoded['related_content']['glossary'], array('term', 'link'));
            if ('' !== $serialized) {
                $values['aura_eco_related_glossary_rows'] = $serialized;
            }
        }
    }

    if (isset($decoded['faq'])) {
        $serialized = aura_eco_serialize_pipe_rows($decoded['faq'], array('q', 'a'));
        if ('' !== $serialized) {
            $values['aura_eco_faq_rows'] = $serialized;
        }
    }

    if (isset($decoded['ecosystem_context']) && is_array($decoded['ecosystem_context'])) {
        if (isset($decoded['ecosystem_context']['title'])) {
            $title = trim((string) $decoded['ecosystem_context']['title']);
            if ('' !== $title) {
                $values['aura_eco_context_title'] = $title;
            }
        }

        if (isset($decoded['ecosystem_context']['description'])) {
            $description = trim((string) $decoded['ecosystem_context']['description']);
            if ('' !== $description) {
                $values['aura_eco_context_description'] = $description;
            }
        }

        if (isset($decoded['ecosystem_context']['links'])) {
            $serialized = aura_eco_serialize_pipe_rows($decoded['ecosystem_context']['links'], array('name', 'link'));
            if ('' !== $serialized) {
                $values['aura_eco_context_links_rows'] = $serialized;
            }
        }
    }

    return $values;
}

/**
 * Read legacy meta as an array.
 *
 * @param int    $page_id  Page ID.
 * @param string $meta_key Meta key.
 * @return array
 */
function aura_eco_get_legacy_meta_array($page_id, $meta_key) {
    $raw_value = get_post_meta((int) $page_id, (string) $meta_key, true);

    if (is_array($raw_value)) {
        return $raw_value;
    }

    if (!is_string($raw_value)) {
        return array();
    }

    $raw_value = trim($raw_value);
    if ('' === $raw_value) {
        return array();
    }

    $decoded = json_decode($raw_value, true);
    if (is_array($decoded)) {
        return $decoded;
    }

    return array();
}

/**
 * Build migration values for a page from legacy ecosystem meta.
 *
 * @param int $page_id Page ID.
 * @return array<string,string>
 */
function aura_eco_build_legacy_values_for_page($page_id) {
    $page_id = (int) $page_id;
    $values = array();

    $json_values = aura_eco_extract_values_from_legacy_json(
        (string) get_post_meta($page_id, '_aura_eco_data_json', true)
    );

    if (!empty($json_values)) {
        $values = $json_values;
    }

    $legacy_simple_map = array(
        '_aura_eco_mode' => 'aura_eco_mode',
        '_aura_eco_topic' => 'aura_eco_topic_slug',
        '_aura_eco_badge_text' => 'aura_eco_badge_text',
        '_aura_eco_name' => 'aura_eco_name',
        '_aura_eco_one_sentence' => 'aura_eco_one_sentence',
        '_aura_eco_definition' => 'aura_eco_definition',
        '_aura_eco_coming_soon_text' => 'aura_eco_coming_soon_text',
        '_aura_eco_how_explanation' => 'aura_eco_how_explanation',
        '_aura_eco_integration_model' => 'aura_eco_integration_model',
        '_aura_eco_related_heading' => 'aura_eco_related_heading',
        '_aura_eco_related_description' => 'aura_eco_related_description',
        '_aura_eco_context_title' => 'aura_eco_context_title',
        '_aura_eco_context_description' => 'aura_eco_context_description',
    );

    foreach ($legacy_simple_map as $legacy_key => $target_key) {
        $raw_value = get_post_meta($page_id, $legacy_key, true);
        if (aura_eco_is_empty_migration_value($raw_value)) {
            continue;
        }

        $value = trim((string) $raw_value);
        if ('aura_eco_mode' === $target_key) {
            $value = sanitize_key($value);
            if (!in_array($value, array('live', 'coming_soon'), true)) {
                continue;
            }
        } elseif ('aura_eco_topic_slug' === $target_key) {
            $value = sanitize_title($value);
            if ('' === $value) {
                continue;
            }
        }

        $values[$target_key] = $value;
    }

    $legacy_multiline_map = array(
        '_aura_eco_architecture_components' => 'aura_eco_architecture_components',
        '_aura_eco_architecture_trust_model' => 'aura_eco_architecture_trust_model',
        '_aura_eco_security_properties' => 'aura_eco_security_properties',
        '_aura_eco_integration_compatibility' => 'aura_eco_integration_compatibility',
    );

    foreach ($legacy_multiline_map as $legacy_key => $target_key) {
        $rows = aura_eco_get_legacy_meta_array($page_id, $legacy_key);
        if (empty($rows)) {
            continue;
        }

        $serialized_rows = aura_eco_serialize_lines($rows);
        if ('' !== $serialized_rows) {
            $values[$target_key] = $serialized_rows;
        }
    }

    $legacy_pipe_map = array(
        '_aura_eco_why_exists' => array(
            'target' => 'aura_eco_why_exists_rows',
            'keys' => array('label', 'content', 'icon'),
        ),
        '_aura_eco_how_steps' => array(
            'target' => 'aura_eco_how_steps_rows',
            'keys' => array('step', 'title'),
        ),
        '_aura_eco_comparison' => array(
            'target' => 'aura_eco_comparison_rows',
            'keys' => array('feature', 'traditional', 'service'),
        ),
        '_aura_eco_use_cases' => array(
            'target' => 'aura_eco_use_cases_rows',
            'keys' => array('name', 'problem', 'solution', 'outcome'),
        ),
        '_aura_eco_integration_capabilities' => array(
            'target' => 'aura_eco_integration_capabilities_rows',
            'keys' => array('label', 'icon'),
        ),
        '_aura_eco_related_guides' => array(
            'target' => 'aura_eco_related_guides_rows',
            'keys' => array('title', 'link', 'summary'),
        ),
        '_aura_eco_related_comparisons' => array(
            'target' => 'aura_eco_related_comparisons_rows',
            'keys' => array('title', 'link'),
        ),
        '_aura_eco_related_glossary' => array(
            'target' => 'aura_eco_related_glossary_rows',
            'keys' => array('term', 'link'),
        ),
        '_aura_eco_faq' => array(
            'target' => 'aura_eco_faq_rows',
            'keys' => array('q', 'a'),
        ),
        '_aura_eco_context_links' => array(
            'target' => 'aura_eco_context_links_rows',
            'keys' => array('name', 'link'),
        ),
    );

    foreach ($legacy_pipe_map as $legacy_key => $config) {
        if (!isset($config['target'], $config['keys']) || !is_array($config['keys'])) {
            continue;
        }

        $rows = aura_eco_get_legacy_meta_array($page_id, $legacy_key);
        if (empty($rows)) {
            continue;
        }

        $serialized_rows = aura_eco_serialize_pipe_rows($rows, $config['keys']);
        if ('' !== $serialized_rows) {
            $values[$config['target']] = $serialized_rows;
        }
    }

    $legacy_related_ids = get_post_meta($page_id, '_aura_eco_manual_related_posts', true);
    if (is_array($legacy_related_ids) && !empty($legacy_related_ids)) {
        $legacy_related_ids = array_map('absint', $legacy_related_ids);
        $legacy_related_ids = array_filter(
            $legacy_related_ids,
            function ($post_id) {
                return $post_id > 0;
            }
        );

        if (!empty($legacy_related_ids)) {
            $values['aura_eco_manual_related_post_ids'] = implode(',', array_values(array_unique($legacy_related_ids)));
        }
    }

    return $values;
}

/**
 * Set migrated value to ACF field (or fallback post-meta) if target is empty.
 *
 * @param int    $page_id    Page ID.
 * @param string $field_name Field name.
 * @param string $value      Migrated value.
 * @return bool
 */
function aura_eco_migrate_set_field_value($page_id, $field_name, $value) {
    $page_id = (int) $page_id;
    $field_name = (string) $field_name;
    $value = (string) $value;

    if (aura_eco_is_empty_migration_value($value)) {
        return false;
    }

    $existing_value = get_post_meta($page_id, $field_name, true);
    if (!aura_eco_is_empty_migration_value($existing_value)) {
        return false;
    }

    $field_key_map = aura_get_ecosystem_acf_field_key_map();
    if (function_exists('update_field') && isset($field_key_map[$field_name])) {
        $updated = update_field($field_key_map[$field_name], $value, $page_id);
        return (bool) $updated;
    }

    return (bool) update_post_meta($page_id, $field_name, $value);
}

/**
 * Run one-time migration of legacy ecosystem meta to new ACF field names.
 *
 * @return array<string,int>
 */
function aura_run_ecosystem_acf_legacy_migration() {
    $stats = array(
        'pages_scanned' => 0,
        'pages_migrated' => 0,
        'fields_migrated' => 0,
    );

    $page_ids = get_posts(
        array(
            'post_type' => 'page',
            'post_status' => array('publish', 'draft', 'private'),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
        )
    );

    if (empty($page_ids)) {
        return $stats;
    }

    foreach ($page_ids as $page_id) {
        $stats['pages_scanned']++;
        $legacy_values = aura_eco_build_legacy_values_for_page((int) $page_id);
        if (empty($legacy_values)) {
            continue;
        }

        $migrated_for_page = 0;
        foreach ($legacy_values as $field_name => $value) {
            if (aura_eco_migrate_set_field_value((int) $page_id, $field_name, $value)) {
                $migrated_for_page++;
                $stats['fields_migrated']++;
            }
        }

        if ($migrated_for_page > 0) {
            $stats['pages_migrated']++;
        }
    }

    return $stats;
}

/**
 * One-time auto migration entrypoint.
 */
function aura_maybe_run_ecosystem_acf_legacy_migration() {
    if (!is_admin() || wp_doing_ajax() || !current_user_can('manage_options')) {
        return;
    }

    $force_rerun = isset($_GET['aura_force_eco_acf_migration']) && '1' === sanitize_text_field(wp_unslash($_GET['aura_force_eco_acf_migration']));
    $already_migrated = get_option('aura_eco_acf_legacy_migrated_v1', false);
    if (!$force_rerun && false !== $already_migrated) {
        return;
    }

    $stats = aura_run_ecosystem_acf_legacy_migration();
    set_transient('aura_eco_acf_legacy_migration_stats', $stats, 60);
    update_option(
        'aura_eco_acf_legacy_migrated_v1',
        array(
            'completed_at' => current_time('mysql'),
            'stats' => $stats,
        ),
        false
    );
}
add_action('admin_init', 'aura_maybe_run_ecosystem_acf_legacy_migration');

/**
 * Display one-time admin notice after migration run.
 */
function aura_render_ecosystem_acf_migration_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $stats = get_transient('aura_eco_acf_legacy_migration_stats');
    if (!is_array($stats)) {
        return;
    }

    delete_transient('aura_eco_acf_legacy_migration_stats');
    ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <?php
            echo esc_html(
                sprintf(
                    'Ecosystem ACF migration completed. Pages scanned: %1$d, pages migrated: %2$d, fields migrated: %3$d.',
                    isset($stats['pages_scanned']) ? (int) $stats['pages_scanned'] : 0,
                    isset($stats['pages_migrated']) ? (int) $stats['pages_migrated'] : 0,
                    isset($stats['fields_migrated']) ? (int) $stats['fields_migrated'] : 0
                )
            );
            ?>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'aura_render_ecosystem_acf_migration_notice');
