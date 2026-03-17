<?php
/**
 * Template Name: Ecosystem
 *
 * @package Aura
 */

$ecosystem_settings = function_exists( 'aura_get_ecosystem_page_settings' ) ? aura_get_ecosystem_page_settings() : array();
$faqs               = isset( $ecosystem_settings['faqs'] ) && is_array( $ecosystem_settings['faqs'] ) ? $ecosystem_settings['faqs'] : array();
$problem_points     = isset( $ecosystem_settings['problem_points'] ) && is_array( $ecosystem_settings['problem_points'] ) ? $ecosystem_settings['problem_points'] : array();
$problem_columns    = array_chunk( $problem_points, 2 );
$architecture_steps = isset( $ecosystem_settings['architecture_steps'] ) && is_array( $ecosystem_settings['architecture_steps'] ) ? $ecosystem_settings['architecture_steps'] : array();
$components         = isset( $ecosystem_settings['components'] ) && is_array( $ecosystem_settings['components'] ) ? $ecosystem_settings['components'] : array();
$comparison_rows    = isset( $ecosystem_settings['comparison_rows'] ) && is_array( $ecosystem_settings['comparison_rows'] ) ? $ecosystem_settings['comparison_rows'] : array();
$unity_bullets      = isset( $ecosystem_settings['unity_bullets'] ) && is_array( $ecosystem_settings['unity_bullets'] ) ? $ecosystem_settings['unity_bullets'] : array();
$control_plane_labels = array();

if ( isset( $ecosystem_settings['control_plane_labels'] ) && is_array( $ecosystem_settings['control_plane_labels'] ) ) {
    foreach ( $ecosystem_settings['control_plane_labels'] as $item ) {
        if ( isset( $item['label'] ) && '' !== trim( (string) $item['label'] ) ) {
            $control_plane_labels[] = (string) $item['label'];
        }
    }
}

$faq_schema_entities = array_values(
    array_filter(
        array_map(
            static function ( $faq ) {
                $question = isset( $faq['question'] ) ? trim( (string) $faq['question'] ) : '';
                $answer   = isset( $faq['answer'] ) ? trim( (string) $faq['answer'] ) : '';

                return array(
                    '@type' => 'Question',
                    'name'  => $question,
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text'  => $answer,
                    ),
                );
            },
            $faqs
        ),
        static function ( $faq ) {
            return '' !== $faq['name'] && '' !== $faq['acceptedAnswer']['text'];
        }
    )
);
$faq_schema = array(
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $faq_schema_entities,
);

$component_icons = array(
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path><path d="m9 12 2 2 4-4"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"></path></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path><path d="M11 8v6M8 11h6"></path></svg>',
);
$component_icon_allowed_tags = array(
    'svg'     => array(
        'viewBox'      => true,
        'fill'         => true,
        'stroke'       => true,
        'stroke-width' => true,
    ),
    'rect'    => array(
        'x'      => true,
        'y'      => true,
        'width'  => true,
        'height' => true,
        'rx'     => true,
    ),
    'path'    => array(
        'd' => true,
    ),
    'polygon' => array(
        'points' => true,
    ),
    'circle'  => array(
        'cx' => true,
        'cy' => true,
        'r'  => true,
    ),
);

$logo_uri = function_exists( 'aura_get_site_logo_url' ) ? aura_get_site_logo_url() : '';
if ( '' === $logo_uri ) {
    $logo_uri = get_template_directory_uri() . '/images/logo.png';
}

get_header();
?>
<script type="application/ld+json">
<?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?>
</script>

<main id="primary" class="site-main eco-page">
    <div class="eco-shell">
        <section class="eco-section eco-hero">
            <div class="eco-hero-inner reveal-up" style="transition-delay: 0.05s;">
                <div class="eco-badge">
                    <span class="eco-badge-dot"></span>
                    <span class="eco-badge-text"><?php echo esc_html( (string) $ecosystem_settings['hero_badge'] ); ?></span>
                </div>

                <h1 class="eco-title">
                    <?php echo esc_html( (string) $ecosystem_settings['hero_title_prefix'] ); ?>
                    <?php if ( '' !== trim( (string) $ecosystem_settings['hero_title_accent'] ) ) : ?>
                        <span><em><?php echo esc_html( (string) $ecosystem_settings['hero_title_accent'] ); ?></em></span>
                    <?php endif; ?>
                </h1>
                <p class="eco-subtitle"><?php echo esc_html( (string) $ecosystem_settings['hero_subtitle'] ); ?></p>

                <div class="eco-lead-card">
                    <p><?php echo esc_html( (string) $ecosystem_settings['hero_lead'] ); ?></p>
                </div>

                <a href="#architecture" class="eco-btn eco-btn-primary">
                    <?php echo esc_html( (string) $ecosystem_settings['hero_cta_label'] ); ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
        </section>

        <section class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html( (string) $ecosystem_settings['problem_title'] ); ?></h2>
            <div class="eco-grid-2">
                <?php foreach ( $problem_columns as $column_index => $column_items ) : ?>
                    <div class="eco-card eco-panel reveal-up" style="transition-delay: <?php echo esc_attr( number_format( (float) ( 0.12 + ( $column_index * 0.08 ) ), 2 ) ); ?>s;">
                        <?php foreach ( $column_items as $item ) : ?>
                            <div class="eco-point">
                                <span class="eco-point-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg>
                                </span>
                                <p><strong><?php echo esc_html( (string) $item['title'] ); ?></strong> <?php echo esc_html( (string) $item['description'] ); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="architecture" class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html( (string) $ecosystem_settings['architecture_title'] ); ?></h2>

            <div class="eco-visual-card reveal-scale" style="transition-delay: 0.1s;">
                <div class="eco-grid-overlay"></div>
                <div class="eco-control-plane" data-control-plane-animation="true" aria-hidden="true">
                    <div class="eco-cp-bg eco-cp-bg-a"></div>
                    <div class="eco-cp-bg eco-cp-bg-b"></div>

                    <svg class="eco-cp-svg" viewBox="0 0 800 800" preserveAspectRatio="xMidYMid meet" role="presentation">
                        <g class="eco-cp-hive-lines"></g>
                        <circle class="eco-cp-core-glow" cx="400" cy="400" r="128"></circle>

                        <g class="eco-cp-logo-wrap" transform="translate(400 400)">
                            <g class="eco-cp-logo-scale">
                                <image class="eco-cp-logo" href="<?php echo esc_url( $logo_uri ); ?>" x="-64" y="-64" width="128" height="128" preserveAspectRatio="xMidYMid meet"></image>
                            </g>
                        </g>

                        <g class="eco-cp-node-layer">
                            <?php foreach ( $control_plane_labels as $index => $label ) : ?>
                                <g class="eco-cp-node" data-node-index="<?php echo esc_attr( (string) $index ); ?>">
                                    <g class="eco-cp-node-scale">
                                        <circle class="eco-cp-node-glow" cx="0" cy="0" r="51"></circle>
                                        <polygon class="eco-cp-node-hex" points=""></polygon>
                                        <text class="eco-cp-node-label" x="0" y="84"><?php echo esc_html( strtoupper( $label ) ); ?></text>
                                    </g>
                                </g>
                            <?php endforeach; ?>
                        </g>
                    </svg>

                    <div class="eco-cp-status">
                        <p class="eco-cp-status-line eco-cp-status-line-1"><?php echo esc_html( (string) $ecosystem_settings['status_line_primary'] ); ?></p>
                        <p class="eco-cp-status-line eco-cp-status-line-2"><?php echo esc_html( (string) $ecosystem_settings['status_line_secondary'] ); ?></p>
                        <p class="eco-cp-status-line eco-cp-status-line-3"><?php echo esc_html( (string) $ecosystem_settings['status_line_tertiary'] ); ?></p>
                    </div>
                </div>
            </div>

            <div class="eco-step-grid">
                <?php foreach ( $architecture_steps as $index => $step ) : ?>
                    <article class="eco-step-card reveal-up" style="transition-delay: <?php echo esc_attr( number_format( (float) ( 0.06 + ( $index * 0.04 ) ), 2 ) ); ?>s;">
                        <span class="eco-step-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
                        <h3><?php echo esc_html( (string) $step['title'] ); ?></h3>
                        <p><?php echo esc_html( (string) $step['description'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html( (string) $ecosystem_settings['components_title'] ); ?></h2>
            <div class="eco-component-grid">
                <?php foreach ( $components as $index => $component ) : ?>
                    <article class="eco-component-card reveal-up" style="transition-delay: <?php echo esc_attr( number_format( (float) ( 0.08 + ( $index * 0.04 ) ), 2 ) ); ?>s;">
                        <div class="eco-icon-wrap"><?php echo wp_kses( isset( $component_icons[ $index ] ) ? $component_icons[ $index ] : '', $component_icon_allowed_tags ); ?></div>
                        <h3><?php echo esc_html( (string) $component['title'] ); ?></h3>
                        <p><?php echo esc_html( (string) $component['description'] ); ?></p>
                        <?php if ( '' !== trim( (string) $component['link_url'] ) ) : ?>
                            <a class="eco-link" href="<?php echo esc_url( (string) $component['link_url'] ); ?>">
                                <?php echo esc_html( (string) $ecosystem_settings['component_link_label'] ); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="eco-section">
            <div class="eco-unity reveal-up" style="transition-delay: 0.08s;">
                <h2 class="eco-h2"><?php echo esc_html( (string) $ecosystem_settings['unity_title'] ); ?></h2>
                <div class="eco-unity-grid">
                    <div>
                        <p><?php echo esc_html( (string) $ecosystem_settings['unity_intro'] ); ?></p>
                        <ul class="eco-check-list">
                            <?php foreach ( $unity_bullets as $item ) : ?>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"></path><circle cx="12" cy="12" r="9"></circle></svg><span><?php echo esc_html( (string) $item['description'] ); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="eco-meter-panel">
                        <div class="eco-meter-glow"></div>
                        <div class="eco-meter-stack">
                            <div class="eco-meter-track"><span class="eco-meter-fill" style="--target: 75%; --delay: 0.1s;"></span></div>
                            <div class="eco-meter-track"><span class="eco-meter-fill" style="--target: 50%; --delay: 0.25s;"></span></div>
                            <div class="eco-meter-track"><span class="eco-meter-fill" style="--target: 84%; --delay: 0.4s;"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="eco-section">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html( (string) $ecosystem_settings['comparison_title'] ); ?></h2>
            <div class="eco-table-wrap reveal-up" style="transition-delay: 0.12s;">
                <table class="eco-table">
                    <thead>
                        <tr>
                            <th><?php echo esc_html( (string) $ecosystem_settings['comparison_header_capability'] ); ?></th>
                            <th><?php echo esc_html( (string) $ecosystem_settings['comparison_header_primary'] ); ?></th>
                            <th><?php echo esc_html( (string) $ecosystem_settings['comparison_header_coinbase'] ); ?></th>
                            <th><?php echo esc_html( (string) $ecosystem_settings['comparison_header_fireblocks'] ); ?></th>
                            <th><?php echo esc_html( (string) $ecosystem_settings['comparison_header_safe'] ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $comparison_rows as $row ) : ?>
                            <tr>
                                <td><?php echo esc_html( (string) $row['capability'] ); ?></td>
                                <td><?php echo esc_html( (string) $row['primary'] ); ?></td>
                                <td><?php echo esc_html( (string) $row['coinbase'] ); ?></td>
                                <td><?php echo esc_html( (string) $row['fireblocks'] ); ?></td>
                                <td><?php echo esc_html( (string) $row['safe'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="eco-table-note reveal-up" style="transition-delay: 0.16s;"><em><?php echo esc_html( (string) $ecosystem_settings['comparison_note'] ); ?></em></p>
        </section>

        <section class="eco-section eco-faq-wrap">
            <h2 class="eco-h2 center reveal-up" style="transition-delay: 0.06s;"><?php echo esc_html( (string) $ecosystem_settings['faq_title'] ); ?></h2>
            <?php foreach ( $faqs as $index => $faq ) : ?>
                <article class="eco-faq-card reveal-up" style="transition-delay: <?php echo esc_attr( number_format( (float) ( 0.1 + ( $index * 0.04 ) ), 2 ) ); ?>s;">
                    <h3><?php echo esc_html( (string) $faq['question'] ); ?></h3>
                    <p><?php echo esc_html( (string) $faq['answer'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </div>
</main>

<?php get_footer(); ?>
