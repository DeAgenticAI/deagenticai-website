<?php
/**
 * Blog archive layout used by blog page template and posts index.
 *
 * @package Aura
 */

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$featured_post = null;
$featured_post_id = 0;

if (1 === $paged) {
    $featured_query = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_key' => '_aura_featured_post',
        'meta_value' => '1',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    if ($featured_query->have_posts()) {
        $featured_post = $featured_query->posts[0];
        $featured_post_id = (int) $featured_post->ID;
    }

    wp_reset_postdata();

    if (!$featured_post) {
        $fallback_featured_query = new WP_Query(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        if ($fallback_featured_query->have_posts()) {
            $featured_post = $fallback_featured_query->posts[0];
            $featured_post_id = (int) $featured_post->ID;
        }

        wp_reset_postdata();
    }
}

$regular_query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC',
);

if ($featured_post_id) {
    $regular_query_args['post__not_in'] = array($featured_post_id);
}

$regular_query = new WP_Query($regular_query_args);
?>

<main id="primary" class="site-main blog-page">
    <div class="container blog-shell">
        <section class="blog-hero reveal-up" style="transition-delay: 0.05s;">
            <h1>DeAgenticAI <span>Journal</span></h1>
            <p>Insights, updates, and deep dives into the future of digital wealth management and secure protocol delegation.</p>
        </section>

        <?php if ($featured_post) : ?>
            <?php
            $featured_title = get_the_title($featured_post_id);
            $featured_link = get_permalink($featured_post_id);
            $featured_excerpt = get_the_excerpt($featured_post_id);
            $featured_author = get_the_author_meta('display_name', (int) $featured_post->post_author);
            $featured_date = get_the_date('M d, Y', $featured_post_id);
            $featured_category = get_the_category($featured_post_id);
            $featured_category_name = !empty($featured_category) ? $featured_category[0]->name : __('General', 'aura');
            $featured_image = get_the_post_thumbnail_url($featured_post_id, 'large');
            ?>
            <section class="blog-featured reveal-up" style="transition-delay: 0.12s;">
                <article class="blog-featured-card">
                    <a href="<?php echo esc_url($featured_link); ?>" class="blog-featured-media">
                        <?php if ($featured_image) : ?>
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($featured_title); ?>">
                        <?php else : ?>
                            <div class="blog-image-fallback"></div>
                        <?php endif; ?>
                    </a>

                    <div class="blog-featured-content">
                        <div class="blog-featured-meta">
                            <span class="blog-featured-pill"><?php esc_html_e('Featured', 'aura'); ?></span>
                            <span class="blog-featured-category"><?php echo esc_html($featured_category_name); ?></span>
                        </div>

                        <h2>
                            <a href="<?php echo esc_url($featured_link); ?>"><?php echo esc_html($featured_title); ?></a>
                        </h2>

                        <p><?php echo esc_html($featured_excerpt); ?></p>

                        <div class="blog-featured-footer">
                            <div class="blog-featured-author">
                                <span class="blog-author-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 21a6 6 0 0 0-12 0"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <div>
                                    <p><?php echo esc_html($featured_author); ?></p>
                                    <span><?php echo esc_html($featured_date); ?></span>
                                </div>
                            </div>

                            <a class="blog-read-link" href="<?php echo esc_url($featured_link); ?>">
                                <?php esc_html_e('Read Article', 'aura'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            </section>
        <?php endif; ?>

        <?php if ($regular_query->have_posts()) : ?>
            <section class="blog-grid">
                <?php
                $index = 0;
                while ($regular_query->have_posts()) :
                    $regular_query->the_post();
                    $index++;
                    $post_id = get_the_ID();
                    $categories = get_the_category($post_id);
                    $category_name = !empty($categories) ? $categories[0]->name : __('General', 'aura');
                    $post_date = get_the_date('M d, Y');
                    $post_excerpt = get_the_excerpt();
                    $post_author = get_the_author();
                    $post_image = get_the_post_thumbnail_url($post_id, 'medium_large');
                    ?>
                    <article class="blog-card reveal-up" style="transition-delay: <?php echo esc_attr(number_format((float) (0.16 + ($index * 0.03)), 2)); ?>s;">
                        <a href="<?php the_permalink(); ?>" class="blog-card-media">
                            <?php if ($post_image) : ?>
                                <img src="<?php echo esc_url($post_image); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else : ?>
                                <div class="blog-image-fallback"></div>
                            <?php endif; ?>
                        </a>

                        <div class="blog-card-body">
                            <div class="blog-card-meta">
                                <span class="blog-card-category"><?php echo esc_html($category_name); ?></span>
                                <span class="blog-card-dot">•</span>
                                <span class="blog-card-date"><?php echo esc_html($post_date); ?></span>
                            </div>

                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html($post_excerpt); ?></p>

                            <div class="blog-card-footer">
                                <span class="blog-card-author"><?php echo esc_html($post_author); ?></span>
                                <a class="blog-card-read" href="<?php the_permalink(); ?>">
                                    <?php esc_html_e('Read', 'aura'); ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>

            <?php if ($regular_query->max_num_pages > 1) : ?>
                <nav class="blog-pagination" aria-label="<?php esc_attr_e('Blog pagination', 'aura'); ?>">
                    <?php
                    echo wp_kses_post(
                        paginate_links(array(
                            'total' => $regular_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('Previous', 'aura'),
                            'next_text' => __('Next', 'aura'),
                            'type' => 'list',
                        ))
                    );
                    ?>
                </nav>
            <?php endif; ?>
        <?php else : ?>
            <section class="blog-empty">
                <p><?php esc_html_e('No journal posts published yet.', 'aura'); ?></p>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php wp_reset_postdata(); ?>
