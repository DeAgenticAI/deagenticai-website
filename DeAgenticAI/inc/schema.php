<?php
/**
 * DeAgenticAI Schema — canonical JSON-LD output for all content types.
 *
 * This file owns schema output for: homepage, glossary entries,
 * pillar pages, guides, research, solutions, compare pages, datasets,
 * the ecosystem FAQPage, and BreadcrumbList for all non-homepage content.
 *
 * Duplicate schema blocks previously in learn-content.php and
 * marketing-pages.php have been stripped; this file is the single source.
 *
 * @package Aura
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Normalize author sameAs values from post meta into a clean URL list.
 *
 * Supports either:
 * - array meta values
 * - newline/comma separated string values
 *
 * @param mixed $raw Raw meta value.
 * @return array<int,string>
 */
function dea_normalize_author_same_as( $raw ) {
    $candidates = array();

    if ( is_array( $raw ) ) {
        $candidates = $raw;
    } elseif ( is_string( $raw ) ) {
        $raw        = trim( $raw );
        $candidates = '' === $raw ? array() : preg_split( '/[\r\n,]+/', $raw );
    }

    $urls = array();
    foreach ( $candidates as $candidate ) {
        if ( ! is_string( $candidate ) ) {
            continue;
        }

        $candidate = trim( $candidate );
        if ( '' === $candidate ) {
            continue;
        }

        $url = esc_url_raw( $candidate );
        if ( '' === $url || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
            continue;
        }

        $urls[] = $url;
    }

    return array_values( array_unique( $urls ) );
}

/**
 * Return the queried author profile page ID when current request is an author page.
 *
 * @return int
 */
function dea_get_author_profile_page_id() {
    if ( ! is_page() ) {
        return 0;
    }

    $page_id = (int) get_queried_object_id();
    if ( $page_id <= 0 ) {
        return 0;
    }

    // Primary path: explicit author profile template assignment.
    if ( is_page_template( 'page-author.php' ) ) {
        return $page_id;
    }

    // Fallback path: child pages under /authors/ even if template assignment is missing.
    $parent_id = (int) wp_get_post_parent_id( $page_id );
    if ( $parent_id <= 0 ) {
        return 0;
    }

    if ( 'authors' === (string) get_post_field( 'post_name', $parent_id ) ) {
        return $page_id;
    }

    return 0;
}

/**
 * Build reusable author profile metadata for template + schema surfaces.
 *
 * @param int $page_id Author page ID.
 * @return array<string,mixed>
 */
function dea_get_author_profile_metadata( $page_id ) {
    $page_id = (int) $page_id;
    if ( $page_id <= 0 ) {
        return array(
            'name'      => '',
            'slug'      => '',
            'user_id'   => 0,
            'job_title' => '',
            'short_bio' => 'Reza Jahankohan is the Founder of DeAgenticAI and publishes on agentic policy enforcement, governed MPC signing, and AI-agent authority controls.',
            'long_bio'  => 'Reza Jahankohan is the Founder of DeAgenticAI and publishes research and implementation guidance on agentic policy enforcement, governed MPC signing, and AI-agent authority controls across Web3 and enterprise financial systems. His work focuses on making autonomous AI execution safe, governed, and auditable through cryptographic policy enforcement.',
            'same_as'   => array(),
            'panel_label' => 'Authority Profile',
            'panel_title' => 'Reza Jahankohan — Founder, DeAgenticAI',
            'panel_body'  => 'Reza Jahankohan is the Founder of DeAgenticAI and publishes on agentic policy enforcement, governed MPC signing, and AI-agent authority controls in Web3 and enterprise financial systems. This is his canonical author profile with verified references.',
        );
    }

    $slug        = (string) get_post_field( 'post_name', $page_id );
    $name        = (string) get_the_title( $page_id );
    $user_id     = (int) get_post_meta( $page_id, '_dea_author_user_id', true );
    $job_title   = trim( (string) get_post_meta( $page_id, '_dea_author_job_title', true ) );
    $short_bio   = trim( (string) get_post_meta( $page_id, '_dea_author_short_bio', true ) );
    $long_bio    = trim( (string) get_post_meta( $page_id, '_dea_author_long_bio', true ) );
    $same_as     = dea_normalize_author_same_as( get_post_meta( $page_id, '_dea_author_same_as', true ) );
    $panel_label = trim( (string) get_post_meta( $page_id, '_dea_author_panel_label', true ) );
    $panel_title = trim( (string) get_post_meta( $page_id, '_dea_author_panel_title', true ) );
    $panel_body  = trim( (string) get_post_meta( $page_id, '_dea_author_panel_body', true ) );

    if ( $user_id > 0 && function_exists( 'dea_get_author_user_same_as_urls' ) && empty( $same_as ) ) {
        $same_as = dea_get_author_user_same_as_urls( $user_id );
    }

    // Backward-compatible defaults for the initial seeded profile.
    if ( 'reza-jahankohan' === $slug ) {
        if ( '' === $job_title ) {
            $job_title = 'Founder, DeAgenticAI';
        }
        if ( '' === $short_bio ) {
            $short_bio = 'Reza Jahankohan is the Founder of DeAgenticAI and publishes on agentic policy enforcement, governed MPC signing, and AI-agent authority controls.';
        }
        if ( '' === $long_bio ) {
            $long_bio = 'Reza Jahankohan is the Founder of DeAgenticAI and publishes research and implementation guidance on agentic policy enforcement, governed MPC signing, and AI-agent authority controls across Web3 and enterprise financial systems. His work focuses on making autonomous AI execution safe, governed, and auditable through cryptographic policy enforcement.';
        }
        if ( empty( $same_as ) ) {
            $same_as = array(
                'https://www.linkedin.com/in/rezajahankohan/',
                'https://x.com/RJahankohan',
            );
        }
    }

    if ( '' === $short_bio ) {
        $short_bio = trim( (string) get_post_field( 'post_excerpt', $page_id ) );
    }
    if ( '' === $short_bio ) {
        $short_bio = wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $page_id ) ), 38, '...' );
    }

    if ( '' === $panel_label ) {
        $panel_label = 'Authority Profile';
    }
    if ( '' === $panel_title ) {
        $panel_title = 'Reza Jahankohan — Founder, DeAgenticAI';
    }
    if ( '' === $panel_body ) {
        $panel_body = 'Reza Jahankohan is the Founder of DeAgenticAI and publishes on agentic policy enforcement, governed MPC signing, and AI-agent authority controls in Web3 and enterprise financial systems. This is his canonical author profile with verified references.';
    }

    return array(
        'name'        => $name,
        'slug'        => $slug,
        'user_id'     => $user_id,
        'job_title'   => $job_title,
        'short_bio'   => $short_bio,
        'long_bio'    => $long_bio,
        'same_as'     => $same_as,
        'panel_label' => $panel_label,
        'panel_title' => $panel_title,
        'panel_body'  => $panel_body,
    );
}

// -------------------------------------------------------------------------
// 1. Homepage — Organization + WebSite
// -------------------------------------------------------------------------

function dea_homepage_schema() {
    if ( ! is_front_page() ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $get = function_exists( 'aura_get_content' ) ? 'aura_get_content' : null;

    $site_name  = $get ? $get( 'site_name', 'DeAgenticAI' ) : 'DeAgenticAI';
    $twitter    = $get ? $get( 'twitter_url', '' ) : '';
    $github     = $get ? $get( 'github_url', '' ) : '';
    $linkedin   = $get ? $get( 'linkedin_url', '' ) : '';

    $same_as = array_values( array_filter( array( $twitter, $linkedin, $github ) ) );

    $logo_url = get_template_directory_uri() . '/images/logo.png';
    $logo_id  = get_theme_mod( 'custom_logo' );
    if ( $logo_id ) {
        $logo_data = wp_get_attachment_image_src( (int) $logo_id, 'full' );
        if ( $logo_data ) {
            $logo_url = $logo_data[0];
        }
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'       => 'Organization',
                '@id'         => home_url( '/#organization' ),
                'name'        => $site_name,
                'url'         => home_url( '/' ),
                'logo'        => $logo_url,
                'description' => "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority \u2014 separating what an agent can do from what it is authorized to do \u2014 in Web3 and enterprise financial environments.",
                'sameAs'      => $same_as,
                'foundingDate' => '2026',
                'makesOffer'  => array(
                    '@type'       => 'Offer',
                    'itemOffered' => array(
                        '@type'               => 'SoftwareApplication',
                        'name'                => 'Agentic Control Plane',
                        'applicationCategory' => 'AI Agent Infrastructure',
                    ),
                ),
            ),
            array(
                '@type'           => 'WebSite',
                '@id'             => home_url( '/#website' ),
                'url'             => home_url( '/' ),
                'name'            => $site_name,
                'publisher'       => array( '@id' => home_url( '/#organization' ) ),
                'potentialAction' => array(
                    '@type'       => 'SearchAction',
                    'target'      => home_url( '/?s={search_term_string}' ),
                    'query-input' => 'required name=search_term_string',
                ),
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_homepage_schema', 5 );

// -------------------------------------------------------------------------
// 2. Glossary Entry — DefinedTerm
// -------------------------------------------------------------------------

function dea_glossary_schema() {
    if ( ! is_page_template( 'page-glossary-entry.php' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $page_id    = (int) get_queried_object_id();
    $definition = '';
    if ( function_exists( 'aura_get_marketing_page_excerpt' ) ) {
        $definition = aura_get_marketing_page_excerpt( $page_id, 32 );
    }
    if ( '' === $definition ) {
        $definition = wp_strip_all_tags( (string) get_the_excerpt( $page_id ) );
    }

    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'DefinedTerm',
        '@id'              => get_permalink( $page_id ) . '#term',
        'name'             => get_the_title( $page_id ),
        'description'      => $definition,
        'inDefinedTermSet' => array(
            '@type' => 'DefinedTermSet',
            'name'  => 'DeAgenticAI Glossary',
            'url'   => home_url( '/glossary/' ),
        ),
        'url' => get_permalink( $page_id ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_glossary_schema', 5 );

// -------------------------------------------------------------------------
// 3. Pillar Pages — Article
// -------------------------------------------------------------------------

function dea_pillar_schema() {
    if ( ! is_singular( 'aura_pillar' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $post_id     = (int) get_queried_object_id();
    $author      = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
    $description = function_exists( 'aura_get_learn_entry_excerpt' )
        ? aura_get_learn_entry_excerpt( $post_id, 48 )
        : wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );

    $image = get_the_post_thumbnail_url( $post_id, 'full' );
    if ( ! $image ) {
        $image = get_template_directory_uri() . '/images/happy-path-hero.svg';
    }

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => get_the_title( $post_id ),
        'description'   => $description,
        'author'        => array(
            '@type'    => 'Person',
            'name'     => $author,
            'worksFor' => array( '@id' => home_url( '/#organization' ) ),
        ),
        'publisher'     => array( '@id' => home_url( '/#organization' ) ),
        'datePublished' => get_post_time( 'c', true, $post_id ),
        'dateModified'  => get_post_modified_time( 'c', true, $post_id ),
        'image'         => $image,
        'url'           => get_permalink( $post_id ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_pillar_schema', 5 );

// -------------------------------------------------------------------------
// 4. Technical Guides — TechArticle
// -------------------------------------------------------------------------

function dea_guide_schema() {
    if ( ! is_singular( 'aura_guide' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $post_id     = (int) get_queried_object_id();
    $author      = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
    $description = function_exists( 'aura_get_learn_entry_excerpt' )
        ? aura_get_learn_entry_excerpt( $post_id, 48 )
        : wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );

    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'TechArticle',
        'headline'         => get_the_title( $post_id ),
        'description'      => $description,
        'proficiencyLevel' => 'Advanced',
        'author'           => array(
            '@type'    => 'Person',
            'name'     => $author,
            'worksFor' => array( '@id' => home_url( '/#organization' ) ),
        ),
        'publisher'        => array( '@id' => home_url( '/#organization' ) ),
        'datePublished'    => get_post_time( 'c', true, $post_id ),
        'dateModified'     => get_post_modified_time( 'c', true, $post_id ),
        'url'              => get_permalink( $post_id ),
    );

    $image = get_the_post_thumbnail_url( $post_id, 'full' );
    if ( $image ) {
        $schema['image'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_guide_schema', 5 );

// -------------------------------------------------------------------------
// 5. Research Articles — ScholarlyArticle
// -------------------------------------------------------------------------

function dea_research_schema() {
    if ( ! is_singular( 'aura_research' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $post_id  = (int) get_queried_object_id();
    $author   = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
    $abstract = function_exists( 'aura_get_learn_entry_excerpt' ) ? aura_get_learn_entry_excerpt( $post_id, 48 ) : wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'ScholarlyArticle',
        'headline'      => get_the_title( $post_id ),
        'abstract'      => $abstract,
        'author'        => array(
            '@type'    => 'Person',
            'name'     => $author,
            'worksFor' => array( '@id' => home_url( '/#organization' ) ),
        ),
        'publisher'     => array( '@id' => home_url( '/#organization' ) ),
        'datePublished' => get_post_time( 'c', true, $post_id ),
        'dateModified'  => get_post_modified_time( 'c', true, $post_id ),
        'url'           => get_permalink( $post_id ),
    );

    $image = get_the_post_thumbnail_url( $post_id, 'full' );
    if ( $image ) {
        $schema['image'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_research_schema', 5 );

// -------------------------------------------------------------------------
// 6. Product Updates — NewsArticle
// -------------------------------------------------------------------------

function dea_update_schema() {
    if ( ! is_singular( 'aura_update' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $post_id      = (int) get_queried_object_id();
    $author       = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
    $description  = function_exists( 'aura_get_learn_entry_excerpt' )
        ? aura_get_learn_entry_excerpt( $post_id, 40 )
        : wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'NewsArticle',
        'headline'      => get_the_title( $post_id ),
        'description'   => $description,
        'author'        => array(
            '@type'    => 'Person',
            'name'     => $author,
            'worksFor' => array( '@id' => home_url( '/#organization' ) ),
        ),
        'publisher'     => array( '@id' => home_url( '/#organization' ) ),
        'datePublished' => get_post_time( 'c', true, $post_id ),
        'dateModified'  => get_post_modified_time( 'c', true, $post_id ),
        'url'           => get_permalink( $post_id ),
        'mainEntityOfPage' => get_permalink( $post_id ),
    );

    $image = get_the_post_thumbnail_url( $post_id, 'full' );
    if ( $image ) {
        $schema['image'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_update_schema', 5 );

// -------------------------------------------------------------------------
// 7. Solution Pages — HowTo
// -------------------------------------------------------------------------

function dea_solution_schema() {
    if ( ! is_page() ) {
        return;
    }
    if ( strpos( (string) get_permalink(), '/solutions/' ) === false ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $page_id = (int) get_queried_object_id();

    // Use payload steps when available (populated from meta boxes).
    $steps = array();
    if ( function_exists( 'aura_get_solution_entry_payload' ) ) {
        $payload = aura_get_solution_entry_payload( $page_id );
        if ( ! empty( $payload['implementation_steps'] ) ) {
            foreach ( $payload['implementation_steps'] as $step ) {
                $steps[] = array(
                    '@type' => 'HowToStep',
                    'name'  => $step['title'],
                    'text'  => $step['text'],
                );
            }
        }
    }

    // Fallback to canonical 4-step sequence from the instruction spec.
    if ( empty( $steps ) ) {
        $steps = array(
            array( '@type' => 'HowToStep', 'name' => 'Define authority boundaries',          'text' => 'Map which agent actions are auto-approved, escalated, or blocked for this ICP context.' ),
            array( '@type' => 'HowToStep', 'name' => 'Apply policy and risk controls',        'text' => 'Encode policy constraints and risk checks before any signing path is executed.' ),
            array( '@type' => 'HowToStep', 'name' => 'Integrate signing and observability',   'text' => 'Wire execution to governed signing and monitor approvals, denials, and anomalies.' ),
            array( '@type' => 'HowToStep', 'name' => 'Validate and expand safely',            'text' => 'Run staged rollout checks and expand coverage once audit and control outcomes are stable.' ),
        );
    }

    $description = function_exists( 'aura_get_marketing_page_excerpt' )
        ? aura_get_marketing_page_excerpt( $page_id, 40 )
        : wp_strip_all_tags( (string) get_the_excerpt( $page_id ) );

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'HowTo',
        'name'        => get_the_title( $page_id ),
        'description' => $description,
        'publisher'   => array( '@id' => home_url( '/#organization' ) ),
        'step'        => $steps,
        'url'         => get_permalink( $page_id ),
    );

    $image = get_the_post_thumbnail_url( $page_id, 'full' );
    if ( $image ) {
        $schema['image'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_solution_schema', 5 );

// -------------------------------------------------------------------------
// 7. Compare Pages — Article + Mentions
// -------------------------------------------------------------------------

function dea_compare_schema() {
    if ( ! is_singular( 'aura_compare' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $post_id     = (int) get_queried_object_id();
    $title       = get_the_title( $post_id );
    $description = function_exists( 'aura_get_learn_entry_excerpt' )
        ? aura_get_learn_entry_excerpt( $post_id, 48 )
        : wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );

    // Build mentions: prefer structured compare payload, fall back to title scan.
    $mentions = array();
    if ( function_exists( 'aura_get_compare_payload' ) ) {
        $payload = aura_get_compare_payload( $post_id );
        foreach ( array( 'entity_left', 'entity_right' ) as $key ) {
            $name = isset( $payload[ $key ] ) ? trim( (string) $payload[ $key ] ) : '';
            if ( '' !== $name ) {
                $mentions[] = array( '@type' => 'Thing', 'name' => $name );
            }
        }
    }

    if ( empty( $mentions ) ) {
        $competitors = array( 'Privy', 'Dynamic', 'Turnkey', 'Multisig', 'Custodial' );
        foreach ( $competitors as $c ) {
            if ( stripos( $title, $c ) !== false ) {
                $mentions[] = array( '@type' => 'Thing', 'name' => $c );
            }
        }
    }
    $mentions[] = array( '@type' => 'Thing', 'name' => 'DeAgenticAI' );

    $author  = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => $title,
        'description'   => $description,
        'author'        => array(
            '@type'    => 'Person',
            'name'     => $author,
            'worksFor' => array( '@id' => home_url( '/#organization' ) ),
        ),
        'publisher'     => array( '@id' => home_url( '/#organization' ) ),
        'datePublished' => get_post_time( 'c', true, $post_id ),
        'dateModified'  => get_post_modified_time( 'c', true, $post_id ),
        'url'           => get_permalink( $post_id ),
        'mentions'      => $mentions,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_compare_schema', 5 );

// -------------------------------------------------------------------------
// 8. Dataset Pages — Dataset
// -------------------------------------------------------------------------

function dea_dataset_schema() {
    if ( ! is_singular( 'aura_dataset' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $post_id     = (int) get_queried_object_id();
    $description = function_exists( 'aura_get_learn_entry_excerpt' )
        ? aura_get_learn_entry_excerpt( $post_id, 48 )
        : wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );
    $author      = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'Dataset',
        'name'          => get_the_title( $post_id ),
        'description'   => $description,
        'creator'       => array( '@id' => home_url( '/#organization' ) ),
        'author'        => array(
            '@type'    => 'Person',
            'name'     => $author,
            'worksFor' => array( '@id' => home_url( '/#organization' ) ),
        ),
        'datePublished' => get_post_time( 'c', true, $post_id ),
        'dateModified'  => get_post_modified_time( 'c', true, $post_id ),
        'url'           => get_permalink( $post_id ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_dataset_schema', 5 );

// -------------------------------------------------------------------------
// 9. Ecosystem Page — FAQPage
// -------------------------------------------------------------------------

function dea_ecosystem_faq_schema() {
    if ( ! is_page( 'ecosystem' ) ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array(
            array(
                '@type' => 'Question',
                'name'  => 'What is the DeAgenticAI Ecosystem?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'The DeAgenticAI Ecosystem is a decentralized control plane infrastructure designed for autonomous AI agents in Web3. It provides a modular framework combining distributed MPC custody, cryptographic policy enforcement, and MCP-native delegation to ensure secure, verifiable, and programmable agent operations.',
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'How does MPC improve AI wallet security?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Multi-Party Computation (MPC) enhances security by sharding private keys across multiple nodes. This ensures that no single entity or point of failure can access the full key, protecting autonomous agents from unauthorized signature attempts and external breaches.',
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'What is a policy-controlled wallet?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'A policy-controlled wallet is a digital asset container where spending, delegation, and execution authority are governed by immutable cryptographic rules. These policies are enforced at the signing layer, preventing AI agents from exceeding their pre-defined operational boundaries.',
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'Can AI agents hold private keys safely?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => "Traditional private key management is insecure for autonomous agents. DeAgenticAI solves this by using distributed MPC shards, where the agent never holds a complete key. Instead, the infrastructure validates the agent's intent against cryptographic policies before generating a threshold signature.",
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'How does delegation work for autonomous agents?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Delegation allows human users or master protocols to grant specific, time-bound, and scoped authority to an AI agent. Using session-based authority and MCP-native protocols, agents can execute transactions on behalf of the delegator within strictly enforced cryptographic limits.',
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'Is the ecosystem compatible with multiple blockchains?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Yes, the DeAgenticAI architecture is designed to be chain-agnostic. The MPC signing layer and Policy Engine can produce valid signatures for EVM-compatible networks, Solana, and other major Layer 1 and Layer 2 ecosystems.',
                ),
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_ecosystem_faq_schema', 5 );

// -------------------------------------------------------------------------
// 10. BreadcrumbList — All non-homepage pages
// -------------------------------------------------------------------------

function dea_breadcrumb_schema() {
    if ( is_front_page() ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    // Only handle CPT singles and solution pages here.
    // Marketing page breadcrumbs (glossary, solutions, etc.) are handled by
    // aura_get_marketing_breadcrumb_schema() in marketing-pages.php.
    $items    = array();
    $position = 1;
    $items[]  = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Home', 'item' => home_url( '/' ) );

    if ( is_singular( 'aura_pillar' ) ) {
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Learn',   'item' => home_url( '/learn/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Pillars', 'item' => home_url( '/learn/pillars/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title(), 'item' => get_permalink() );
    } elseif ( is_singular( 'aura_guide' ) ) {
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Learn',  'item' => home_url( '/learn/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Guides', 'item' => home_url( '/learn/guides/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title(), 'item' => get_permalink() );
    } elseif ( is_singular( 'aura_research' ) ) {
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Learn',    'item' => home_url( '/learn/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Research', 'item' => home_url( '/learn/research/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title(), 'item' => get_permalink() );
    } elseif ( is_singular( 'aura_update' ) ) {
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Learn',   'item' => home_url( '/learn/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Updates', 'item' => home_url( '/learn/updates/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title(), 'item' => get_permalink() );
    } elseif ( is_singular( 'aura_compare' ) ) {
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Compare', 'item' => home_url( '/compare/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title(), 'item' => get_permalink() );
    } elseif ( is_singular( 'aura_dataset' ) ) {
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Learn',    'item' => home_url( '/learn/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Datasets', 'item' => home_url( '/learn/datasets/' ) );
        $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title(), 'item' => get_permalink() );
    } else {
        $author_profile_page_id = dea_get_author_profile_page_id();
        if ( $author_profile_page_id > 0 ) {
            $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => 'Authors', 'item' => home_url( '/authors/' ) );
            $items[] = array( '@type' => 'ListItem', 'position' => $position++, 'name' => get_the_title( $author_profile_page_id ), 'item' => get_permalink( $author_profile_page_id ) );
        } else {
            return; // Not a CPT or known page; let marketing-pages.php handle the rest.
        }
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_breadcrumb_schema', 5 );

// -------------------------------------------------------------------------
// 11. Author Page — Person
// -------------------------------------------------------------------------

function dea_author_schema() {
    $page_id = dea_get_author_profile_page_id();
    if ( $page_id <= 0 ) {
        return;
    }
    if ( function_exists( 'aura_should_output_theme_schema_tags' ) && ! aura_should_output_theme_schema_tags() ) {
        return;
    }

    $meta        = dea_get_author_profile_metadata( $page_id );
    $description = isset( $meta['short_bio'] ) ? trim( (string) $meta['short_bio'] ) : '';
    if ( '' === $description ) {
        $description = function_exists( 'aura_get_marketing_page_excerpt' )
            ? aura_get_marketing_page_excerpt( $page_id, 40 )
            : wp_strip_all_tags( (string) get_the_excerpt( $page_id ) );
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Person',
        '@id'      => get_permalink( $page_id ) . '#person',
        'name'     => '' !== $meta['name'] ? $meta['name'] : get_the_title( $page_id ),
        'url'      => get_permalink( $page_id ),
        'mainEntityOfPage' => get_permalink( $page_id ),
        'worksFor' => array( '@id' => home_url( '/#organization' ) ),
    );

    if ( '' !== (string) $meta['job_title'] ) {
        $schema['jobTitle'] = (string) $meta['job_title'];
    }

    if ( '' !== $description ) {
        $schema['description'] = $description;
    }

    if ( ! empty( $meta['same_as'] ) ) {
        $schema['sameAs'] = array_values( $meta['same_as'] );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dea_author_schema', 5 );
