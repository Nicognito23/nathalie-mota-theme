<article class="photo-block">
    <div class="photo-block-inner">
        <a href="<?php the_permalink(); ?>" class="photo-block-link">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['class' => 'photo-block-img']); ?>
            <?php endif; ?>

            <div class="photo-block-overlay">
                <a href="<?php the_permalink(); ?>" class="photo-icon" title="Voir les infos">👁</a>
                <a href="<?php the_post_thumbnail_url('full'); ?>"
                   class="photo-icon photo-lightbox"
                   data-ref="<?php echo get_field('reference'); ?>"
                   data-cat="<?php
                        $cats = get_the_terms(get_the_ID(), 'categorie');
                        echo $cats ? esc_html($cats[0]->name) : '';
                   ?>"
                   title="Plein écran">⛶</a>
            </div>
        </a>
    </div>
</article>

