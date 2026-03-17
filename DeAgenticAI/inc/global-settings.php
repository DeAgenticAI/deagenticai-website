<?php
/**
 * Global content settings — social links, footer tagline, waitlist copy, site brand.
 *
 * Settings are stored in the 'aura_content' WP option and exposed via the
 * "Appearance → Aura Content" admin page.
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
 * Return all global content settings, merged with built-in defaults.
 *
 * Result is statically cached for the duration of the request.
 *
 * @return array<string,string>
 */
function aura_get_content_settings() {
    static $cache = null;
    if ( null !== $cache ) {
        return $cache;
    }

    $defaults = array(
        'site_name'             => 'DeAgenticAI',
        'org_url'               => '',
        'founding_year'         => '2026',
        'product_name'          => 'Agentic Control Plane',
        'service_type'          => 'AI Agent Infrastructure',
        'social_image_url'      => '',
        'twitter_url'           => '',
        'linkedin_url'          => '',
        'github_url'            => '',
        'email_address'         => '',
        'footer_tagline'        => 'The Agentic Control Plane for Web3 and the Enterprise. Making autonomous intelligence safe to own and move capital.',
        'waitlist_title'        => 'Shape the Control Layer for',
        'waitlist_title_accent' => 'Agentic AI',
        'waitlist_description'  => "Our early access is invite-only. Join the design partner waitlist to track DeAgenticAI's progress and shape governed autonomous execution with our team. No marketing fluff-just infrastructure updates.",
        'waitlist_page_title'   => 'Join the Waitlist',
        'waitlist_page_description' => 'Get early access updates and product milestones from DeAgenticAI.',
        'about_badge'           => 'About DeAgenticAI',
        'about_title'           => 'We Built the Missing Layer Between AI Agents and On-Chain Authority',
        'about_lead'            => 'Autonomous AI agents are already operating on-chain. They are executing treasury strategies, interacting with DeFi protocols, and beginning to represent institutional interests in governance systems. The infrastructure they are running on - private key management, multisig governance, rule-based automation - was designed for human actors. It breaks down under every assumption that AI introduces. DeAgenticAI exists to close that gap at the infrastructure layer.',
        'about_contact_title'   => 'Work With Us',
        'about_contact_intro'   => 'DeAgenticAI is currently working with a select group of design partners - DAOs, Web3 funds, and RWA platform operators - who need governed autonomous execution before it is available at general access. If your organisation is evaluating infrastructure for AI agent operations, we want to hear from you.',
        'about_contact_button_label' => 'Send Transmission',
    );

    $stored = get_option( 'aura_content', array() );
    if ( is_array( $stored ) ) {
        $has_updates = false;

        $legacy_tagline = 'The modern standard for financial protocols and secure agent delegation. Empowering the next generation of wealth management.';
        if ( isset( $stored['footer_tagline'] ) && $legacy_tagline === trim( (string) $stored['footer_tagline'] ) ) {
            $stored['footer_tagline'] = $defaults['footer_tagline'];
            $has_updates = true;
        }

        $legacy_waitlist = "Our early access is invite-only. Join the waitlist to stay informed about DeAgenticAI's progress and help define the future of safe, policy-driven autonomy in Web3. No marketing fluff-just infrastructure updates.";
        if ( isset( $stored['waitlist_description'] ) && $legacy_waitlist === trim( (string) $stored['waitlist_description'] ) ) {
            $stored['waitlist_description'] = $defaults['waitlist_description'];
            $has_updates = true;
        }

        if ( $has_updates ) {
            update_option( 'aura_content', $stored );
        }
    }

    $cache  = wp_parse_args( is_array( $stored ) ? $stored : array(), $defaults );
    return $cache;
}

/**
 * Read a single global content setting.
 *
 * @param string $key     Setting key.
 * @param string $default Optional per-call override default (only used when
 *                        the option AND the built-in default are both empty).
 * @return string
 */
function aura_get_content( $key, $default = '' ) {
    $settings = aura_get_content_settings();
    if ( isset( $settings[ $key ] ) && '' !== (string) $settings[ $key ] ) {
        return (string) $settings[ $key ];
    }
    return '' !== $default ? $default : '';
}

/**
 * Canonical semantic triple required by the GEO blueprint.
 *
 * @return string
 */
function aura_get_semantic_triple() {
    return "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do - in Web3 and enterprise financial environments.";
}

// -------------------------------------------------------------------------
// Admin — settings registration
// -------------------------------------------------------------------------

add_action( 'admin_init', 'aura_register_content_settings' );
add_action( 'admin_menu', 'aura_register_content_settings_page' );

/**
 * Register the aura_content option with Settings API.
 */
function aura_register_content_settings() {
    register_setting(
        'aura_content_settings_group',
        'aura_content',
        array(
            'sanitize_callback' => 'aura_sanitize_content_settings',
            'default'           => array(),
        )
    );
}

/**
 * Sanitize global content settings before saving.
 *
 * @param mixed $input Raw POST data.
 * @return array<string,string>
 */
function aura_sanitize_content_settings( $input ) {
    $input = is_array( $input ) ? $input : array();

    $text_fields     = array(
        'site_name',
        'waitlist_title',
        'waitlist_title_accent',
        'founding_year',
        'product_name',
        'service_type',
        'waitlist_page_title',
        'about_badge',
        'about_title',
        'about_contact_title',
        'about_contact_button_label',
    );
    $textarea_fields = array(
        'footer_tagline',
        'waitlist_description',
        'waitlist_page_description',
        'about_lead',
        'about_contact_intro',
    );
    $url_fields      = array( 'org_url', 'social_image_url', 'twitter_url', 'linkedin_url', 'github_url' );
    $email_fields    = array( 'email_address' );

    $out = array();

    foreach ( $text_fields as $key ) {
        $out[ $key ] = isset( $input[ $key ] )
            ? sanitize_text_field( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    foreach ( $textarea_fields as $key ) {
        $out[ $key ] = isset( $input[ $key ] )
            ? sanitize_textarea_field( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    foreach ( $url_fields as $key ) {
        $out[ $key ] = isset( $input[ $key ] )
            ? esc_url_raw( wp_unslash( (string) $input[ $key ] ) )
            : '';
    }

    foreach ( $email_fields as $key ) {
        $out[ $key ] = isset( $input[ $key ] )
            ? sanitize_email( (string) $input[ $key ] )
            : '';
    }

    return $out;
}

/**
 * Register the "Aura Content" settings page under Appearance.
 */
function aura_register_content_settings_page() {
    add_theme_page(
        __( 'Aura Content', 'aura' ),
        __( 'Aura Content', 'aura' ),
        'manage_options',
        'aura-content',
        'aura_render_content_settings_page'
    );
}

/**
 * Render the Aura Content settings page.
 */
function aura_render_content_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    wp_safe_redirect( admin_url( 'admin.php?page=aura-site-settings&tab=sitewide' ) );
    exit;
}
