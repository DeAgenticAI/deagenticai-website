<?php
/**
 * Extended tab renderers for the Aura settings hub.
 *
 * @package Aura
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the About page settings form.
 *
 * @param array<string,mixed> $settings About page settings.
 */
function aura_render_about_settings_tab( $settings ) {
    ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="aura_save_site_settings">
        <input type="hidden" name="aura_settings_tab" value="about">
        <?php wp_nonce_field( 'aura_save_site_settings_about' ); ?>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Hero', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-hero-badge', __( 'Badge', 'aura' ), 'aura_about_page[hero_badge]', (string) $settings['hero_badge'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-hero-title', __( 'Title', 'aura' ), 'aura_about_page[hero_title]', (string) $settings['hero_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-hero-lead', __( 'Lead', 'aura' ), 'aura_about_page[hero_lead]', (string) $settings['hero_lead'], 5 ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Mission Card', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-mission-title', __( 'Card Title', 'aura' ), 'aura_about_page[mission_title]', (string) $settings['mission_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-mission-body', __( 'Primary Copy', 'aura' ), 'aura_about_page[mission_body]', (string) $settings['mission_body'], 3 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-mission-supporting', __( 'Supporting Copy', 'aura' ), 'aura_about_page[mission_supporting]', (string) $settings['mission_supporting'], 5 ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Vision Card', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-vision-title', __( 'Card Title', 'aura' ), 'aura_about_page[vision_title]', (string) $settings['vision_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-vision-primary', __( 'Primary Paragraph', 'aura' ), 'aura_about_page[vision_body_primary]', (string) $settings['vision_body_primary'], 6 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-vision-secondary', __( 'Secondary Paragraph', 'aura' ), 'aura_about_page[vision_body_secondary]', (string) $settings['vision_body_secondary'], 4 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-vision-emphasis', __( 'Emphasis Line', 'aura' ), 'aura_about_page[vision_emphasis]', (string) $settings['vision_emphasis'], 3 ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Founding Thesis', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-thesis-label', __( 'Section Label', 'aura' ), 'aura_about_page[thesis_label]', (string) $settings['thesis_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-thesis-title', __( 'Section Title', 'aura' ), 'aura_about_page[thesis_title]', (string) $settings['thesis_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-thesis-primary', __( 'Primary Paragraph', 'aura' ), 'aura_about_page[thesis_body_primary]', (string) $settings['thesis_body_primary'], 6 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-thesis-emphasis', __( 'Emphasis Line', 'aura' ), 'aura_about_page[thesis_emphasis]', (string) $settings['thesis_emphasis'], 3 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-thesis-secondary', __( 'Secondary Paragraph', 'aura' ), 'aura_about_page[thesis_body_secondary]', (string) $settings['thesis_body_secondary'], 4 ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Architecture Focus', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-focus-label', __( 'Section Label', 'aura' ), 'aura_about_page[focus_label]', (string) $settings['focus_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-focus-title', __( 'Section Title', 'aura' ), 'aura_about_page[focus_title]', (string) $settings['focus_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-focus-intro', __( 'Intro Copy', 'aura' ), 'aura_about_page[focus_intro]', (string) $settings['focus_intro'], 3 ); ?>
            </table>

            <?php foreach ( $settings['focus_items'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Focus Item %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-about-focus-item-title-' . $index, __( 'Title', 'aura' ), 'aura_about_page[focus_items][' . $index . '][title]', (string) $item['title'], 'large-text' ); ?>
                        <?php aura_render_site_settings_textarea_row( 'aura-about-focus-item-description-' . $index, __( 'Description', 'aura' ), 'aura_about_page[focus_items][' . $index . '][description]', (string) $item['description'], 5 ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Architecture Visual Labels', 'aura' ); ?></h2>
            <?php foreach ( $settings['visual_labels'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Visual Label %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-about-visual-label-' . $index, __( 'Label', 'aura' ), 'aura_about_page[visual_labels][' . $index . '][label]', (string) $item['label'], 'large-text' ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Founding Story', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-team-label', __( 'Section Label', 'aura' ), 'aura_about_page[team_label]', (string) $settings['team_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-team-title', __( 'Section Title', 'aura' ), 'aura_about_page[team_title]', (string) $settings['team_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-team-primary', __( 'Primary Paragraph', 'aura' ), 'aura_about_page[team_body_primary]', (string) $settings['team_body_primary'], 3 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-team-secondary', __( 'Secondary Paragraph', 'aura' ), 'aura_about_page[team_body_secondary]', (string) $settings['team_body_secondary'], 4 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-team-tertiary', __( 'Tertiary Paragraph', 'aura' ), 'aura_about_page[team_body_tertiary]', (string) $settings['team_body_tertiary'], 3 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-team-note', __( 'Footnote', 'aura' ), 'aura_about_page[team_note]', (string) $settings['team_note'], 2 ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Contact Section', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-contact-title', __( 'Section Heading', 'aura' ), 'aura_about_page[contact_title]', (string) $settings['contact_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-contact-intro', __( 'Section Intro', 'aura' ), 'aura_about_page[contact_intro]', (string) $settings['contact_intro'], 4 ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-button', __( 'Submit Button Label', 'aura' ), 'aura_about_page[contact_button_label]', (string) $settings['contact_button_label'] ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-contact-success', __( 'Success Message', 'aura' ), 'aura_about_page[contact_success_message]', (string) $settings['contact_success_message'], 2 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-contact-recaptcha', __( 'reCAPTCHA Error Message', 'aura' ), 'aura_about_page[contact_recaptcha_message]', (string) $settings['contact_recaptcha_message'], 2 ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-about-contact-error', __( 'Generic Error Message', 'aura' ), 'aura_about_page[contact_error_message]', (string) $settings['contact_error_message'], 2 ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Contact Form Labels', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-about-contact-name-label', __( 'Name Field Label', 'aura' ), 'aura_about_page[contact_name_label]', (string) $settings['contact_name_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-name-placeholder', __( 'Name Placeholder', 'aura' ), 'aura_about_page[contact_name_placeholder]', (string) $settings['contact_name_placeholder'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-email-label', __( 'Email Field Label', 'aura' ), 'aura_about_page[contact_email_label]', (string) $settings['contact_email_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-email-placeholder', __( 'Email Placeholder', 'aura' ), 'aura_about_page[contact_email_placeholder]', (string) $settings['contact_email_placeholder'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-company-label', __( 'Company Field Label', 'aura' ), 'aura_about_page[contact_company_label]', (string) $settings['contact_company_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-company-placeholder', __( 'Company Placeholder', 'aura' ), 'aura_about_page[contact_company_placeholder]', (string) $settings['contact_company_placeholder'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-message-label', __( 'Message Field Label', 'aura' ), 'aura_about_page[contact_message_label]', (string) $settings['contact_message_label'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-about-contact-message-placeholder', __( 'Message Placeholder', 'aura' ), 'aura_about_page[contact_message_placeholder]', (string) $settings['contact_message_placeholder'], 'large-text' ); ?>
            </table>
        </section>

        <?php submit_button( __( 'Save About Page', 'aura' ) ); ?>
    </form>
    <?php
}

/**
 * Render the Ecosystem page settings form.
 *
 * @param array<string,mixed> $settings Ecosystem page settings.
 */
function aura_render_ecosystem_settings_tab( $settings ) {
    ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="aura_save_site_settings">
        <input type="hidden" name="aura_settings_tab" value="ecosystem">
        <?php wp_nonce_field( 'aura_save_site_settings_ecosystem' ); ?>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Hero', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-hero-badge', __( 'Badge', 'aura' ), 'aura_ecosystem_page[hero_badge]', (string) $settings['hero_badge'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-hero-title-prefix', __( 'Title Prefix', 'aura' ), 'aura_ecosystem_page[hero_title_prefix]', (string) $settings['hero_title_prefix'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-hero-title-accent', __( 'Title Accent', 'aura' ), 'aura_ecosystem_page[hero_title_accent]', (string) $settings['hero_title_accent'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-hero-subtitle', __( 'Subtitle', 'aura' ), 'aura_ecosystem_page[hero_subtitle]', (string) $settings['hero_subtitle'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-hero-lead', __( 'Lead Copy', 'aura' ), 'aura_ecosystem_page[hero_lead]', (string) $settings['hero_lead'], 7 ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-hero-cta', __( 'CTA Label', 'aura' ), 'aura_ecosystem_page[hero_cta_label]', (string) $settings['hero_cta_label'] ); ?>
            </table>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Problem Framing', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-problem-title', __( 'Section Title', 'aura' ), 'aura_ecosystem_page[problem_title]', (string) $settings['problem_title'], 'large-text' ); ?>
            </table>

            <?php foreach ( $settings['problem_points'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Problem Point %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-problem-title-' . $index, __( 'Point Title', 'aura' ), 'aura_ecosystem_page[problem_points][' . $index . '][title]', (string) $item['title'], 'large-text' ); ?>
                        <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-problem-description-' . $index, __( 'Point Copy', 'aura' ), 'aura_ecosystem_page[problem_points][' . $index . '][description]', (string) $item['description'], 5 ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Architecture Overview', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-architecture-title', __( 'Section Title', 'aura' ), 'aura_ecosystem_page[architecture_title]', (string) $settings['architecture_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-status-primary', __( 'Status Line 1', 'aura' ), 'aura_ecosystem_page[status_line_primary]', (string) $settings['status_line_primary'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-status-secondary', __( 'Status Line 2', 'aura' ), 'aura_ecosystem_page[status_line_secondary]', (string) $settings['status_line_secondary'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-status-tertiary', __( 'Status Line 3', 'aura' ), 'aura_ecosystem_page[status_line_tertiary]', (string) $settings['status_line_tertiary'], 'large-text' ); ?>
            </table>

            <?php foreach ( $settings['control_plane_labels'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Control Plane Label %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-control-plane-label-' . $index, __( 'Label', 'aura' ), 'aura_ecosystem_page[control_plane_labels][' . $index . '][label]', (string) $item['label'], 'large-text' ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Architecture Steps', 'aura' ); ?></h2>
            <?php foreach ( $settings['architecture_steps'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Step %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-step-title-' . $index, __( 'Step Title', 'aura' ), 'aura_ecosystem_page[architecture_steps][' . $index . '][title]', (string) $item['title'], 'large-text' ); ?>
                        <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-step-description-' . $index, __( 'Step Copy', 'aura' ), 'aura_ecosystem_page[architecture_steps][' . $index . '][description]', (string) $item['description'], 6 ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Core Components', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-components-title', __( 'Section Title', 'aura' ), 'aura_ecosystem_page[components_title]', (string) $settings['components_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-component-link-label', __( 'Link Label', 'aura' ), 'aura_ecosystem_page[component_link_label]', (string) $settings['component_link_label'] ); ?>
            </table>

            <?php foreach ( $settings['components'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Component %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-component-title-' . $index, __( 'Title', 'aura' ), 'aura_ecosystem_page[components][' . $index . '][title]', (string) $item['title'], 'large-text' ); ?>
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-component-link-' . $index, __( 'Link URL', 'aura' ), 'aura_ecosystem_page[components][' . $index . '][link_url]', (string) $item['link_url'], 'large-text' ); ?>
                        <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-component-description-' . $index, __( 'Description', 'aura' ), 'aura_ecosystem_page[components][' . $index . '][description]', (string) $item['description'], 6 ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Systemic Unity', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-unity-title', __( 'Section Title', 'aura' ), 'aura_ecosystem_page[unity_title]', (string) $settings['unity_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-unity-intro', __( 'Intro Copy', 'aura' ), 'aura_ecosystem_page[unity_intro]', (string) $settings['unity_intro'], 4 ); ?>
            </table>

            <?php foreach ( $settings['unity_bullets'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Unity Bullet %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-unity-bullet-' . $index, __( 'Bullet Copy', 'aura' ), 'aura_ecosystem_page[unity_bullets][' . $index . '][description]', (string) $item['description'], 4 ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'Comparison Matrix', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-comparison-title', __( 'Section Title', 'aura' ), 'aura_ecosystem_page[comparison_title]', (string) $settings['comparison_title'], 'large-text' ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-header-capability', __( 'Capability Header', 'aura' ), 'aura_ecosystem_page[comparison_header_capability]', (string) $settings['comparison_header_capability'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-header-primary', __( 'Primary Column Header', 'aura' ), 'aura_ecosystem_page[comparison_header_primary]', (string) $settings['comparison_header_primary'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-header-coinbase', __( 'Coinbase Column Header', 'aura' ), 'aura_ecosystem_page[comparison_header_coinbase]', (string) $settings['comparison_header_coinbase'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-header-fireblocks', __( 'Fireblocks Column Header', 'aura' ), 'aura_ecosystem_page[comparison_header_fireblocks]', (string) $settings['comparison_header_fireblocks'] ); ?>
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-header-safe', __( 'Safe Column Header', 'aura' ), 'aura_ecosystem_page[comparison_header_safe]', (string) $settings['comparison_header_safe'] ); ?>
                <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-comparison-note', __( 'Comparison Note', 'aura' ), 'aura_ecosystem_page[comparison_note]', (string) $settings['comparison_note'], 4 ); ?>
            </table>

            <?php foreach ( $settings['comparison_rows'] as $index => $row ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'Comparison Row %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-comparison-capability-' . $index, __( 'Capability', 'aura' ), 'aura_ecosystem_page[comparison_rows][' . $index . '][capability]', (string) $row['capability'], 'large-text' ); ?>
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-comparison-primary-' . $index, __( 'Primary Column', 'aura' ), 'aura_ecosystem_page[comparison_rows][' . $index . '][primary]', (string) $row['primary'] ); ?>
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-comparison-coinbase-' . $index, __( 'Coinbase Column', 'aura' ), 'aura_ecosystem_page[comparison_rows][' . $index . '][coinbase]', (string) $row['coinbase'] ); ?>
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-comparison-fireblocks-' . $index, __( 'Fireblocks Column', 'aura' ), 'aura_ecosystem_page[comparison_rows][' . $index . '][fireblocks]', (string) $row['fireblocks'] ); ?>
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-comparison-safe-' . $index, __( 'Safe Column', 'aura' ), 'aura_ecosystem_page[comparison_rows][' . $index . '][safe]', (string) $row['safe'] ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="aura-site-settings-section">
            <h2><?php esc_html_e( 'FAQ Section', 'aura' ); ?></h2>
            <table class="form-table" role="presentation">
                <?php aura_render_site_settings_text_row( 'aura-ecosystem-faq-title', __( 'Section Title', 'aura' ), 'aura_ecosystem_page[faq_title]', (string) $settings['faq_title'], 'large-text' ); ?>
            </table>

            <?php foreach ( $settings['faqs'] as $index => $item ) : ?>
                <div class="aura-site-settings-subsection">
                    <h3><?php echo esc_html( sprintf( __( 'FAQ %d', 'aura' ), $index + 1 ) ); ?></h3>
                    <table class="form-table" role="presentation">
                        <?php aura_render_site_settings_text_row( 'aura-ecosystem-faq-question-' . $index, __( 'Question', 'aura' ), 'aura_ecosystem_page[faqs][' . $index . '][question]', (string) $item['question'], 'large-text' ); ?>
                        <?php aura_render_site_settings_textarea_row( 'aura-ecosystem-faq-answer-' . $index, __( 'Answer', 'aura' ), 'aura_ecosystem_page[faqs][' . $index . '][answer]', (string) $item['answer'], 5 ); ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>

        <?php submit_button( __( 'Save Ecosystem Page', 'aura' ) ); ?>
    </form>
    <?php
}
