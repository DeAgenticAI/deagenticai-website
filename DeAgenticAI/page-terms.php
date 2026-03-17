<?php
/**
 * Template Name: Terms of Service
 *
 * @package Aura
 */

get_header();

$sections = array(
    array(
        'title' => '1. Acceptance of Terms',
        'content' => 'By accessing or using the DeAgenticAI platform, infrastructure, or services, you agree to be bound by these Terms of Service. If you do not agree to these terms, you must immediately cease all use of our services.',
    ),
    array(
        'title' => '2. Description of Service',
        'content' => 'DeAgenticAI provides a decentralized control plane infrastructure for autonomous AI agents, including MPC-based custody, policy enforcement, and delegation protocols. Our services are provided \'as-is\' and may evolve over time.',
    ),
    array(
        'title' => '3. User Responsibilities',
        'content' => 'You are responsible for maintaining the security of your accounts and any delegated authority granted to AI agents. DeAgenticAI is not responsible for losses resulting from compromised private keys, unauthorized agent configurations, or protocol-level exploits outside our direct control.',
    ),
    array(
        'title' => '4. Autonomous Agent Operations',
        'content' => 'When you delegate authority to an AI agent via our platform, you acknowledge that the agent will execute transactions based on its internal logic and the policies you define. You accept full responsibility for all transactions executed by agents under your delegation.',
    ),
    array(
        'title' => '5. Intellectual Property',
        'content' => 'All rights, title, and interest in and to the DeAgenticAI platform, including its software, logos, and trademarks, are and will remain the exclusive property of DeAgenticAI and its licensors.',
    ),
    array(
        'title' => '6. Limitation of Liability',
        'content' => 'To the maximum extent permitted by law, DeAgenticAI shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly.',
    ),
);
?>

<main id="primary" class="site-main legal-page">
    <div class="container">
        <div class="legal-wrap">
            <section class="legal-hero">
                <span class="legal-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3 4 7v6c0 5 3.4 8.8 8 10 4.6-1.2 8-5 8-10V7l-8-4Z"></path>
                        <path d="M8 12h8M12 8v8"></path>
                    </svg>
                </span>
                <h1 class="legal-title">Terms of <span>Service</span></h1>
                <p class="legal-updated">Last Updated: March 03, 2026</p>
            </section>

            <section class="legal-timeline">
                <?php foreach ($sections as $section) : ?>
                    <article class="legal-item">
                        <h2><?php echo esc_html($section['title']); ?></h2>
                        <p><?php echo esc_html($section['content']); ?></p>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="legal-cta">
                <p>Questions regarding our terms? Contact our legal and architecture team.</p>
                <a class="aura-pill-btn aura-pill-btn-primary" href="<?php echo esc_url(home_url('/about/#contact')); ?>">
                    Contact Us
                </a>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
