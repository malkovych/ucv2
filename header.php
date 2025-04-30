<?php
/**
 * Шапка сайту
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> <?php if (is_front_page()) echo 'data-frontpage="true"'; ?>>
    <div class="sidebar">
        <?php
        // Додаємо текст на фоні сайдбару
        $sidebar_bg_text = get_theme_mod('sidebar_background_text', 'CV');
        
        echo '<div class="sidebar-background-text">' . esc_html($sidebar_bg_text) . '</div>';
        ?>
        
        <div class="sidebar-top">
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            
            <input type="checkbox" id="navcheck" role="button" title="menu">
            <label for="navcheck" aria-hidden="true" title="menu">
                <span class="burger">
                    <span class="bar"></span>
                </span>
            </label>
            
            <nav id="menu" class="nav-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'fallback_cb' => function() {
                        echo '<ul id="primary-menu"><li><a href="#">Налаштуйте меню в адмінці</a></li></ul>';
                    },
                ));
                ?>
            </nav>
        </div>
        
        <p class="site-description">
            <?php bloginfo('description'); ?>
        </p>
    </div>
    <?php
    // Перевіряємо, якщо це домашня сторінка, то не виводимо контент тут (він буде в front-page.php)
    if (is_front_page()) {
        // Не виводимо нічого для головної сторінки, оскільки це вже зроблено в front-page.php
    } else {
        // Не виводимо контент тут, оскільки це буде у відповідних файлах шаблонів (page.php, single.php, тощо)
    }
    ?>