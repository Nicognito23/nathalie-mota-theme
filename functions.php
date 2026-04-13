<?php

function nathalie_mota_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    register_nav_menus(array(
        'primary' => 'Menu Principal',
        'footer'  => 'Menu Footer',
    ));
}
add_action('after_setup_theme', 'nathalie_mota_setup');

function nathalie_mota_enqueue_assets() {
    // Charge le style.css principal
    wp_enqueue_style('nathalie-mota-style', get_stylesheet_uri(), array(), '1.0');
    // Charge les polices
    wp_enqueue_style('nathalie-mota-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), '1.0');
    // Charge le JavaScript
    wp_enqueue_script('nathalie-mota-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_assets');

