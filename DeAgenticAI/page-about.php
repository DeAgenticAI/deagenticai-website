<?php
/**
 * Template Name: About
 *
 * @package Aura
 */

get_header();

$contact_status = '';
if (isset($_GET['contact'])) {
    $contact_status = sanitize_key(wp_unslash($_GET['contact']));
}
$aura_recaptcha_enabled = function_exists('aura_is_recaptcha_enabled') && aura_is_recaptcha_enabled();
?>

<main id="primary" class="site-main about-page">
    <div class="container">
        <section class="about-hero">
            <div class="about-badge">
                <span class="about-badge-dot animate-ping"></span>
                <span class="about-badge-text">About DeAgenticAI</span>
            </div>

            <h1 class="about-title">
                We Built the Missing Layer Between AI Agents and On-Chain Authority
            </h1>

            <p class="about-lead">
                Autonomous AI agents are already operating on-chain. They are executing treasury strategies, interacting with DeFi protocols, and beginning to represent institutional interests in governance systems. The infrastructure they are running on &mdash; private key management, multisig governance, rule-based automation &mdash; was designed for human actors. It breaks down under every assumption that AI introduces. DeAgenticAI exists to close that gap at the infrastructure layer.
            </p>
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
                <h3>Our Mission</h3>
                <p>
                    To make autonomous intelligence safe to own and move capital.
                </p>
                <p class="about-card-supporting">
                    We do this by separating an agent's capability from its authority and enforcing that authority cryptographically - so that AI agents can participate in Web3 and enterprise financial operations responsibly, transparently, and at scale.
                </p>
            </article>

            <article class="about-card">
                <span class="about-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V6l-8-4-8 4v6c0 6 8 10 8 10Z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
                <h3>Our Vision</h3>
                <p>
                    A world in which autonomous agents are first-class participants in both decentralised and traditional financial systems - where DAOs operate without governance latency, where institutional asset managers automate complex portfolio management without compromising compliance, and where AI agents negotiate, pay, and collaborate with each other over open protocols.
                </p>
                <p>
                    Achieving that requires infrastructure that today's ecosystem lacks: a universal Agentic Control Plane that any agent, platform, or organisation can integrate without surrendering oversight or auditability.
                </p>
                <p class="about-card-emphasis">
                    Authority should not be inferred from possession. It must be explicitly defined, enforced, and provable.
                </p>
            </article>
        </section>

        <section class="about-thesis">
            <article class="about-thesis-card">
                <p class="about-section-label">Why We Exist</p>
                <h2>Founding Thesis</h2>
                <p>
                    The fundamental premise of Web3 security - that authority derives from possession of a private key - was designed around human actors who make deliberate, slow, and accountable decisions. An AI agent inverts all three of those assumptions. Agents are probabilistic rather than deterministic. They operate at speeds that make human review impossible in real time. And they can be compromised through prompt injection - a threat class that has no analog in human behaviour.
                </p>
                <p class="about-thesis-emphasis">
                    Giving an AI agent a private key is not a security posture. It is an abdication of one.
                </p>
                <p>
                    This is not a risk to be managed at the application layer or the smart contract layer. It is a structural gap in infrastructure - a missing primitive that must exist between the AI agent and the execution environment. That primitive is the Agentic Control Plane.
                </p>
            </article>
        </section>

        <section class="about-focus">
            <div class="about-focus-layout">
                <div>
                    <p class="about-section-label">The Architecture</p>
                    <h2>What We're Building</h2>
                    <p>
                        Five interoperable control layers define how autonomous agents can execute safely in adversarial financial environments.
                    </p>

                    <div class="about-focus-list">
                        <article class="about-focus-list-item">
                            <span class="about-focus-list-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <div>
                                <h4>Intent Sanitization</h4>
                                <p>Every incoming intent is treated as potentially adversarially crafted and evaluated for structural integrity, source authenticity, semantic coherence, and context manipulation before it is allowed to enter the enforcement pipeline, making it the most critical security boundary in the architecture and the only component specifically designed to block the threat class that makes AI agents fundamentally different from human signers.</p>
                            </div>
                        </article>

                        <article class="about-focus-list-item">
                            <span class="about-focus-list-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M8 8h8v8H8z"></path>
                                </svg>
                            </span>
                            <div>
                                <h4>Policy-First Enforcement</h4>
                                <p>No action is authorised without explicit policy validation, with policies expressed in a declarative Domain-Specific Language, evaluated deterministically at the signing layer, and versioned so every historical governance context can be reconstructed.</p>
                            </div>
                        </article>

                        <article class="about-focus-list-item">
                            <span class="about-focus-list-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3a7 7 0 0 0-7 7v4a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4v-4a7 7 0 0 0-7-7Z"></path>
                                    <path d="M9 14a3 3 0 1 0 6 0"></path>
                                </svg>
                            </span>
                            <div>
                                <h4>Behavioural Fraud Detection</h4>
                                <p>Static policy evaluates whether an action is permitted, while fraud detection evaluates whether it is consistent with the agent's declared purpose, historical behaviour, and current portfolio state to catch structurally valid but semantically anomalous actions.</p>
                            </div>
                        </article>

                        <article class="about-focus-list-item">
                            <span class="about-focus-list-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7h16"></path>
                                    <path d="M6 11h12"></path>
                                    <path d="M8 15h8"></path>
                                    <path d="M10 19h4"></path>
                                </svg>
                            </span>
                            <div>
                                <h4>MPC Distributed Execution</h4>
                                <p>Key shares are distributed across geographically separated signing nodes using threshold signing protocols, no individual node ever holds the complete private key, and each signing node independently verifies the policy authorisation hash before contributing its partial signature, a cryptographic requirement that cannot be bypassed by compromising any single component including the orchestrator.</p>
                            </div>
                        </article>

                        <article class="about-focus-list-item">
                            <span class="about-focus-list-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 5h16v14H4z"></path>
                                    <path d="M8 9h8"></path>
                                    <path d="M8 13h5"></path>
                                    <path d="M8 17h6"></path>
                                </svg>
                            </span>
                            <div>
                                <h4>Audit and Compliance Infrastructure</h4>
                                <p>Every decision and signature in the control plane is recorded as a cryptographically signed, hash-chained, tamper-evident log entry formatted for MiCA regulatory reporting, DORA operational resilience requirements, and Travel Rule data exchange, making compliance a first-class architectural layer rather than a reporting overlay.</p>
                            </div>
                        </article>
                    </div>
                </div>

                <div class="about-visual">
                    <div class="about-visual-glow"></div>
                    <div class="about-visual-card">
                        <div class="about-visual-grid">
                            <div class="about-visual-cell">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m4 12 4-4 4 4 8-8"></path>
                                    <path d="M4 19h16"></path>
                                </svg>
                                <span>Intent Guard</span>
                            </div>

                            <div class="about-visual-cell active">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7h16"></path>
                                    <path d="M6 11h12"></path>
                                    <path d="M8 15h8"></path>
                                </svg>
                                <span>Policy DSL</span>
                            </div>

                            <div class="about-visual-cell">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7h16"></path>
                                    <path d="M7 12h10"></path>
                                    <path d="M10 17h4"></path>
                                </svg>
                                <span>MPC Quorum</span>
                            </div>

                            <div class="about-visual-cell">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 5h16v14H4z"></path>
                                    <path d="M8 9h8"></path>
                                    <path d="M8 13h5"></path>
                                </svg>
                                <span>Audit Trail</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-team">
            <article class="about-team-card">
                <p class="about-section-label">The Team</p>
                <h2>Founding Story</h2>
                <p>
                    DeAgenticAI was founded by [Name] and [Name] in [year], after [one sentence on the specific problem or moment that made the gap obvious].
                </p>
                <p>
                    [Founder 1 name] brings [relevant background - e.g. background in MPC cryptography, institutional custody, distributed systems]. [Founder 2 name] brings [relevant background - e.g. background in DeFi protocol design, enterprise compliance, AI infrastructure].
                </p>
                <p>
                    The company is [location / remote / distributed], building in public and operating under the principle that infrastructure this critical should be verifiable, not trusted.
                </p>
                <p class="about-team-note">
                    Once real founder details are confirmed, this section can be finalized in full.
                </p>
            </article>
        </section>

        <section id="contact" class="about-contact">
            <div class="about-contact-card">
                <div class="about-contact-head">
                    <h2>Work With Us</h2>
                    <p>
                        DeAgenticAI is currently working with a select group of design partners - DAOs, Web3 funds, and RWA platform operators - who need governed autonomous execution before it is available at general access. If your organisation is evaluating infrastructure for AI agent operations, we want to hear from you.
                    </p>
                    <p>
                        Reach us at <span class="about-contact-inline-email">[email]</span> or use the form below.
                    </p>
                </div>

                <?php if ('success' === $contact_status) : ?>
                    <div class="about-contact-notice success">
                        Message sent successfully. Our team will contact you shortly.
                    </div>
                <?php elseif ('recaptcha' === $contact_status) : ?>
                    <div class="about-contact-notice error">
                        Security verification failed. Please retry and submit again.
                    </div>
                <?php elseif ('error' === $contact_status) : ?>
                    <div class="about-contact-notice error">
                        Failed to send your message. Please try again.
                    </div>
                <?php endif; ?>

                <form class="about-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="aura_contact_submit">
                    <?php wp_nonce_field('aura_contact_submit', 'aura_contact_nonce'); ?>
                    <input type="hidden" name="aura_recaptcha_token" class="aura-recaptcha-token" value="">
                    <input type="hidden" name="aura_recaptcha_action" value="contact_submit">

                    <div class="about-contact-form-row">
                        <div class="about-field">
                            <label for="aura-contact-name">Full Name</label>
                            <input id="aura-contact-name" type="text" name="aura_contact_name" required placeholder="John Doe">
                        </div>
                        <div class="about-field">
                            <label for="aura-contact-email">Email Address</label>
                            <input id="aura-contact-email" type="email" name="aura_contact_email" required placeholder="john@company.com">
                        </div>
                    </div>

                    <div class="about-field">
                        <label for="aura-contact-company">Company / Project</label>
                        <input id="aura-contact-company" type="text" name="aura_contact_company" placeholder="DeAgentic AI Systems">
                    </div>

                    <div class="about-field">
                        <label for="aura-contact-message">Message</label>
                        <textarea id="aura-contact-message" name="aura_contact_message" rows="4" required placeholder="Tell us about your architectural needs..."></textarea>
                    </div>

                    <button class="aura-pill-btn aura-pill-btn-primary about-submit" type="submit">
                        Send Transmission
                    </button>

                    <?php if ($aura_recaptcha_enabled) : ?>
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
