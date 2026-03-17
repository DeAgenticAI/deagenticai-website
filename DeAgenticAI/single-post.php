<?php
/**
 * Single post template.
 *
 * @package Aura
 */

get_header();
?>

<main id="primary" class="site-main blog-post-page">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();
        $author_id = (int) get_the_author_meta('ID');
        $author_name = get_the_author();
        $author_role = aura_get_post_author_role($post_id);
        $post_category = get_the_category($post_id);
        $category_name = !empty($post_category) ? $post_category[0]->name : __('General', 'aura');
        $post_date_readable = get_the_date('M d, Y');
        $post_date_iso = get_the_date('c');
        $reading_time = aura_get_post_reading_time($post_id);
        $post_excerpt = has_excerpt($post_id) ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags((string) get_the_content()), 28, '...');
        $featured_image_url = get_the_post_thumbnail_url($post_id, 'large');
        $raw_content = (string) get_the_content();
        $content_html = apply_filters('the_content', $raw_content);
        $post_tags = get_the_tags($post_id);
        $common_questions = aura_get_post_common_questions($post_id);
        $blog_page_id = (int) get_option('page_for_posts');
        $blog_index_url = $blog_page_id > 0 ? get_permalink($blog_page_id) : home_url('/blog');
        $site_url = home_url('/');
        $organization_id = trailingslashit($site_url) . '#organization';
        $site_logo_url = function_exists('aura_get_site_logo_url') ? aura_get_site_logo_url() : '';

        $toc_items = array();
        if (preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/i', $content_html, $matches)) {
            foreach ($matches[1] as $heading_text) {
                $clean_heading = trim(wp_strip_all_tags((string) $heading_text));
                if ('' !== $clean_heading) {
                    $toc_items[] = $clean_heading;
                }

                if (count($toc_items) >= 6) {
                    break;
                }
            }
        }

        if (empty($toc_items)) {
            $toc_items = array(
                __('Introduction', 'aura'),
                __('Key Features', 'aura'),
                __('Case Studies', 'aura'),
                __('Future Outlook', 'aura'),
            );
        }

        $json_ld = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'datePublished' => $post_date_iso,
            'dateModified' => get_the_modified_date('c'),
            'description' => $post_excerpt,
            'mainEntityOfPage' => get_permalink($post_id),
            'articleBody' => wp_strip_all_tags($raw_content),
            'author' => array(
                '@type' => 'Person',
                'name' => $author_name,
                'jobTitle' => $author_role,
            ),
            'publisher' => array(
                '@type' => 'Organization',
                '@id' => $organization_id,
                'name' => get_bloginfo('name'),
                'url' => $site_url,
            ),
        );

        if ($featured_image_url) {
            $json_ld['image'] = array($featured_image_url);
        }

        if ($site_logo_url) {
            $json_ld['publisher']['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $site_logo_url,
            );
        }

        $breadcrumb_json_ld = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => __('Home', 'aura'),
                    'item' => home_url('/'),
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => __('Journal', 'aura'),
                    'item' => $blog_index_url,
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => get_the_title(),
                    'item' => get_permalink($post_id),
                ),
            ),
        );

        $faq_json_ld = null;
        if (!empty($common_questions)) {
            $faq_entities = array();
            foreach ($common_questions as $faq) {
                $faq_entities[] = array(
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ),
                );
            }

            if (!empty($faq_entities)) {
                $faq_json_ld = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faq_entities,
                );
            }
        }
        ?>

        <script type="application/ld+json">
        <?php echo wp_json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
        </script>

        <script type="application/ld+json">
        <?php echo wp_json_encode($breadcrumb_json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
        </script>

        <?php if (!empty($faq_json_ld)) : ?>
            <script type="application/ld+json">
            <?php echo wp_json_encode($faq_json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
            </script>
        <?php endif; ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post-article'); ?>>
            <div class="container blog-post-shell">
                <nav class="blog-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'aura'); ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aura'); ?></a>
                    <span>›</span>
                    <a href="<?php echo esc_url($blog_index_url); ?>"><?php esc_html_e('Journal', 'aura'); ?></a>
                    <span>›</span>
                    <span><?php the_title(); ?></span>
                </nav>

                <header class="blog-post-header reveal-up" style="transition-delay: 0.06s;">
                    <div class="blog-post-top-meta">
                        <span class="blog-post-category"><?php echo esc_html($category_name); ?></span>
                        <span class="blog-post-meta-dot">•</span>
                        <span class="blog-post-reading">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v5l3 3"></path>
                            </svg>
                            <?php echo esc_html($reading_time); ?>
                        </span>
                    </div>

                    <h1><?php the_title(); ?></h1>

                    <div class="blog-post-author-row">
                        <div class="blog-post-author">
                            <span class="blog-post-author-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 21a6 6 0 0 0-12 0"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <div>
                                <p><?php echo esc_html($author_name); ?></p>
                                <span><?php echo esc_html($author_role); ?></span>
                            </div>
                        </div>

                        <div class="blog-post-date-share">
                            <span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                    <path d="M16 3v4M8 3v4M3 11h18"></path>
                                </svg>
                                <?php echo esc_html($post_date_readable); ?>
                            </span>
                            <button type="button" class="blog-post-share-btn" aria-label="<?php esc_attr_e('Share this article', 'aura'); ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <path d="M8.6 13.5 15.4 17.5M15.4 6.5 8.6 10.5"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <?php if ($featured_image_url) : ?>
                    <figure class="blog-post-image reveal-scale" style="transition-delay: 0.1s;">
                        <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php the_title_attribute(); ?>" fetchpriority="high" decoding="async">
                    </figure>
                <?php endif; ?>

                <div class="blog-post-layout">
                    <aside class="blog-post-toc reveal-up" style="transition-delay: 0.12s;">
                        <h4><?php esc_html_e('Table of Contents', 'aura'); ?></h4>
                        <ul>
                            <?php foreach ($toc_items as $index => $toc_item) : ?>
                                <li class="<?php echo 0 === $index ? 'active' : ''; ?>">
                                    <?php echo esc_html($toc_item); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </aside>

                    <div class="blog-post-main reveal-up" style="transition-delay: 0.15s;">
                        <div class="blog-prose">
                            <?php echo wp_kses_post($content_html); ?>
                        </div>

                        <?php if ($post_tags) : ?>
                            <section class="blog-post-tags">
                                <?php foreach ($post_tags as $tag) : ?>
                                    <span class="blog-tag-chip">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 13V7a2 2 0 0 0-2-2h-6L4 13l8 8 8-8z"></path>
                                            <circle cx="15" cy="9" r="1"></circle>
                                        </svg>
                                        <?php echo esc_html($tag->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </section>
                        <?php endif; ?>

                        <?php if (!empty($common_questions)) : ?>
                            <section class="blog-post-faq">
                                <h3>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <?php esc_html_e('Common Questions', 'aura'); ?>
                                </h3>

                                <div class="blog-post-faq-list">
                                    <?php foreach ($common_questions as $faq) : ?>
                                        <article class="blog-post-faq-item">
                                            <h4><?php echo esc_html($faq['question']); ?></h4>
                                            <p><?php echo esc_html($faq['answer']); ?></p>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <section class="blog-post-author-box">
                            <span class="blog-post-author-box-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 21a6 6 0 0 0-12 0"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <div>
                                <h4><?php echo esc_html($author_name); ?></h4>
                                <p class="blog-post-author-box-role"><?php echo esc_html($author_role); ?> <?php esc_html_e('at DeAgenticAI', 'aura'); ?></p>
                                <p class="blog-post-author-box-bio">
                                    <?php
                                    $author_description = get_the_author_meta('description', $author_id);
                                    if ('' !== trim((string) $author_description)) {
                                        echo esc_html($author_description);
                                    } else {
                                        esc_html_e('Expert in digital asset security and autonomous protocol design.', 'aura');
                                    }
                                    ?>
                                </p>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </article>

        <?php
        $related_posts = aura_get_related_posts($post_id, 3);
        ?>

        <?php if (!empty($related_posts)) : ?>
            <section class="blog-related">
                <div class="container blog-post-shell">
                    <h3><?php esc_html_e('Related Articles', 'aura'); ?></h3>
                    <div class="blog-related-grid">
                        <?php foreach ($related_posts as $related_post) : ?>
                            <?php
                            setup_postdata($related_post);
                            $related_id = get_the_ID();
                            $related_image = get_the_post_thumbnail_url($related_id, 'medium_large');
                            $related_categories = get_the_category($related_id);
                            $related_category = !empty($related_categories) ? $related_categories[0]->name : __('General', 'aura');
                            ?>
                            <a class="blog-related-card" href="<?php the_permalink(); ?>">
                                <div class="blog-related-media">
                                    <?php if ($related_image) : ?>
                                        <img src="<?php echo esc_url($related_image); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async">
                                    <?php else : ?>
                                        <div class="blog-image-fallback"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="blog-related-body">
                                    <p><?php echo esc_html($related_category); ?></p>
                                    <h4><?php the_title(); ?></h4>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
