<?php
/**
 * Шаблон для відображення сторінок
 */
get_header();
?>

<main class="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="entry-content">
                <?php the_content(); ?>
                
                <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . __('Pages:', 'ucv-pro'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </article>

        <?php
        // Якщо коментарі дозволені або вже є коментарі, завантажуємо шаблон коментарів
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>