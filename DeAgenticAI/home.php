<?php
/**
 * Posts index template.
 *
 * Mirrors the blog archive design when "Posts page" is configured in Reading settings.
 *
 * @package Aura
 */

get_header();
get_template_part('template-parts/blog', 'archive');
get_footer();
