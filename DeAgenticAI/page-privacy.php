<?php
/**
 * Template Name: Privacy Policy
 *
 * @package Aura
 */

get_header();

$sections = array(
    array(
        'title' => '1. Data Collection',
        'content' => 'We collect information necessary to provide our infrastructure services, including wallet addresses, transaction metadata, and agent configuration parameters. We do not collect private keys or personal identification unless voluntarily provided via contact forms.',
    ),
    array(
        'title' => '2. Use of Information',
        'content' => 'The data collected is used to optimize protocol performance, enforce cryptographic policies, and maintain the security of the DeAgenticAI ecosystem. We may also use your email to provide critical system updates if you have joined our waitlist.',
    ),
    array(
        'title' => '3. Cryptographic Privacy',
        'content' => 'DeAgenticAI utilizes MPC (Multi-Party Computation) to ensure that your sensitive cryptographic material is never stored in a central location. Key shards are distributed, ensuring that no single entity, including DeAgenticAI, can reconstruct your full private key.',
    ),
    array(
        'title' => '4. Third-Party Sharing',
        'content' => 'We do not sell your data to third parties. We may share anonymized, aggregated transaction data with research partners or ecosystem developers to improve the autonomous agent landscape.',
    ),
    array(
        'title' => '5. Security Measures',
        'content' => 'We implement industry-standard security protocols, including end-to-end encryption and regular architectural audits, to protect our infrastructure from unauthorized access.',
    ),
    array(
        'title' => '6. Your Rights',
        'content' => 'You have the right to request the deletion of your contact information and any off-chain metadata associated with your project. On-chain transaction records, however, are immutable and cannot be deleted.',
    ),
);
?>

<main id="primary" class="site-main legal-page">
    <div class="container">
        <div class="legal-wrap">
            <section class="legal-hero">
                <span class="legal-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V6l-8-4-8 4v6c0 6 8 10 8 10Z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
                <h1 class="legal-title">Privacy <span>Policy</span></h1>
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
                <p>Concerned about your cryptographic privacy? Read our technical documentation on MPC.</p>
                <a class="aura-pill-btn aura-pill-btn-primary" href="<?php echo esc_url(home_url('/ecosystem/mpc-wallet')); ?>">
                    Explore Tech Stack
                </a>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
