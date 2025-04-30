<?php
/**
 * Шаблон для відображення окремого запису
 */
get_header();
?>

<main class="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                
                <div class="post-meta">
                    <span class="post-date"><?php echo get_the_date(); ?></span>
                    <span class="post-author"><?php _e('By', 'ucv-pro'); ?> <?php the_author(); ?></span>
                    <?php if (has_category()) : ?>
                        <span class="post-categories"><?php the_category(', '); ?></span>
                    <?php endif; ?>
                </div>
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
            
            <?php if (has_tag()) : ?>
                <footer class="entry-footer">
                    <div class="entry-tags">
                        <?php the_tags('<span class="tag-links">' . __('Tags: ', 'ucv-pro'), ', ', '</span>'); ?>
                    </div>
                </footer>
            <?php endif; ?>
        </article>

        <div class="post-navigation">
            <?php
            previous_post_link('<div class="nav-previous">%link</div>', '← %title');
            next_post_link('<div class="nav-next">%link</div>', '%title →');
            ?>
        </div>

        <?php
        // Якщо коментарі дозволені або вже є коментарі, завантажуємо шаблон коментарів
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>