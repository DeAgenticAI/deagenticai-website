<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main error-404-page">
    <section class="error-404-section">
        <div class="container">
            <div class="error-404-card reveal-up">
                <div class="error-404-badge">
                    <span class="error-404-indicator">
                        <span class="error-404-dot animate-ping"></span>
                        <span class="error-404-dot"></span>
                    </span>
                    <span>Error 404</span>
                </div>

                <p class="error-404-code">404</p>
                <h1 class="error-404-title">Page Not Found</h1>
                <p class="error-404-description">
                    The page you are looking for does not exist or may have been moved.
                    Return to the command center and continue from there.
                </p>

                <div class="error-404-actions">
                    <a class="error-404-btn error-404-btn-primary" href="<?php echo esc_url(home_url('/')); ?>">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
