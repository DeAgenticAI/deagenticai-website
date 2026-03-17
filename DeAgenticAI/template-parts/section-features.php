<?php
/**
 * Features Section Template
 *
 * @package Aura
 */

$aura_features_logo_url = function_exists( 'aura_get_site_logo_url' ) ? aura_get_site_logo_url() : '';
if ( '' === $aura_features_logo_url ) {
    $aura_features_logo_url = get_template_directory_uri() . '/images/logo.png';
}
?>

<section class="features-section">
    <!-- Clean Background Line -->
    <div class="section-divider"></div>

    <div class="container">
        <?php
        $features_title = aura_get_home_meta( 'features_title', 'How the Control Plane Works' );
        $features_title_muted = aura_get_home_meta( 'features_title_muted', '' );
        ?>
        <!-- Section Header -->
        <div class="section-header reveal-up" style="transition-delay: 0.06s;">
            <!-- Hero-Inspired Vertical Beam Animation -->
            <div class="header-beam">
                <svg class="h-full w-full" viewBox="0 0 6 192" fill="none">
                    <path d="M3 0V192" stroke="url(#header-beam)" stroke-width="1.5" stroke-linecap="round" class="beam-line animate-beam opacity-70"></path>
                    <defs>
                        <linearGradient id="header-beam" x1="3" y1="0" x2="3" y2="192" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#38BDF8" stop-opacity="0" />
                            <stop offset="0.5" stop-color="#38BDF8" />
                            <stop offset="1" stop-color="#38BDF8" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="section-badge">
                <span class="status-indicator">
                    <span class="status-dot animate-ping"></span>
                    <span class="status-dot"></span>
                </span>
                <span class="badge-text">Control Plane Architecture</span>
            </div>

            <h2 class="section-title">
                <?php echo esc_html( $features_title ); ?>
                <?php if ( '' !== trim( (string) $features_title_muted ) ) : ?>
                    <br>
                    <span class="text-white/60"><?php echo esc_html( $features_title_muted ); ?></span>
                <?php endif; ?>
            </h2>

            <p class="section-description">
                <?php echo esc_html( aura_get_home_meta( 'features_description', 'DeAgenticAI enforces safe autonomous execution through three architecture differentiators: Intent Sanitization at the boundary, Policy-First Enforcement at the cryptographic signing layer, and MPC Distributed Execution with no single point of failure.' ) ); ?>
            </p>
        </div>

        <!-- Feature Cards Grid -->
        <div class="features-grid">
            <!-- Intent Sanitization Card -->
            <div class="feature-card feature-card-policy spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="feature-card-hover-gradient"></div>
                <div class="feature-card-inner">
                    <h3 class="feature-title">Intent Sanitization</h3>
                    <p class="feature-description">
                        Every agent request is normalized and sanitized before enforcement, blocking malformed or unsafe payloads at the boundary competitors leave exposed.
                    </p>

                    <!-- Visual: Chat/Terminal UI -->
                    <div class="feature-visual feature-terminal">
                        <div class="terminal policy-terminal">
                            <div class="terminal-header">
                                <div class="terminal-dots">
                                    <span></span>
                                    <span></span>
                                </div>
                                <span class="terminal-title">DeAgenticAI v2.1</span>
                            </div>
                            <div class="terminal-body">
                                <div class="message user-message">
                                    <p>Initialize secure connection <span class="highlight">Alpha AI Bot</span>. Target <span class="text-primary">UniSwap</span>.</p>
                                </div>
                                <div class="message system-message">
                                    <div class="message-header">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9.94 15.5a2 2 0 0 0-1.44-1.44L2.37 12.48a.5.5 0 0 1 0-.96L8.5 9.94A2 2 0 0 0 9.94 8.5l1.58-6.13a.5.5 0 0 1 .96 0l1.58 6.13a2 2 0 0 0 1.44 1.44l6.13 1.58a.5.5 0 0 1 0 .96l-6.13 1.58a2 2 0 0 0-1.44 1.44l-1.58 6.13a.5.5 0 0 1-.96 0z"></path>
                                            <path d="M20 3v4"></path>
                                            <path d="M22 5h-4"></path>
                                        </svg>
                                        DeAgenticAI Protocol
                                    </div>
                                    <p>Analyzing Bot Intent. Optimal route secured.</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>
                                    <div class="progress-info">
                                        <span>EXECUTING</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policy-First Enforcement Card -->
            <div class="feature-card feature-card-wallet spotlight-card smart-liquidity-card animate-on-scroll" data-spotlight>
                <div class="wallet-card-shell"></div>
                <div class="wallet-card-inner-bg">
                    <div class="wallet-inner-spotlight"></div>
                </div>
                <div class="spotlight-border wallet-spotlight-border"></div>
                <div class="feature-card-inner wallet-card-content">
                    <div class="feature-title-row">
                        <div class="feature-icon-wrap">
                            <div class="feature-icon-glow"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="wallet-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2 3 14h9l-1 8 10-12h-9z"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">Policy-First Enforcement</h3>
                    </div>

                    <p class="feature-description">
                        Authorization is enforced cryptographically at signing time, not as an API middleware check, so policy remains binding under real execution conditions.
                    </p>

                    <!-- Visual: Hero-Inspired Orbit & Sonar -->
                    <div class="feature-visual wallet-orbit-visual">
                        <svg class="wallet-orbit-svg" viewBox="0 0 400 320" preserveAspectRatio="xMidYMid slice">
                            <defs>
                                <linearGradient id="smart-beam-grad-features" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#38BDF8" stop-opacity="0" />
                                    <stop offset="50%" stop-color="#38BDF8" />
                                    <stop offset="100%" stop-color="#38BDF8" stop-opacity="0" />
                                </linearGradient>
                            </defs>

                            <path d="M420,40 C320,40 280,160 200,160" class="wallet-path-base"></path>
                            <path d="M420,40 C320,40 280,160 200,160" class="wallet-path-beam animate-beam" style="stroke:url(#smart-beam-grad-features);stroke-dasharray:100 1000;animation-duration:3s;"></path>

                            <path d="M-20,280 C80,280 120,160 200,160" class="wallet-path-base"></path>
                            <path d="M-20,280 C80,280 120,160 200,160" class="wallet-path-beam animate-beam" style="stroke:url(#smart-beam-grad-features);stroke-dasharray:80 1000;animation-duration:4s;"></path>

                            <path d="M200,0 L200,160" class="wallet-path-drop"></path>
                        </svg>

                        <div class="wallet-sonar-ring ring-a animate-ping" style="animation-duration:4s;"></div>
                        <div class="wallet-sonar-ring ring-b animate-ping" style="animation-duration:3s;animation-delay:0.7s;"></div>

                        <div class="wallet-outer-ring animate-spin-slow" style="animation-duration:40s;">
                            <span class="wallet-ring-dot dot-right"></span>
                            <span class="wallet-ring-dot dot-left"></span>
                        </div>

                        <div class="wallet-middle-ring animate-spin-slow" style="animation-duration:30s;"></div>
                        <div class="wallet-inner-dashed-ring animate-spin-slow-reverse" style="animation-duration:20s;"></div>

                        <div class="wallet-active-glow-ring animate-spin-slow" style="animation-duration:15s;">
                            <span class="wallet-active-dot"></span>
                        </div>

                        <div class="wallet-orbit-nodes animate-spin-slow" style="animation-duration:30s;">
                            <div class="wallet-node wallet-node-gem">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="6 3 18 3 22 9 12 21 2 9 6 3"></polygon>
                                    <path d="M12 21 7 9"></path>
                                    <path d="M12 21 17 9"></path>
                                </svg>
                            </div>
                            <div class="wallet-node wallet-node-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <path d="M2 10h20"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="wallet-hub">
                            <img src="<?php echo esc_url( $aura_features_logo_url ); ?>" alt="DeAgenticAI Logo" loading="lazy" decoding="async">
                        </div>
                    </div>
                </div>
            </div>

            <!-- MPC Distributed Execution Card -->
            <div class="feature-card feature-card-safe spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="feature-card-hover-gradient"></div>
                <div class="feature-card-inner">
                    <div class="feature-title-row">
                        <div class="feature-icon-wrap">
                            <div class="feature-icon-glow"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="safe-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="2"></circle>
                                <path d="M16.24 7.76a6 6 0 1 1-8.48 8.48"></path>
                                <path d="M20 12a8 8 0 0 0-8-8"></path>
                                <path d="M12 20a8 8 0 0 1-8-8"></path>
                            </svg>
                        </div>
                        <h3 class="feature-title">MPC Distributed Execution</h3>
                    </div>
                    <p class="feature-description">
                        Threshold execution is coordinated across distributed MPC nodes so no single key holder or infrastructure component can unilaterally move capital.
                    </p>

                    <!-- Visual: Integration Nodes & Scaling -->
                    <div class="feature-visual safe-actions-visual">
                        <div class="safe-actions-header">
                            <div class="safe-header-dots">
                                <span></span>
                                <span class="animate-pulse"></span>
                            </div>
                            <span class="safe-header-label">CLUSTER :: SYNC_ACTIVE</span>
                        </div>

                        <div class="safe-actions-body">
                            <div class="safe-actions-body-glow"></div>
                            <div class="safe-actions-dot-grid"></div>

                            <div class="safe-hub-row">
                                <svg class="safe-connection-svg" viewBox="0 0 240 100">
                                    <defs>
                                        <linearGradient id="node-beam-features" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#38BDF8" stop-opacity="0" />
                                            <stop offset="50%" stop-color="#38BDF8" />
                                            <stop offset="100%" stop-color="#38BDF8" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                    <path d="M40,50 L200,50" stroke="rgba(255,255,255,0.05)" stroke-width="1"></path>
                                    <path d="M40,50 L200,50" stroke="url(#node-beam-features)" stroke-width="1.5" stroke-dasharray="40 160" stroke-linecap="round" class="animate-beam"></path>
                                </svg>

                                <div class="safe-node safe-node-side">
                                    <span class="safe-node-dot"></span>
                                </div>

                                <div class="safe-node safe-node-center">
                                    <span class="safe-node-core-glow"></span>
                                    <span class="safe-node-core"></span>
                                </div>

                                <div class="safe-node safe-node-side">
                                    <span class="safe-node-dot"></span>
                                </div>
                            </div>

                            <div class="safe-status-grid">
                                <div class="safe-status-item"><span class="safe-status-track"><span class="safe-status-fill status-1"></span></span></div>
                                <div class="safe-status-item"><span class="safe-status-track"><span class="safe-status-fill status-2"></span></span></div>
                                <div class="safe-status-item"><span class="safe-status-track"><span class="safe-status-fill status-3"></span></span></div>
                                <div class="safe-status-item"><span class="safe-status-track"><span class="safe-status-fill status-4"></span></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
