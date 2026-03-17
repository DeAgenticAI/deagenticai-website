<?php
/**
 * The header for our theme
 *
 * @package Aura
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Background Effects -->
<div class="aura-background">
    <div class="aura-background-gradient"></div>
</div>
<div class="aura-background-effects grid-bg"></div>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aura'); ?></a>

    <header id="masthead" class="site-header">
        <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'aura'); ?>">
            <div class="nav-wrapper">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                    <?php
                    $aura_logo_url    = get_template_directory_uri() . '/images/logo.png';
                    $aura_logo_id     = get_theme_mod( 'custom_logo' );
                    if ( $aura_logo_id ) {
                        $aura_logo_data = wp_get_attachment_image_src( $aura_logo_id, 'full' );
                        if ( $aura_logo_data ) {
                            $aura_logo_url = $aura_logo_data[0];
                        }
                    }
                    $aura_site_name = function_exists( 'aura_get_content' ) ? aura_get_content( 'site_name', 'DeAgenticAI' ) : 'DeAgenticAI';
                    ?>
                    <img src="<?php echo esc_url( $aura_logo_url ); ?>" alt="<?php echo esc_attr( $aura_site_name ); ?>" class="logo-image" />
                    <span class="logo-text"><?php echo esc_html( $aura_site_name ); ?></span>
                </a>

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'walker'         => new Aura_Walker_Nav_Menu(),
                    'items_wrap'     => '<ul id="%1$s" class="nav-menu">%3$s</ul>',
                ));
                ?>

                <a href="#waitlist" class="nav-cta">
                    <span>Join Waitlist</span>
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M13.25 12.75V18a.75.75 0 0 0 1.28.53l6-6a.75.75 0 0 0 0-1.06l-6-6a.75.75 0 0 0-1.28.53z"></path>
                    </svg>
                </a>
            </div>
        </nav>
    </header><!-- #masthead -->

    <div id="content" class="site-content">
