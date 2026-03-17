<?php
/**
 * Architecture Section Template
 *
 * @package Aura
 */

$aura_architecture_logo_url = function_exists( 'aura_get_site_logo_url' ) ? aura_get_site_logo_url() : '';
if ( '' === $aura_architecture_logo_url ) {
    $aura_architecture_logo_url = get_template_directory_uri() . '/images/logo.png';
}
?>

<section class="architecture-section">
    <!-- Background Effects -->
    <div class="section-divider"></div>
    <div class="grid-bg architecture-grid-bg"></div>

    <div class="container">
        <!-- Section Header -->
        <div class="section-header reveal-up" style="transition-delay: 0.06s;">
            <div class="section-badge">
                <span class="status-indicator">
                    <span class="status-dot animate-ping"></span>
                    <span class="status-dot"></span>
                </span>
                <span class="badge-text">Conceptual Architecture</span>
            </div>

            <h2 class="section-title">
                <?php echo esc_html( aura_get_home_meta( 'arch_title', 'Modular building blocks' ) ); ?> <br>
                <span class="text-white/60 italic"><?php echo esc_html( aura_get_home_meta( 'arch_title_muted', 'for agentic systems' ) ); ?></span>
            </h2>

            <p class="section-description">
                <?php echo esc_html( aura_get_home_meta( 'arch_description', 'DeAgenticAI is organized into six control-plane layers: Agent Identity & Registry, Intent Sanitization, Policy Engine, Fraud Detection, MPC Distributed Execution, and Chain Abstraction. Together they enforce safe, verifiable autonomous execution across Web3 and enterprise environments.' ) ); ?>
            </p>
        </div>

        <!-- Central Platform Animation -->
        <div class="platform-core reveal-scale" style="transition-delay: 0.1s;">
            <div class="sonar-ring sonar-ring-1"></div>
            <div class="sonar-ring sonar-ring-2"></div>
            
            <div class="platform-hub">
                <img src="<?php echo esc_url( $aura_architecture_logo_url ); ?>" alt="DeAgenticAI Logo" class="platform-logo animate-pulse" loading="lazy" decoding="async">
                <div class="platform-ring ring-1"></div>
                <div class="platform-ring ring-2"></div>
            </div>
            
            <!-- Connection Lines SVG -->
            <svg class="connection-lines" viewBox="0 0 800 400">
                <defs>
                    <linearGradient id="core-beam" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#38BDF8" stop-opacity="0" />
                        <stop offset="50%" stop-color="#38BDF8" />
                        <stop offset="100%" stop-color="#38BDF8" stop-opacity="0" />
                    </linearGradient>
                </defs>
                <!-- Static base lines -->
                <path d="M400,200 L150,100" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none" />
                <path d="M400,200 L650,100" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none" />
                <path d="M400,200 L150,300" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none" />
                <path d="M400,200 L650,300" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none" />
                <path d="M400,200 L150,200" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none" />
                <path d="M400,200 L650,200" stroke="rgba(255,255,255,0.05)" stroke-width="1" fill="none" />
                <!-- Animated beam paths -->
                <path class="beam-path" d="M400,200 L150,100" stroke="url(#core-beam)" stroke-width="2" fill="none" stroke-dasharray="50 400" style="animation-duration: 3s; animation-delay: 0s;" />
                <path class="beam-path" d="M400,200 L650,100" stroke="url(#core-beam)" stroke-width="2" fill="none" stroke-dasharray="50 400" style="animation-duration: 3.5s; animation-delay: 0.2s;" />
                <path class="beam-path" d="M400,200 L150,300" stroke="url(#core-beam)" stroke-width="2" fill="none" stroke-dasharray="50 400" style="animation-duration: 4s; animation-delay: 0.4s;" />
                <path class="beam-path" d="M400,200 L650,300" stroke="url(#core-beam)" stroke-width="2" fill="none" stroke-dasharray="50 400" style="animation-duration: 4.5s; animation-delay: 0.6s;" />
                <path class="beam-path" d="M400,200 L150,200" stroke="url(#core-beam)" stroke-width="2" fill="none" stroke-dasharray="50 400" style="animation-duration: 5s; animation-delay: 0.8s;" />
                <path class="beam-path" d="M400,200 L650,200" stroke="url(#core-beam)" stroke-width="2" fill="none" stroke-dasharray="50 400" style="animation-duration: 5.5s; animation-delay: 1s;" />
            </svg>
            
            <!-- Floating Nodes -->
            <div class="floating-nodes">
                <div class="floating-node node-mpc" style="top: 15%; left: 10%;">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <span class="node-label">MPC Execution</span>
                </div>
                <div class="floating-node node-policy" style="top: 15%; right: 10%;">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 12 15 22 5"></polyline></svg>
                    </div>
                    <span class="node-label">Policy Engine</span>
                </div>
                <div class="floating-node node-mcp" style="top: 45%; left: 5%;">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect></svg>
                    </div>
                    <span class="node-label">Chain Abstraction</span>
                </div>
                <div class="floating-node node-realtime" style="top: 45%; right: 5%;">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <span class="node-label">Intent Sanitization</span>
                </div>
                <div class="floating-node node-storage" style="bottom: 15%; left: 10%;">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                    </div>
                    <span class="node-label">Identity Registry</span>
                </div>
                <div class="floating-node node-audit" style="bottom: 15%; right: 10%;">
                    <div class="node-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                    </div>
                    <span class="node-label">Fraud Detection</span>
                </div>
            </div>
        </div>

        <!-- 6-Card Grid -->
        <div class="architecture-grid">
            <!-- Card 1: Agent Identity & Registry -->
            <div class="architecture-card spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="card-title">Agent Identity &amp; Registry</h3>
                <p class="card-description">
                    Verifiable identity and authority registry for autonomous agents, including scope, provenance, and delegated execution boundaries.
                </p>
            </div>

            <!-- Card 2: Intent Sanitization -->
            <div class="architecture-card spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <polyline points="9 12 12 15 22 5"></polyline>
                    </svg>
                </div>
                <h3 class="card-title">Intent Sanitization</h3>
                <p class="card-description">
                    Every request is normalized and sanitized at the boundary to remove malformed, unsafe, and ambiguous execution payloads.
                </p>
            </div>

            <!-- Card 3: Policy Engine -->
            <div class="architecture-card spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                        <rect x="9" y="9" width="6" height="6"></rect>
                        <line x1="9" y1="1" x2="9" y2="4"></line>
                        <line x1="15" y1="1" x2="15" y2="4"></line>
                        <line x1="9" y1="20" x2="9" y2="23"></line>
                        <line x1="15" y1="20" x2="15" y2="23"></line>
                        <line x1="20" y1="9" x2="23" y2="9"></line>
                        <line x1="20" y1="14" x2="23" y2="14"></line>
                        <line x1="1" y1="9" x2="4" y2="9"></line>
                        <line x1="1" y1="14" x2="4" y2="14"></line>
                    </svg>
                </div>
                <h3 class="card-title">Policy Engine</h3>
                <p class="card-description">
                    Cryptographic policy enforcement at signing time, not middleware-only controls, with explicit limits for spending, targets, and delegation.
                </p>
            </div>

            <!-- Card 4: Fraud Detection -->
            <div class="architecture-card spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                    </svg>
                </div>
                <h3 class="card-title">Fraud Detection</h3>
                <p class="card-description">
                    Runtime anomaly and fraud controls score each execution request and block high-risk behavior before signatures are produced.
                </p>
            </div>

            <!-- Card 5: MPC Distributed Execution -->
            <div class="architecture-card spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="11" y1="8" x2="11" y2="14"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                </div>
                <h3 class="card-title">MPC Distributed Execution</h3>
                <p class="card-description">
                    Threshold signatures are generated across distributed MPC nodes so no single key holder or service can move capital alone.
                </p>
            </div>

            <!-- Card 6: Chain Abstraction -->
            <div class="architecture-card spotlight-card animate-on-scroll" data-spotlight>
                <div class="spotlight-bg"></div>
                <div class="spotlight-border"></div>
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
                        <path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
                        <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path>
                        <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path>
                    </svg>
                </div>
                <h3 class="card-title">Chain Abstraction</h3>
                <p class="card-description">
                    Chain-agnostic execution routes approved intents across networks with consistent policy enforcement and settlement semantics.
                </p>
            </div>
        </div>
    </div>
</section>
