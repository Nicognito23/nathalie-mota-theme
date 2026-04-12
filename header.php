<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Logo.png" alt="Nathalie Mota">
        </a>
        <nav class="header-nav">
            <?php wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'     => 'nav-list',
                'container'      => false,
            )); ?>
        </nav>
        <button class="burger" aria-label="Ouvrir le menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>