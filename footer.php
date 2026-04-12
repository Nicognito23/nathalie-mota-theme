<footer class="site-footer">
    <div class="footer-inner">
        <?php wp_nav_menu(array(
            'theme_location' => 'footer',
            'menu_class'     => 'footer-list',
            'container'      => false,
        )); ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>