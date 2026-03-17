<?php
/**
 * Learn/compare archive layout.
 *
 * @package Aura
 */

$post_type = get_query_var('post_type');
if (is_array($post_type)) {
    $post_type = reset($post_type);
}
$post_type = sanitize_key((string) $post_type);

$archive_obj = get_post_type_object($post_type);
$archive_title = $archive_obj && !empty($archive_obj->labels->name) ? (string) $archive_obj->labels->name : post_type_archive_title('', false);
$archive_meta = function_exists('aura_get_learn_archive_meta') ? aura_get_learn_archive_meta($post_type) : array();
$archive_description = isset($archive_meta['description']) ? (string) $archive_meta['description'] : '';
$card_label = isset($archive_meta['card_label']) ? (string) $archive_meta['card_label'] : __('Resource', 'aura');
$has_entries = have_posts();
?>

<main id="primary" class="site-main marketing-page learn-archive-page">
    <div class="container marketing-shell">
        <section class="marketing-hero marketing-hero-centered">
            <div class="marketing-badge">
                <span class="marketing-badge-dot"></span>
                <span class="marketing-badge-text"><?php echo esc_html(isset($archive_meta['badge']) ? $archive_meta['badge'] : __('Learn', 'aura')); ?></span>
            </div>

            <h1 class="marketing-title"><?php echo esc_html($archive_title); ?></h1>
            <?php if ('' !== trim($archive_description)) : ?>
                <p class="marketing-lead"><?php echo esc_html($archive_description); ?></p>
            <?php endif; ?>
        </section>

        <?php if ($has_entries) : ?>
            <section class="marketing-grid-section">
                <div class="marketing-card-grid marketing-card-grid-solutions">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php $entry_lead = function_exists('aura_get_learn_entry_excerpt') ? aura_get_learn_entry_excerpt(get_the_ID(), 34) : get_the_excerpt(); ?>
                        <article class="marketing-card marketing-card-solution">
                            <div class="marketing-card-topline"><?php echo esc_html($card_label); ?></div>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php if ('' !== trim((string) $entry_lead)) : ?>
                                <p><?php echo esc_html($entry_lead); ?></p>
                            <?php endif; ?>
                            <a class="marketing-card-link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('Read', 'aura'); ?>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div style="margin-top: 1.75rem;">
                    <?php the_posts_pagination(array(
                        'prev_text' => __('Previous', 'aura'),
                        'next_text' => __('Next', 'aura'),
                        'screen_reader_text' => __('Archive navigation', 'aura'),
                    )); ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>
