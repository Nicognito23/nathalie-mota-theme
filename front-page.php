<?php get_header(); ?>

<main class="home-page">

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/nathalie-11.jpeg');"></div>
        <h1 class="hero-titre">Photographe Event</h1>
    </section>

    <!-- FILTRES -->
    <section class="filtres-section">
        <div class="filtres-inner">

            <div class="filtres-gauche">
                <!-- Filtre Catégories -->
                <select id="filtre-categorie" class="filtre-select">
                    <option value="">Catégories</option>
                    <?php
                    $categories = get_terms(['taxonomy' => 'categorie', 'hide_empty' => false]);
                    foreach ($categories as $cat) :
                    ?>
                        <option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtre Formats -->
                <select id="filtre-format" class="filtre-select">
                    <option value="">Formats</option>
                    <?php
                    $formats = get_terms(['taxonomy' => 'format', 'hide_empty' => false]);
                    foreach ($formats as $fmt) :
                    ?>
                        <option value="<?php echo $fmt->term_id; ?>"><?php echo $fmt->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tri par date -->
            <select id="filtre-tri" class="filtre-select">
                <option value="DESC">Trier par</option>
                <option value="DESC">Plus récentes</option>
                <option value="ASC">Plus anciennes</option>
            </select>

        </div>
    </section>

    <!-- GRILLE PHOTOS -->
    <section class="photos-section">
        <div class="photos-grid" id="photos-grid">
            <?php
            $photos = new WP_Query([
                'post_type'      => 'photo',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ($photos->have_posts()) :
                while ($photos->have_posts()) : $photos->the_post();
                    get_template_part('template-parts/photo-block');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <!-- BOUTON CHARGER PLUS -->
        <div class="charger-plus-wrap">
            <button id="charger-plus" class="btn-charger-plus" data-page="2">
                Charger plus
            </button>
        </div>
    </section>

</main>

<?php get_footer(); ?>