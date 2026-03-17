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
        'twitter_url'           => '',
        'linkedin_url'          => '',
        'github_url'            => '',
        'email_address'         => '',
        'footer_tagline'        => 'The Agentic Control Plane for Web3 and the Enterprise. Making autonomous intelligence safe to own and move capital.',
        'waitlist_title'        => 'Shape the Control Layer for',
        'waitlist_title_accent' => 'Agentic AI',
        'waitlist_description'  => "Our early access is invite-only. Join the design partner waitlist to track DeAgenticAI's progress and shape governed autonomous execution with our team. No marketing fluff-just infrastructure updates.",
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

    $text_fields     = array( 'site_name', 'waitlist_title', 'waitlist_title_accent', 'founding_year', 'product_name', 'service_type' );
    $textarea_fields = array( 'footer_tagline', 'waitlist_description' );
    $url_fields      = array( 'org_url', 'twitter_url', 'linkedin_url', 'github_url' );
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

    $s = aura_get_content_settings();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Aura Content Settings', 'aura' ); ?></h1>
        <p><?php esc_html_e( 'Manage global site copy, social links, and footer settings. These apply sitewide.', 'aura' ); ?></p>

        <form method="post" action="options.php">
            <?php settings_fields( 'aura_content_settings_group' ); ?>

            <h2><?php esc_html_e( 'Brand', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-site-name"><?php esc_html_e( 'Site / Brand Name', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-site-name" class="regular-text" type="text"
                               name="aura_content[site_name]"
                               value="<?php echo esc_attr( $s['site_name'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Used in logo alt text, footer, and schema markup.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-org-url"><?php esc_html_e( 'Organisation URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-org-url" class="regular-text" type="url"
                               name="aura_content[org_url]"
                               value="<?php echo esc_attr( $s['org_url'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Canonical URL used in JSON-LD Organisation schema. Leave blank to use the WordPress site URL.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-founding-year"><?php esc_html_e( 'Founding Year', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-founding-year" class="small-text" type="text"
                               name="aura_content[founding_year]"
                               value="<?php echo esc_attr( $s['founding_year'] ); ?>"
                               maxlength="4">
                        <p class="description">
                            <?php esc_html_e( 'Used for Organization schema foundingDate. Example: 2026.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-product-name"><?php esc_html_e( 'Primary Product Name', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-product-name" class="regular-text" type="text"
                               name="aura_content[product_name]"
                               value="<?php echo esc_attr( $s['product_name'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Used in Organization schema product field.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-service-type"><?php esc_html_e( 'Service Type', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-service-type" class="regular-text" type="text"
                               name="aura_content[service_type]"
                               value="<?php echo esc_attr( $s['service_type'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Used in Organization schema serviceType field.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
            </table>

            <h2><?php esc_html_e( 'Social Links', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-twitter"><?php esc_html_e( 'Twitter / X URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-twitter" class="regular-text" type="url"
                               name="aura_content[twitter_url]"
                               value="<?php echo esc_attr( $s['twitter_url'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-linkedin"><?php esc_html_e( 'LinkedIn URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-linkedin" class="regular-text" type="url"
                               name="aura_content[linkedin_url]"
                               value="<?php echo esc_attr( $s['linkedin_url'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-github"><?php esc_html_e( 'GitHub URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-github" class="regular-text" type="url"
                               name="aura_content[github_url]"
                               value="<?php echo esc_attr( $s['github_url'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-email"><?php esc_html_e( 'Contact Email', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-email" class="regular-text" type="email"
                               name="aura_content[email_address]"
                               value="<?php echo esc_attr( $s['email_address'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Used as the href for the email icon in the footer social bar.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
            </table>

            <h2><?php esc_html_e( 'Footer', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-footer-tagline"><?php esc_html_e( 'Footer Brand Tagline', 'aura' ); ?></label>
                    </th>
                    <td>
                        <textarea id="aura-footer-tagline" class="large-text" rows="3"
                                  name="aura_content[footer_tagline]"><?php echo esc_textarea( $s['footer_tagline'] ); ?></textarea>
                        <p class="description">
                            <?php esc_html_e( 'Short description shown below the footer logo.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
            </table>

            <h2><?php esc_html_e( 'Waitlist Section', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-title"><?php esc_html_e( 'Title Prefix', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-waitlist-title" class="large-text" type="text"
                               name="aura_content[waitlist_title]"
                               value="<?php echo esc_attr( $s['waitlist_title'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'The plain-text portion of the title that appears before the highlighted accent word(s).', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-accent"><?php esc_html_e( 'Title Accent', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-waitlist-accent" class="regular-text" type="text"
                               name="aura_content[waitlist_title_accent]"
                               value="<?php echo esc_attr( $s['waitlist_title_accent'] ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Highlighted word(s) rendered in the brand accent colour. Leave blank for no highlight.', 'aura' ); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-desc"><?php esc_html_e( 'Section Description', 'aura' ); ?></label>
                    </th>
                    <td>
                        <textarea id="aura-waitlist-desc" class="large-text" rows="4"
                                  name="aura_content[waitlist_description]"><?php echo esc_textarea( $s['waitlist_description'] ); ?></textarea>
                    </td>
                </tr>
            </table>

            <?php submit_button( __( 'Save Content Settings', 'aura' ) ); ?>
        </form>
    </div>
    <?php
}
