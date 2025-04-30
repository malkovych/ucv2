<?php
// Підключення стилів і скриптів
function ucv_theme_scripts() {
    wp_enqueue_style('ucv-style', get_stylesheet_uri(), array(), '1.0');
    
    // Підключаємо JavaScript для меню
    wp_enqueue_script('ucv-menu-script', get_template_directory_uri() . '/script.js', array('jquery'), '1.0', true);

    // Стилі для Lightbox
    wp_enqueue_style('ucv-lightbox', get_template_directory_uri() . '/css/lightbox.css', array(), '1.0');
    
    // Скрипт для Lightbox
    wp_enqueue_script('ucv-lightbox', get_template_directory_uri() . '/js/lightbox.js', array('jquery'), '1.0', true);

    $custom_css = '';
    $sidebar_bg = get_theme_mod('sidebar_bg_color', '#f5f5f5');
    $content_bg = get_theme_mod('content_bg_color', '#ffffff');
    $custom_css .= ".sidebar { background-color: {$sidebar_bg} !important; }\n";
    $custom_css .= ".main-content { background-color: {$content_bg} !important; }\n";
    $custom_css .= ".homepage { background-color: {$content_bg} !important; }\n"; // Додаємо явно фон для homepage

    $typography_elements = array(
        'site_title' => '.site-title a',
        'subtitle' => '.site-description',
        'menu_items' => '.nav-menu a',
        'heading1' => 'h1',
        'heading2' => 'h2',
        'heading3' => 'h3',
        'heading4' => 'h4',
        'paragraph' => 'p',
        'quote' => 'blockquote',
        'link' => 'a',
    );

    foreach ($typography_elements as $key => $selector) {
        $font = get_theme_mod("ucv_{$key}_font", 'Arial');
        $size = get_theme_mod("ucv_{$key}_size", ($key === 'site_title' ? 24 : ($key === 'subtitle' ? 16 : ($key === 'menu_items' ? 24 : 14))));
        $color = get_theme_mod("ucv_{$key}_color", '#333333');
        $custom_css .= "{$selector} { font-family: '{$font}', sans-serif !important; font-size: {$size}px !important; color: {$color} !important; }\n";
    }

    // Додаємо стилі для фонового тексту сайдбару
    $sidebar_bg_text_font = get_theme_mod('ucv_sidebar_bg_text_font', 'Arial');
    $sidebar_bg_text_size = get_theme_mod('ucv_sidebar_bg_text_size', '200');
    $sidebar_bg_text_color = get_theme_mod('ucv_sidebar_bg_text_color', '#000000');
    $sidebar_bg_text_opacity = get_theme_mod('sidebar_background_text_opacity', '0.05');
    
    // Конвертуємо HEX в RGBA
    $rgb = sscanf($sidebar_bg_text_color, "#%02x%02x%02x");
    if ($rgb) {
        $rgba_color = "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, {$sidebar_bg_text_opacity})";
        $custom_css .= ".sidebar-background-text { font-family: '{$sidebar_bg_text_font}', sans-serif !important; font-size: {$sidebar_bg_text_size}px !important; color: {$rgba_color} !important; }\n";
    }

    // Налаштування тіні для фонового тексту
    $shadow_h_offset = get_theme_mod('sidebar_bg_text_shadow_h', '0');
    $shadow_v_offset = get_theme_mod('sidebar_bg_text_shadow_v', '0');
    $shadow_blur = get_theme_mod('sidebar_bg_text_shadow_blur', '0');
    $shadow_color = get_theme_mod('sidebar_bg_text_shadow_color', 'rgba(0,0,0,0)');
    
    if ($shadow_h_offset !== '0' || $shadow_v_offset !== '0' || $shadow_blur !== '0') {
        $custom_css .= ".sidebar-background-text { text-shadow: {$shadow_h_offset}px {$shadow_v_offset}px {$shadow_blur}px {$shadow_color}; }\n";
    }

    $custom_css .= ".site-title a { text-decoration: none; }\n";
    $custom_css .= ".site-title a:hover { text-decoration: none; }\n"; // Прибираємо підкреслення для назви сайту
    $custom_css .= "a:not(.site-title a):hover { text-decoration: underline; }\n"; // Додаємо підкреслення для інших посилань

    // Динамічний CSS для фото
    $photo_border_width = get_theme_mod('homepage_photo_border_width', 2);
    $photo_border_color = get_theme_mod('homepage_photo_border_color', '#333333');
    $custom_css .= ".homepage-photo { border: {$photo_border_width}px solid {$photo_border_color}; border-radius: 50%; width: 200px; height: 200px; object-fit: cover; display: block; margin: 30px auto 40px; }\n";

    // Динамічний CSS для кнопки "Download CV"
    $cv_button_color = get_theme_mod('cv_button_color', '#333333');
    $custom_css .= ".download-cv { color: {$cv_button_color}; border-color: {$cv_button_color}; }\n";
    $custom_css .= ".download-cv:hover { background-color: {$cv_button_color}; color: white; }\n";

    // Динамічний CSS для соціальних іконок
    $social_icons_color = get_theme_mod('social_icons_color', '#333333');
    $custom_css .= ".social-icon svg { fill: {$social_icons_color}; }\n";

    wp_add_inline_style('ucv-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'ucv_theme_scripts', 10);

// Функція для регулювання яскравості кольору (для ефекту ховеру)
function adjustBrightness($hex, $steps) {
    // Видаляємо # якщо вона є
    if (strpos($hex, '#') === 0) {
        $hex = substr($hex, 1);
    }
    
    // Конвертуємо у RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Регулюємо яскравість
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    // Конвертуємо назад у HEX
    return '#' . sprintf('%02x', $r) . sprintf('%02x', $g) . sprintf('%02x', $b);
}

function ucv_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => 'Primary Menu',
    ));
}
add_action('after_setup_theme', 'ucv_theme_setup');
// Налаштування кастомайзера
function ucv_customize_register($wp_customize) {
    // Секція "Colors"
    $wp_customize->add_section('ucv_colors', array(
        'title' => 'Colors',
        'priority' => 30,
    ));

    $wp_customize->add_setting('sidebar_bg_color', array(
        'default' => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_bg_color', array(
        'label' => 'Sidebar Background Color',
        'section' => 'ucv_colors',
    )));

    $wp_customize->add_setting('content_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'content_bg_color', array(
        'label' => 'Content Background Color',
        'section' => 'ucv_colors',
    )));

    // Секція для фонового тексту сайдбару
    $wp_customize->add_section('ucv_sidebar_background_text', array(
        'title' => 'Sidebar Background Text',
        'priority' => 32,
    ));

    // Текст для фону
    $wp_customize->add_setting('sidebar_background_text', array(
        'default' => 'CV',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sidebar_background_text', array(
        'label' => 'Background Text',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'text',
    ));

    // Колір фонового тексту
    $wp_customize->add_setting('ucv_sidebar_bg_text_color', array(
        'default' => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ucv_sidebar_bg_text_color', array(
        'label' => 'Background Text Color',
        'section' => 'ucv_sidebar_background_text',
    )));

    // Прозорість фонового тексту
    $wp_customize->add_setting('sidebar_background_text_opacity', array(
        'default' => '0.05',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sidebar_background_text_opacity', array(
        'label' => 'Background Text Opacity (0-1)',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0.01,
            'max' => 1,
            'step' => 0.01,
        ),
    ));

    // Налаштування тіні тексту
    $wp_customize->add_setting('sidebar_bg_text_shadow_h', array(
        'default' => '0',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sidebar_bg_text_shadow_h', array(
        'label' => 'Shadow Horizontal Offset (px)',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'number',
        'input_attrs' => array(
            'min' => -50,
            'max' => 50,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('sidebar_bg_text_shadow_v', array(
        'default' => '0',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sidebar_bg_text_shadow_v', array(
        'label' => 'Shadow Vertical Offset (px)',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'number',
        'input_attrs' => array(
            'min' => -50,
            'max' => 50,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('sidebar_bg_text_shadow_blur', array(
        'default' => '0',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sidebar_bg_text_shadow_blur', array(
        'label' => 'Shadow Blur Radius (px)',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('sidebar_bg_text_shadow_color', array(
        'default' => 'rgba(0,0,0,0.3)',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('sidebar_bg_text_shadow_color', array(
        'label' => 'Shadow Color (rgba format)',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'text',
        'description' => 'Format: rgba(0,0,0,0.3) - black with 30% opacity',
    ));

    // Шрифт для фонового тексту
    $wp_customize->add_setting('ucv_sidebar_bg_text_font', array(
        'default' => 'Arial',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('ucv_sidebar_bg_text_font', array(
        'label' => 'Background Text Font',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'select',
        'choices' => array(
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Montserrat' => 'Montserrat',
            'Oswald' => 'Oswald',
            'Noto Sans' => 'Noto Sans',
            'Playfair Display' => 'Playfair Display',
            'Source Code Pro' => 'Source Code Pro',
            'Ubuntu' => 'Ubuntu',
            'Play' => 'Play',
            'Great Vibes' => 'Great Vibes',
            'Amatic SC' => 'Amatic SC',
            'Sofia Sans' => 'Sofia Sans',
            'Philosopher' => 'Philosopher',
            'Dela Gothic One' => 'Dela Gothic One',
            'Cormorant Infant' => 'Cormorant Infant',
            'Cormorant Garamond' => 'Cormorant Garamond',
            'Arial' => 'Arial',
        ),
    ));
    // Розмір фонового тексту
    $wp_customize->add_setting('ucv_sidebar_bg_text_size', array(
        'default' => '200',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('ucv_sidebar_bg_text_size', array(
        'label' => 'Background Text Size (px)',
        'section' => 'ucv_sidebar_background_text',
        'type' => 'number',
        'input_attrs' => array('min' => 50, 'max' => 400, 'step' => 10),
    ));

    // Секція "Typography"
    $wp_customize->add_section('ucv_typography', array(
        'title' => 'Typography',
        'priority' => 35,
    ));

    $google_fonts = array(
        'Roboto' => 'Roboto',
        'Open Sans' => 'Open Sans',
        'Montserrat' => 'Montserrat',
        'Oswald' => 'Oswald',
        'Noto Sans' => 'Noto Sans',
        'Playfair Display' => 'Playfair Display',
        'Source Code Pro' => 'Source Code Pro',
        'Ubuntu' => 'Ubuntu',
        'Play' => 'Play',
        'Great Vibes' => 'Great Vibes',
        'Amatic SC' => 'Amatic SC',
        'Sofia Sans' => 'Sofia Sans',
        'Philosopher' => 'Philosopher',
        'Dela Gothic One' => 'Dela Gothic One',
        'Cormorant Infant' => 'Cormorant Infant',
        'Cormorant Garamond' => 'Cormorant Garamond',
    );

    $typography_elements = array(
        'site_title' => 'Site Title',
        'subtitle' => 'Subtitle',
        'menu_items' => 'Menu Items',
        'heading1' => 'Heading 1',
        'heading2' => 'Heading 2',
        'heading3' => 'Heading 3',
        'heading4' => 'Heading 4',
        'paragraph' => 'Paragraph',
        'quote' => 'Quote',
        'link' => 'Link Color',
    );

    foreach ($typography_elements as $key => $label) {
        $wp_customize->add_setting("ucv_{$key}_font", array(
            'default' => 'Arial',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("ucv_{$key}_font", array(
            'label' => "$label Font",
            'section' => 'ucv_typography',
            'type' => 'select',
            'choices' => $google_fonts,
        ));

        $wp_customize->add_setting("ucv_{$key}_size", array(
            'default' => ($key === 'site_title' ? 24 : ($key === 'subtitle' ? 16 : ($key === 'menu_items' ? 24 : 14))),
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control("ucv_{$key}_size", array(
            'label' => "$label Size (px)",
            'section' => 'ucv_typography',
            'type' => 'number',
            'input_attrs' => array('min' => 10, 'max' => 100, 'step' => 1),
        ));

        $wp_customize->add_setting("ucv_{$key}_color", array(
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "ucv_{$key}_color", array(
            'label' => "$label Color",
            'section' => 'ucv_typography',
        )));
    }

    // Секція "Homepage Settings"
    $wp_customize->add_section('ucv_homepage', array(
        'title' => 'Homepage Settings',
        'priority' => 40,
    ));

    // Фото
    $wp_customize->add_setting('homepage_photo', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'homepage_photo', array(
        'label' => 'Profile Photo',
        'section' => 'ucv_homepage',
    )));

    $wp_customize->add_setting('homepage_photo_border_width', array(
        'default' => 2,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('homepage_photo_border_width', array(
        'label' => 'Photo Border Width (px)',
        'section' => 'ucv_homepage',
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'max' => 20, 'step' => 1),
    ));

    $wp_customize->add_setting('homepage_photo_border_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'homepage_photo_border_color', array(
        'label' => 'Photo Border Color',
        'section' => 'ucv_homepage',
    )));
    // Ім'я та спеціальність
    $wp_customize->add_setting('homepage_name', array(
        'default' => 'Your Name',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('homepage_name', array(
        'label' => 'Name (H1)',
        'section' => 'ucv_homepage',
        'type' => 'text',
    ));

    $wp_customize->add_setting('homepage_specialty', array(
        'default' => 'Your Specialty',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('homepage_specialty', array(
        'label' => 'Specialty (H2)',
        'section' => 'ucv_homepage',
        'type' => 'text',
    ));

    // Заголовки секцій
    $section_titles = array(
        'about_me' => 'About Me',
        'skills' => 'Skills',
        'experience' => 'Experience',
        'education' => 'Education',
        'portfolio' => 'Portfolio',
        'blog' => 'Blog',
        'contact' => 'Contact Me'
    );
    
    foreach ($section_titles as $key => $default) {
        $wp_customize->add_setting("homepage_section_title_{$key}", array(
            'default' => $default,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_section_title_{$key}", array(
            'label' => "Section Title: " . $default,
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));
    }

    // About Me
    $wp_customize->add_setting('homepage_about_me', array(
        'default' => 'Tell something about yourself.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('homepage_about_me', array(
        'label' => 'About Me Text',
        'section' => 'ucv_homepage',
        'type' => 'textarea',
    ));

    // Experience
    for ($i = 1; $i <= 3; $i++) {
        $wp_customize->add_setting("homepage_experience_{$i}_years", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_experience_{$i}_years", array(
            'label' => "Experience $i: Years",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));

        $wp_customize->add_setting("homepage_experience_{$i}_company", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_experience_{$i}_company", array(
            'label' => "Experience $i: Company",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));

        $wp_customize->add_setting("homepage_experience_{$i}_position", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_experience_{$i}_position", array(
            'label' => "Experience $i: Position",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));

        $wp_customize->add_setting("homepage_experience_{$i}_description", array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        ));
        $wp_customize->add_control("homepage_experience_{$i}_description", array(
            'label' => "Experience $i: Description",
            'section' => 'ucv_homepage',
            'type' => 'textarea',
        ));
    }

    // Skills (до 10)
    for ($i = 1; $i <= 10; $i++) {
        $wp_customize->add_setting("homepage_skill_{$i}_name", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_skill_{$i}_name", array(
            'label' => "Skill $i: Name",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));

        $wp_customize->add_setting("homepage_skill_{$i}_level", array(
            'default' => 0,
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control("homepage_skill_{$i}_level", array(
            'label' => "Skill $i: Level (0-10)",
            'section' => 'ucv_homepage',
            'type' => 'number',
            'input_attrs' => array('min' => 0, 'max' => 10, 'step' => 1),
        ));
    }
    // Education
    for ($i = 1; $i <= 2; $i++) {
        $wp_customize->add_setting("homepage_education_{$i}_institution", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_education_{$i}_institution", array(
            'label' => "Education $i: Institution",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));

        $wp_customize->add_setting("homepage_education_{$i}_specialty", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_education_{$i}_specialty", array(
            'label' => "Education $i: Specialty",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));

        $wp_customize->add_setting("homepage_education_{$i}_years", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("homepage_education_{$i}_years", array(
            'label' => "Education $i: Years",
            'section' => 'ucv_homepage',
            'type' => 'text',
        ));
    }

    // Блог
    $wp_customize->add_setting('homepage_blog_count', array(
        'default' => '4',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('homepage_blog_count', array(
        'label' => 'Number of Blog Posts to Show',
        'section' => 'ucv_homepage',
        'type' => 'number',
        'input_attrs' => array('min' => 1, 'max' => 8, 'step' => 1),
    ));

    // Portfolio - індивідуальні медіа-поля
    $wp_customize->add_section('ucv_portfolio', array(
        'title' => 'Portfolio Items',
        'priority' => 70,
        'description' => 'Add up to 8 portfolio items',
    ));

    // Кількість робіт портфоліо
    $max_portfolio_items = 8;

    for ($i = 1; $i <= $max_portfolio_items; $i++) {
        $wp_customize->add_setting("portfolio_item_{$i}_image", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "portfolio_item_{$i}_image", array(
            'label' => "Portfolio Item {$i}: Image",
            'section' => 'ucv_portfolio',
        )));

        $wp_customize->add_setting("portfolio_item_{$i}_title", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("portfolio_item_{$i}_title", array(
            'label' => "Portfolio Item {$i}: Title",
            'section' => 'ucv_portfolio',
            'type' => 'text',
        ));

        $wp_customize->add_setting("portfolio_item_{$i}_description", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("portfolio_item_{$i}_description", array(
            'label' => "Portfolio Item {$i}: Description",
            'section' => 'ucv_portfolio',
            'type' => 'textarea',
        ));

        $wp_customize->add_setting("portfolio_item_{$i}_url", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("portfolio_item_{$i}_url", array(
            'label' => "Portfolio Item {$i}: URL",
            'section' => 'ucv_portfolio',
            'type' => 'url',
        ));
    }
    // Contact Me
    $wp_customize->add_setting('homepage_contact_text', array(
        'default' => 'Email: example@email.com',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('homepage_contact_text', array(
        'label' => 'Contact Text',
        'section' => 'ucv_homepage',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('homepage_contact_form', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('homepage_contact_form', array(
        'label' => 'Contact Form Shortcode',
        'section' => 'ucv_homepage',
        'type' => 'text',
        'description' => 'Insert a shortcode from a contact form plugin (e.g., Contact Form 7)',
    ));
    
    // Налаштування кнопки "Download CV"
    $wp_customize->add_section('ucv_footer_button', array(
        'title' => 'Download CV Button',
        'priority' => 90,
    ));
    
    $wp_customize->add_setting('cv_download_link', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('cv_download_link', array(
        'label' => 'CV File Link',
        'section' => 'ucv_footer_button',
        'type' => 'url',
        'description' => 'Enter a link to your CV file for download',
    ));
    
    $wp_customize->add_setting('cv_button_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cv_button_color', array(
        'label' => 'Button Color',
        'section' => 'ucv_footer_button',
    )));

    // Додаємо секцію для соціальних мереж
    $wp_customize->add_section('ucv_social_media', array(
        'title' => 'Social Media',
        'priority' => 45,
        'description' => 'Add links to your social media profiles',
    ));

    // Список соціальних мереж
    $social_networks = array(
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'github' => 'GitHub',
        'youtube' => 'YouTube',
        'telegram' => 'Telegram',
        'pinterest' => 'Pinterest',
        'behance' => 'Behance',
        'dribbble' => 'Dribbble'
    );

    foreach ($social_networks as $key => $label) {
        $wp_customize->add_setting("social_media_{$key}", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control("social_media_{$key}", array(
            'label' => $label,
            'section' => 'ucv_social_media',
            'type' => 'url',
        ));
    }
    // Додаємо налаштування кольору іконок
    $wp_customize->add_setting('social_icons_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_icons_color', array(
        'label' => 'Icons Color',
        'section' => 'ucv_social_media',
    )));

    // Розмір іконок
    $wp_customize->add_setting('social_icons_size', array(
        'default' => '24',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('social_icons_size', array(
        'label' => 'Icons Size (px)',
        'section' => 'ucv_social_media',
        'type' => 'number',
        'input_attrs' => array('min' => 16, 'max' => 48, 'step' => 2),
    ));
    
    // Налаштування пульсуючої точки
    $wp_customize->add_setting('pulse_dot_active', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    $wp_customize->add_control('pulse_dot_active', array(
        'label' => 'Enable Pulsing Dot',
        'section' => 'ucv_homepage',
        'type' => 'checkbox',
    ));

    $wp_customize->add_setting('pulse_dot_color', array(
        'default' => 'red',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('pulse_dot_color', array(
        'label' => 'Dot Color',
        'section' => 'ucv_homepage',
        'type' => 'select',
        'choices' => array(
            'red' => 'Red',
            'green' => 'Light Green',
            'orange' => 'Orange',
        ),
    ));

    $wp_customize->add_setting('pulse_dot_text', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('pulse_dot_text', array(
        'label' => 'Text After Dot',
        'section' => 'ucv_homepage',
        'type' => 'text',
        'description' => 'Text to display after the pulsing dot'
    ));
}
add_action('customize_register', 'ucv_customize_register');

// Функція для санітації чекбоксу
function sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}
// Функція для виведення іконок соціальних мереж
function ucv_social_icons() {
    $social_networks = array(
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>',
        'github' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>',
        'telegram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M248,8C111.033,8,0,119.033,0,256S111.033,504,248,504,496,392.967,496,256,384.967,8,248,8ZM362.952,176.66c-3.732,39.215-19.881,134.378-28.1,178.3-3.476,18.584-10.322,24.816-16.948,25.425-14.4,1.326-25.338-9.517-39.287-18.661-21.827-14.308-34.158-23.215-55.346-37.177-24.485-16.135-8.612-25,5.342-39.5,3.652-3.793,67.107-61.51,68.335-66.746.153-.655.3-3.1-1.154-4.384s-3.59-.849-5.135-.5q-3.283.746-104.608,69.142-14.845,10.194-26.894,9.934c-8.855-.191-25.888-5.006-38.551-9.123-15.531-5.048-27.875-7.717-26.8-16.291q.84-6.7,18.45-13.7,108.446-47.248,144.628-62.3c68.872-28.647,83.183-33.623,92.511-33.789,2.052-.034,6.639.474,9.61,2.885a10.452,10.452,0,0,1,3.53,6.716A43.765,43.765,0,0,1,362.952,176.66Z"/></svg>',
        'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"/></svg>',
        'behance' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M232 237.2c31.8-15.2 48.4-38.2 48.4-74 0-70.6-52.6-87.8-113.3-87.8H0v354.4h171.8c64.4 0 124.9-30.9 124.9-102.9 0-44.5-21.1-77.4-64.7-89.7zM77.9 135.9H151c28.1 0 53.4 7.9 53.4 40.5 0 30.1-19.7 42.2-47.5 42.2h-79v-82.7zm83.3 233.7H77.9V272h84.9c34.3 0 56 14.3 56 50.6 0 35.8-25.9 47-57.6 47zm358.5-240.7H376V94h143.7v34.9zM576 305.2c0-75.9-44.4-139.2-124.9-139.2-78.2 0-131.3 58.8-131.3 135.8 0 79.9 50.3 134.7 131.3 134.7 61.3 0 101-27.6 120.1-86.3H509c-6.7 21.9-34.3 33.5-55.7 33.5-41.3 0-63-24.2-63-65.3h185.1c.3-4.2.6-8.7.6-13.2zM390.4 274c2.3-33.7 24.7-54.8 58.5-54.8 35.4 0 53.2 20.8 56.2 54.8H390.4z"/></svg>',
        'dribbble' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc.--><path d="M256 8C119.252 8 8 119.252 8 256s111.252 248 248 248 248-111.252 248-248S392.748 8 256 8zm163.97 114.366c29.503 36.046 47.369 81.957 47.835 131.955-6.984-1.477-77.018-15.682-147.502-6.818-5.752-14.041-11.181-26.393-18.617-41.614 78.321-31.977 113.818-77.482 118.284-83.523zM396.421 97.87c-3.81 5.427-35.697 48.286-111.021 76.519-34.712-63.776-73.185-116.168-79.04-124.008 67.176-16.193 137.966 1.27 190.061 47.489zm-230.48-33.25c5.585 7.659 43.438 60.116 78.537 122.509-99.087 26.313-186.36 25.934-195.834 25.809C62.38 147.205 106.678 92.573 165.941 64.62zM44.17 256.323c0-2.166.043-4.322.108-6.473 9.268.19 111.92 1.513 217.706-30.146 6.064 11.868 11.857 23.915 17.174 35.949-76.599 21.575-146.194 83.527-180.531 142.306C64.794 360.405 44.17 310.73 44.17 256.323zm81.807 167.113c22.127-45.233 82.178-103.622 167.579-132.756 29.74 77.283 42.039 142.053 45.189 160.638-68.112 29.013-150.015 21.053-212.768-27.882zm248.38 8.489c-2.171-12.886-13.446-74.897-41.152-151.033 66.38-10.626 124.7 6.768 131.947 9.055-9.442 58.941-43.273 109.844-90.795 141.978z"/></svg>'
    );
    
    $output = '<div class="social-icons">';
    
    foreach ($social_networks as $network => $icon) {
        $url = get_theme_mod("social_media_{$network}", '');
        if ($url) {
            $color = get_theme_mod('social_icons_color', '#333333');
            $size = get_theme_mod('social_icons_size', '24');
            
            $output .= '<a href="' . esc_url($url) . '" class="social-icon ' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr(ucfirst($network)) . '">';
            $output .= str_replace('<svg', '<svg width="' . esc_attr($size) . 'px" height="' . esc_attr($size) . 'px" fill="' . esc_attr($color) . '"', $icon);
            $output .= '</a>';
        }
    }
    
    $output .= '</div>';
    
    return $output;
}
// Додаємо сторінку про тему з можливістю імпорту демоданих
function ucv_theme_page() {
    add_theme_page(
        'UCV Theme', 
        'UCV Theme', 
        'edit_theme_options', 
        'ucv-theme', 
        'ucv_theme_page_content'
    );
}
add_action('admin_menu', 'ucv_theme_page');

// Вміст сторінки про тему
function ucv_theme_page_content() {
    ?>
    <div class="wrap">
        <h1>UCV Theme</h1>
        <p>Thank you for choosing UCV Theme for your website!</p>
        
        <div class="card" style="max-width: 800px; padding: 20px; margin-top: 20px;">
            <h2>Import Demo Data</h2>
            <p>Click the button below to import demo data and make your site look like the demo version.</p>
            <form method="post" action="">
                <?php wp_nonce_field('ucv_import_demo_data', 'ucv_import_nonce'); ?>
                <input type="submit" name="ucv_import_demo" class="button button-primary" value="Import Demo Data">
            </form>
        </div>
    </div>
    <?php
    
    // Обробка натискання кнопки
    if (isset($_POST['ucv_import_demo']) && check_admin_referer('ucv_import_demo_data', 'ucv_import_nonce')) {
        ucv_import_demo_data();
        echo '<div class="notice notice-success"><p>Demo data has been successfully imported!</p></div>';
    }
}

// Функція імпорту демоданих
function ucv_import_demo_data() {
    // 1. Перевірка, чи встановлені необхідні плагіни
    if (!class_exists('WordPress_Importer')) {
        // Якщо плагін WordPress Importer не встановлений, використовуємо вбудований
        require get_template_directory() . '/inc/importers/wordpress-importer.php';
    }
    
    // 2. Імпорт вмісту з XML
    $xml_file = get_template_directory() . '/inc/demo-data/demo-content.xml';
    if (file_exists($xml_file)) {
        $importer = new WordPress_Importer();
        $importer->fetch_attachments = true;
        $importer->import($xml_file);
    }
    
    // 3. Імпорт налаштувань кастомайзера
    $customizer_file = get_template_directory() . '/inc/demo-data/customizer.dat';
    if (file_exists($customizer_file)) {
        $customizer_data = file_get_contents($customizer_file);
        $customizer_data = unserialize(base64_decode($customizer_data));
        
        foreach ($customizer_data as $key => $value) {
            set_theme_mod($key, $value);
        }
    }
    
    // 4. Налаштування головної сторінки і меню
    $front_page = get_page_by_title('Home');
    if ($front_page) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_page->ID);
    }
    
    // Налаштування меню
    $main_menu = get_term_by('name', 'Primary Menu', 'nav_menu');
    if ($main_menu) {
        set_theme_mod('nav_menu_locations', array(
            'primary' => $main_menu->term_id
        ));
    }
    // 5. Демо-налаштування для портфоліо
    $portfolio_demo = array(
        'portfolio_item_1_image' => get_template_directory_uri() . '/inc/demo-data/images/portfolio-1.jpg',
        'portfolio_item_1_title' => 'Website Redesign',
        'portfolio_item_1_description' => 'Modern and responsive redesign for a corporate client',
        'portfolio_item_1_url' => '#',
        
        'portfolio_item_2_image' => get_template_directory_uri() . '/inc/demo-data/images/portfolio-2.jpg',
        'portfolio_item_2_title' => 'Mobile App',
        'portfolio_item_2_description' => 'User-friendly mobile application for iOS and Android',
        'portfolio_item_2_url' => '#',
        
        'portfolio_item_3_image' => get_template_directory_uri() . '/inc/demo-data/images/portfolio-3.jpg',
        'portfolio_item_3_title' => 'E-commerce Platform',
        'portfolio_item_3_description' => 'Full-featured online store with payment processing',
        'portfolio_item_3_url' => '#',
        
        'portfolio_item_4_image' => get_template_directory_uri() . '/inc/demo-data/images/portfolio-4.jpg',
        'portfolio_item_4_title' => 'Tech Conference Website',
        'portfolio_item_4_description' => 'Interactive website for a tech conference',
        'portfolio_item_4_url' => '#',
        
        'portfolio_item_5_image' => get_template_directory_uri() . '/inc/demo-data/images/portfolio-5.jpg',
        'portfolio_item_5_title' => 'Architecture Portfolio',
        'portfolio_item_5_description' => 'Minimalist design for an architectural firm',
        'portfolio_item_5_url' => '#',
        
        'portfolio_item_6_image' => get_template_directory_uri() . '/inc/demo-data/images/portfolio-6.jpg',
        'portfolio_item_6_title' => 'Medical Services Platform',
        'portfolio_item_6_description' => 'Accessible telemedicine platform',
        'portfolio_item_6_url' => '#'
    );
    
    // Додаємо демо-налаштування для соціальних мереж
    $social_demo = array(
        'social_media_facebook' => 'https://facebook.com',
        'social_media_twitter' => 'https://twitter.com',
        'social_media_instagram' => 'https://instagram.com',
        'social_media_linkedin' => 'https://linkedin.com',
        'social_media_github' => 'https://github.com',
        'social_media_behance' => 'https://behance.net',
        'social_media_dribbble' => 'https://dribbble.com',
        'social_icons_color' => '#333333',
        'social_icons_size' => '24'
    );
    
    // Встановлюємо налаштування
    foreach ($portfolio_demo as $key => $value) {
        set_theme_mod($key, $value);
    }
    
    foreach ($social_demo as $key => $value) {
        set_theme_mod($key, $value);
    }
    
    // Створюємо демо-контент, якщо він не існує
    ucv_create_demo_content();
    
    // Очищуємо кеш
    wp_cache_flush();
}

// Створення демо-контенту
function ucv_create_demo_content() {
    // Перевіряємо, чи вже створені пости
    $demo_post = get_page_by_title('Sample Blog Post', OBJECT, 'post');
    if (!$demo_post) {
        // Створюємо демо-пости
        $post_data = array(
            array(
                'title' => 'Sample Blog Post',
                'content' => 'This is a sample blog post created automatically to help you get started with UCV Theme. You can edit or delete it as needed.',
                'excerpt' => 'This is a sample blog post to get you started.',
                'status' => 'publish',
                'categories' => array('Uncategorized')
            ),
            array(
                'title' => 'How to Customize Your CV',
                'content' => 'This is another sample blog post that demonstrates how to use UCV Theme features to create a personalized CV website.',
                'excerpt' => 'Learn how to customize your CV with UCV Theme.',
                'status' => 'publish',
                'categories' => array('Uncategorized')
            )
        );
        
        foreach ($post_data as $post) {
            wp_insert_post(array(
                'post_title' => $post['title'],
                'post_content' => $post['content'],
                'post_excerpt' => $post['excerpt'],
                'post_status' => $post['status'],
                'post_type' => 'post',
                'post_author' => 1,
                'post_category' => array(1) // Uncategorized by default
            ));
        }
    }
}

/**
 * Клас для emoji picker контролу
 */
class UCV_Emoji_Picker_Control extends WP_Customize_Control {
    public $type = 'emoji-picker';
    
    public function render_content() {
        ?>
        <label>
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
            
            <div class="emoji-picker-control">
                <input type="text" id="<?php echo esc_attr($this->id); ?>" class="emoji-picker-input" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" />
                <button type="button" class="button emoji-picker-button">
                    😊 Вибрати emoji
                </button>
                <div class="emoji-preview">
                    <?php if ($this->value()) : ?>
                        <span class="emoji-display"><?php echo esc_html($this->value()); ?></span>
                    <?php else : ?>
                        <span class="emoji-none">Немає emoji</span>
                    <?php endif; ?>
                </div>
            </div>
        </label>
        <?php
    }
    function ucv_emoji_customizer_scripts() {
        // Підключаємо emoji-picker-element
        wp_enqueue_script('emoji-picker', 'https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js', array(), '1.0', true);
        wp_enqueue_style('emoji-picker-css', 'https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.css', array(), '1.0');
        
        // Підключаємо наш власний скрипт
        wp_enqueue_script('ucv-emoji-picker', get_template_directory_uri() . '/js/emoji-picker.js', array('jquery', 'customize-controls', 'emoji-picker'), '1.0', true);
        wp_enqueue_style('ucv-emoji-picker-css', get_template_directory_uri() . '/js/emoji-picker.css', array(), '1.0');
    }
    add_action('customize_controls_enqueue_scripts', 'ucv_emoji_customizer_scripts');
}
