<?php

// CONFIGURATION DU THÈME
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

// CHARGEMENT DES ASSETS
function nathalie_mota_enqueue_assets() {
    wp_enqueue_style('nathalie-mota-style', get_stylesheet_uri(), array(), '1.0');
    wp_enqueue_style('nathalie-mota-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), '1.0');
    wp_enqueue_script('nathalie-mota-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), '1.0', true);

    // Envoie l'URL de admin-ajax.php au JavaScript
    // Sans ça, le JS ne sait pas où envoyer ses requêtes
    wp_localize_script('nathalie-mota-scripts', 'motaAjax', array(
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mota_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_assets');

// HANDLER AJAX — récupère les photos selon les filtres
// Cette fonction est appelée par le JavaScript via admin-ajax.php
function nathalie_mota_get_photos() {

    // Récupère les paramètres envoyés par le JavaScript
    $page      = isset($_POST['page'])      ? intval($_POST['page'])                    : 1;
    $categorie = isset($_POST['categorie']) ? intval($_POST['categorie'])               : 0;
    $format    = isset($_POST['format'])    ? intval($_POST['format'])                  : 0;
    $order     = isset($_POST['order'])     ? sanitize_text_field($_POST['order'])      : 'DESC';

    // Prépare la requête WordPress
    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => $order,
    );

    // Ajoute le filtre catégorie si sélectionné
    if ($categorie) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field'    => 'term_id',
            'terms'    => $categorie,
        );
    }

    // Ajoute le filtre format si sélectionné
    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field'    => 'term_id',
            'terms'    => $format,
        );
    }

    $query = new WP_Query($args);

    // Prépare la réponse JSON
    $response = array(
        'photos'      => array(),
        'total_pages' => $query->max_num_pages,
    );

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $response['photos'][] = array(
                'lien'  => get_permalink(),
                'titre' => get_the_title(),
                'img'   => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            );
        }
        wp_reset_postdata();
    }

    // Renvoie le JSON au JavaScript
    wp_send_json($response);
    wp_die(); // termine proprement la requête Ajax
}
// Enregistre la fonction pour les visiteurs connectés ET non connectés
add_action('wp_ajax_get_photos',        'nathalie_mota_get_photos');
add_action('wp_ajax_nopriv_get_photos', 'nathalie_mota_get_photos');