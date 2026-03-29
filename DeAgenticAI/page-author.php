<?php
/**
 * Template Name: Author Profile
 *
 * Full-width author profile page. No sidebar, no date stamp.
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page author-profile-page">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php
        $page_id = get_the_ID();

        $breadcrumbs = array(
            array(
                'label' => __( 'Home', 'aura' ),
                'url'   => home_url( '/' ),
            ),
        );

        $ancestors = array_reverse( get_post_ancestors( $page_id ) );
        foreach ( $ancestors as $ancestor_id ) {
            $breadcrumbs[] = array(
                'label' => get_the_title( $ancestor_id ),
                'url'   => get_permalink( $ancestor_id ),
            );
        }

        $breadcrumbs[] = array(
            'label' => get_the_title( $page_id ),
            'url'   => get_permalink( $page_id ),
        );

        $profile_meta = function_exists( 'dea_get_author_profile_metadata' )
            ? dea_get_author_profile_metadata( $page_id )
            : array(
                'user_id'    => 0,
                'job_title' => '',
                'short_bio' => '',
                'long_bio' => '',
                'same_as'   => array(),
                'panel_label' => __( 'Authority Profile', 'aura' ),
                'panel_title' => __( 'Reza Jahankohan — Founder, DeAgenticAI', 'aura' ),
                'panel_body'  => __( 'Reza Jahankohan is the Founder of DeAgenticAI and publishes on agentic policy enforcement, governed MPC signing, and AI-agent authority controls in Web3 and enterprise financial systems. This is his canonical author profile with verified references.', 'aura' ),
            );
        $author_user_id = isset( $profile_meta['user_id'] ) ? (int) $profile_meta['user_id'] : 0;
        $job_title      = isset( $profile_meta['job_title'] ) ? trim( (string) $profile_meta['job_title'] ) : '';
        $short_bio      = isset( $profile_meta['short_bio'] ) ? trim( (string) $profile_meta['short_bio'] ) : '';
        $long_bio       = isset( $profile_meta['long_bio'] ) ? trim( (string) $profile_meta['long_bio'] ) : '';
        $same_as        = ( isset( $profile_meta['same_as'] ) && is_array( $profile_meta['same_as'] ) ) ? $profile_meta['same_as'] : array();
        $panel_label    = isset( $profile_meta['panel_label'] ) ? trim( (string) $profile_meta['panel_label'] ) : '';
        $panel_title    = isset( $profile_meta['panel_title'] ) ? trim( (string) $profile_meta['panel_title'] ) : '';
        $panel_body     = isset( $profile_meta['panel_body'] ) ? trim( (string) $profile_meta['panel_body'] ) : '';
        $author_user    = $author_user_id > 0 ? get_userdata( $author_user_id ) : false;

        if ( '' === $panel_label ) {
            $panel_label = __( 'Authority Profile', 'aura' );
        }
        if ( '' === $panel_title ) {
            $panel_title = __( 'Reza Jahankohan — Founder, DeAgenticAI', 'aura' );
        }
        if ( '' === $panel_body ) {
            $panel_body = __( 'Reza Jahankohan is the Founder of DeAgenticAI and publishes on agentic policy enforcement, governed MPC signing, and AI-agent authority controls in Web3 and enterprise financial systems. This is his canonical author profile with verified references.', 'aura' );
        }

        $author_content = array();
        if ( $author_user instanceof WP_User ) {
            $author_post_types = array( 'post' );
            if ( function_exists( 'aura_get_learn_post_types' ) ) {
                $learn_post_types = aura_get_learn_post_types();
                if ( is_array( $learn_post_types ) ) {
                    $author_post_types = array_merge( $author_post_types, $learn_post_types );
                }
            }
            $author_post_types = array_values( array_unique( array_filter( array_map( 'sanitize_key', $author_post_types ) ) ) );
            if ( ! empty( $author_post_types ) ) {
                $author_content = get_posts(
                    array(
                        'post_type'           => $author_post_types,
                        'post_status'         => 'publish',
                        'author'              => (int) $author_user->ID,
                        'posts_per_page'      => 8,
                        'orderby'             => 'date',
                        'order'               => 'DESC',
                        'suppress_filters'    => false,
                        'ignore_sticky_posts' => true,
                    )
                );
            }
        }
        ?>

        <div class="container marketing-shell">
            <nav class="marketing-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aura' ); ?>">
                <?php foreach ( $breadcrumbs as $index => $item ) : ?>
                    <?php if ( $index > 0 ) : ?>
                        <span>›</span>
                    <?php endif; ?>
                    <?php if ( $index === count( $breadcrumbs ) - 1 ) : ?>
                        <span><?php echo esc_html( $item['label'] ); ?></span>
                    <?php else : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <section class="marketing-entry-hero author-entry-hero fade-in-up">
                <div class="marketing-entry-copy">
                    <div class="marketing-badge">
                        <span class="marketing-badge-dot"></span>
                        <span class="marketing-badge-text"><?php esc_html_e( 'Team Author', 'aura' ); ?></span>
                    </div>

                    <h1 class="marketing-entry-title"><?php the_title(); ?></h1>

                    <?php if ( '' !== $job_title ) : ?>
                        <p class="author-profile-role"><?php echo esc_html( $job_title ); ?></p>
                    <?php endif; ?>

                    <?php if ( '' !== $short_bio ) : ?>
                        <p class="marketing-lead marketing-lead-left"><?php echo esc_html( $short_bio ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="marketing-entry-visual">
                    <div class="marketing-visual-panel author-visual-panel">
                        <div class="marketing-visual-glow"></div>
                        <div class="marketing-visual-chip"><?php echo esc_html( $panel_label ); ?></div>
                        <strong><?php echo esc_html( $panel_title ); ?></strong>
                        <p><?php echo esc_html( $panel_body ); ?></p>
                    </div>
                </div>
            </section>

            <div class="marketing-content-layout author-content-layout fade-in-up-delay-1">
                <div class="marketing-main-column">
                    <section class="marketing-section-card marketing-prose-card">
                        <div class="marketing-section-heading">
                            <h2><?php esc_html_e( 'Biography', 'aura' ); ?></h2>
                        </div>

                        <div class="marketing-prose author-profile-prose">
                            <?php if ( '' !== $long_bio ) : ?>
                                <?php echo wp_kses_post( wpautop( esc_html( $long_bio ) ) ); ?>
                            <?php else : ?>
                                <?php the_content(); ?>
                            <?php endif; ?>
                        </div>
                    </section>

                    <?php if ( $author_user instanceof WP_User ) : ?>
                        <section class="marketing-section-card author-content-card">
                            <div class="marketing-section-heading">
                                <h2><?php esc_html_e( 'Published Content', 'aura' ); ?></h2>
                                <p>
                                    <?php
                                    printf(
                                        /* translators: %s: author display name. */
                                        esc_html__( 'Recent published posts by %s.', 'aura' ),
                                        esc_html( $author_user->display_name )
                                    );
                                    ?>
                                </p>
                            </div>

                            <?php if ( ! empty( $author_content ) ) : ?>
                                <ul class="author-content-list">
                                    <?php foreach ( $author_content as $content_item ) : ?>
                                        <?php
                                        if ( ! $content_item instanceof WP_Post ) {
                                            continue;
                                        }
                                        $content_type_object = get_post_type_object( $content_item->post_type );
                                        $content_type_label  = $content_type_object && ! empty( $content_type_object->labels->singular_name )
                                            ? (string) $content_type_object->labels->singular_name
                                            : ucfirst( (string) $content_item->post_type );
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_permalink( $content_item->ID ) ); ?>">
                                                <?php echo esc_html( get_the_title( $content_item->ID ) ); ?>
                                            </a>
                                            <span>
                                                <?php
                                                echo esc_html(
                                                    sprintf(
                                                        '%1$s • %2$s',
                                                        $content_type_label,
                                                        get_the_date( get_option( 'date_format' ), $content_item->ID )
                                                    )
                                                );
                                                ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p class="author-content-empty"><?php esc_html_e( 'No published content found for the mapped user yet.', 'aura' ); ?></p>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
                </div>

                <aside class="marketing-sidebar-column">
                    <section class="marketing-side-card author-signals-card">
                        <h2><?php esc_html_e( 'Authority Signals', 'aura' ); ?></h2>
                        <ul class="author-signal-list">
                            <li>
                                <span><?php esc_html_e( 'Role', 'aura' ); ?></span>
                                <strong><?php echo esc_html( '' !== $job_title ? $job_title : __( 'Author', 'aura' ) ); ?></strong>
                            </li>
                            <li>
                                <span><?php esc_html_e( 'Organization', 'aura' ); ?></span>
                                <strong><?php esc_html_e( 'DeAgenticAI', 'aura' ); ?></strong>
                            </li>
                            <li>
                                <span><?php esc_html_e( 'Canonical URL', 'aura' ); ?></span>
                                <a href="<?php echo esc_url( get_permalink( $page_id ) ); ?>">
                                    <?php esc_html_e( 'View profile URL', 'aura' ); ?>
                                </a>
                            </li>
                        </ul>
                    </section>

                    <?php if ( ! empty( $same_as ) ) : ?>
                        <section class="marketing-side-card author-links-card">
                            <h2><?php esc_html_e( 'External Profiles', 'aura' ); ?></h2>
                            <ul class="marketing-link-list">
                                <?php foreach ( $same_as as $profile_url ) : ?>
                                    <?php
                                    $host = wp_parse_url( $profile_url, PHP_URL_HOST );
                                    $host = is_string( $host ) ? strtolower( preg_replace( '/^www\./', '', $host ) ) : '';
                                    $label = $profile_url;
                                    if ( 'x.com' === $host || 'twitter.com' === $host ) {
                                        $label = __( 'X (Twitter)', 'aura' );
                                    } elseif ( false !== strpos( $host, 'linkedin.com' ) ) {
                                        $label = __( 'LinkedIn', 'aura' );
                                    } elseif ( false !== strpos( $host, 'github.com' ) ) {
                                        $label = __( 'GitHub', 'aura' );
                                    }
                                    ?>
                                    <li>
                                        <a href="<?php echo esc_url( $profile_url ); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo esc_html( $label ); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
