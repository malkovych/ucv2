<?php
/**
 * Підвал сайту
 */
?>
    <footer class="site-footer">
        <p>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Усі права захищено.</p>
        <a href="<?php echo esc_url(get_theme_mod('cv_download_link', '#')); ?>" class="download-cv" title="Download CV" download>Download CV</a>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>