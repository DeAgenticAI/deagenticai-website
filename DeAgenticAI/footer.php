<?php
/**
 * The footer for our theme
 *
 * @package Aura
 */

$current_year = date('Y');

$aura_footer_site_name  = function_exists( 'aura_get_content' ) ? aura_get_content( 'site_name', 'DeAgenticAI' ) : 'DeAgenticAI';
$aura_footer_tagline    = function_exists( 'aura_get_content' ) ? aura_get_content( 'footer_tagline', 'The Agentic Control Plane for Web3 and the Enterprise. Making autonomous intelligence safe to own and move capital.' ) : 'The Agentic Control Plane for Web3 and the Enterprise. Making autonomous intelligence safe to own and move capital.';
$aura_twitter_url       = function_exists( 'aura_get_content' ) ? aura_get_content( 'twitter_url', '#' ) : '#';
$aura_linkedin_url      = function_exists( 'aura_get_content' ) ? aura_get_content( 'linkedin_url', '#' ) : '#';
$aura_github_url        = function_exists( 'aura_get_content' ) ? aura_get_content( 'github_url', '#' ) : '#';
$aura_email_address     = function_exists( 'aura_get_content' ) ? aura_get_content( 'email_address', '' ) : '';
$aura_email_href        = '' !== $aura_email_address ? esc_url( 'mailto:' . $aura_email_address ) : '#';

$aura_footer_logo_url   = get_template_directory_uri() . '/images/logo.png';
$aura_footer_logo_id    = get_theme_mod( 'custom_logo' );
if ( $aura_footer_logo_id ) {
    $aura_footer_logo_data = wp_get_attachment_image_src( $aura_footer_logo_id, 'full' );
    if ( $aura_footer_logo_data ) {
        $aura_footer_logo_url = $aura_footer_logo_data[0];
    }
}
?>

    </div><!-- #content -->

    <?php if (!is_page_template('page-about.php') && !is_page('about') && !is_page_template('page-waitlist.php') && !is_page('waitlist') && !is_404()) : ?>
        <?php get_template_part('template-parts/section', 'waitlist-cta'); ?>
    <?php endif; ?>

    <footer id="colophon" class="site-footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <!-- Brand Column -->
                    <div class="footer-brand">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                            <img src="<?php echo esc_url( $aura_footer_logo_url ); ?>" alt="<?php echo esc_attr( $aura_footer_site_name ); ?>" class="logo-image" loading="lazy" decoding="async" />
                            <span><?php echo esc_html( $aura_footer_site_name ); ?></span>
                        </a>
                        <p class="footer-description">
                            <?php echo esc_html( $aura_footer_tagline ); ?>
                        </p>
                        <div class="footer-social">
                            <a href="<?php echo esc_url( '' !== $aura_twitter_url ? $aura_twitter_url : '#' ); ?>" class="social-link" aria-label="Twitter"
                               <?php if ( '#' !== $aura_twitter_url && '' !== $aura_twitter_url ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                                </svg>
                            </a>
                            <a href="<?php echo esc_url( '' !== $aura_linkedin_url ? $aura_linkedin_url : '#' ); ?>" class="social-link" aria-label="LinkedIn"
                               <?php if ( '#' !== $aura_linkedin_url && '' !== $aura_linkedin_url ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                    <rect x="2" y="9" width="4" height="12"></rect>
                                    <circle cx="4" cy="4" r="2"></circle>
                                </svg>
                            </a>
                            <a href="<?php echo esc_url( '' !== $aura_github_url ? $aura_github_url : '#' ); ?>" class="social-link" aria-label="GitHub"
                               <?php if ( '#' !== $aura_github_url && '' !== $aura_github_url ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                </svg>
                            </a>
                            <a href="<?php echo $aura_email_href; ?>" class="social-link" aria-label="Email">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Platform Links -->
                    <div class="footer-links footer-links-platform">
                        <h4><?php esc_html_e( 'Platform', 'aura' ); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/ecosystem' ) ); ?>"><?php esc_html_e( 'Ecosystem', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/solutions' ) ); ?>"><?php esc_html_e( 'Solutions', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/glossary' ) ); ?>"><?php esc_html_e( 'Glossary', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/compare' ) ); ?>"><?php esc_html_e( 'Compare', 'aura' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Solutions (ICP) Links -->
                    <div class="footer-links footer-links-solutions">
                        <h4><?php esc_html_e( 'Solutions', 'aura' ); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/solutions/ai-infrastructure' ) ); ?>"><?php esc_html_e( 'AI Infrastructure', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/solutions/web3-startups' ) ); ?>"><?php esc_html_e( 'Web3 Startups', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/solutions/dao-treasury' ) ); ?>"><?php esc_html_e( 'DAO Treasury', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/solutions/enterprise' ) ); ?>"><?php esc_html_e( 'Enterprise', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/solutions/rwa-management' ) ); ?>"><?php esc_html_e( 'RWA Management', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/solutions/compliance' ) ); ?>"><?php esc_html_e( 'Compliance / MiCA &amp; DORA', 'aura' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Learn Links -->
                    <div class="footer-links footer-links-learn">
                        <h4><?php esc_html_e( 'Learn', 'aura' ); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/learn/pillars' ) ); ?>"><?php esc_html_e( 'Pillars', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/learn/guides' ) ); ?>"><?php esc_html_e( 'Guides', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/learn/research' ) ); ?>"><?php esc_html_e( 'Research', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/learn/updates' ) ); ?>"><?php esc_html_e( 'Updates', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/learn/datasets' ) ); ?>"><?php esc_html_e( 'Datasets', 'aura' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Company Links -->
                    <div class="footer-links footer-links-company">
                        <h4><?php esc_html_e( 'Company', 'aura' ); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/waitlist' ) ); ?>"><?php esc_html_e( 'Waitlist', 'aura' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Legal Links -->
                    <div class="footer-links footer-links-legal">
                        <h4><?php esc_html_e( 'Legal', 'aura' ); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/terms' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'aura' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/privacy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'aura' ); ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="footer-bottom">
                    <p>&copy; <?php echo esc_html($current_year); ?> DeAgenticAI Platform. All rights reserved.</p>
                    <div class="footer-status">
                        <span class="status-version">Secure Protocol V2.4.0</span>
                        <span class="status-indicator">
                            <span class="status-dot"></span>
                            <span class="status-text">Systems Operational</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Background Glow -->
        <div class="footer-glow"></div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
