<?php
/**
 * Template Name: Ecosystem
 *
 * @package Aura
 */

$faqs = array(
    array(
        'q' => 'What is the Agentic Control Plane?',
        'a' => 'DeAgenticAI\'s Agentic Control Plane is a multi-layer infrastructure architecture that enforces cryptographic policy over autonomous AI agent authority - separating what an agent can do from what it is authorized to do - in Web3 and enterprise financial environments. It operates as the enforcement layer between an AI agent and on-chain execution, ensuring that every action is sanitized, policy-evaluated, fraud-checked, and threshold-signed before it reaches any blockchain.',
    ),
    array(
        'q' => 'Why is Intent Sanitization the most important security boundary?',
        'a' => 'Cryptographic enforcement downstream of a compromised input provides a false sense of security, not actual security. An MPC-signed transaction is cryptographically valid regardless of whether the intent that authorized it was manipulated through prompt injection. Intent Sanitization is the only component in the architecture specifically designed to detect and block adversarially crafted inputs - validating structural integrity, source authentication, semantic coherence, and context integrity before any intent reaches the policy engine or signing layer.',
    ),
    array(
        'q' => 'How does MPC threshold signing differ from multisig?',
        'a' => 'Multisig requires human signers to coordinate at every approval, introducing latency that eliminates the operational benefit of automation. MPC threshold signing distributes key shares mathematically across independent nodes - no individual node holds the complete key, and signatures are produced through secure multi-party computation without ever reconstructing the key. Policy evaluation is enforced as a cryptographic prerequisite for signing, not as an application-layer gate that can be bypassed.',
    ),
    array(
        'q' => 'What is Hardware-Hybrid Custody?',
        'a' => 'Hardware-Hybrid Custody is an institutional security model in which one MPC key share is stored on a physical hardware device - a Ledger, YubiKey, or HSM - while the remaining threshold shares are distributed across the MPC node network. Even a complete compromise of the node network cannot produce a valid signature without the hardware-held share. This addresses the specific enterprise requirement for assurance that no purely software or cloud-based attack can unilaterally move funds.',
    ),
    array(
        'q' => 'How does agent delegation work across multi-agent systems?',
        'a' => 'When an orchestrator agent delegates a task to a subagent, the delegation creates a scoped policy context for the subagent that inherits the relevant constraints from the orchestrator\'s policy. The authority granted to a subagent is never greater than what the delegating agent was explicitly authorized to grant. This constraint applies recursively through delegation chains of arbitrary depth, ensuring that multi-agent hierarchies cannot be exploited to escalate authority beyond what has been explicitly defined at each level.',
    ),
    array(
        'q' => 'Is the platform compatible with multiple blockchains?',
        'a' => 'The Chain Abstraction layer presents a unified interface across EVM chains - including Ethereum, Base, Polygon, and Arbitrum - and non-EVM chains including Solana and Cosmos-based networks. Each chain is served by a pluggable adapter that handles transaction building, simulation, MPC signing serialization, and broadcast. The policy engine, fraud detection layer, and MPC signing infrastructure operate against a normalized transaction representation and are chain-agnostic by design.',
    ),
);

$faq_schema = array(
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(
        static function ( $faq ) {
            return array(
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $faq['a'],
                ),
            );
        },
        $faqs
    ),
);

$architecture_steps = array(
    array(
        'step' => '01',
        'title' => 'Intent Generation',
        'desc' => 'Autonomous agent generates a transaction intent based on its current objective, operational context, and the authority scope defined in its active policy credentials.',
    ),
    array(
        'step' => '02',
        'title' => 'Intent Sanitization',
        'desc' => 'Every intent is treated as potentially adversarially crafted. The sanitization pipeline validates structural integrity, authenticates the source against the agent\'s W3C DID-anchored identity, evaluates semantic coherence against the agent\'s declared context, and checks for known prompt injection patterns and upstream data manipulation. This is the most critical security boundary in the architecture - cryptographic enforcement downstream cannot compensate for a compromised input.',
    ),
    array(
        'step' => '03',
        'title' => 'Policy Evaluation',
        'desc' => 'The sanitized intent is evaluated deterministically against the full stack of applicable Policy DSL rules - spending limits, protocol allowlists, temporal constraints, risk thresholds, and governance escalation triggers. The policy engine returns an explicit authorization or denial. No signing occurs without an explicit authorization. There is no execution pathway that bypasses this step.',
    ),
    array(
        'step' => '04',
        'title' => 'Fraud Detection',
        'desc' => 'Behavioural analytics, contract risk scoring, and intent consistency verification evaluate whether the proposed action is consistent with the agent\'s declared strategy, historical behaviour, and current portfolio state. Risk scores map to a graduated escalation model - from autonomous execution at low risk, to elevated MPC signing threshold at moderate risk, to mandatory human or DAO approval at high risk.',
    ),
    array(
        'step' => '05',
        'title' => 'MPC Threshold Signing',
        'desc' => 'MPC signing nodes independently verify the policy authorization hash before contributing partial signatures. No individual node ever holds the complete private key. A valid transaction signature requires the cooperation of at least t-of-n geographically distributed nodes - a cryptographic requirement that cannot be bypassed by compromising any single component, including the orchestrator.',
    ),
    array(
        'step' => '06',
        'title' => 'Chain Broadcast',
        'desc' => 'The threshold-signed transaction is broadcast to the target chain with confirmation monitoring. Every event in the sequence - intent submission, sanitization outcome, policy decision, risk score, signing node participation, broadcast, and confirmation - is recorded as a cryptographically signed, hash-chained, tamper-evident audit log entry.',
    ),
);

$components = array(
    array(
        'title' => 'MPC Wallet Infrastructure',
        'desc' => 'Key shares are distributed across geographically separated MPC nodes using GG18 and FROST threshold signing protocols. No individual node ever holds the complete private key, and no valid signature can be produced without the cooperation of the required signing threshold. Hardware-Hybrid Custody extends this model by storing one key share on a physical hardware device - ensuring no cloud-based compromise can unilaterally move funds.',
        'link' => home_url('/ecosystem/mpc-wallet'),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
    ),
    array(
        'title' => 'Policy Engine',
        'desc' => 'Policies are expressed in a declarative Domain-Specific Language and evaluated deterministically at the signing layer. Every policy decision references a versioned policy hash - meaning the exact governance context for any historical transaction can be reconstructed by auditors and regulators. Policy updates require DAO vote or designated governance approval and take effect after a configurable time-lock period.',
        'link' => home_url('/ecosystem/policy-engine'),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path><path d="m9 12 2 2 4-4"></path></svg>',
    ),
    array(
        'title' => 'Delegation & Session Authority',
        'desc' => 'Session keys are ephemeral credentials issued by the MPC infrastructure for a specific, policy-defined operational scope - a bounded set of actions, a time window, and a maximum cumulative spend. They enable Fast-Path execution for low-risk operations at sub-100ms signing latency, while remaining cryptographically bound to the policy context that authorized them and automatically expiring at the end of their window.',
        'link' => home_url('/ecosystem/delegation'),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
    ),
    array(
        'title' => 'MCP & Integration Layer',
        'desc' => 'The Protocol Integration Bus connects external protocols - MCP servers, A2A agent networks, AgentKit integrations, and future standards - to the appropriate enforcement points in the control plane without modifying core layer logic. DeAgenticAI exposes an MCP server interface for its Policy Engine and Intent Service, making platform capabilities accessible to any MCP-compatible agent. All external MCP tool interactions flow through the Intent Sanitization layer\'s security pipeline.',
        'link' => home_url('/ecosystem/integrations'),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"></path></svg>',
    ),
    array(
        'title' => 'Compliance & Audit Framework',
        'desc' => 'Every component in the platform emits structured, cryptographically signed log entries at each significant event. These entries are hash-chained into a tamper-evident audit trail formatted for MiCA regulatory reporting, Travel Rule data exchange, and DORA operational resilience documentation. The compliance layer is not a reporting overlay - it is a first-class architectural layer that shapes the information model of the entire system.',
        'link' => home_url('/ecosystem/compliance'),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path><path d="M11 8v6M8 11h6"></path></svg>',
    ),
);

$comparison_rows = array(
    array('capability' => 'Cryptographic policy enforcement', 'deagenticai' => 'Yes - MPC-enforced', 'coinbase' => 'API-layer only', 'fireblocks' => 'No AI autonomy', 'safe' => 'Module-based'),
    array('capability' => 'Intent Sanitization (prompt injection defence)', 'deagenticai' => 'Yes - Layer 2', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'No'),
    array('capability' => 'Behavioural fraud detection', 'deagenticai' => 'Yes - native', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'No'),
    array('capability' => 'DAO governance integration', 'deagenticai' => 'Yes - native', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'Yes - multisig'),
    array('capability' => 'W3C DID agent identity', 'deagenticai' => 'Yes', 'coinbase' => 'No', 'fireblocks' => 'No', 'safe' => 'No'),
    array('capability' => 'MiCA / DORA compliance infrastructure', 'deagenticai' => 'Yes - Layer 8', 'coinbase' => 'Partial', 'fireblocks' => 'Yes - custody only', 'safe' => 'No'),
    array('capability' => 'Multi-agent support (A2A / MCP)', 'deagenticai' => 'Yes - Layer 7', 'coinbase' => 'Partial - AgentKit', 'fireblocks' => 'No', 'safe' => 'No'),
    array('capability' => 'Hardware-Hybrid Custody', 'deagenticai' => 'Yes', 'coinbase' => 'No', 'fireblocks' => 'Yes - custody', 'safe' => 'No'),
    array('capability' => 'Cross-chain abstraction', 'deagenticai' => 'Yes', 'coinbase' => 'No', 'fireblocks' => 'Partial', 'safe' => 'No'),
);

$control_plane_labels = array(
    'MPC Shards',
    'Policy Engine',
    'Delegation',
    'Audit Log',
    'MCP Protocol',
    'Session Auth',
);

$logo_uri = function_exists('aura_get_site_logo_url') ? aura_get_site_logo_url() : '';
if ('' === $logo_uri) {
    $logo_uri = get_template_directory_uri() . '/images/logo.png';
}

get_header();
?>
<script type="application/ld+json">
<?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="primary" class="site-main eco-page">
    <div class="eco-shell">
        <section class="eco-section eco-hero">
            <div class="eco-hero-inner reveal-up" style="transition-delay: 0.05s;">
                <div class="eco-badge">
                    <span class="eco-badge-dot"></span>
                    <span class="eco-badge-text">Platform Thesis</span>
                </div>

                <h1 class="eco-title">The DeAgenticAI <span><em>Ecosystem</em></span></h1>
                <p class="eco-subtitle">Infrastructure for Autonomous Agents Operating in Web3</p>

                <div class="eco-lead-card">
                    <p>The DeAgenticAI Ecosystem is the Agentic Control Plane - a multi-layer infrastructure architecture that enforces cryptographic policy over autonomous AI agent authority, separating what an agent can do from what it is authorized to do, in Web3 and enterprise financial environments. It is not a wallet, not an automation framework, and not a governance tool in isolation. It is the missing primitive between an AI agent and on-chain execution: the layer that makes it possible to grant an agent a precisely scoped, cryptographically enforced, fully auditable slice of authority over on-chain and off-chain resources, while ensuring that any human, DAO, or regulatory authority retains meaningful oversight and override capability at all times.</p>
                </div>

                <a href="#architecture" class="eco-btn eco-btn-primary">
                    Explore the Architecture
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
        </section>

        <section class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;">Why Agentic AI Needs New Infrastructure</h2>
            <div class="eco-grid-2">
                <div class="eco-card eco-panel reveal-up" style="transition-delay: 0.12s;">
                    <div class="eco-point">
                        <span class="eco-point-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                        </span>
                        <p><strong>Private Keys Are a Broken Abstraction for AI</strong> The foundational security model of every blockchain - authority derived from key possession - was designed for human actors who make deliberate, accountable decisions. An AI agent inverts every assumption: it is probabilistic, not deterministic; it operates at speeds that make human review impossible; and it can be compromised through prompt injection, a threat that has no analog in human behaviour.</p>
                    </div>
                    <div class="eco-point">
                        <span class="eco-point-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                        </span>
                        <p><strong>Existing Infrastructure Cannot Bridge the Gap</strong> Multisig wallets require human coordination at every signing step. Smart contracts enforce spending limits but cannot evaluate the semantic intent behind a proposed action. Automation frameworks either execute blindly or trust the agent completely. Neither constitutes a controlled, governable system.</p>
                    </div>
                </div>

                <div class="eco-card eco-panel reveal-up" style="transition-delay: 0.2s;">
                    <div class="eco-point">
                        <span class="eco-point-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                        </span>
                        <p><strong>The Enforcement Must Be Cryptographic, Not Advisory</strong> API-layer policy controls can be bypassed if the platform is compromised. A smart contract policy can be bypassed if the contract has a vulnerability. Only a cryptographic enforcement layer - one in which the policy decision is a mathematical prerequisite for the signature - provides a guarantee that cannot be circumvented by infrastructure-level compromise.</p>
                    </div>
                    <div class="eco-point">
                        <span class="eco-point-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                        </span>
                        <p><strong>Multi-Agent Systems Require Authority Propagation</strong> As orchestrator-subagent architectures become standard, the policy constraints, spending limits, and governance requirements defined at the top of an agent hierarchy must propagate cryptographically down through every delegation step. Without this, multi-agent systems become an attack surface rather than a capability.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="architecture" class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;">The Agentic Control Plane Architecture</h2>

            <div class="eco-visual-card reveal-scale" style="transition-delay: 0.1s;">
                <div class="eco-grid-overlay"></div>
                <div class="eco-control-plane" data-control-plane-animation="true" aria-hidden="true">
                    <div class="eco-cp-bg eco-cp-bg-a"></div>
                    <div class="eco-cp-bg eco-cp-bg-b"></div>

                    <svg class="eco-cp-svg" viewBox="0 0 800 800" preserveAspectRatio="xMidYMid meet" role="presentation">
                        <g class="eco-cp-hive-lines"></g>
                        <circle class="eco-cp-core-glow" cx="400" cy="400" r="128"></circle>

                        <g class="eco-cp-logo-wrap" transform="translate(400 400)">
                            <g class="eco-cp-logo-scale">
                                <image class="eco-cp-logo" href="<?php echo esc_url($logo_uri); ?>" x="-64" y="-64" width="128" height="128" preserveAspectRatio="xMidYMid meet"></image>
                            </g>
                        </g>

                        <g class="eco-cp-node-layer">
                            <?php foreach ($control_plane_labels as $index => $label) : ?>
                                <g class="eco-cp-node" data-node-index="<?php echo esc_attr((string) $index); ?>">
                                    <g class="eco-cp-node-scale">
                                        <circle class="eco-cp-node-glow" cx="0" cy="0" r="51"></circle>
                                        <polygon class="eco-cp-node-hex" points=""></polygon>
                                        <text class="eco-cp-node-label" x="0" y="84"><?php echo esc_html(strtoupper($label)); ?></text>
                                    </g>
                                </g>
                            <?php endforeach; ?>
                        </g>
                    </svg>

                    <div class="eco-cp-status">
                        <p class="eco-cp-status-line eco-cp-status-line-1">System Modularity Initialized</p>
                        <p class="eco-cp-status-line eco-cp-status-line-2">Harmonizing Architectural Shards</p>
                        <p class="eco-cp-status-line eco-cp-status-line-3">Unified Control Plane Active</p>
                    </div>
                </div>
            </div>

            <div class="eco-step-grid">
                <?php foreach ($architecture_steps as $index => $step) : ?>
                    <article class="eco-step-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.06 + ($index * 0.04)), 2)); ?>s;">
                        <span class="eco-step-number"><?php echo esc_html($step['step']); ?></span>
                        <h3><?php echo esc_html($step['title']); ?></h3>
                        <p><?php echo esc_html($step['desc']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;">Core Infrastructure Layers</h2>
            <div class="eco-component-grid">
                <?php foreach ($components as $index => $component) : ?>
                    <article class="eco-component-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.08 + ($index * 0.04)), 2)); ?>s;">
                        <div class="eco-icon-wrap"><?php echo wp_kses($component['icon'], array('svg' => array('viewBox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true), 'rect' => array('x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true), 'path' => array('d' => true), 'polygon' => array('points' => true), 'circle' => array('cx' => true, 'cy' => true, 'r' => true), 'line' => array('x1' => true, 'x2' => true, 'y1' => true, 'y2' => true))); ?></div>
                        <h3><?php echo esc_html($component['title']); ?></h3>
                        <p><?php echo esc_html($component['desc']); ?></p>
                        <a class="eco-link" href="<?php echo esc_url($component['link']); ?>">
                            Learn More
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="eco-section">
            <div class="eco-unity reveal-up" style="transition-delay: 0.08s;">
                <h2 class="eco-h2">Systemic Unity</h2>
                <div class="eco-unity-grid">
                    <div>
                        <p>The DeAgenticAI ecosystem operates as a unified system where no single layer functions in isolation. Each component is architected to support a higher-order objective of autonomous agent safety.</p>
                        <ul class="eco-check-list">
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg><span>Intent Sanitization precedes policy evaluation - the only component specifically designed to detect and block adversarially crafted inputs before they reach any enforcement mechanism.</span></li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg><span>Policy evaluation precedes signing - no execution pathway bypasses the policy engine, and the policy hash is independently verified by each MPC signing node.</span></li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg><span>Signing requires distributed threshold - no single node, cloud provider, or infrastructure compromise can produce an unauthorized signature.</span></li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg><span>Every state transition is audited - the complete decision chain from intent to chain confirmation is cryptographically provable after the fact, independently of the platform operator.</span></li>
                        </ul>
                    </div>
                    <div class="eco-meter-panel">
                        <div class="eco-meter-glow"></div>
                        <div class="eco-meter-stack">
                            <div class="eco-meter-track"><span class="eco-meter-fill" style="--target: 75%; --delay: 0.1s;"></span></div>
                            <div class="eco-meter-track"><span class="eco-meter-fill" style="--target: 50%; --delay: 0.25s;"></span></div>
                            <div class="eco-meter-track"><span class="eco-meter-fill" style="--target: 84%; --delay: 0.4s;"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;">Comparison Matrix</h2>
            <div class="eco-table-wrap reveal-up" style="transition-delay: 0.12s;">
                <table class="eco-table">
                    <thead>
                        <tr>
                            <th>Capability</th>
                            <th>DeAgenticAI</th>
                            <th>Coinbase Agentic Wallets</th>
                            <th>Fireblocks</th>
                            <th>Safe (Gnosis)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparison_rows as $row) : ?>
                            <tr>
                                <td><?php echo esc_html($row['capability']); ?></td>
                                <td><?php echo esc_html($row['deagenticai']); ?></td>
                                <td><?php echo esc_html($row['coinbase']); ?></td>
                                <td><?php echo esc_html($row['fireblocks']); ?></td>
                                <td><?php echo esc_html($row['safe']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="eco-table-note reveal-up" style="transition-delay: 0.16s;"><em>Coinbase and Fireblocks capability descriptions sourced from their published documentation. Fireblocks restricts AI to an advisory role and does not support autonomous execution. Coinbase Agentic Wallets enforce policy at the API layer only - controls are contingent on Coinbase platform integrity, not cryptographic guarantees.</em></p>
        </section>

        <section class="eco-section eco-faq-wrap">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;">Frequently Asked Questions</h2>
            <?php foreach ($faqs as $index => $faq) : ?>
                <article class="eco-faq-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.1 + ($index * 0.04)), 2)); ?>s;">
                    <h3><?php echo esc_html($faq['q']); ?></h3>
                    <p><?php echo esc_html($faq['a']); ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </div>
</main>

<?php get_footer(); ?>
