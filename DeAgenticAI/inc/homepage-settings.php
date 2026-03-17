<?php
/**
 * Homepage section metaboxes and meta helpers.
 *
 * Registers the "Homepage Section Content" meta-box on the front page
 * (front-page.php template) so editors can change headlines, descriptions,
 * and use-case card copy from wp-admin without touching PHP files.
 *
 * @package Aura
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -------------------------------------------------------------------------
// Data helpers
// -------------------------------------------------------------------------

/**
 * Return the resolved ID of the WordPress front page, or 0 if not set.
 *
 * @return int
 */
function aura_get_home_page_id() {
    if ( 'page' !== get_option( 'show_on_front' ) ) {
        return 0;
    }
    return (int) get_option( 'page_on_front' );
}

/**
 * Read a single homepage meta field, falling back to a default.
 *
 * Uses the current queried page ID when called from a template (avoids an
 * extra DB query when we are already on the front page).
 *
 * @param string $key     Meta key suffix (without leading _aura_home_ prefix).
 * @param string $default Fallback value when the meta row is empty.
 * @return string
 */
function aura_get_home_meta( $key, $default = '' ) {
    // Prefer the currently-loaded post when on the front page.
    if ( is_front_page() && is_page() ) {
        $page_id = (int) get_the_ID();
    } else {
        $page_id = aura_get_home_page_id();
    }

    if ( $page_id <= 0 ) {
        return $default;
    }

    $value = get_post_meta( $page_id, '_aura_home_' . $key, true );
    return ( '' !== (string) $value ) ? (string) $value : $default;
}

/**
 * Return an array of bullet-point strings from a newline-separated meta value.
 * Falls back to a preset array when the meta value is empty or not yet saved.
 *
 * @param string   $key      Meta key suffix (without _aura_home_ prefix).
 * @param string[] $defaults Fallback array of bullet strings.
 * @return string[]
 */
function aura_get_home_meta_points( $key, array $defaults ) {
    $raw = aura_get_home_meta( $key, '' );
    if ( '' === $raw ) {
        return $defaults;
    }
    $lines = array_filter(
        array_map( 'trim', explode( "\n", str_replace( "\r\n", "\n", $raw ) ) )
    );
    return ! empty( $lines ) ? array_values( $lines ) : $defaults;
}

// -------------------------------------------------------------------------
// Admin hooks
// -------------------------------------------------------------------------

add_action( 'add_meta_boxes_page', 'aura_register_homepage_meta_boxes' );
add_action( 'save_post_page',       'aura_save_homepage_meta', 10, 1 );
add_action( 'init',                 'aura_migrate_legacy_home_features_copy', 30 );
add_action( 'init',                 'aura_migrate_legacy_home_architecture_copy', 31 );
add_action( 'init',                 'aura_migrate_legacy_home_usecases_copy', 32 );

/**
 * Migrate legacy features section copy to the canonical control-plane framing.
 *
 * Only updates values that exactly match the old boilerplate defaults so custom
 * editorial copy is preserved.
 */
function aura_migrate_legacy_home_features_copy() {
    $page_id = aura_get_home_page_id();
    if ( $page_id <= 0 ) {
        return;
    }

    $migration_flag = '_aura_home_features_copy_migrated_v2';
    if ( '1' === (string) get_post_meta( $page_id, $migration_flag, true ) ) {
        return;
    }

    $legacy = array(
        'features_title' => 'The Foundations of',
        'features_title_muted' => 'Safe Autonomy',
        'features_description' => 'DeAgenticAI is built on three essential pillars that turn policy-driven ideas into real, enforceable control. Together, they provide the framework your AI agents need to operate securely, predictably, and efficiently in Web3 environments.',
    );

    $canonical = array(
        'features_title' => 'How the Control Plane Works',
        'features_title_muted' => '',
        'features_description' => 'DeAgenticAI enforces safe autonomous execution through three architecture differentiators: Intent Sanitization at the boundary, Policy-First Enforcement at the cryptographic signing layer, and MPC Distributed Execution with no single point of failure.',
    );

    foreach ( $legacy as $key => $legacy_value ) {
        $meta_key = '_aura_home_' . $key;
        $current = trim( (string) get_post_meta( $page_id, $meta_key, true ) );

        if ( $legacy_value !== $current ) {
            continue;
        }

        $replacement = isset( $canonical[ $key ] ) ? (string) $canonical[ $key ] : '';
        if ( '' === $replacement ) {
            delete_post_meta( $page_id, $meta_key );
            continue;
        }

        update_post_meta( $page_id, $meta_key, $replacement );
    }

    update_post_meta( $page_id, $migration_flag, '1' );
}

/**
 * Migrate legacy architecture section description to canonical layer-based copy.
 *
 * Only updates when the current saved value exactly matches the old boilerplate
 * text so custom editorial updates remain untouched.
 */
function aura_migrate_legacy_home_architecture_copy() {
    $page_id = aura_get_home_page_id();
    if ( $page_id <= 0 ) {
        return;
    }

    $migration_flag = '_aura_home_architecture_copy_migrated_v2';
    if ( '1' === (string) get_post_meta( $page_id, $migration_flag, true ) ) {
        return;
    }

    $legacy = 'DeAgenticAI is composed of modular, composable infrastructure designed to enforce policy-based control, enable agent-aware wallets, and provide a secure execution layer for autonomous AI in Web3. Each component lays the foundation for safe, human-aligned autonomy.';
    $canonical = 'DeAgenticAI is organized into six control-plane layers: Agent Identity & Registry, Intent Sanitization, Policy Engine, Fraud Detection, MPC Distributed Execution, and Chain Abstraction. Together they enforce safe, verifiable autonomous execution across Web3 and enterprise environments.';

    $meta_key = '_aura_home_arch_description';
    $current = trim( (string) get_post_meta( $page_id, $meta_key, true ) );
    if ( $legacy === $current ) {
        update_post_meta( $page_id, $meta_key, $canonical );
    }

    update_post_meta( $page_id, $migration_flag, '1' );
}

/**
 * Migrate legacy 3-card usecase copy to canonical 6-ICP defaults.
 *
 * Existing customized values are preserved unless they exactly match old
 * boilerplate defaults. New card slots (4-6) are initialized when empty.
 */
function aura_migrate_legacy_home_usecases_copy() {
    $page_id = aura_get_home_page_id();
    if ( $page_id <= 0 ) {
        return;
    }

    $migration_flag = '_aura_home_usecases_copy_migrated_v2';
    if ( '1' === (string) get_post_meta( $page_id, $migration_flag, true ) ) {
        return;
    }

    $legacy = array(
        1 => array(
            'title'  => 'For AI Infrastructure Teams',
            'url'    => home_url( '/solutions/ai-infrastructure' ),
            'points' => "Policy-enforced agent wallets\nDeterministic permission layers\nSecure delegation & session control\nAuditable agent transaction logic",
        ),
        2 => array(
            'title'  => 'For Web3 Startups',
            'url'    => home_url( '/solutions/web3-startups' ),
            'points' => "Plug-and-play AI wallet layer\nMPC-based custody models\nFaster go-to-market for AI agents\nBuilt-in compliance primitives",
        ),
        3 => array(
            'title'  => 'For Crypto Enterprises',
            'url'    => home_url( '/solutions/enterprise' ),
            'points' => "Institutional-grade MPC architecture\nAI governance & control enforcement\nEnterprise compliance alignment\nRisk-aware agent execution",
        ),
    );

    $canonical = array(
        1 => array(
            'title'  => 'AI Infrastructure Teams',
            'url'    => home_url( '/solutions/ai-infrastructure' ),
            'points' => "Ship governed execution without building key management from scratch\nReplace ad-hoc wallet orchestration with deterministic policy controls\nAvoid single-vendor custody lock-in as the stack evolves\nGet audit-grade traces for every agent decision and signature",
        ),
        2 => array(
            'title'  => 'Web3 Startups',
            'url'    => home_url( '/solutions/web3-startups' ),
            'points' => "Reduce platform risk from third-party wallet vendor consolidation\nLaunch agent products without rebuilding custody and signing primitives\nEnforce policy boundaries before transactions hit the chain\nPreserve product velocity while hardening execution governance",
        ),
        3 => array(
            'title'  => 'DAO Treasury',
            'url'    => home_url( '/solutions/dao-treasury' ),
            'points' => "Cut governance latency on routine treasury operations\nPrevent signer concentration and key-person execution risk\nEnforce proposal-aligned spending policies automatically\nKeep transparent, on-chain auditable trails for every action",
        ),
        4 => array(
            'title'  => 'Enterprise',
            'url'    => home_url( '/solutions/enterprise' ),
            'points' => "Map autonomous execution to internal approval and risk controls\nEnforce cryptographic policy across teams, wallets, and workflows\nIntegrate governed delegation without replacing core systems\nProduce regulator-ready evidence for oversight and audit",
        ),
        5 => array(
            'title'  => 'RWA Management',
            'url'    => home_url( '/solutions/rwa-management' ),
            'points' => "Operate tokenized portfolios with bounded agent authority\nConstrain rebalancing, settlement, and transfer rules by policy\nReduce continuity risk with distributed signing infrastructure\nMaintain traceable control for institutional counterparties",
        ),
        6 => array(
            'title'  => 'Compliance / MiCA & DORA',
            'url'    => home_url( '/solutions/compliance' ),
            'points' => "Implement explicit control surfaces for regulated environments\nDetect anomalous intents before signing and broadcast\nGenerate verifiable audit trails for supervisory review\nSupport MiCA and DORA resilience expectations by design",
        ),
    );

    foreach ( $canonical as $index => $card ) {
        $title_key = '_aura_home_usecase_' . (int) $index . '_title';
        $url_key = '_aura_home_usecase_' . (int) $index . '_url';
        $points_key = '_aura_home_usecase_' . (int) $index . '_points';

        $current_title = trim( (string) get_post_meta( $page_id, $title_key, true ) );
        $current_url = trim( (string) get_post_meta( $page_id, $url_key, true ) );
        $current_points = trim( (string) get_post_meta( $page_id, $points_key, true ) );

        if ( isset( $legacy[ $index ] ) ) {
            if ( $legacy[ $index ]['title'] === $current_title ) {
                update_post_meta( $page_id, $title_key, $card['title'] );
            }
            if ( $legacy[ $index ]['url'] === $current_url ) {
                update_post_meta( $page_id, $url_key, $card['url'] );
            }
            if ( $legacy[ $index ]['points'] === $current_points ) {
                update_post_meta( $page_id, $points_key, $card['points'] );
            }
        }

        if ( '' === $current_title ) {
            update_post_meta( $page_id, $title_key, $card['title'] );
        }
        if ( '' === $current_url ) {
            update_post_meta( $page_id, $url_key, $card['url'] );
        }
        if ( '' === $current_points ) {
            update_post_meta( $page_id, $points_key, $card['points'] );
        }
    }

    update_post_meta( $page_id, $migration_flag, '1' );
}

/**
 * Register the homepage content meta box, only on the front page template.
 *
 * @param \WP_Post $post Current post object.
 */
function aura_register_homepage_meta_boxes( $post ) {
    $template = (string) get_post_meta( (int) $post->ID, '_wp_page_template', true );
    if ( 'front-page.php' !== $template ) {
        return;
    }
    add_meta_box(
        'aura_homepage_content',
        __( 'Homepage Section Content', 'aura' ),
        'aura_render_homepage_meta_box',
        'page',
        'normal',
        'high'
    );
}

/**
 * Render the homepage content meta box.
 *
 * @param \WP_Post $post
 */
function aura_render_homepage_meta_box( $post ) {
    wp_nonce_field( 'aura_homepage_meta_save_' . $post->ID, 'aura_homepage_meta_nonce' );

    // Helper: escaped attribute value for text/url inputs.
    $f = static function( $key, $default = '' ) use ( $post ) {
        $val = get_post_meta( (int) $post->ID, '_aura_home_' . $key, true );
        return ( '' !== (string) $val ) ? esc_attr( (string) $val ) : esc_attr( $default );
    };

    // Helper: escaped textarea value.
    $fta = static function( $key, $default = '' ) use ( $post ) {
        $val = get_post_meta( (int) $post->ID, '_aura_home_' . $key, true );
        return ( '' !== (string) $val ) ? esc_textarea( (string) $val ) : esc_textarea( $default );
    };

    $secondary_url_raw = get_post_meta( (int) $post->ID, '_aura_home_hero_secondary_url', true );
    $secondary_url     = '' !== (string) $secondary_url_raw
        ? esc_attr( (string) $secondary_url_raw )
        : esc_attr( home_url( '/ecosystem' ) );

    $semantic_triple = function_exists( 'aura_get_semantic_triple' )
        ? aura_get_semantic_triple()
        : "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do - in Web3 and enterprise financial environments.";

    $usecase_defaults = array(
        1 => array(
            'title'  => 'AI Infrastructure Teams',
            'url'    => home_url( '/solutions/ai-infrastructure' ),
            'points' => "Ship governed execution without building key management from scratch\nReplace ad-hoc wallet orchestration with deterministic policy controls\nAvoid single-vendor custody lock-in as the stack evolves\nGet audit-grade traces for every agent decision and signature",
        ),
        2 => array(
            'title'  => 'Web3 Startups',
            'url'    => home_url( '/solutions/web3-startups' ),
            'points' => "Reduce platform risk from third-party wallet vendor consolidation\nLaunch agent products without rebuilding custody and signing primitives\nEnforce policy boundaries before transactions hit the chain\nPreserve product velocity while hardening execution governance",
        ),
        3 => array(
            'title'  => 'DAO Treasury',
            'url'    => home_url( '/solutions/dao-treasury' ),
            'points' => "Cut governance latency on routine treasury operations\nPrevent signer concentration and key-person execution risk\nEnforce proposal-aligned spending policies automatically\nKeep transparent, on-chain auditable trails for every action",
        ),
        4 => array(
            'title'  => 'Enterprise',
            'url'    => home_url( '/solutions/enterprise' ),
            'points' => "Map autonomous execution to internal approval and risk controls\nEnforce cryptographic policy across teams, wallets, and workflows\nIntegrate governed delegation without replacing core systems\nProduce regulator-ready evidence for oversight and audit",
        ),
        5 => array(
            'title'  => 'RWA Management',
            'url'    => home_url( '/solutions/rwa-management' ),
            'points' => "Operate tokenized portfolios with bounded agent authority\nConstrain rebalancing, settlement, and transfer rules by policy\nReduce continuity risk with distributed signing infrastructure\nMaintain traceable control for institutional counterparties",
        ),
        6 => array(
            'title'  => 'Compliance / MiCA & DORA',
            'url'    => home_url( '/solutions/compliance' ),
            'points' => "Implement explicit control surfaces for regulated environments\nDetect anomalous intents before signing and broadcast\nGenerate verifiable audit trails for supervisory review\nSupport MiCA and DORA resilience expectations by design",
        ),
    );
    ?>
    <style>
    .aura-hm { margin-bottom: 28px; }
    .aura-hm h3 { margin: 0 0 12px; padding: 6px 10px; background: #f0f0f0; border-left: 3px solid #2271b1; font-size: 13px; }
    .aura-hm-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 10px; }
    .aura-hm-row > * { flex: 1 1 240px; }
    .aura-hm label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 12px; color: #555; }
    .aura-hm input[type=text], .aura-hm input[type=url], .aura-hm textarea { width: 100%; box-sizing: border-box; }
    .aura-hm textarea { min-height: 80px; }
    .aura-hm textarea.points { min-height: 100px; font-family: monospace; font-size: 12px; }
    .aura-hm .points-hint { font-size: 11px; color: #888; margin-top: 2px; }
    .aura-hm-card { border: 1px solid #ddd; padding: 12px; margin-bottom: 12px; border-radius: 4px; }
    .aura-hm-card .aura-hm-card-label { font-weight: 700; font-size: 13px; margin-bottom: 8px; display: block; }
    </style>

    <div class="aura-hm">
        <h3><?php esc_html_e( 'Hero Section', 'aura' ); ?></h3>
        <div class="aura-hm-row">
            <div>
                <label for="aura-hero-headline"><?php esc_html_e( 'Headline Line 1', 'aura' ); ?></label>
                <input id="aura-hero-headline" type="text" name="aura_home[hero_headline]"
                       value="<?php echo $f( 'hero_headline', 'The Agentic Control Plane for' ); ?>">
            </div>
            <div>
                <label for="aura-hero-accent"><?php esc_html_e( 'Headline Accent (line 2)', 'aura' ); ?></label>
                <input id="aura-hero-accent" type="text" name="aura_home[hero_headline_accent]"
                       value="<?php echo $f( 'hero_headline_accent', 'AI Agent Authority in Web3 and Enterprise' ); ?>">
            </div>
        </div>
        <div style="margin-bottom:10px;">
            <label for="aura-hero-desc"><?php esc_html_e( 'Hero Description', 'aura' ); ?></label>
            <textarea id="aura-hero-desc" name="aura_home[hero_description]"><?php echo $fta( 'hero_description', $semantic_triple ); ?></textarea>
        </div>
        <div class="aura-hm-row">
            <div>
                <label for="aura-hero-cta"><?php esc_html_e( 'Primary CTA Label', 'aura' ); ?></label>
                <input id="aura-hero-cta" type="text" name="aura_home[hero_cta_label]"
                       value="<?php echo $f( 'hero_cta_label', 'Join Waitlist' ); ?>">
            </div>
            <div>
                <label for="aura-hero-sec-label"><?php esc_html_e( 'Secondary Link Label', 'aura' ); ?></label>
                <input id="aura-hero-sec-label" type="text" name="aura_home[hero_secondary_label]"
                       value="<?php echo $f( 'hero_secondary_label', 'View Ecosystem' ); ?>">
            </div>
            <div>
                <label for="aura-hero-sec-url"><?php esc_html_e( 'Secondary Link URL', 'aura' ); ?></label>
                <input id="aura-hero-sec-url" type="url" name="aura_home[hero_secondary_url]"
                       value="<?php echo $secondary_url; ?>">
            </div>
        </div>
    </div>

    <div class="aura-hm">
        <h3><?php esc_html_e( 'Features Section', 'aura' ); ?></h3>
        <div class="aura-hm-row">
            <div>
                <label><?php esc_html_e( 'Section Heading Line 1', 'aura' ); ?></label>
                <input type="text" name="aura_home[features_title]"
                       value="<?php echo $f( 'features_title', 'How the Control Plane Works' ); ?>">
            </div>
            <div>
                <label><?php esc_html_e( 'Section Heading Line 2 (muted)', 'aura' ); ?></label>
                <input type="text" name="aura_home[features_title_muted]"
                       value="<?php echo $f( 'features_title_muted', '' ); ?>">
            </div>
        </div>
        <div>
            <label><?php esc_html_e( 'Section Description', 'aura' ); ?></label>
            <textarea name="aura_home[features_description]"><?php echo $fta( 'features_description', 'DeAgenticAI enforces safe autonomous execution through three architecture differentiators: Intent Sanitization at the boundary, Policy-First Enforcement at the cryptographic signing layer, and MPC Distributed Execution with no single point of failure.' ); ?></textarea>
        </div>
    </div>

    <div class="aura-hm">
        <h3><?php esc_html_e( 'Architecture Section', 'aura' ); ?></h3>
        <div class="aura-hm-row">
            <div>
                <label><?php esc_html_e( 'Section Heading Line 1', 'aura' ); ?></label>
                <input type="text" name="aura_home[arch_title]"
                       value="<?php echo $f( 'arch_title', 'Modular building blocks' ); ?>">
            </div>
            <div>
                <label><?php esc_html_e( 'Section Heading Line 2 (muted italic)', 'aura' ); ?></label>
                <input type="text" name="aura_home[arch_title_muted]"
                       value="<?php echo $f( 'arch_title_muted', 'for agentic systems' ); ?>">
            </div>
        </div>
        <div>
            <label><?php esc_html_e( 'Section Description', 'aura' ); ?></label>
            <textarea name="aura_home[arch_description]"><?php echo $fta( 'arch_description', 'DeAgenticAI is organized into six control-plane layers: Agent Identity & Registry, Intent Sanitization, Policy Engine, Fraud Detection, MPC Distributed Execution, and Chain Abstraction. Together they enforce safe, verifiable autonomous execution across Web3 and enterprise environments.' ); ?></textarea>
        </div>
    </div>

    <div class="aura-hm">
        <h3><?php esc_html_e( 'Use Cases Section', 'aura' ); ?></h3>
        <div class="aura-hm-row">
            <div>
                <label><?php esc_html_e( 'Section Heading Line 1', 'aura' ); ?></label>
                <input type="text" name="aura_home[usecases_title]"
                       value="<?php echo $f( 'usecases_title', 'Control, safety, and autonomy' ); ?>">
            </div>
            <div>
                <label><?php esc_html_e( 'Section Heading Line 2 (muted)', 'aura' ); ?></label>
                <input type="text" name="aura_home[usecases_title_muted]"
                       value="<?php echo $f( 'usecases_title_muted', 'for agentic AI in Web3' ); ?>">
            </div>
        </div>
        <div style="margin-bottom:10px;">
            <label><?php esc_html_e( 'Section Description', 'aura' ); ?></label>
            <textarea name="aura_home[usecases_description]"><?php echo $fta( 'usecases_description', 'DeAgenticAI is built for Web3 builders, AI engineers, and security-conscious organizations seeking policy-based control and safe execution for autonomous agents. Our infrastructure enables human-defined constraints and composable integration across decentralized services.' ); ?></textarea>
        </div>

        <?php foreach ( $usecase_defaults as $n => $card_defaults ) :
            $card_title = get_post_meta( (int) $post->ID, "_aura_home_usecase_{$n}_title", true );
            $card_url   = get_post_meta( (int) $post->ID, "_aura_home_usecase_{$n}_url", true );
            $card_pts   = get_post_meta( (int) $post->ID, "_aura_home_usecase_{$n}_points", true );
        ?>
        <div class="aura-hm-card">
            <span class="aura-hm-card-label"><?php echo esc_html( sprintf( __( 'Card %d', 'aura' ), $n ) ); ?></span>
            <div class="aura-hm-row">
                <div>
                    <label><?php esc_html_e( 'Title', 'aura' ); ?></label>
                    <input type="text" name="aura_home[usecase_<?php echo (int) $n; ?>_title]"
                           value="<?php echo esc_attr( '' !== (string) $card_title ? (string) $card_title : $card_defaults['title'] ); ?>">
                </div>
                <div>
                    <label><?php esc_html_e( 'Link URL', 'aura' ); ?></label>
                    <input type="url" name="aura_home[usecase_<?php echo (int) $n; ?>_url]"
                           value="<?php echo esc_attr( '' !== (string) $card_url ? (string) $card_url : $card_defaults['url'] ); ?>">
                </div>
            </div>
            <div>
                <label><?php esc_html_e( 'Bullet Points (one per line)', 'aura' ); ?></label>
                <textarea class="points" name="aura_home[usecase_<?php echo (int) $n; ?>_points]"><?php echo esc_textarea( '' !== (string) $card_pts ? (string) $card_pts : $card_defaults['points'] ); ?></textarea>
                <p class="points-hint"><?php esc_html_e( 'One bullet point per line.', 'aura' ); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Save homepage meta fields.
 *
 * @param int $post_id Post ID.
 */
function aura_save_homepage_meta( $post_id ) {
    $post_id = (int) $post_id;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['aura_homepage_meta_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce(
        sanitize_text_field( wp_unslash( (string) $_POST['aura_homepage_meta_nonce'] ) ),
        'aura_homepage_meta_save_' . $post_id
    ) ) {
        return;
    }

    if ( ! isset( $_POST['aura_home'] ) || ! is_array( $_POST['aura_home'] ) ) {
        return;
    }

    $text_fields = array(
        'hero_headline', 'hero_headline_accent', 'hero_cta_label', 'hero_secondary_label',
        'features_title', 'features_title_muted',
        'arch_title', 'arch_title_muted',
        'usecases_title', 'usecases_title_muted',
        'usecase_1_title', 'usecase_2_title', 'usecase_3_title',
        'usecase_4_title', 'usecase_5_title', 'usecase_6_title',
    );

    $url_fields = array(
        'hero_secondary_url',
        'usecase_1_url', 'usecase_2_url', 'usecase_3_url',
        'usecase_4_url', 'usecase_5_url', 'usecase_6_url',
    );

    $textarea_fields = array(
        'hero_description',
        'features_description',
        'arch_description',
        'usecases_description',
        'usecase_1_points', 'usecase_2_points', 'usecase_3_points',
        'usecase_4_points', 'usecase_5_points', 'usecase_6_points',
    );

    $raw = $_POST['aura_home']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized below per field.

    foreach ( $text_fields as $key ) {
        $value = isset( $raw[ $key ] ) ? sanitize_text_field( wp_unslash( (string) $raw[ $key ] ) ) : '';
        if ( '' !== $value ) {
            update_post_meta( $post_id, '_aura_home_' . $key, $value );
        } else {
            delete_post_meta( $post_id, '_aura_home_' . $key );
        }
    }

    foreach ( $url_fields as $key ) {
        $value = isset( $raw[ $key ] ) ? esc_url_raw( wp_unslash( (string) $raw[ $key ] ) ) : '';
        if ( '' !== $value ) {
            update_post_meta( $post_id, '_aura_home_' . $key, $value );
        } else {
            delete_post_meta( $post_id, '_aura_home_' . $key );
        }
    }

    foreach ( $textarea_fields as $key ) {
        $value = isset( $raw[ $key ] ) ? sanitize_textarea_field( wp_unslash( (string) $raw[ $key ] ) ) : '';
        if ( '' !== $value ) {
            update_post_meta( $post_id, '_aura_home_' . $key, $value );
        } else {
            delete_post_meta( $post_id, '_aura_home_' . $key );
        }
    }
}
