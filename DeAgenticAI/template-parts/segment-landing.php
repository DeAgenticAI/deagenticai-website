<?php
/**
 * Shared segment landing renderer (React parity).
 *
 * Expected $args['segment'] shape:
 * - hero: headline, subheadline, ctaPrimary, ctaSecondary
 * - problem: title, blocks[{title,desc}]
 * - capabilities: title, blocks[{title,desc}]
 * - scenarios: title, items[{title,desc}]
 *
 * @package Aura
 */

if (!defined('ABSPATH')) {
    exit;
}

$segment = (isset($args['segment']) && is_array($args['segment'])) ? $args['segment'] : array();

$hero = isset($segment['hero']) && is_array($segment['hero']) ? $segment['hero'] : array();
$problem = isset($segment['problem']) && is_array($segment['problem']) ? $segment['problem'] : array();
$capabilities = isset($segment['capabilities']) && is_array($segment['capabilities']) ? $segment['capabilities'] : array();
$scenarios = isset($segment['scenarios']) && is_array($segment['scenarios']) ? $segment['scenarios'] : array();

$hero_headline = isset($hero['headline']) ? (string) $hero['headline'] : '';
$hero_headline_primary = isset($hero['headlinePrimary']) ? (string) $hero['headlinePrimary'] : '';
$hero_headline_accent = isset($hero['headlineAccent']) ? (string) $hero['headlineAccent'] : '';
$hero_subheadline = isset($hero['subheadline']) ? (string) $hero['subheadline'] : '';
$hero_cta_primary = isset($hero['ctaPrimary']) ? (string) $hero['ctaPrimary'] : '';
$hero_cta_secondary = isset($hero['ctaSecondary']) ? (string) $hero['ctaSecondary'] : '';

if ('' === trim($hero_headline_primary) && '' !== trim($hero_headline)) {
    $hero_headline_primary = $hero_headline;
}

$problem_title = isset($problem['title']) ? (string) $problem['title'] : '';
$problem_blocks = isset($problem['blocks']) && is_array($problem['blocks']) ? $problem['blocks'] : array();

$capabilities_title = isset($capabilities['title']) ? (string) $capabilities['title'] : '';
$capability_blocks = isset($capabilities['blocks']) && is_array($capabilities['blocks']) ? $capabilities['blocks'] : array();

$scenarios_title = isset($scenarios['title']) ? (string) $scenarios['title'] : '';
$scenario_items = isset($scenarios['items']) && is_array($scenarios['items']) ? $scenarios['items'] : array();


$architecture_items = array(
    array(
        'title' => 'Agent Layer',
        'desc' => 'Connect any model or framework (LangChain, AutoGPT, custom).',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />',
    ),
    array(
        'title' => 'Policy Layer',
        'desc' => 'Define granular rules for transaction size, frequency, and intent.',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
    ),
    array(
        'title' => 'MPC Execution Layer',
        'desc' => 'Distributed key shares ensure no single point of failure.',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />',
    ),
);

$gap_items = array(
    array(
        'title' => 'Multisig ≠ Agent Control',
        'desc' => 'Multisigs require human latency. Agents need deterministic, autonomous enforcement based on programmatic policies.',
    ),
    array(
        'title' => 'Smart Contracts ≠ Policy Governance',
        'desc' => 'Policies must be dynamic and off-chain aware, not just rigid on-chain logic that\'s expensive to upgrade.',
    ),
    array(
        'title' => 'Traditional MPC ≠ AI-Aware Enforcement',
        'desc' => 'Generic MPC doesn\'t understand agent reasoning. We build AI-specific session and policy management into key share logic.',
    ),
);

$integration_items = array(
    array('label' => 'SDK', 'value' => 'Native TypeScript & Python SDKs'),
    array('label' => 'API', 'value' => 'RESTful endpoints for policy management'),
    array('label' => 'MPC Backend', 'value' => 'Distributed signer infrastructure'),
    array('label' => 'Deployment', 'value' => 'Cloud-hosted or On-prem options'),
);
?>

<main id="primary" class="site-main page-content segment-page segment-page-v2">
    <section class="segment-v2-section segment-v2-hero">
        <div class="container segment-v2-hero-inner">
            <h1 class="segment-v2-hero-title reveal-up">
                <span class="segment-v2-hero-title-main"><?php echo esc_html($hero_headline_primary); ?></span>
                <?php if ('' !== trim($hero_headline_accent)) : ?>
                    <span class="segment-v2-hero-title-accent"><?php echo esc_html($hero_headline_accent); ?></span>
                <?php endif; ?>
            </h1>

            <p class="segment-v2-hero-subtitle reveal-up" style="transition-delay: 0.08s;">
                <?php echo esc_html($hero_subheadline); ?>
            </p>

            <div class="segment-v2-actions reveal-up" style="transition-delay: 0.14s;">
                <a href="#waitlist" class="segment-v2-btn segment-v2-btn-primary">
                    <?php echo esc_html($hero_cta_primary); ?>
                </a>
                <a href="#waitlist" class="segment-v2-btn segment-v2-btn-secondary">
                    <?php echo esc_html($hero_cta_secondary); ?>
                </a>
            </div>
        </div>
    </section>

    <section class="segment-v2-section segment-v2-problem">
        <div class="container">
            <div class="segment-v2-headline-wrap reveal-up">
                <h2 class="segment-v2-section-title"><?php echo esc_html($problem_title); ?></h2>
                <div class="segment-v2-title-bar"></div>
            </div>

            <div class="segment-v2-problem-grid">
                <?php foreach ($problem_blocks as $index => $block) : ?>
                    <?php
                    $title = isset($block['title']) ? (string) $block['title'] : '';
                    $desc = isset($block['desc']) ? (string) $block['desc'] : '';
                    $delay = 0.06 + ((int) $index * 0.08);
                    ?>
                    <article class="segment-v2-problem-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format($delay, 2)); ?>s;">
                        <span class="segment-v2-problem-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <h3><?php echo esc_html($title); ?></h3>
                        <p><?php echo esc_html($desc); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="segment-v2-section segment-v2-architecture">
        <div class="container">
            <div class="segment-v2-architecture-layout">
                <div class="segment-v2-architecture-copy">
                    <h2 class="segment-v2-section-title reveal-up">Architectural Fit</h2>
                    <p class="segment-v2-architecture-desc reveal-up" style="transition-delay: 0.08s;">
                        Our infrastructure plugs directly into your AI agent stack, providing a deterministic control plane between model reasoning and chain execution.
                    </p>

                    <div class="segment-v2-architecture-list">
                        <?php foreach ($architecture_items as $index => $item) : ?>
                            <?php $delay = 0.14 + ((int) $index * 0.08); ?>
                            <article class="segment-v2-architecture-item reveal-up" style="transition-delay: <?php echo esc_attr(number_format($delay, 2)); ?>s;">
                                <span class="segment-v2-architecture-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <?php echo $item['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </svg>
                                </span>
                                <div>
                                    <h4><?php echo esc_html($item['title']); ?></h4>
                                    <p><?php echo esc_html($item['desc']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="segment-v2-architecture-visual reveal-scale" style="transition-delay: 0.12s;">
                    <div class="segment-v2-visual-glow"></div>
                    <div class="segment-v2-visual-panel">
                        <div class="segment-v2-visual-node">
                            <span>Model Layer</span>
                            <div>Reasoning &amp; Intent</div>
                        </div>

                        <div class="segment-v2-visual-arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>

                        <div class="segment-v2-visual-node segment-v2-visual-node-active">
                            <span>DeAgenticAI Policy Layer</span>
                            <div>Control &amp; Safety Enforcement</div>
                        </div>

                        <div class="segment-v2-visual-arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>

                        <div class="segment-v2-visual-node">
                            <span>MPC Execution Layer</span>
                            <div>Final Distributed Settlement</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="segment-v2-section segment-v2-capabilities">
        <div class="container">
            <h2 class="segment-v2-section-title segment-v2-center reveal-up">
                <?php echo esc_html($capabilities_title); ?>
            </h2>

            <div class="segment-v2-capabilities-grid">
                <?php foreach ($capability_blocks as $index => $block) : ?>
                    <?php
                    $title = isset($block['title']) ? (string) $block['title'] : '';
                    $desc = isset($block['desc']) ? (string) $block['desc'] : '';
                    $delay = 0.08 + ((int) $index * 0.07);
                    ?>
                    <article class="segment-v2-capability-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format($delay, 2)); ?>s;">
                        <span class="segment-v2-capability-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" />
                            </svg>
                        </span>
                        <h3><?php echo esc_html($title); ?></h3>
                        <p><?php echo esc_html($desc); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="segment-v2-section segment-v2-usecases">
        <div class="container">
            <h2 class="segment-v2-section-title reveal-up"><?php echo esc_html($scenarios_title); ?></h2>

            <div class="segment-v2-usecase-grid">
                <?php foreach ($scenario_items as $index => $item) : ?>
                    <?php
                    $title = isset($item['title']) ? (string) $item['title'] : '';
                    $desc = isset($item['desc']) ? (string) $item['desc'] : '';
                    $delay = 0.08 + ((int) $index * 0.08);
                    ?>
                    <article class="segment-v2-usecase-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format($delay, 2)); ?>s;">
                        <h3><?php echo esc_html($title); ?></h3>
                        <p><?php echo esc_html($desc); ?></p>
                        <div class="segment-v2-usecase-meta">
                            <span>Active Implementation</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="segment-v2-section segment-v2-gap">
        <div class="container">
            <h2 class="segment-v2-section-title segment-v2-center reveal-up">The Infrastructure Gap</h2>

            <div class="segment-v2-gap-grid">
                <?php foreach ($gap_items as $index => $item) : ?>
                    <?php $delay = 0.08 + ((int) $index * 0.08); ?>
                    <article class="segment-v2-gap-item reveal-up" style="transition-delay: <?php echo esc_attr(number_format($delay, 2)); ?>s;">
                        <span class="segment-v2-gap-mark" aria-hidden="true">
                            <span></span>
                        </span>
                        <h4><?php echo esc_html($item['title']); ?></h4>
                        <p><?php echo esc_html($item['desc']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="segment-v2-section segment-v2-integration">
        <div class="container">
            <div class="segment-v2-integration-box reveal-scale">
                <div class="segment-v2-integration-glow"></div>
                <div class="segment-v2-integration-inner">
                    <h2 class="segment-v2-section-title">Seamless Integration</h2>
                    <p>Architected for developer experience and institutional security.</p>

                    <div class="segment-v2-integration-grid">
                        <?php foreach ($integration_items as $item) : ?>
                            <div>
                                <div class="segment-v2-integration-label"><?php echo esc_html($item['label']); ?></div>
                                <div class="segment-v2-integration-value"><?php echo esc_html($item['value']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
