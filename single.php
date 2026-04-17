<?php get_header(); ?>

<main class="single-photo-page">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <section class="photo-content">

        <!-- BLOC GAUCHE : infos -->
        <div class="photo-infos">
            <h1 class="photo-titre"><?php the_title(); ?></h1>

            <p class="photo-meta">
                RÉFÉRENCE : <?php echo get_field('reference'); ?>
            </p>
            <p class="photo-meta">
                CATÉGORIE :
                <?php
                $categories = get_the_terms(get_the_ID(), 'categorie');
                if ($categories) {
                    echo esc_html($categories[0]->name);
                }
                ?>
            </p>
            <p class="photo-meta">
                FORMAT :
                <?php
                $formats = get_the_terms(get_the_ID(), 'format');
                if ($formats) {
                    echo esc_html($formats[0]->name);
                }
                ?>
            </p>
            <p class="photo-meta">
                TYPE : <?php echo get_field('type'); ?>
            </p>
            <p class="photo-meta">
                ANNÉE : <?php echo get_the_date('Y'); ?>
            </p>
        </div>

        <!-- BLOC DROITE : image -->
        <div class="photo-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', ['class' => 'photo-img']); ?>
            <?php endif; ?>
        </div>

    </section>

    <!-- BARRE DU BAS : contact + navigation -->
    <section class="photo-bar">

        <div class="photo-contact">
            <span>Cette photo vous intéresse ?</span>
            <a href="#"
               class="btn-contact-photo open-modal"
               data-reference="<?php echo get_field('reference'); ?>">
                Contact
            </a>
        </div>

<div class="photo-navigation">
    <?php
    $prev = get_adjacent_post(false, '', true);
    $next = get_adjacent_post(false, '', false);
    ?>

    <!-- MINIATURE au-dessus des flèches -->
    <div class="nav-miniature">
        <?php if ($next) : ?>
            <?php echo get_the_post_thumbnail($next->ID, 'thumbnail', ['class' => 'nav-thumb-img']); ?>
        <?php endif; ?>
    </div>

    <!-- FLÈCHES en dessous -->
    <div class="nav-arrows">
        <?php if ($prev) : ?>
            <a href="<?php echo get_permalink($prev->ID); ?>" class="nav-photo">&larr;</a>
        <?php endif; ?>
        <?php if ($next) : ?>
            <a href="<?php echo get_permalink($next->ID); ?>" class="nav-photo">&rarr;</a>
        <?php endif; ?>
    </div>
</div>

    </section>

    <!-- ZONE PHOTOS APPARENTÉES -->
    <section class="photos-apparentees">
        <h3 class="apparentees-titre">Vous aimerez aussi</h3>

        <div class="apparentees-grid">
            <?php
            $categories = get_the_terms(get_the_ID(), 'categorie');
            $cat_ids = [];
            if ($categories) {
                foreach ($categories as $cat) {
                    $cat_ids[] = $cat->term_id;
                }
            }

            $related = new WP_Query([
                'post_type'      => 'photo',
                'posts_per_page' => 2,
                'post__not_in'   => [get_the_ID()],
                'tax_query'      => [[
                    'taxonomy' => 'categorie',
                    'field'    => 'term_id',
                    'terms'    => $cat_ids,
                ]],
            ]);

            if ($related->have_posts()) :
                while ($related->have_posts()) : $related->the_post();
                    get_template_part('template-parts/photo-block');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>

    <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>