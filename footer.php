<footer class="site-footer">
    <div class="footer-inner">
        <?php wp_nav_menu(array(
            'theme_location' => 'footer',
            'menu_class'     => 'footer-list',
            'container'      => false,
        )); ?>
    </div>
</footer>

<!-- LIGHTBOX -->
 <div id="lightbox-overlay" class="lightbox-overlay" aria-hidden="true">
    <button class="lightbox-close" id="lightbox-close" aria-label="Fermer">&#x2715;</button>

    <button class="lightbox-prev" id="lightbox-prev" aria-label="précédente">
        &larr; Précédente
    </button>

    <div class="lightbox-content">
        <img src="" alt="" class="lightbox-img" id="lightbox-img">
        <div class="lightbox-infos">
            <span class="lightbox-ref" id="lightbox-ref">REFERENCE DE LA PHOTO</span>
            <span class="lightbox-cat" id="lightbox-cat">CATEGORIE</span> 
        </div>
    </div>

    <button class="lightbox-next" id="lightbox-next" aria-label="Suivante">
        Suivante &rarr;
    </button>
 </div>




<?php get_template_part('template-parts/modal-contact'); ?>

<?php wp_footer(); ?>
</body>
</html>