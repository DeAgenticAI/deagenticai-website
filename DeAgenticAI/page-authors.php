<?php
/**
 * Template for the /authors/ index page.
 *
 * Automatically lists published child pages (author profiles).
 * WordPress applies this template via slug matching (page-authors.php).
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main marketing-page authors-index-page">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php
        $page_id       = get_the_ID();
        $author_pages  = get_pages(
            array(
                'parent'      => $page_id,
                'sort_column' => 'menu_order',
                'post_status' => 'publish',
            )
        );
        $lead          = function_exists( 'aura_get_marketing_page_excerpt' )
            ? aura_get_marketing_page_excerpt( $page_id, 36 )
            : wp_strip_all_tags( (string) get_the_excerpt( $page_id ) );
        ?>

        <div class="container marketing-shell">
            <section class="marketing-hero marketing-hero-centered fade-in-up">
                <div class="marketing-badge">
                    <span class="marketing-badge-dot"></span>
                    <span class="marketing-badge-text"><?php esc_html_e( 'Team', 'aura' ); ?></span>
                </div>

                <h1 class="marketing-title"><?php the_title(); ?></h1>

                <?php if ( '' !== $lead ) : ?>
                    <p class="marketing-lead"><?php echo esc_html( $lead ); ?></p>
                <?php endif; ?>

                <div class="marketing-prose marketing-prose-centered">
                    <?php the_content(); ?>
                </div>
            </section>

            <?php if ( ! empty( $author_pages ) ) : ?>
                <section class="marketing-grid-section fade-in-up-delay-1">
                    <div class="marketing-section-heading">
                        <h2 id="authors-profiles"><?php esc_html_e( 'Author Profiles', 'aura' ); ?></h2>
                        <p><?php esc_html_e( 'Canonical profiles for DeAgenticAI contributors and research leads.', 'aura' ); ?></p>
                        <div class="marketing-section-meta">
                            <?php
                            printf(
                                esc_html( _n( '%s profile', '%s profiles', count( $author_pages ), 'aura' ) ),
                                esc_html( number_format_i18n( count( $author_pages ) ) )
                            );
                            ?>
                        </div>
                    </div>

                    <div class="marketing-card-grid authors-card-grid">
                        <?php foreach ( $author_pages as $author_page ) : ?>
                            <?php
                            $author_id    = (int) $author_page->ID;
                            $author_role  = trim( (string) get_post_meta( $author_id, '_dea_author_job_title', true ) );
                            $author_lead  = function_exists( 'aura_get_marketing_page_excerpt' )
                                ? aura_get_marketing_page_excerpt( $author_id, 22 )
                                : wp_trim_words( wp_strip_all_tags( (string) $author_page->post_content ), 22, '...' );
                            ?>
                            <article class="marketing-card authors-card">
                                <div class="marketing-card-topline">
                                    <?php echo esc_html( '' !== $author_role ? $author_role : __( 'Author', 'aura' ) ); ?>
                                </div>
                                <h2>
                                    <a href="<?php echo esc_url( get_permalink( $author_id ) ); ?>">
                                        <?php echo esc_html( get_the_title( $author_id ) ); ?>
                                    </a>
                                </h2>
                                <?php if ( '' !== $author_lead ) : ?>
                                    <p><?php echo esc_html( $author_lead ); ?></p>
                                <?php endif; ?>
                                <a class="marketing-card-link" href="<?php echo esc_url( get_permalink( $author_id ) ); ?>">
                                    <?php esc_html_e( 'View Profile', 'aura' ); ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php else : ?>
                <section class="marketing-section-card authors-empty-state fade-in-up-delay-1">
                    <div class="marketing-section-heading">
                        <h2><?php esc_html_e( 'No Author Profiles Published Yet', 'aura' ); ?></h2>
                        <p><?php esc_html_e( 'Publish child pages under /authors/ to populate this directory.', 'aura' ); ?></p>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
