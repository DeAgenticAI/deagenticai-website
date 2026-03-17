<?php
/**
 * Waitlist CTA Section Template
 *
 * @package Aura
 */

$aura_recaptcha_enabled = function_exists('aura_is_recaptcha_enabled') && aura_is_recaptcha_enabled();
?>

<section class="waitlist-cta-section" id="waitlist">
    <div class="container">
        <div class="waitlist-content reveal-up" style="transition-delay: 0.06s;">
            <h2 class="waitlist-title">
                <?php
                $wl_prefix = aura_get_content( 'waitlist_title', 'Shape the Control Layer for' );
                $wl_accent = aura_get_content( 'waitlist_title_accent', 'Agentic AI' );
                echo esc_html( $wl_prefix );
                if ( '' !== $wl_accent ) : ?>
                    <span class="text-primary"> <?php echo esc_html( $wl_accent ); ?></span>
                <?php endif; ?>
            </h2>
            <p class="waitlist-description">
                <?php echo esc_html( aura_get_content( 'waitlist_description', "Our early access is invite-only. Join the design partner waitlist to track DeAgenticAI's progress and shape governed autonomous execution with our team. No marketing fluff-just infrastructure updates." ) ); ?>
            </p>
            
            <form id="waitlist-form" class="waitlist-form">
                <div class="waitlist-input-wrapper">
                    <input 
                        type="email" 
                        id="waitlist-email" 
                        name="email" 
                        placeholder="Enter your email" 
                        required
                        class="waitlist-input"
                    />
                    <div class="waitlist-input-glow"></div>
                </div>
                <button type="submit" class="waitlist-submit" id="waitlist-submit">
                    <span class="waitlist-btn-text">Join Waitlist</span>
                    <span class="waitlist-btn-loading">
                        <svg class="waitlist-spinner" viewBox="0 0 24 24">
                            <circle class="spinner-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                            <circle class="spinner-path" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        <span>Joining...</span>
                    </span>
                </button>
            </form>
            
            <div class="waitlist-message waitlist-message-success" id="waitlist-success" style="display: none;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>You have been added to the waitlist.</span>
            </div>
            
            <div class="waitlist-message waitlist-message-error" id="waitlist-error" style="display: none;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Something went wrong. Please try again.</span>
            </div>
            
            <p class="waitlist-disclaimer">
                By joining, you agree to receive updates about our platform. No spam, ever.
                <?php if ($aura_recaptcha_enabled) : ?>
                    <span class="waitlist-recaptcha-legal">
                        This site is protected by reCAPTCHA and the Google
                        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
                        and
                        <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Terms of Service</a>
                        apply.
                    </span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    
    <!-- Background decoration -->
    <div class="waitlist-bg">
        <div class="waitlist-glow waitlist-glow-1"></div>
        <div class="waitlist-glow waitlist-glow-2"></div>
    </div>
</section>
