<?php
/**
 * Hero Section Template
 *
 * @package Aura
 */

$semantic_triple = function_exists( 'aura_get_semantic_triple' )
    ? aura_get_semantic_triple()
    : "DeAgenticAI's Agentic Control Plane enforces cryptographic policy over AI agent authority - separating what an agent can do from what it is authorized to do - in Web3 and enterprise financial environments.";

$hero_description = aura_get_home_meta( 'hero_description', $semantic_triple );
if ( '' !== $semantic_triple && false === stripos( $hero_description, $semantic_triple ) ) {
    $hero_description = trim( $semantic_triple . ' ' . $hero_description );
}

?>

<section class="hero-section">
    <div class="container">
        <div class="hero-wrapper">
            <!-- Left Column: Copy -->
            <div class="hero-content reveal-up" style="transition-delay: 0.05s;">
                <div class="hero-status">
                    <span class="status-indicator">
                        <span class="status-dot animate-ping"></span>
                        <span class="status-dot"></span>
                    </span>
                    <span class="status-text">System Operational</span>
                </div>

                <h1 class="hero-title">
                    <?php echo esc_html( aura_get_home_meta( 'hero_headline', 'The Agentic Control Plane for' ) ); ?> <br>
                    <span class="hero-title-accent"><?php echo esc_html( aura_get_home_meta( 'hero_headline_accent', 'AI Agent Authority in Web3 and Enterprise' ) ); ?></span>
                </h1>

                <p class="hero-description">
                    <?php echo esc_html( $hero_description ); ?>
                </p>

                <div class="hero-buttons">
                    <!-- Animated Shiny CTA Button -->
                    <button class="shiny-cta" type="button" onclick="document.getElementById('waitlist')?.scrollIntoView({ behavior: 'smooth' });">
                        <span><?php echo esc_html( aura_get_home_meta( 'hero_cta_label', 'Join Waitlist' ) ); ?></span>
                    </button>

                    <!-- Gradient Pill Button with Icon -->
                    <a href="<?php echo esc_url( aura_get_home_meta( 'hero_secondary_url', home_url( '/ecosystem' ) ) ); ?>" class="btn-secondary">
                        <span><?php echo esc_html( aura_get_home_meta( 'hero_secondary_label', 'View Ecosystem' ) ); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13.25 12.75V18a.75.75 0 0 0 1.28.53l6-6a.75.75 0 0 0 0-1.06l-6-6a.75.75 0 0 0-1.28.53z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Column: Network Visualization -->
            <div class="hero-visualization reveal-scale perspective-1000" style="transition-delay: 0.12s;">
                <svg class="visualization-svg" viewBox="0 0 600 600" aria-hidden="true">
                    <defs>
                        <radialGradient id="hero-center-glow" cx="0.5" cy="0.5" r="0.5">
                            <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.15"></stop>
                            <stop offset="100%" stop-color="#38BDF8" stop-opacity="0"></stop>
                        </radialGradient>
                    </defs>

                    <g>
                        <path d="M -50 150 C 100 150, 100 300, 300 300" fill="none" stroke="white" stroke-width="1" stroke-opacity="0.08"></path>
                        <path d="M -50 150 C 100 150, 100 300, 300 300" fill="none" stroke="#38BDF8" stroke-width="1.5" class="beam-line animate-beam" opacity="0.6"></path>
                    </g>

                    <g>
                        <path d="M -50 450 C 100 450, 100 300, 300 300" fill="none" stroke="white" stroke-width="1" stroke-opacity="0.08"></path>
                        <path d="M -50 450 C 100 450, 100 300, 300 300" fill="none" stroke="#38BDF8" stroke-width="1.5" class="beam-line animate-beam" opacity="0.6" style="animation-delay: -1s;"></path>
                    </g>

                    <g>
                        <path d="M 650 100 C 500 100, 500 300, 300 300" fill="none" stroke="white" stroke-width="1" stroke-opacity="0.08"></path>
                        <path d="M 650 100 C 500 100, 500 300, 300 300" fill="none" stroke="#38BDF8" stroke-width="1.5" class="beam-line animate-beam" opacity="0.6" style="animation-delay: -2s;"></path>
                    </g>

                    <g>
                        <path d="M 650 500 C 500 500, 500 300, 300 300" fill="none" stroke="white" stroke-width="1" stroke-opacity="0.08"></path>
                        <path d="M 650 500 C 500 500, 500 300, 300 300" fill="none" stroke="#38BDF8" stroke-width="1.5" class="beam-line animate-beam" opacity="0.6" style="animation-delay: -1.5s;"></path>
                    </g>

                    <g transform="translate(300, 300)">
                        <circle r="120" fill="url(#hero-center-glow)" class="animate-pulse"></circle>

                        <circle r="20" fill="none" stroke="#38BDF8" stroke-width="1" opacity="0.5" class="animate-ping" style="animation-duration: 3s;"></circle>
                        <circle r="20" fill="none" stroke="#38BDF8" stroke-width="1" opacity="0.5" class="animate-ping" style="animation-duration: 3s; animation-delay: 1s;"></circle>
                        <circle r="20" fill="none" stroke="#38BDF8" stroke-width="1" opacity="0.5" class="animate-ping" style="animation-duration: 3s; animation-delay: 2s;"></circle>

                        <circle r="65" fill="none" stroke="white" stroke-opacity="0.1" stroke-width="1" stroke-dasharray="10 20" class="animate-spin-slow"></circle>
                        <circle r="45" fill="none" stroke="#38BDF8" stroke-opacity="0.2" stroke-width="1" stroke-dasharray="4 6" class="animate-spin-slow-reverse"></circle>

                        <g class="animate-spin-slow" style="animation-duration: 20s;">
                            <path d="M -80 0 L -70 0" stroke="white" stroke-opacity="0.2"></path>
                            <path d="M 80 0 L 70 0" stroke="white" stroke-opacity="0.2"></path>
                            <path d="M 0 -80 L 0 -70" stroke="white" stroke-opacity="0.2"></path>
                            <path d="M 0 80 L 0 70" stroke="white" stroke-opacity="0.2"></path>
                        </g>

                        <circle r="8" fill="#0A0A0A" stroke="#38BDF8" stroke-width="2"></circle>
                        <circle r="4" fill="#38BDF8" class="animate-pulse-fast"></circle>
                    </g>
                </svg>

                <div class="floating-label top-left">
                    <span class="label-text"><?php esc_html_e( 'Auditable', 'aura' ); ?></span>
                    <div class="label-line"></div>
                </div>

                <div class="floating-label bottom-right">
                    <span class="label-text"><?php esc_html_e( 'DeFi Native', 'aura' ); ?></span>
                    <div class="label-line"></div>
                </div>

                <div class="hero-data-indicator" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span class="is-active"></span>
                </div>
            </div>
        </div>

        <div class="hero-agentic-lifecycle reveal-up" style="transition-delay: 0.2s;">
            <?php
            if (function_exists('aura_render_agentic_lifecycle_dock')) {
                echo aura_render_agentic_lifecycle_dock(array(
                    'title' => __('Master Happy Path Diagram', 'aura'),
                    'status' => __('Happy Path Protocol Active', 'aura'),
                ));
            }
            ?>
        </div>
    </div>
</section>
