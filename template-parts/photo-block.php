<article class="photo-block">
    <a href="<?php the_permalink(); ?>" class="photo-block-link">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium', ['class' => 'photo-block-img']); ?>
        <?php endif; ?>
    </a>
</article>