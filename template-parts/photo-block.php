<article class="photo-block">
    <div class="photo-block-inner">
        <a href="<?php the_permalink(); ?>" class="photo-block-img-wrap">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', ['class' => 'photo-block-img']); ?>
            <?php endif; ?>

            <!-- Icônes au survol -->
            <div class="photo-block-overlay">
                <a href="<?php the_permalink(); ?>" class="photo-icon" title="Voir les infos">
                    👁
                </a>
                <a href="<?php the_post_thumbnail_url('full'); ?>" class="photo-icon photo-lightbox" title="Plein écran">
                    ⛶
                </a>
            </div>
        </a>
    </div>
</article>