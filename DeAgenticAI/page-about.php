<?php
/**
 * Template Name: About
 *
 * @package Aura
 */

$contact_status = '';
if ( isset( $_GET['contact'] ) ) {
    $contact_status = sanitize_key( wp_unslash( $_GET['contact'] ) );
}

$aura_recaptcha_enabled = function_exists( 'aura_is_recaptcha_enabled' ) && aura_is_recaptcha_enabled();
$about_settings         = function_exists( 'aura_get_about_page_settings' ) ? aura_get_about_page_settings() : array();
$about_badge            = isset( $about_settings['hero_badge'] ) ? (string) $about_settings['hero_badge'] : 'About DeAgenticAI';
$about_title            = isset( $about_settings['hero_title'] ) ? (string) $about_settings['hero_title'] : 'We Built the Missing Layer Between AI Agents and On-Chain Authority';
$about_lead             = isset( $about_settings['hero_lead'] ) ? (string) $about_settings['hero_lead'] : '';
$about_focus_items      = isset( $about_settings['focus_items'] ) && is_array( $about_settings['focus_items'] ) ? $about_settings['focus_items'] : array();
$about_visual_labels    = isset( $about_settings['visual_labels'] ) && is_array( $about_settings['visual_labels'] ) ? $about_settings['visual_labels'] : array();
$about_contact_email    = function_exists( 'aura_get_content' ) ? aura_get_content( 'email_address', '' ) : '';
$about_founder_profile  = array(
    'url'   => '',
    'name'  => '',
    'role'  => '',
    'bio'   => '',
);

if ( '' === $about_contact_email || ! is_email( $about_contact_email ) ) {
    $about_contact_email = get_option( 'admin_email' );
}

$founder_profile_page = get_page_by_path( 'authors/reza-jahankohan' );
if ( $founder_profile_page instanceof WP_Post ) {
    $founder_page_id = (int) $founder_profile_page->ID;

    $about_founder_profile['url']  = get_permalink( $founder_page_id );
    $about_founder_profile['name'] = get_the_title( $founder_page_id );

    if ( function_exists( 'dea_get_author_profile_metadata' ) ) {
        $founder_meta = dea_get_author_profile_metadata( $founder_page_id );
        if ( isset( $founder_meta['job_title'] ) && is_string( $founder_meta['job_title'] ) ) {
            $about_founder_profile['role'] = trim( $founder_meta['job_title'] );
        }
        if ( isset( $founder_meta['short_bio'] ) && is_string( $founder_meta['short_bio'] ) ) {
            $about_founder_profile['bio'] = trim( $founder_meta['short_bio'] );
        }
    }
}

$about_focus_icons = array(
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"></rect><path d="M8 8h8v8H8z"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a7 7 0 0 0-7 7v4a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4v-4a7 7 0 0 0-7-7Z"></path><path d="M9 14a3 3 0 1 0 6 0"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"></path><path d="M6 11h12"></path><path d="M8 15h8"></path><path d="M10 19h4"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16v14H4z"></path><path d="M8 9h8"></path><path d="M8 13h5"></path><path d="M8 17h6"></path></svg>',
);
$about_visual_icons = array(
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m4 12 4-4 4 4 8-8"></path><path d="M4 19h16"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"></path><path d="M6 11h12"></path><path d="M8 15h8"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"></path><path d="M7 12h10"></path><path d="M10 17h4"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16v14H4z"></path><path d="M8 9h8"></path><path d="M8 13h5"></path></svg>',
);
$about_icon_allowed_tags = array(
    'svg'  => array(
        'viewBox'      => true,
        'fill'         => true,
        'stroke'       => true,
        'stroke-width' => true,
    ),
    'path' => array(
        'd' => true,
    ),
    'rect' => array(
        'x'      => true,
        'y'      => true,
        'width'  => true,
        'height' => true,
        'rx'     => true,
    ),
);

get_header();
?>

<main id="primary" class="site-main about-page">
    <div class="container">
        <section class="about-hero">
            <div class="about-badge">
                <span class="about-badge-dot animate-ping"></span>
                <span class="about-badge-text"><?php echo esc_html( $about_badge ); ?></span>
            </div>

            <h1 class="about-title"><?php echo esc_html( $about_title ); ?></h1>

            <p class="about-lead"><?php echo esc_html( $about_lead ); ?></p>
        </section>

        <section class="about-card-grid">
            <article class="about-card">
                <span class="about-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3 3 7l9 4 9-4-9-4Z"></path>
                        <path d="m3 17 9 4 9-4"></path>
                        <path d="m3 12 9 4 9-4"></path>
                    </svg>
                </span>
                <h3><?php echo esc_html( (string) $about_settings['mission_title'] ); ?></h3>
                <p><?php echo esc_html( (string) $about_settings['mission_body'] ); ?></p>
                <p class="about-card-supporting"><?php echo esc_html( (string) $about_settings['mission_supporting'] ); ?></p>
            </article>

            <article class="about-card">
                <span class="about-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V6l-8-4-8 4v6c0 6 8 10 8 10Z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
                <h3><?php echo esc_html( (string) $about_settings['vision_title'] ); ?></h3>
                <p><?php echo esc_html( (string) $about_settings['vision_body_primary'] ); ?></p>
                <p><?php echo esc_html( (string) $about_settings['vision_body_secondary'] ); ?></p>
                <p class="about-card-emphasis"><?php echo esc_html( (string) $about_settings['vision_emphasis'] ); ?></p>
            </article>
        </section>

        <section class="about-thesis">
            <article class="about-thesis-card">
                <p class="about-section-label"><?php echo esc_html( (string) $about_settings['thesis_label'] ); ?></p>
                <h2><?php echo esc_html( (string) $about_settings['thesis_title'] ); ?></h2>
                <p><?php echo esc_html( (string) $about_settings['thesis_body_primary'] ); ?></p>
                <p class="about-thesis-emphasis"><?php echo esc_html( (string) $about_settings['thesis_emphasis'] ); ?></p>
                <p><?php echo esc_html( (string) $about_settings['thesis_body_secondary'] ); ?></p>
            </article>
        </section>

        <section class="about-focus">
            <div class="about-focus-layout">
                <div>
                    <p class="about-section-label"><?php echo esc_html( (string) $about_settings['focus_label'] ); ?></p>
                    <h2><?php echo esc_html( (string) $about_settings['focus_title'] ); ?></h2>
                    <p><?php echo esc_html( (string) $about_settings['focus_intro'] ); ?></p>

                    <div class="about-focus-list">
                        <?php foreach ( $about_focus_items as $index => $item ) : ?>
                            <article class="about-focus-list-item">
                                <span class="about-focus-list-icon" aria-hidden="true">
                                    <?php echo wp_kses( isset( $about_focus_icons[ $index ] ) ? $about_focus_icons[ $index ] : '', $about_icon_allowed_tags ); ?>
                                </span>
                                <div>
                                    <h4><?php echo esc_html( (string) $item['title'] ); ?></h4>
                                    <p><?php echo esc_html( (string) $item['description'] ); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="about-visual">
                    <div class="about-visual-glow"></div>
                    <div class="about-visual-card">
                        <div class="about-visual-grid">
                            <?php foreach ( $about_visual_labels as $index => $item ) : ?>
                                <?php $visual_class = 1 === $index ? 'about-visual-cell active' : 'about-visual-cell'; ?>
                                <div class="<?php echo esc_attr( $visual_class ); ?>">
                                    <?php echo wp_kses( isset( $about_visual_icons[ $index ] ) ? $about_visual_icons[ $index ] : '', $about_icon_allowed_tags ); ?>
                                    <span><?php echo esc_html( (string) $item['label'] ); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-team">
            <article class="about-team-card">
                <p class="about-section-label"><?php echo esc_html( (string) $about_settings['team_label'] ); ?></p>
                <h2><?php echo esc_html( (string) $about_settings['team_title'] ); ?></h2>
                <p><?php echo esc_html( (string) $about_settings['team_body_primary'] ); ?></p>
                <p><?php echo esc_html( (string) $about_settings['team_body_secondary'] ); ?></p>
                <p><?php echo esc_html( (string) $about_settings['team_body_tertiary'] ); ?></p>
                <p class="about-team-note"><?php echo esc_html( (string) $about_settings['team_note'] ); ?></p>

                <?php if ( '' !== $about_founder_profile['url'] ) : ?>
                    <div class="about-founder-profile">
                        <p class="about-founder-eyebrow"><?php esc_html_e( 'Founder Profile', 'aura' ); ?></p>
                        <h3><?php echo esc_html( '' !== $about_founder_profile['name'] ? $about_founder_profile['name'] : __( 'Reza Jahankohan', 'aura' ) ); ?></h3>
                        <?php if ( '' !== $about_founder_profile['role'] ) : ?>
                            <p class="about-founder-role"><?php echo esc_html( $about_founder_profile['role'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( '' !== $about_founder_profile['bio'] ) : ?>
                            <p class="about-founder-bio"><?php echo esc_html( $about_founder_profile['bio'] ); ?></p>
                        <?php endif; ?>
                        <a class="about-founder-link" href="<?php echo esc_url( $about_founder_profile['url'] ); ?>">
                            <?php esc_html_e( 'View Full Founder Profile', 'aura' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </article>
        </section>

        <section id="contact" class="about-contact">
            <div class="about-contact-card">
                <div class="about-contact-head">
                    <h2><?php echo esc_html( (string) $about_settings['contact_title'] ); ?></h2>
                    <p><?php echo esc_html( (string) $about_settings['contact_intro'] ); ?></p>
                    <?php if ( '' !== $about_contact_email && is_email( $about_contact_email ) ) : ?>
                        <p>
                            <?php
                            printf(
                                wp_kses(
                                    __( 'Reach us at <a href="%1$s"><span class="about-contact-inline-email">%2$s</span></a> or use the form below.', 'aura' ),
                                    array(
                                        'a' => array(
                                            'href' => true,
                                        ),
                                        'span' => array(
                                            'class' => true,
                                        ),
                                    )
                                ),
                                esc_url( 'mailto:' . $about_contact_email ),
                                esc_html( $about_contact_email )
                            );
                            ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ( 'success' === $contact_status ) : ?>
                    <div class="about-contact-notice success"><?php echo esc_html( (string) $about_settings['contact_success_message'] ); ?></div>
                <?php elseif ( 'recaptcha' === $contact_status ) : ?>
                    <div class="about-contact-notice error"><?php echo esc_html( (string) $about_settings['contact_recaptcha_message'] ); ?></div>
                <?php elseif ( 'error' === $contact_status ) : ?>
                    <div class="about-contact-notice error"><?php echo esc_html( (string) $about_settings['contact_error_message'] ); ?></div>
                <?php endif; ?>

                <form class="about-contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="aura_contact_submit">
                    <?php wp_nonce_field( 'aura_contact_submit', 'aura_contact_nonce' ); ?>
                    <input type="hidden" name="aura_recaptcha_token" class="aura-recaptcha-token" value="">
                    <input type="hidden" name="aura_recaptcha_action" value="contact_submit">

                    <div class="about-contact-form-row">
                        <div class="about-field">
                            <label for="aura-contact-name"><?php echo esc_html( (string) $about_settings['contact_name_label'] ); ?></label>
                            <input id="aura-contact-name" type="text" name="aura_contact_name" required placeholder="<?php echo esc_attr( (string) $about_settings['contact_name_placeholder'] ); ?>">
                        </div>
                        <div class="about-field">
                            <label for="aura-contact-email"><?php echo esc_html( (string) $about_settings['contact_email_label'] ); ?></label>
                            <input id="aura-contact-email" type="email" name="aura_contact_email" required placeholder="<?php echo esc_attr( (string) $about_settings['contact_email_placeholder'] ); ?>">
                        </div>
                    </div>

                    <div class="about-field">
                        <label for="aura-contact-company"><?php echo esc_html( (string) $about_settings['contact_company_label'] ); ?></label>
                        <input id="aura-contact-company" type="text" name="aura_contact_company" placeholder="<?php echo esc_attr( (string) $about_settings['contact_company_placeholder'] ); ?>">
                    </div>

                    <div class="about-field">
                        <label for="aura-contact-message"><?php echo esc_html( (string) $about_settings['contact_message_label'] ); ?></label>
                        <textarea id="aura-contact-message" name="aura_contact_message" rows="4" required placeholder="<?php echo esc_attr( (string) $about_settings['contact_message_placeholder'] ); ?>"></textarea>
                    </div>

                    <button class="aura-pill-btn aura-pill-btn-primary about-submit" type="submit"><?php echo esc_html( (string) $about_settings['contact_button_label'] ); ?></button>

                    <?php if ( $aura_recaptcha_enabled ) : ?>
                        <p class="about-recaptcha-legal">
                            This site is protected by reCAPTCHA and the Google
                            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
                            and
                            <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Terms of Service</a>
                            apply.
                        </p>
                    <?php endif; ?>
                </form>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>
