<?php
/**
 * Use Cases Section Template
 *
 * @package Aura
 */

$usecase_cards = array(
    array(
        'title' => aura_get_home_meta( 'usecase_1_title', 'AI Infrastructure Teams' ),
        'link'  => aura_get_home_meta( 'usecase_1_url', home_url( '/solutions/ai-infrastructure' ) ),
        'points' => aura_get_home_meta_points( 'usecase_1_points', array(
            'Ship governed execution without building key management from scratch',
            'Replace ad-hoc wallet orchestration with deterministic policy controls',
            'Avoid single-vendor custody lock-in as the stack evolves',
            'Get audit-grade traces for every agent decision and signature',
        ) ),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 1 1-8.48 8.48"></path><path d="M20 12a8 8 0 0 0-8-8"></path><path d="M12 20a8 8 0 0 1-8-8"></path></svg>',
    ),
    array(
        'title' => aura_get_home_meta( 'usecase_2_title', 'Web3 Startups' ),
        'link'  => aura_get_home_meta( 'usecase_2_url', home_url( '/solutions/web3-startups' ) ),
        'points' => aura_get_home_meta_points( 'usecase_2_points', array(
            'Reduce platform risk from third-party wallet vendor consolidation',
            'Launch agent products without rebuilding custody and signing primitives',
            'Enforce policy boundaries before transactions hit the chain',
            'Preserve product velocity while hardening execution governance',
        ) ),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path></svg>',
    ),
    array(
        'title' => aura_get_home_meta( 'usecase_3_title', 'DAO Treasury' ),
        'link'  => aura_get_home_meta( 'usecase_3_url', home_url( '/solutions/dao-treasury' ) ),
        'points' => aura_get_home_meta_points( 'usecase_3_points', array(
            'Cut governance latency on routine treasury operations',
            'Prevent signer concentration and key-person execution risk',
            'Enforce proposal-aligned spending policies automatically',
            'Keep transparent, on-chain auditable trails for every action',
        ) ),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v18"></path><path d="M2 22h20"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>',
    ),
    array(
        'title' => aura_get_home_meta( 'usecase_4_title', 'Enterprise' ),
        'link'  => aura_get_home_meta( 'usecase_4_url', home_url( '/solutions/enterprise' ) ),
        'points' => aura_get_home_meta_points( 'usecase_4_points', array(
            'Map autonomous execution to internal approval and risk controls',
            'Enforce cryptographic policy across teams, wallets, and workflows',
            'Integrate governed delegation without replacing core systems',
            'Produce regulator-ready evidence for oversight and audit',
        ) ),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v18"></path><path d="M2 22h20"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>',
    ),
    array(
        'title' => aura_get_home_meta( 'usecase_5_title', 'RWA Management' ),
        'link'  => aura_get_home_meta( 'usecase_5_url', home_url( '/solutions/rwa-management' ) ),
        'points' => aura_get_home_meta_points( 'usecase_5_points', array(
            'Operate tokenized portfolios with bounded agent authority',
            'Constrain rebalancing, settlement, and transfer rules by policy',
            'Reduce continuity risk with distributed signing infrastructure',
            'Maintain traceable control for institutional counterparties',
        ) ),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 1 1-8.48 8.48"></path><path d="M20 12a8 8 0 0 0-8-8"></path><path d="M12 20a8 8 0 0 1-8-8"></path></svg>',
    ),
    array(
        'title' => aura_get_home_meta( 'usecase_6_title', 'Compliance / MiCA & DORA' ),
        'link'  => aura_get_home_meta( 'usecase_6_url', home_url( '/solutions/compliance' ) ),
        'points' => aura_get_home_meta_points( 'usecase_6_points', array(
            'Implement explicit control surfaces for regulated environments',
            'Detect anomalous intents before signing and broadcast',
            'Generate verifiable audit trails for supervisory review',
            'Support MiCA and DORA resilience expectations by design',
        ) ),
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 1 1-8.48 8.48"></path><path d="M20 12a8 8 0 0 0-8-8"></path><path d="M12 20a8 8 0 0 1-8-8"></path></svg>',
    ),
);

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
    'circle' => array('cx' => true, 'cy' => true, 'r' => true),
);
?>

<section class="usecases-section" id="use-cases">
    <div class="section-divider"></div>

    <div class="container">
        <div class="section-header reveal-up" style="transition-delay: 0.06s;">
            <div class="section-badge">
                <span class="status-indicator">
                    <span class="status-dot animate-ping"></span>
                    <span class="status-dot"></span>
                </span>
                <span class="badge-text">Who We Serve</span>
            </div>

            <h2 class="section-title">
                <?php echo esc_html( aura_get_home_meta( 'usecases_title', 'Control, safety, and autonomy' ) ); ?> <br>
                <span class="text-white/60"><?php echo esc_html( aura_get_home_meta( 'usecases_title_muted', 'for agentic AI in Web3' ) ); ?></span>
            </h2>

            <p class="section-description">
                <?php echo esc_html( aura_get_home_meta( 'usecases_description', 'DeAgenticAI is built for Web3 builders, AI engineers, and security-conscious organizations seeking policy-based control and safe execution for autonomous agents. Our infrastructure enables human-defined constraints and composable integration across decentralized services.' ) ); ?>
            </p>
        </div>

        <div class="usecases-grid">
            <?php foreach ($usecase_cards as $index => $card) : ?>
                <div class="usecase-card spotlight-card animate-on-scroll" data-spotlight>
                    <div class="spotlight-bg"></div>
                    <div class="spotlight-border"></div>
                    <div class="usecase-card-hover-gradient"></div>

                    <div class="usecase-card-inner">
                        <div class="usecase-header">
                            <div class="usecase-icon-wrap">
                                <?php echo wp_kses($card['icon'], $icon_allowed); ?>
                            </div>
                            <h3 class="usecase-title"><?php echo esc_html($card['title']); ?></h3>
                        </div>

                        <ul class="usecase-points">
                            <?php foreach ($card['points'] as $point) : ?>
                                <li>
                                    <span class="usecase-check-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m9 12 2 2 4-4"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                        </svg>
                                    </span>
                                    <span><?php echo esc_html($point); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <a href="<?php echo esc_url($card['link']); ?>" class="usecase-link-box">
                            <span>Explore Solution</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 17L17 7"></path>
                                <path d="M7 7h10v10"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
