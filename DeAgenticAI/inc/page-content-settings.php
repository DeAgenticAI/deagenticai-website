<?php
/**
 * Structured settings for static marketing pages.
 *
 * @package Aura
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Merge a stored repeater array with defaults.
 *
 * @param mixed $stored_items   Raw stored items.
 * @param array $default_items  Default items.
 * @return array<int,array<string,string>>
 */
function aura_merge_page_content_items( $stored_items, array $default_items ) {
    $stored_items = is_array( $stored_items ) ? $stored_items : array();
    $merged       = array();

    foreach ( $default_items as $index => $default_item ) {
        $candidate = isset( $stored_items[ $index ] ) && is_array( $stored_items[ $index ] )
            ? $stored_items[ $index ]
            : array();

        $merged[] = wp_parse_args( $candidate, $default_item );
    }

    return $merged;
}

/**
 * Sanitize a repeater array against a default item schema.
 *
 * @param mixed $input_items    Raw submitted items.
 * @param array $default_items  Default items.
 * @param array $textarea_keys  Keys that should be sanitized as textareas.
 * @param array $url_keys       Keys that should be sanitized as URLs.
 * @return array<int,array<string,string>>
 */
function aura_sanitize_page_content_items( $input_items, array $default_items, array $textarea_keys = array(), array $url_keys = array() ) {
    $input_items = is_array( $input_items ) ? $input_items : array();
    $sanitized   = array();

    foreach ( $default_items as $index => $default_item ) {
        $source = isset( $input_items[ $index ] ) && is_array( $input_items[ $index ] )
            ? $input_items[ $index ]
            : array();

        $item = array();
        foreach ( $default_item as $key => $default_value ) {
            $raw = isset( $source[ $key ] ) ? wp_unslash( (string) $source[ $key ] ) : '';

            if ( in_array( $key, $url_keys, true ) ) {
                $item[ $key ] = esc_url_raw( $raw );
                continue;
            }

            if ( in_array( $key, $textarea_keys, true ) ) {
                $item[ $key ] = sanitize_textarea_field( $raw );
                continue;
            }

            $item[ $key ] = sanitize_text_field( $raw );
        }

        $sanitized[] = $item;
    }

    return $sanitized;
}

/**
 * Default copy for the About page.
 *
 * @return array<string,mixed>
 */
function aura_get_about_page_defaults() {
    $hero_badge = function_exists( 'aura_get_content' )
        ? aura_get_content( 'about_badge', 'About DeAgenticAI' )
        : 'About DeAgenticAI';
    $hero_title = function_exists( 'aura_get_content' )
        ? aura_get_content( 'about_title', 'We Built the Missing Layer Between AI Agents and On-Chain Authority' )
        : 'We Built the Missing Layer Between AI Agents and On-Chain Authority';
    $hero_lead = function_exists( 'aura_get_content' )
        ? aura_get_content( 'about_lead', 'Autonomous AI agents are already operating on-chain. They are executing treasury strategies, interacting with DeFi protocols, and beginning to represent institutional interests in governance systems. The infrastructure they are running on - private key management, multisig governance, rule-based automation - was designed for human actors. It breaks down under every assumption that AI introduces. DeAgenticAI exists to close that gap at the infrastructure layer.' )
        : 'Autonomous AI agents are already operating on-chain. They are executing treasury strategies, interacting with DeFi protocols, and beginning to represent institutional interests in governance systems. The infrastructure they are running on - private key management, multisig governance, rule-based automation - was designed for human actors. It breaks down under every assumption that AI introduces. DeAgenticAI exists to close that gap at the infrastructure layer.';
    $contact_title = function_exists( 'aura_get_content' )
        ? aura_get_content( 'about_contact_title', 'Work With Us' )
        : 'Work With Us';
    $contact_intro = function_exists( 'aura_get_content' )
        ? aura_get_content( 'about_contact_intro', 'DeAgenticAI is currently working with a select group of design partners - DAOs, Web3 funds, and RWA platform operators - who need governed autonomous execution before it is available at general access. If your organisation is evaluating infrastructure for AI agent operations, we want to hear from you.' )
        : 'DeAgenticAI is currently working with a select group of design partners - DAOs, Web3 funds, and RWA platform operators - who need governed autonomous execution before it is available at general access. If your organisation is evaluating infrastructure for AI agent operations, we want to hear from you.';
    $contact_button = function_exists( 'aura_get_content' )
        ? aura_get_content( 'about_contact_button_label', 'Send Transmission' )
        : 'Send Transmission';

    return array(
        'hero_badge'                    => $hero_badge,
        'hero_title'                    => $hero_title,
        'hero_lead'                     => $hero_lead,
        'mission_title'                 => 'Our Mission',
        'mission_body'                  => 'To make autonomous intelligence safe to own and move capital.',
        'mission_supporting'            => "We do this by separating an agent's capability from its authority and enforcing that authority cryptographically - so that AI agents can participate in Web3 and enterprise financial operations responsibly, transparently, and at scale.",
        'vision_title'                  => 'Our Vision',
        'vision_body_primary'           => 'A world in which autonomous agents are first-class participants in both decentralised and traditional financial systems - where DAOs operate without governance latency, where institutional asset managers automate complex portfolio management without compromising compliance, and where AI agents negotiate, pay, and collaborate with each other over open protocols.',
        'vision_body_secondary'         => 'Achieving that requires infrastructure that today\'s ecosystem lacks: a universal Agentic Control Plane that any agent, platform, or organisation can integrate without surrendering oversight or auditability.',
        'vision_emphasis'               => 'Authority should not be inferred from possession. It must be explicitly defined, enforced, and provable.',
        'thesis_label'                  => 'Why We Exist',
        'thesis_title'                  => 'Founding Thesis',
        'thesis_body_primary'           => 'The fundamental premise of Web3 security - that authority derives from possession of a private key - was designed around human actors who make deliberate, slow, and accountable decisions. An AI agent inverts all three of those assumptions. Agents are probabilistic rather than deterministic. They operate at speeds that make human review impossible in real time. And they can be compromised through prompt injection - a threat class that has no analog in human behaviour.',
        'thesis_emphasis'               => 'Giving an AI agent a private key is not a security posture. It is an abdication of one.',
        'thesis_body_secondary'         => 'This is not a risk to be managed at the application layer or the smart contract layer. It is a structural gap in infrastructure - a missing primitive that must exist between the AI agent and the execution environment. That primitive is the Agentic Control Plane.',
        'focus_label'                   => 'The Architecture',
        'focus_title'                   => "What We're Building",
        'focus_intro'                   => 'Five interoperable control layers define how autonomous agents can execute safely in adversarial financial environments.',
        'focus_items'                   => array(
            array(
                'title'       => 'Intent Sanitization',
                'description' => 'Every incoming intent is treated as potentially adversarially crafted and evaluated for structural integrity, source authenticity, semantic coherence, and context manipulation before it is allowed to enter the enforcement pipeline, making it the most critical security boundary in the architecture and the only component specifically designed to block the threat class that makes AI agents fundamentally different from human signers.',
            ),
            array(
                'title'       => 'Policy-First Enforcement',
                'description' => 'No action is authorised without explicit policy validation, with policies expressed in a declarative Domain-Specific Language, evaluated deterministically at the signing layer, and versioned so every historical governance context can be reconstructed.',
            ),
            array(
                'title'       => 'Behavioural Fraud Detection',
                'description' => 'Static policy evaluates whether an action is permitted, while fraud detection evaluates whether it is consistent with the agent\'s declared purpose, historical behaviour, and current portfolio state to catch structurally valid but semantically anomalous actions.',
            ),
            array(
                'title'       => 'MPC Distributed Execution',
                'description' => 'Key shares are distributed across geographically separated signing nodes using threshold signing protocols, no individual node ever holds the complete private key, and each signing node independently verifies the policy authorisation hash before contributing its partial signature, a cryptographic requirement that cannot be bypassed by compromising any single component including the orchestrator.',
            ),
            array(
                'title'       => 'Audit and Compliance Infrastructure',
                'description' => 'Every decision and signature in the control plane is recorded as a cryptographically signed, hash-chained, tamper-evident log entry formatted for MiCA regulatory reporting, DORA operational resilience requirements, and Travel Rule data exchange, making compliance a first-class architectural layer rather than a reporting overlay.',
            ),
        ),
        'visual_labels'                 => array(
            array( 'label' => 'Intent Guard' ),
            array( 'label' => 'Policy DSL' ),
            array( 'label' => 'MPC Quorum' ),
            array( 'label' => 'Audit Trail' ),
        ),
        'team_label'                    => 'The Team',
        'team_title'                    => 'Founding Story',
        'team_body_primary'             => 'DeAgenticAI was founded by [Name] and [Name] in [year], after [one sentence on the specific problem or moment that made the gap obvious].',
        'team_body_secondary'           => '[Founder 1 name] brings [relevant background - e.g. background in MPC cryptography, institutional custody, distributed systems]. [Founder 2 name] brings [relevant background - e.g. background in DeFi protocol design, enterprise compliance, AI infrastructure].',
        'team_body_tertiary'            => 'The company is [location / remote / distributed], building in public and operating under the principle that infrastructure this critical should be verifiable, not trusted.',
        'team_note'                     => 'Once real founder details are confirmed, this section can be finalized in full.',
        'contact_title'                 => $contact_title,
        'contact_intro'                 => $contact_intro,
        'contact_button_label'          => $contact_button,
        'contact_success_message'       => 'Message sent successfully. Our team will contact you shortly.',
        'contact_recaptcha_message'     => 'Security verification failed. Please retry and submit again.',
        'contact_error_message'         => 'Failed to send your message. Please try again.',
        'contact_name_label'            => 'Full Name',
        'contact_name_placeholder'      => 'John Doe',
        'contact_email_label'           => 'Email Address',
        'contact_email_placeholder'     => 'john@company.com',
        'contact_company_label'         => 'Company / Project',
        'contact_company_placeholder'   => 'DeAgentic AI Systems',
        'contact_message_label'         => 'Message',
        'contact_message_placeholder'   => 'Tell us about your architectural needs...',
    );
}

/**
 * Get the current About page settings.
 *
 * @return array<string,mixed>
 */
function aura_get_about_page_settings() {
    static $cache = null;
    if ( null !== $cache ) {
        return $cache;
    }

    $defaults = aura_get_about_page_defaults();
    $stored   = get_option( 'aura_about_page', array() );
    $stored   = is_array( $stored ) ? $stored : array();
    $settings = array();

    foreach ( $defaults as $key => $default_value ) {
        if ( is_array( $default_value ) ) {
            $settings[ $key ] = aura_merge_page_content_items(
                isset( $stored[ $key ] ) ? $stored[ $key ] : array(),
                $default_value
            );
            continue;
        }

        $settings[ $key ] = array_key_exists( $key, $stored )
            ? (string) $stored[ $key ]
            : $default_value;
    }

    $cache = $settings;
    return $cache;
}

/**
 * Read a single About page setting.
 *
 * @param string $key     Setting key.
 * @param string $default Fallback value.
 * @return string
 */
function aura_get_about_page_content( $key, $default = '' ) {
    $settings = aura_get_about_page_settings();

    if ( isset( $settings[ $key ] ) && ! is_array( $settings[ $key ] ) && '' !== (string) $settings[ $key ] ) {
        return (string) $settings[ $key ];
    }

    return $default;
}

/**
 * Sanitize About page settings.
 *
 * @param mixed $input Raw POST payload.
 * @return array<string,mixed>
 */
function aura_sanitize_about_page_settings( $input ) {
    $input    = is_array( $input ) ? $input : array();
    $defaults = aura_get_about_page_defaults();

    $text_fields = array(
        'hero_badge',
        'hero_title',
        'mission_title',
        'vision_title',
        'thesis_label',
        'thesis_title',
        'focus_label',
        'focus_title',
        'team_label',
        'team_title',
        'contact_title',
        'contact_button_label',
        'contact_name_label',
        'contact_name_placeholder',
        'contact_email_label',
        'contact_email_placeholder',
        'contact_company_label',
        'contact_company_placeholder',
        'contact_message_label',
        'contact_message_placeholder',
    );
    $textarea_fields = array(
        'hero_lead',
        'mission_body',
        'mission_supporting',
        'vision_body_primary',
        'vision_body_secondary',
        'vision_emphasis',
        'thesis_body_primary',
        'thesis_emphasis',
        'thesis_body_secondary',
        'focus_intro',
        'team_body_primary',
        'team_body_secondary',
        'team_body_tertiary',
        'team_note',
        'contact_intro',
        'contact_success_message',
        'contact_recaptcha_message',
        'contact_error_message',
    );

    $sanitized = array();

    foreach ( $text_fields as $key ) {
        $sanitized[ $key ] = isset( $input[ $key ] )
            ? sanitize_text_field( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    foreach ( $textarea_fields as $key ) {
        $sanitized[ $key ] = isset( $input[ $key ] )
            ? sanitize_textarea_field( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    $sanitized['focus_items'] = aura_sanitize_page_content_items(
        isset( $input['focus_items'] ) ? $input['focus_items'] : array(),
        $defaults['focus_items'],
        array( 'description' )
    );
    $sanitized['visual_labels'] = aura_sanitize_page_content_items(
        isset( $input['visual_labels'] ) ? $input['visual_labels'] : array(),
        $defaults['visual_labels']
    );

    return $sanitized;
}

/**
 * Default copy for the Ecosystem page.
 *
 * @return array<string,mixed>
 */
function aura_get_ecosystem_page_defaults() {
    return array(
        'hero_badge'                    => 'Platform Thesis',
        'hero_title_prefix'             => 'The DeAgenticAI',
        'hero_title_accent'             => 'Ecosystem',
        'hero_subtitle'                 => 'Infrastructure for Autonomous Agents Operating in Web3',
        'hero_lead'                     => 'The DeAgenticAI Ecosystem is the Agentic Control Plane - a multi-layer infrastructure architecture that enforces cryptographic policy over autonomous AI agent authority, separating what an agent can do from what it is authorized to do, in Web3 and enterprise financial environments. It is not a wallet, not an automation framework, and not a governance tool in isolation. It is the missing primitive between an AI agent and on-chain execution: the layer that makes it possible to grant an agent a precisely scoped, cryptographically enforced, fully auditable slice of authority over on-chain and off-chain resources, while ensuring that any human, DAO, or regulatory authority retains meaningful oversight and override capability at all times.',
        'hero_cta_label'                => 'Explore the Architecture',
        'problem_title'                 => 'Why Agentic AI Needs New Infrastructure',
        'problem_points'                => array(
            array(
                'title'       => 'Private Keys Are a Broken Abstraction for AI',
                'description' => 'The foundational security model of every blockchain - authority derived from key possession - was designed for human actors who make deliberate, accountable decisions. An AI agent inverts every assumption: it is probabilistic, not deterministic; it operates at speeds that make human review impossible; and it can be compromised through prompt injection, a threat that has no analog in human behaviour.',
            ),
            array(
                'title'       => 'Existing Infrastructure Cannot Bridge the Gap',
                'description' => 'Multisig wallets require human coordination at every signing step. Smart contracts enforce spending limits but cannot evaluate the semantic intent behind a proposed action. Automation frameworks either execute blindly or trust the agent completely. Neither constitutes a controlled, governable system.',
            ),
            array(
                'title'       => 'The Enforcement Must Be Cryptographic, Not Advisory',
                'description' => 'API-layer policy controls can be bypassed if the platform is compromised. A smart contract policy can be bypassed if the contract has a vulnerability. Only a cryptographic enforcement layer - one in which the policy decision is a mathematical prerequisite for the signature - provides a guarantee that cannot be circumvented by infrastructure-level compromise.',
            ),
            array(
                'title'       => 'Multi-Agent Systems Require Authority Propagation',
                'description' => 'As orchestrator-subagent architectures become standard, the policy constraints, spending limits, and governance requirements defined at the top of an agent hierarchy must propagate cryptographically down through every delegation step. Without this, multi-agent systems become an attack surface rather than a capability.',
            ),
        ),
        'architecture_title'            => 'The Agentic Control Plane Architecture',
        'status_line_primary'           => 'System Modularity Initialized',
        'status_line_secondary'         => 'Harmonizing Architectural Shards',
        'status_line_tertiary'          => 'Unified Control Plane Active',
        'control_plane_labels'          => array(
            array( 'label' => 'MPC Shards' ),
            array( 'label' => 'Policy Engine' ),
            array( 'label' => 'Delegation' ),
            array( 'label' => 'Audit Log' ),
            array( 'label' => 'MCP Protocol' ),
            array( 'label' => 'Session Auth' ),
        ),
        'architecture_steps'            => array(
            array(
                'title'       => 'Intent Generation',
                'description' => 'Autonomous agent generates a transaction intent based on its current objective, operational context, and the authority scope defined in its active policy credentials.',
            ),
            array(
                'title'       => 'Intent Sanitization',
                'description' => 'Every intent is treated as potentially adversarially crafted. The sanitization pipeline validates structural integrity, authenticates the source against the agent\'s W3C DID-anchored identity, evaluates semantic coherence against the agent\'s declared context, and checks for known prompt injection patterns and upstream data manipulation. This is the most critical security boundary in the architecture - cryptographic enforcement downstream cannot compensate for a compromised input.',
            ),
            array(
                'title'       => 'Policy Evaluation',
                'description' => 'The sanitized intent is evaluated deterministically against the full stack of applicable Policy DSL rules - spending limits, protocol allowlists, temporal constraints, risk thresholds, and governance escalation triggers. The policy engine returns an explicit authorization or denial. No signing occurs without an explicit authorization. There is no execution pathway that bypasses this step.',
            ),
            array(
                'title'       => 'Fraud Detection',
                'description' => 'Behavioural analytics, contract risk scoring, and intent consistency verification evaluate whether the proposed action is consistent with the agent\'s declared strategy, historical behaviour, and current portfolio state. Risk scores map to a graduated escalation model - from autonomous execution at low risk, to elevated MPC signing threshold at moderate risk, to mandatory human or DAO approval at high risk.',
            ),
            array(
                'title'       => 'MPC Threshold Signing',
                'description' => 'MPC signing nodes independently verify the policy authorization hash before contributing partial signatures. No individual node ever holds the complete private key. A valid transaction signature requires the cooperation of at least t-of-n geographically distributed nodes - a cryptographic requirement that cannot be bypassed by compromising any single component, including the orchestrator.',
            ),
            array(
                'title'       => 'Chain Broadcast',
                'description' => 'The threshold-signed transaction is broadcast to the target chain with confirmation monitoring. Every event in the sequence - intent submission, sanitization outcome, policy decision, risk score, signing node participation, broadcast, and confirmation - is recorded as a cryptographically signed, hash-chained, tamper-evident audit log entry.',
            ),
        ),
        'components_title'              => 'Core Infrastructure Layers',
        'component_link_label'          => 'Learn More',
        'components'                    => array(
            array(
                'title'       => 'MPC Wallet Infrastructure',
                'description' => 'Key shares are distributed across geographically separated MPC nodes using GG18 and FROST threshold signing protocols. No individual node ever holds the complete private key, and no valid signature can be produced without the cooperation of the required signing threshold. Hardware-Hybrid Custody extends this model by storing one key share on a physical hardware device - ensuring no cloud-based compromise can unilaterally move funds.',
                'link_url'    => home_url( '/ecosystem/mpc-wallet' ),
            ),
            array(
                'title'       => 'Policy Engine',
                'description' => 'Policies are expressed in a declarative Domain-Specific Language and evaluated deterministically at the signing layer. Every policy decision references a versioned policy hash - meaning the exact governance context for any historical transaction can be reconstructed by auditors and regulators. Policy updates require DAO vote or designated governance approval and take effect after a configurable time-lock period.',
                'link_url'    => home_url( '/ecosystem/policy-engine' ),
            ),
            array(
                'title'       => 'Delegation & Session Authority',
                'description' => 'Session keys are ephemeral credentials issued by the MPC infrastructure for a specific, policy-defined operational scope - a bounded set of actions, a time window, and a maximum cumulative spend. They enable Fast-Path execution for low-risk operations at sub-100ms signing latency, while remaining cryptographically bound to the policy context that authorized them and automatically expiring at the end of their window.',
                'link_url'    => home_url( '/ecosystem/delegation' ),
            ),
            array(
                'title'       => 'MCP & Integration Layer',
                'description' => 'The Protocol Integration Bus connects external protocols - MCP servers, A2A agent networks, AgentKit integrations, and future standards - to the appropriate enforcement points in the control plane without modifying core layer logic. DeAgenticAI exposes an MCP server interface for its Policy Engine and Intent Service, making platform capabilities accessible to any MCP-compatible agent. All external MCP tool interactions flow through the Intent Sanitization layer\'s security pipeline.',
                'link_url'    => home_url( '/ecosystem/integrations' ),
            ),
            array(
                'title'       => 'Compliance & Audit Framework',
                'description' => 'Every component in the platform emits structured, cryptographically signed log entries at each significant event. These entries are hash-chained into a tamper-evident audit trail formatted for MiCA regulatory reporting, Travel Rule data exchange, and DORA operational resilience documentation. The compliance layer is not a reporting overlay - it is a first-class architectural layer that shapes the information model of the entire system.',
                'link_url'    => home_url( '/ecosystem/compliance' ),
            ),
        ),
        'unity_title'                   => 'Systemic Unity',
        'unity_intro'                   => 'The DeAgenticAI ecosystem operates as a unified system where no single layer functions in isolation. Each component is architected to support a higher-order objective of autonomous agent safety.',
        'unity_bullets'                 => array(
            array( 'description' => 'Intent Sanitization precedes policy evaluation - the only component specifically designed to detect and block adversarially crafted inputs before they reach any enforcement mechanism.' ),
            array( 'description' => 'Policy evaluation precedes signing - no execution pathway bypasses the policy engine, and the policy hash is independently verified by each MPC signing node.' ),
            array( 'description' => 'Signing requires distributed threshold - no single node, cloud provider, or infrastructure compromise can produce an unauthorized signature.' ),
            array( 'description' => 'Every state transition is audited - the complete decision chain from intent to chain confirmation is cryptographically provable after the fact, independently of the platform operator.' ),
        ),
        'comparison_title'              => 'Comparison Matrix',
        'comparison_header_capability'  => 'Capability',
        'comparison_header_primary'     => 'DeAgenticAI',
        'comparison_header_coinbase'    => 'Coinbase Agentic Wallets',
        'comparison_header_fireblocks'  => 'Fireblocks',
        'comparison_header_safe'        => 'Safe (Gnosis)',
        'comparison_rows'               => array(
            array( 'capability' => 'Cryptographic policy enforcement', 'primary' => 'Yes - MPC-enforced', 'coinbase' => 'API-layer only', 'fireblocks' => 'No AI autonomy', 'safe' => 'Module-based' ),
            array( 'capability' => 'Intent Sanitization (prompt injection defence)', 'primary' => 'Yes - Layer 2', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'No' ),
            array( 'capability' => 'Behavioural fraud detection', 'primary' => 'Yes - native', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'No' ),
            array( 'capability' => 'DAO governance integration', 'primary' => 'Yes - native', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'Yes - multisig' ),
            array( 'capability' => 'W3C DID agent identity', 'primary' => 'Yes', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'No' ),
            array( 'capability' => 'MiCA / DORA compliance infrastructure', 'primary' => 'Yes - Layer 8', 'coinbase' => 'Partial', 'fireblocks' => 'Yes - custody only', 'safe' => 'No' ),
            array( 'capability' => 'Multi-agent support (A2A / MCP)', 'primary' => 'Yes - Layer 7', 'coinbase' => 'Partial - AgentKit', 'fireblocks' => 'No', 'safe' => 'No' ),
            array( 'capability' => 'Hardware-Hybrid Custody', 'primary' => 'Yes', 'coinbase' => 'No', 'fireblocks' => 'Yes - custody', 'safe' => 'No' ),
            array( 'capability' => 'Cross-chain abstraction', 'primary' => 'Yes', 'coinbase' => 'No', 'fireblocks' => 'Partial', 'safe' => 'No' ),
        ),
        'comparison_note'               => 'Coinbase and Fireblocks capability descriptions sourced from their published documentation. Fireblocks restricts AI to an advisory role and does not support autonomous execution. Coinbase Agentic Wallets enforce policy at the API layer only - controls are contingent on Coinbase platform integrity, not cryptographic guarantees.',
        'faq_title'                     => 'Frequently Asked Questions',
        'faqs'                          => array(
            array(
                'question' => 'What is the Agentic Control Plane?',
                'answer'   => 'DeAgenticAI\'s Agentic Control Plane is a multi-layer infrastructure architecture that enforces cryptographic policy over autonomous AI agent authority - separating what an agent can do from what it is authorized to do - in Web3 and enterprise financial environments. It operates as the enforcement layer between an AI agent and on-chain execution, ensuring that every action is sanitized, policy-evaluated, fraud-checked, and threshold-signed before it reaches any blockchain.',
            ),
            array(
                'question' => 'Why is Intent Sanitization the most important security boundary?',
                'answer'   => 'Cryptographic enforcement downstream of a compromised input provides a false sense of security, not actual security. An MPC-signed transaction is cryptographically valid regardless of whether the intent that authorized it was manipulated through prompt injection. Intent Sanitization is the only component in the architecture specifically designed to detect and block adversarially crafted inputs - validating structural integrity, source authentication, semantic coherence, and context integrity before any intent reaches the policy engine or signing layer.',
            ),
            array(
                'question' => 'How does MPC threshold signing differ from multisig?',
                'answer'   => 'Multisig requires human signers to coordinate at every approval, introducing latency that eliminates the operational benefit of automation. MPC threshold signing distributes key shares mathematically across independent nodes - no individual node holds the complete key, and signatures are produced through secure multi-party computation without ever reconstructing the key. Policy evaluation is enforced as a cryptographic prerequisite for signing, not as an application-layer gate that can be bypassed.',
            ),
            array(
                'question' => 'What is Hardware-Hybrid Custody?',
                'answer'   => 'Hardware-Hybrid Custody is an institutional security model in which one MPC key share is stored on a physical hardware device - a Ledger, YubiKey, or HSM - while the remaining threshold shares are distributed across the MPC node network. Even a complete compromise of the node network cannot produce a valid signature without the hardware-held share. This addresses the specific enterprise requirement for assurance that no purely software or cloud-based attack can unilaterally move funds.',
            ),
            array(
                'question' => 'How does agent delegation work across multi-agent systems?',
                'answer'   => 'When an orchestrator agent delegates a task to a subagent, the delegation creates a scoped policy context for the subagent that inherits the relevant constraints from the orchestrator\'s policy. The authority granted to a subagent is never greater than what the delegating agent was explicitly authorized to grant. This constraint applies recursively through delegation chains of arbitrary depth, ensuring that multi-agent hierarchies cannot be exploited to escalate authority beyond what has been explicitly defined at each level.',
            ),
            array(
                'question' => 'Is the platform compatible with multiple blockchains?',
                'answer'   => 'The Chain Abstraction layer presents a unified interface across EVM chains - including Ethereum, Base, Polygon, and Arbitrum - and non-EVM chains including Solana and Cosmos-based networks. Each chain is served by a pluggable adapter that handles transaction building, simulation, MPC signing serialization, and broadcast. The policy engine, fraud detection layer, and MPC signing infrastructure operate against a normalized transaction representation and are chain-agnostic by design.',
            ),
        ),
    );
}

/**
 * Get the current Ecosystem page settings.
 *
 * @return array<string,mixed>
 */
function aura_get_ecosystem_page_settings() {
    static $cache = null;
    if ( null !== $cache ) {
        return $cache;
    }

    $defaults = aura_get_ecosystem_page_defaults();
    $stored   = get_option( 'aura_ecosystem_page', array() );
    $stored   = is_array( $stored ) ? $stored : array();
    $settings = array();

    foreach ( $defaults as $key => $default_value ) {
        if ( is_array( $default_value ) ) {
            $settings[ $key ] = aura_merge_page_content_items(
                isset( $stored[ $key ] ) ? $stored[ $key ] : array(),
                $default_value
            );
            continue;
        }

        $settings[ $key ] = array_key_exists( $key, $stored )
            ? (string) $stored[ $key ]
            : $default_value;
    }

    $cache = $settings;
    return $cache;
}

/**
 * Sanitize Ecosystem page settings.
 *
 * @param mixed $input Raw POST payload.
 * @return array<string,mixed>
 */
function aura_sanitize_ecosystem_page_settings( $input ) {
    $input    = is_array( $input ) ? $input : array();
    $defaults = aura_get_ecosystem_page_defaults();

    $text_fields = array(
        'hero_badge',
        'hero_title_prefix',
        'hero_title_accent',
        'hero_subtitle',
        'hero_cta_label',
        'problem_title',
        'architecture_title',
        'status_line_primary',
        'status_line_secondary',
        'status_line_tertiary',
        'components_title',
        'component_link_label',
        'unity_title',
        'comparison_title',
        'comparison_header_capability',
        'comparison_header_primary',
        'comparison_header_coinbase',
        'comparison_header_fireblocks',
        'comparison_header_safe',
        'faq_title',
    );
    $textarea_fields = array(
        'hero_lead',
        'unity_intro',
        'comparison_note',
    );

    $sanitized = array();

    foreach ( $text_fields as $key ) {
        $sanitized[ $key ] = isset( $input[ $key ] )
            ? sanitize_text_field( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    foreach ( $textarea_fields as $key ) {
        $sanitized[ $key ] = isset( $input[ $key ] )
            ? sanitize_textarea_field( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    $sanitized['problem_points'] = aura_sanitize_page_content_items(
        isset( $input['problem_points'] ) ? $input['problem_points'] : array(),
        $defaults['problem_points'],
        array( 'description' )
    );
    $sanitized['control_plane_labels'] = aura_sanitize_page_content_items(
        isset( $input['control_plane_labels'] ) ? $input['control_plane_labels'] : array(),
        $defaults['control_plane_labels']
    );
    $sanitized['architecture_steps'] = aura_sanitize_page_content_items(
        isset( $input['architecture_steps'] ) ? $input['architecture_steps'] : array(),
        $defaults['architecture_steps'],
        array( 'description' )
    );
    $sanitized['components'] = aura_sanitize_page_content_items(
        isset( $input['components'] ) ? $input['components'] : array(),
        $defaults['components'],
        array( 'description' ),
        array( 'link_url' )
    );
    $sanitized['unity_bullets'] = aura_sanitize_page_content_items(
        isset( $input['unity_bullets'] ) ? $input['unity_bullets'] : array(),
        $defaults['unity_bullets'],
        array( 'description' )
    );
    $sanitized['comparison_rows'] = aura_sanitize_page_content_items(
        isset( $input['comparison_rows'] ) ? $input['comparison_rows'] : array(),
        $defaults['comparison_rows']
    );
    $sanitized['faqs'] = aura_sanitize_page_content_items(
        isset( $input['faqs'] ) ? $input['faqs'] : array(),
        $defaults['faqs'],
        array( 'answer' )
    );

    return $sanitized;
}
