<?php
/**
 * Unified admin settings hub for sitewide copy, homepage sections, and integrations.
 *
 * @package Aura
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get the admin URL for the Aura settings hub.
 *
 * @param string $tab        Active tab slug.
 * @param array  $extra_args Extra query args.
 * @return string
 */
function aura_get_site_settings_admin_url( $tab = 'sitewide', array $extra_args = array() ) {
    $args = array_merge(
        array(
            'page' => 'aura-site-settings',
            'tab'  => $tab,
        ),
        $extra_args
    );

    return add_query_arg( $args, admin_url( 'admin.php' ) );
}

/**
 * Get supported tabs for the unified settings hub.
 *
 * @return array<string,string>
 */
function aura_get_site_settings_tabs() {
    return array(
        'sitewide'     => __( 'Sitewide', 'aura' ),
        'homepage'     => __( 'Homepage', 'aura' ),
        'about'        => __( 'About Page', 'aura' ),
        'ecosystem'    => __( 'Ecosystem Page', 'aura' ),
        'integrations' => __( 'Integrations', 'aura' ),
    );
}

/**
 * Resolve the active settings tab.
 *
 * @return string
 */
function aura_get_site_settings_active_tab() {
    $tabs = aura_get_site_settings_tabs();
    $tab  = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'sitewide';

    return isset( $tabs[ $tab ] ) ? $tab : 'sitewide';
}

/**
 * Persist a structured page settings option without autoloading bulky records.
 *
 * @param string              $option_name Option key.
 * @param array<string,mixed> $value       Option value.
 */
function aura_update_structured_site_settings_option( $option_name, array $value ) {
    if ( null === get_option( $option_name, null ) ) {
        add_option( $option_name, $value, '', false );
        return;
    }

    update_option( $option_name, $value );
}

/**
 * Render a text input row for the settings hub.
 *
 * @param string $field_id    Input ID.
 * @param string $label       Field label.
 * @param string $name        Input name.
 * @param string $value       Input value.
 * @param string $class       CSS class.
 * @param string $description Optional help text.
 */
function aura_render_site_settings_text_row( $field_id, $label, $name, $value, $class = 'regular-text', $description = '' ) {
    ?>
    <tr>
        <th scope="row">
            <label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $label ); ?></label>
        </th>
        <td>
            <input id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo esc_attr( $class ); ?>" type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
            <?php if ( '' !== $description ) : ?>
                <p class="description"><?php echo esc_html( $description ); ?></p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

/**
 * Render a textarea row for the settings hub.
 *
 * @param string $field_id    Input ID.
 * @param string $label       Field label.
 * @param string $name        Input name.
 * @param string $value       Input value.
 * @param int    $rows        Visible rows.
 * @param string $description Optional help text.
 */
function aura_render_site_settings_textarea_row( $field_id, $label, $name, $value, $rows = 4, $description = '' ) {
    ?>
    <tr>
        <th scope="row">
            <label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $label ); ?></label>
        </th>
        <td>
            <textarea id="<?php echo esc_attr( $field_id ); ?>" class="large-text" rows="<?php echo esc_attr( (string) $rows ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
            <?php if ( '' !== $description ) : ?>
                <p class="description"><?php echo esc_html( $description ); ?></p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

/**
 * Register the top-level admin menu item.
 */
function aura_register_site_settings_menu() {
    add_menu_page(
        __( 'Aura Settings', 'aura' ),
        __( 'Aura Settings', 'aura' ),
        'manage_options',
        'aura-site-settings',
        'aura_render_site_settings_page',
        'dashicons-admin-generic',
        58
    );
}
add_action( 'admin_menu', 'aura_register_site_settings_menu' );

/**
 * Hide legacy Appearance entries now that the unified hub exists.
 */
function aura_hide_legacy_site_settings_pages() {
    remove_submenu_page( 'themes.php', 'aura-content' );
    remove_submenu_page( 'themes.php', 'aura-integrations' );
}
add_action( 'admin_menu', 'aura_hide_legacy_site_settings_pages', 99 );

/**
 * Disable the old homepage metabox so content editing stays in the left menu.
 */
function aura_disable_legacy_homepage_metabox() {
    remove_action( 'add_meta_boxes_page', 'aura_register_homepage_meta_boxes' );
}
add_action( 'admin_init', 'aura_disable_legacy_homepage_metabox' );

/**
 * Enqueue WordPress media assets for the settings hub.
 *
 * @param string $hook_suffix Current admin screen hook suffix.
 */
function aura_enqueue_site_settings_assets( $hook_suffix ) {
    if ( 'toplevel_page_aura-site-settings' !== $hook_suffix ) {
        return;
    }

    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'aura_enqueue_site_settings_assets' );

/**
 * Save a settings hub tab.
 */
function aura_handle_site_settings_save() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to manage these settings.', 'aura' ) );
    }

    $tabs = aura_get_site_settings_tabs();
    $tab  = isset( $_POST['aura_settings_tab'] ) ? sanitize_key( wp_unslash( $_POST['aura_settings_tab'] ) ) : 'sitewide';
    if ( ! isset( $tabs[ $tab ] ) ) {
        $tab = 'sitewide';
    }

    check_admin_referer( 'aura_save_site_settings_' . $tab );

    switch ( $tab ) {
        case 'homepage':
            $home_page_id = function_exists( 'aura_get_home_page_id' ) ? aura_get_home_page_id() : 0;
            if ( $home_page_id > 0 && function_exists( 'aura_update_homepage_settings' ) ) {
                $payload = isset( $_POST['aura_home'] ) && is_array( $_POST['aura_home'] )
                    ? $_POST['aura_home']
                    : array();
                aura_update_homepage_settings( $home_page_id, $payload );
            }
            break;

        case 'about':
            $payload = isset( $_POST['aura_about_page'] ) && is_array( $_POST['aura_about_page'] )
                ? $_POST['aura_about_page']
                : array();

            if ( function_exists( 'aura_sanitize_about_page_settings' ) ) {
                aura_update_structured_site_settings_option( 'aura_about_page', aura_sanitize_about_page_settings( $payload ) );
            }
            break;

        case 'ecosystem':
            $payload = isset( $_POST['aura_ecosystem_page'] ) && is_array( $_POST['aura_ecosystem_page'] )
                ? $_POST['aura_ecosystem_page']
                : array();

            if ( function_exists( 'aura_sanitize_ecosystem_page_settings' ) ) {
                aura_update_structured_site_settings_option( 'aura_ecosystem_page', aura_sanitize_ecosystem_page_settings( $payload ) );
            }
            break;

        case 'integrations':
            $payload = isset( $_POST['aura_integrations'] ) && is_array( $_POST['aura_integrations'] )
                ? $_POST['aura_integrations']
                : array();

            if ( function_exists( 'aura_sanitize_integrations_settings' ) ) {
                update_option( 'aura_integrations', aura_sanitize_integrations_settings( $payload ) );
            }
            break;

        case 'sitewide':
        default:
            $payload   = isset( $_POST['aura_content'] ) && is_array( $_POST['aura_content'] )
                ? $_POST['aura_content']
                : array();
            $current   = get_option( 'aura_content', array() );
            $sanitized = function_exists( 'aura_sanitize_content_settings' )
                ? aura_sanitize_content_settings( $payload )
                : array();

            if ( is_array( $current ) ) {
                $sanitized = array_merge( $current, $sanitized );
            }

            update_option( 'aura_content', $sanitized );

            $logo_id = isset( $_POST['aura_site_logo_id'] ) ? absint( wp_unslash( $_POST['aura_site_logo_id'] ) ) : 0;
            if ( $logo_id > 0 ) {
                set_theme_mod( 'custom_logo', $logo_id );
            } else {
                remove_theme_mod( 'custom_logo' );
            }
            break;
    }

    wp_safe_redirect( aura_get_site_settings_admin_url( $tab, array( 'aura-updated' => '1' ) ) );
    exit;
}
add_action( 'admin_post_aura_save_site_settings', 'aura_handle_site_settings_save' );

/**
 * Render the sitewide settings form.
 *
 * @param array<string,string> $settings Sitewide settings.
 */
function aura_render_sitewide_settings_tab( $settings ) {
    $logo_id          = (int) get_theme_mod( 'custom_logo' );
    $logo_preview_url = $logo_id > 0 ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
    $social_image_url = isset( $settings['social_image_url'] ) ? (string) $settings['social_image_url'] : '';
    ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="aura_save_site_settings">
        <input type="hidden" name="aura_settings_tab" value="sitewide">
        <?php wp_nonce_field( 'aura_save_site_settings_sitewide' ); ?>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Brand & Media', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-site-name"><?php esc_html_e( 'Site / Brand Name', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-site-name" class="regular-text" type="text" name="aura_content[site_name]" value="<?php echo esc_attr( $settings['site_name'] ); ?>">
                        <p class="description"><?php esc_html_e( 'Used in logo alt text, footer copy, and schema markup.', 'aura' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-org-url"><?php esc_html_e( 'Organisation URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-org-url" class="regular-text" type="url" name="aura_content[org_url]" value="<?php echo esc_attr( $settings['org_url'] ); ?>">
                        <p class="description"><?php esc_html_e( 'Canonical organisation URL for structured data. Leave blank to use the WordPress site URL.', 'aura' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-founding-year"><?php esc_html_e( 'Founding Year', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-founding-year" class="small-text" type="text" maxlength="4" name="aura_content[founding_year]" value="<?php echo esc_attr( $settings['founding_year'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-product-name"><?php esc_html_e( 'Primary Product Name', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-product-name" class="regular-text" type="text" name="aura_content[product_name]" value="<?php echo esc_attr( $settings['product_name'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-service-type"><?php esc_html_e( 'Service Type', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-service-type" class="regular-text" type="text" name="aura_content[service_type]" value="<?php echo esc_attr( $settings['service_type'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Site Logo', 'aura' ); ?></th>
                    <td>
                        <div class="aura-site-settings-media" data-target-field="aura-site-logo-id" data-target-type="id" data-preview-field="aura-site-logo-preview" data-media-title="<?php echo esc_attr__( 'Select site logo', 'aura' ); ?>" data-button-text="<?php echo esc_attr__( 'Use this logo', 'aura' ); ?>" data-empty-label="<?php echo esc_attr__( 'No custom logo selected.', 'aura' ); ?>">
                            <input id="aura-site-logo-id" type="hidden" name="aura_site_logo_id" value="<?php echo esc_attr( (string) $logo_id ); ?>">
                            <div id="aura-site-logo-preview" class="aura-site-settings-media-preview">
                                <?php if ( $logo_preview_url ) : ?>
                                    <img src="<?php echo esc_url( $logo_preview_url ); ?>" alt="">
                                <?php else : ?>
                                    <span><?php esc_html_e( 'No custom logo selected.', 'aura' ); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="aura-site-settings-media-controls">
                                <button type="button" class="button aura-site-settings-media-open"><?php esc_html_e( 'Choose Image', 'aura' ); ?></button>
                                <button type="button" class="button-link-delete aura-site-settings-media-clear"><?php esc_html_e( 'Remove', 'aura' ); ?></button>
                                <p class="description"><?php esc_html_e( 'Used in the header, footer, schema markup, and homepage logo visuals.', 'aura' ); ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-social-image-url"><?php esc_html_e( 'Default Social Image', 'aura' ); ?></label>
                    </th>
                    <td>
                        <div class="aura-site-settings-media" data-target-field="aura-social-image-url" data-target-type="url" data-preview-field="aura-social-image-preview" data-media-title="<?php echo esc_attr__( 'Select default social image', 'aura' ); ?>" data-button-text="<?php echo esc_attr__( 'Use this image', 'aura' ); ?>" data-empty-label="<?php echo esc_attr__( 'No fallback social image selected.', 'aura' ); ?>">
                            <input id="aura-social-image-url" class="regular-text aura-site-settings-media-url" type="url" name="aura_content[social_image_url]" value="<?php echo esc_attr( $social_image_url ); ?>">
                            <div id="aura-social-image-preview" class="aura-site-settings-media-preview">
                                <?php if ( '' !== $social_image_url ) : ?>
                                    <img src="<?php echo esc_url( $social_image_url ); ?>" alt="">
                                <?php else : ?>
                                    <span><?php esc_html_e( 'No fallback social image selected.', 'aura' ); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="aura-site-settings-media-controls">
                                <div class="aura-site-settings-media-actions">
                                    <button type="button" class="button aura-site-settings-media-open"><?php esc_html_e( 'Choose Image', 'aura' ); ?></button>
                                    <button type="button" class="button-link-delete aura-site-settings-media-clear"><?php esc_html_e( 'Remove', 'aura' ); ?></button>
                                </div>
                                <p class="description"><?php esc_html_e( 'Used when a page or post does not have its own featured image for social sharing metadata.', 'aura' ); ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Social & Contact', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-twitter"><?php esc_html_e( 'Twitter / X URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-twitter" class="regular-text" type="url" name="aura_content[twitter_url]" value="<?php echo esc_attr( $settings['twitter_url'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-linkedin"><?php esc_html_e( 'LinkedIn URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-linkedin" class="regular-text" type="url" name="aura_content[linkedin_url]" value="<?php echo esc_attr( $settings['linkedin_url'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-github"><?php esc_html_e( 'GitHub URL', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-github" class="regular-text" type="url" name="aura_content[github_url]" value="<?php echo esc_attr( $settings['github_url'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-email"><?php esc_html_e( 'Contact Email', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-email" class="regular-text" type="email" name="aura_content[email_address]" value="<?php echo esc_attr( $settings['email_address'] ); ?>">
                        <p class="description"><?php esc_html_e( 'Shown in the footer and About page. Contact form submissions also route here when this address is valid.', 'aura' ); ?></p>
                    </td>
                </tr>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Footer', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-footer-tagline"><?php esc_html_e( 'Footer Brand Tagline', 'aura' ); ?></label>
                    </th>
                    <td>
                        <textarea id="aura-footer-tagline" class="large-text" rows="3" name="aura_content[footer_tagline]"><?php echo esc_textarea( $settings['footer_tagline'] ); ?></textarea>
                    </td>
                </tr>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Waitlist CTA Section', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-title"><?php esc_html_e( 'Title Prefix', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-waitlist-title" class="large-text" type="text" name="aura_content[waitlist_title]" value="<?php echo esc_attr( $settings['waitlist_title'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-accent"><?php esc_html_e( 'Title Accent', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-waitlist-accent" class="regular-text" type="text" name="aura_content[waitlist_title_accent]" value="<?php echo esc_attr( $settings['waitlist_title_accent'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-desc"><?php esc_html_e( 'Section Description', 'aura' ); ?></label>
                    </th>
                    <td>
                        <textarea id="aura-waitlist-desc" class="large-text" rows="4" name="aura_content[waitlist_description]"><?php echo esc_textarea( $settings['waitlist_description'] ); ?></textarea>
                    </td>
                </tr>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Waitlist Page', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-page-title"><?php esc_html_e( 'Page Title', 'aura' ); ?></label>
                    </th>
                    <td>
                        <input id="aura-waitlist-page-title" class="large-text" type="text" name="aura_content[waitlist_page_title]" value="<?php echo esc_attr( $settings['waitlist_page_title'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="aura-waitlist-page-description"><?php esc_html_e( 'Page Description', 'aura' ); ?></label>
                    </th>
                    <td>
                        <textarea id="aura-waitlist-page-description" class="large-text" rows="3" name="aura_content[waitlist_page_description]"><?php echo esc_textarea( $settings['waitlist_page_description'] ); ?></textarea>
                    </td>
                </tr>
            </table>
        </section>

        <?php submit_button( __( 'Save Sitewide Settings', 'aura' ) ); ?>
    </form>
    <?php
}

/**
 * Render the integrations settings form.
 *
 * @param array<string,mixed> $settings Integration settings.
 */
function aura_render_integrations_settings_tab( $settings ) {
    ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="aura_save_site_settings">
        <input type="hidden" name="aura_settings_tab" value="integrations">
        <?php wp_nonce_field( 'aura_save_site_settings_integrations' ); ?>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Google reCAPTCHA', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Enable Google reCAPTCHA', 'aura' ); ?></th>
                        <td>
                            <label for="aura-recaptcha-enabled">
                                <input id="aura-recaptcha-enabled" type="checkbox" name="aura_integrations[enabled]" value="1" <?php checked( 1 === (int) $settings['enabled'] ); ?>>
                                <?php esc_html_e( 'Protect waitlist and contact forms with reCAPTCHA v3.', 'aura' ); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aura-recaptcha-site-key"><?php esc_html_e( 'Site Key', 'aura' ); ?></label>
                        </th>
                        <td>
                            <input id="aura-recaptcha-site-key" type="text" class="regular-text" name="aura_integrations[site_key]" value="<?php echo esc_attr( (string) $settings['site_key'] ); ?>" autocomplete="off">
                            <p class="description"><?php esc_html_e( 'Public site key from Google reCAPTCHA v3.', 'aura' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aura-recaptcha-secret-key"><?php esc_html_e( 'Secret Key', 'aura' ); ?></label>
                        </th>
                        <td>
                            <input id="aura-recaptcha-secret-key" type="password" class="regular-text" name="aura_integrations[secret_key]" value="<?php echo esc_attr( (string) $settings['secret_key'] ); ?>" autocomplete="new-password">
                            <p class="description"><?php esc_html_e( 'Private secret key used for server-side verification.', 'aura' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aura-recaptcha-min-score"><?php esc_html_e( 'Minimum Score', 'aura' ); ?></label>
                        </th>
                        <td>
                            <input id="aura-recaptcha-min-score" type="number" min="0.1" max="1" step="0.1" name="aura_integrations[min_score]" value="<?php echo esc_attr( (string) $settings['min_score'] ); ?>">
                            <p class="description"><?php esc_html_e( 'Requests with a lower score are rejected. Recommended default: 0.5.', 'aura' ); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <?php submit_button( __( 'Save Integrations', 'aura' ) ); ?>
    </form>
    <?php
}

/**
 * Render the unified site settings page.
 */
function aura_render_site_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $tabs              = aura_get_site_settings_tabs();
    $active_tab        = aura_get_site_settings_active_tab();
    $settings          = function_exists( 'aura_get_content_settings' ) ? aura_get_content_settings() : array();
    $about_settings    = function_exists( 'aura_get_about_page_settings' ) ? aura_get_about_page_settings() : array();
    $ecosystem_settings = function_exists( 'aura_get_ecosystem_page_settings' ) ? aura_get_ecosystem_page_settings() : array();
    $home_page_id      = function_exists( 'aura_get_home_page_id' ) ? aura_get_home_page_id() : 0;
    $home_page         = $home_page_id > 0 ? get_post( $home_page_id ) : null;
    $integrations      = function_exists( 'aura_get_recaptcha_settings' )
        ? aura_get_recaptcha_settings()
        : array(
            'enabled'    => 0,
            'site_key'   => '',
            'secret_key' => '',
            'min_score'  => 0.5,
        );
    ?>
    <div class="wrap aura-site-settings-wrap">
        <h1><?php esc_html_e( 'Aura Settings', 'aura' ); ?></h1>
        <p><?php esc_html_e( 'Manage sitewide copy, homepage sections, About and Ecosystem page content, images, and reCAPTCHA from one left-side admin panel.', 'aura' ); ?></p>

        <?php if ( isset( $_GET['aura-updated'] ) ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Settings saved.', 'aura' ); ?></p>
            </div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper" aria-label="<?php esc_attr_e( 'Aura settings tabs', 'aura' ); ?>">
            <?php foreach ( $tabs as $tab => $label ) : ?>
                <?php $tab_class = 'nav-tab' . ( $tab === $active_tab ? ' nav-tab-active' : '' ); ?>
                <a class="<?php echo esc_attr( $tab_class ); ?>" href="<?php echo esc_url( aura_get_site_settings_admin_url( $tab ) ); ?>">
                    <?php echo esc_html( $label ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="aura-site-settings-panel">
            <?php if ( 'homepage' === $active_tab ) : ?>
                <?php if ( $home_page instanceof WP_Post ) : ?>
                    <div class="notice notice-info inline">
                        <p>
                            <?php
                            printf(
                                esc_html__( 'Editing homepage content for "%s".', 'aura' ),
                                esc_html( get_the_title( $home_page ) )
                            );
                            ?>
                            <a href="<?php echo esc_url( get_permalink( $home_page ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View page', 'aura' ); ?></a>
                        </p>
                    </div>

                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="aura_save_site_settings">
                        <input type="hidden" name="aura_settings_tab" value="homepage">
                        <?php wp_nonce_field( 'aura_save_site_settings_homepage' ); ?>

                        <?php aura_render_homepage_settings_fields( (int) $home_page->ID ); ?>

                        <?php submit_button( __( 'Save Homepage Settings', 'aura' ) ); ?>
                    </form>
                <?php else : ?>
                    <div class="notice notice-warning inline">
                        <p>
                            <?php esc_html_e( 'No static homepage is configured yet. Set one in Settings → Reading to unlock homepage fields here.', 'aura' ); ?>
                            <a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>"><?php esc_html_e( 'Open Reading Settings', 'aura' ); ?></a>
                        </p>
                    </div>
                <?php endif; ?>
            <?php elseif ( 'about' === $active_tab ) : ?>
                <?php aura_render_about_settings_tab( $about_settings ); ?>
            <?php elseif ( 'ecosystem' === $active_tab ) : ?>
                <?php aura_render_ecosystem_settings_tab( $ecosystem_settings ); ?>
            <?php elseif ( 'integrations' === $active_tab ) : ?>
                <?php aura_render_integrations_settings_tab( $integrations ); ?>
            <?php else : ?>
                <?php aura_render_sitewide_settings_tab( $settings ); ?>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .aura-site-settings-panel {
            margin-top: 18px;
            max-width: 1100px;
            padding: 24px;
            background: #fff;
            border: 1px solid #dcdcde;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .aura-site-settings-section + .aura-site-settings-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f0f0f1;
        }

        .aura-site-settings-section h2 {
            margin: 0 0 16px;
        }

        .aura-site-settings-subsection {
            margin-top: 18px;
            padding: 18px;
            border: 1px solid #e6e7ea;
            border-radius: 8px;
            background: #fcfcfd;
        }

        .aura-site-settings-subsection h3 {
            margin: 0 0 12px;
        }

        .aura-site-settings-subsection .form-table {
            margin-top: 0;
        }

        .aura-site-settings-media {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .aura-site-settings-media-preview {
            width: 140px;
            min-height: 140px;
            border: 1px dashed #c3c4c7;
            border-radius: 8px;
            background: #f6f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #646970;
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
        }

        .aura-site-settings-media-preview img {
            display: block;
            width: 100%;
            height: auto;
        }

        .aura-site-settings-media-controls {
            flex: 1 1 340px;
            min-width: 260px;
        }

        .aura-site-settings-media-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .aura-site-settings-wrap .notice.inline {
            margin: 16px 0 0;
        }
    </style>

    <script>
        (function($) {
            function setPreview($container, url) {
                var previewField = $container.data('previewField');
                var emptyLabel = $container.data('emptyLabel') || '<?php echo esc_js( __( 'No image selected.', 'aura' ) ); ?>';
                var $preview = $('#' + previewField);

                if (!$preview.length) {
                    return;
                }

                $preview.empty();

                if (url) {
                    $('<img>', { src: url, alt: '' }).appendTo($preview);
                } else {
                    $('<span>').text(emptyLabel).appendTo($preview);
                }
            }

            $(document).on('click', '.aura-site-settings-media-open', function(event) {
                event.preventDefault();

                var $container = $(this).closest('.aura-site-settings-media');
                var frame = wp.media({
                    title: $container.data('mediaTitle') || '<?php echo esc_js( __( 'Select image', 'aura' ) ); ?>',
                    button: {
                        text: $container.data('buttonText') || '<?php echo esc_js( __( 'Use this image', 'aura' ) ); ?>'
                    },
                    library: {
                        type: 'image'
                    },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var targetField = $container.data('targetField');
                    var targetType = $container.data('targetType') || 'url';
                    var value = 'id' === targetType ? attachment.id : attachment.url;

                    $('#' + targetField).val(value).trigger('change');
                    setPreview($container, attachment.url);
                });

                frame.open();
            });

            $(document).on('click', '.aura-site-settings-media-clear', function(event) {
                event.preventDefault();

                var $container = $(this).closest('.aura-site-settings-media');
                $('#' + $container.data('targetField')).val('').trigger('change');
                setPreview($container, '');
            });

            $(document).on('input change', '.aura-site-settings-media-url', function() {
                var $container = $(this).closest('.aura-site-settings-media');
                setPreview($container, $(this).val());
            });
        })(jQuery);
    </script>
    <?php
}