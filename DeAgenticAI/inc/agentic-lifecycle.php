<?php
/**
 * Shared Agentic Lifecycle dock renderer.
 *
 * @package Aura
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the shared lifecycle step catalog.
 *
 * @return array<int,array<string,mixed>>
 */
function aura_get_agentic_lifecycle_catalog() {
    return array(
        1 => array(
            'slug' => 'intent-generation',
            'label' => __('Intent Generation', 'aura'),
            'dock_label' => __('INTENT GENERATION', 'aura'),
            'aliases' => array('intent generation', 'trigger', 'initiation'),
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3.5"></circle><path d="M12 2.5v3"></path><path d="M12 18.5v3"></path><path d="M4.9 4.9l2.1 2.1"></path><path d="M17 17l2.1 2.1"></path><path d="M2.5 12h3"></path><path d="M18.5 12h3"></path><path d="M4.9 19.1 7 17"></path><path d="M17 7l2.1-2.1"></path></svg>',
        ),
        2 => array(
            'slug' => 'intent-sanitization',
            'label' => __('Intent Sanitization', 'aura'),
            'dock_label' => __('INTENT SANITIZATION', 'aura'),
            'aliases' => array('intent sanitization', 'sanitization', 'delegation', 'session authority'),
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-3.5 7-9.3V5.8L12 3 5 5.8v5.9C5 17.5 12 21 12 21Z"></path><path d="m9.4 12.1 1.7 1.7 3.8-3.8"></path></svg>',
        ),
        3 => array(
            'slug' => 'policy-evaluation',
            'label' => __('Policy Evaluation', 'aura'),
            'dock_label' => __('POLICY EVALUATION', 'aura'),
            'aliases' => array('policy evaluation', 'evaluation', 'policy engine'),
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16"></path><path d="M6 7h12"></path><path d="m7.5 7-3 5"></path><path d="m7.5 7 3 5"></path><path d="m13.5 7-3 5"></path><path d="m13.5 7 3 5"></path><path d="M5 12a2.5 2.5 0 0 0 5 0"></path><path d="M14 12a2.5 2.5 0 0 0 5 0"></path><path d="M9 20h6"></path></svg>',
        ),
        4 => array(
            'slug' => 'fraud-detection',
            'label' => __('Fraud Detection', 'aura'),
            'dock_label' => __('FRAUD DETECTION', 'aura'),
            'aliases' => array('fraud detection', 'compliance', 'audit framework', 'monitoring'),
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2.2"></circle><path d="M12 12 18.5 5.5"></path><path d="M12 5a7 7 0 1 1-7 7"></path><path d="M12 2a10 10 0 1 1-10 10"></path></svg>',
        ),
        5 => array(
            'slug' => 'mpc-threshold-signing',
            'label' => __('MPC Threshold Signing', 'aura'),
            'dock_label' => __('MPC MECHANISM', 'aura'),
            'aliases' => array('mpc threshold signing', 'mpc mechanism', 'signature', 'threshold signing', 'mpc wallet'),
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8.5" cy="12" r="3.5"></circle><path d="M12 12h8"></path><path d="M17 12v-2.2"></path><path d="M20 12v2.2"></path></svg>',
        ),
        6 => array(
            'slug' => 'chain-broadcast',
            'label' => __('Chain Broadcast', 'aura'),
            'dock_label' => __('CHAIN BROADCAST', 'aura'),
            'aliases' => array('chain broadcast', 'broadcast', 'on-chain execution', 'execution'),
            'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16"></path><path d="M8 20h8"></path><path d="m7 11 5-7 5 7"></path><path d="M5.5 17.5a7 7 0 0 1 0-11"></path><path d="M18.5 17.5a7 7 0 0 0 0-11"></path></svg>',
        ),
    );
}

/**
 * Resolve a lifecycle step selection to the canonical numeric step.
 *
 * @param mixed $active_step Step index or label.
 * @return int
 */
function aura_resolve_agentic_lifecycle_step($active_step) {
    $catalog = aura_get_agentic_lifecycle_catalog();

    if (is_numeric($active_step)) {
        $active_step = (int) $active_step;
        return isset($catalog[$active_step]) ? $active_step : 0;
    }

    $active_step = trim((string) $active_step);
    if ('' === $active_step) {
        return 0;
    }

    $active_slug = sanitize_title($active_step);
    $active_upper = strtoupper($active_step);

    foreach ($catalog as $index => $step) {
        $aliases = isset($step['aliases']) && is_array($step['aliases']) ? $step['aliases'] : array();

        if ($active_slug === sanitize_title((string) $step['slug'])) {
            return $index;
        }

        if ($active_upper === strtoupper((string) $step['dock_label']) || $active_upper === strtoupper((string) $step['label'])) {
            return $index;
        }

        foreach ($aliases as $alias) {
            if ($active_slug === sanitize_title((string) $alias) || $active_upper === strtoupper((string) $alias)) {
                return $index;
            }
        }
    }

    return 0;
}

/**
 * Render the shared Agentic Lifecycle dock.
 *
 * @param array<string,mixed> $args Component arguments.
 * @return string
 */
function aura_render_agentic_lifecycle_dock($args = array()) {
    $defaults = array(
        'title' => __('The Agentic Lifecycle', 'aura'),
        'status' => __('Happy Path Protocol Active', 'aura'),
        'active_step' => 0,
        'class_name' => '',
    );

    $args = wp_parse_args(is_array($args) ? $args : array(), $defaults);
    $catalog = aura_get_agentic_lifecycle_catalog();
    $active_step = aura_resolve_agentic_lifecycle_step($args['active_step']);
    $root_classes = trim('agentic-lifecycle ' . (string) $args['class_name']);
    $instance_id = function_exists('wp_unique_id') ? wp_unique_id('agentic-lifecycle-') : uniqid('agentic-lifecycle-');

    ob_start();
    ?>
    <div class="<?php echo esc_attr($root_classes); ?>">
        <div class="agentic-lifecycle-head">
            <h2 class="agentic-lifecycle-title"><?php echo esc_html((string) $args['title']); ?></h2>
            <?php if ('' !== trim((string) $args['status'])) : ?>
                <p class="agentic-lifecycle-status"><?php echo esc_html((string) $args['status']); ?></p>
            <?php endif; ?>
        </div>

        <div class="agentic-lifecycle-shell">
            <svg class="agentic-lifecycle-beam-layer" viewBox="0 0 1200 400" preserveAspectRatio="none" aria-hidden="true">
                <defs>
                    <linearGradient id="<?php echo esc_attr($instance_id . '-beam'); ?>" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="hsl(var(--primary))" stop-opacity="0"></stop>
                        <stop offset="50%" stop-color="hsl(var(--primary))" stop-opacity="0.6"></stop>
                        <stop offset="100%" stop-color="hsl(var(--primary))" stop-opacity="0"></stop>
                    </linearGradient>
                    <filter id="<?php echo esc_attr($instance_id . '-glow'); ?>">
                        <feGaussianBlur stdDeviation="4" result="coloredBlur"></feGaussianBlur>
                        <feMerge>
                            <feMergeNode in="coloredBlur"></feMergeNode>
                            <feMergeNode in="SourceGraphic"></feMergeNode>
                        </feMerge>
                    </filter>
                </defs>

                <?php foreach (array(-4, 0, 4) as $beam_index => $beam_offset) : ?>
                    <?php
                    $duration = 6 + ($beam_index * 2);
                    $delay = $beam_index * 2;
                    ?>
                    <path
                        class="agentic-lifecycle-beam"
                        d="M 0 200 L 1200 200"
                        stroke="url(#<?php echo esc_attr($instance_id . '-beam'); ?>)"
                        stroke-width="3"
                        fill="none"
                        stroke-dasharray="150 1200"
                        filter="url(#<?php echo esc_attr($instance_id . '-glow'); ?>)"
                        style="--beam-offset: <?php echo esc_attr((string) $beam_offset); ?>px; --beam-duration: <?php echo esc_attr((string) $duration); ?>s; --beam-delay: <?php echo esc_attr((string) $delay); ?>s;"
                    ></path>
                    <circle
                        class="agentic-lifecycle-spark"
                        cx="0"
                        cy="200"
                        r="2"
                        fill="#ffffff"
                        style="--spark-offset: <?php echo esc_attr((string) $beam_offset); ?>px; --spark-duration: <?php echo esc_attr((string) $duration); ?>s; --spark-delay: <?php echo esc_attr((string) $delay); ?>s;"
                    ></circle>
                <?php endforeach; ?>
            </svg>

            <div class="agentic-lifecycle-track" data-agentic-dock>
                <?php foreach ($catalog as $index => $step) : ?>
                    <?php
                    $is_active = ($active_step === $index);
                    $gradient_id = $instance_id . '-step-' . $index;
                    ?>
                    <article class="agentic-dock-item<?php echo $is_active ? ' is-active' : ''; ?>" data-agentic-dock-item>
                        <div class="agentic-dock-item-frame" aria-hidden="true">
                            <svg viewBox="0 0 100 100" class="agentic-dock-item-svg">
                                <defs>
                                    <linearGradient id="<?php echo esc_attr($gradient_id); ?>" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="hsl(var(--primary))" stop-opacity="<?php echo $is_active ? '0.6' : '0.2'; ?>"></stop>
                                        <stop offset="100%" stop-color="hsl(var(--primary))" stop-opacity="<?php echo $is_active ? '0.2' : '0.05'; ?>"></stop>
                                    </linearGradient>
                                </defs>
                                <path class="agentic-dock-inner-path" d="M50 8 L87 30 L87 70 L50 92 L13 70 L13 30 Z"></path>
                                <?php if ($is_active) : ?>
                                    <circle class="agentic-dock-active-aura" cx="50" cy="50" r="49"></circle>
                                <?php endif; ?>
                                <path
                                    class="agentic-dock-main-hex"
                                    d="M50 5 L90 28 L90 72 L50 95 L10 72 L10 28 Z"
                                    fill="url(#<?php echo esc_attr($gradient_id); ?>)"
                                    stroke-width="<?php echo $is_active ? '3' : '1.8'; ?>"
                                ></path>
                            </svg>
                        </div>

                        <div class="agentic-dock-icon"><?php echo $step['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

                        <div class="agentic-dock-tooltip<?php echo $is_active ? ' is-active' : ''; ?>" aria-hidden="true">
                            <div class="agentic-dock-tooltip-bubble"><?php echo esc_html((string) $step['dock_label']); ?></div>
                            <div class="agentic-dock-tooltip-arrow"></div>
                        </div>

                        <?php if ($is_active) : ?>
                            <div class="agentic-dock-active-marker" aria-hidden="true">
                                <span class="agentic-dock-active-dot"></span>
                                <span class="agentic-dock-active-line"></span>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php

    return (string) ob_get_clean();
}