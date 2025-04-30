<?php
/**
 * Шаблон головної сторінки
 */
get_header();
?>

<main class="main-content homepage">
    <?php
    // Фото
    $photo = get_theme_mod('homepage_photo', '');
    if ($photo) {
        echo '<img src="' . esc_url($photo) . '" alt="Profile Photo" class="homepage-photo">';
    }

    // Ім'я та спеціальність
$name = get_theme_mod('homepage_name', 'Your Name');
$specialty = get_theme_mod('homepage_specialty', 'Your Specialty');
echo '<h1>' . esc_html($name) . '</h1>';

// Спеціальність з пульсуючою точкою
echo '<div class="specialty-wrapper">';
echo '<h2>' . esc_html($specialty);

// Додаємо пульсуючу точку, якщо активована
$pulse_dot_active = get_theme_mod('pulse_dot_active', false);
if ($pulse_dot_active) {
    $dot_color = get_theme_mod('pulse_dot_color', 'red');
    $dot_text = get_theme_mod('pulse_dot_text', '');
    
    echo '<span class="pulse-dot-wrapper">';
    echo '<span class="pulse-dot ' . esc_attr($dot_color) . '"></span>';
    
    if (!empty($dot_text)) {
        echo '<span class="pulse-text">' . esc_html($dot_text) . '</span>';
    }
    
    echo '</span>';
}

echo '</h2>';
echo '</div>';

    // About Me
    $about_me = get_theme_mod('homepage_about_me', '');
    if ($about_me) {
        $about_title = get_theme_mod('homepage_section_title_about_me', 'About Me');
        echo '<section id="about" class="homepage-about-me">';
        echo '<h3>' . esc_html($about_title) . '</h3>';
        echo '<p>' . wp_kses_post($about_me) . '</p>';
        echo '</section>';
    }

    // Skills
    $skills = [];
    for ($i = 1; $i <= 10; $i++) {
        $name = get_theme_mod("homepage_skill_{$i}_name", '');
        $level = get_theme_mod("homepage_skill_{$i}_level", 0);
        if ($name && $level >= 0) {
            $skills[] = ['name' => $name, 'level' => min($level, 10)]; // Обмежуємо рівень до 10
        }
    }
    if (!empty($skills)) {
        $skills_title = get_theme_mod('homepage_section_title_skills', 'Skills');
        echo '<section id="skills" class="homepage-skills">';
        echo '<h3>' . esc_html($skills_title) . '</h3>';
        echo '<ul class="skills-list">';
        foreach ($skills as $skill) {
            echo '<li>';
            echo '<span class="skill-name">' . esc_html($skill['name']) . '</span>';
            echo '<div class="skill-level">';
            for ($j = 1; $j <= 10; $j++) {
                $class = ($j <= $skill['level']) ? 'filled' : '';
                echo '<span class="skill-square ' . $class . '"></span>';
            }
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }

    // Experience
    $experience_items = [];
    for ($i = 1; $i <= 3; $i++) {
        $years = get_theme_mod("homepage_experience_{$i}_years", '');
        $company = get_theme_mod("homepage_experience_{$i}_company", '');
        $position = get_theme_mod("homepage_experience_{$i}_position", '');
        $description = get_theme_mod("homepage_experience_{$i}_description", '');
        if ($years || $company || $position || $description) {
            $experience_items[] = [
                'years' => $years,
                'company' => $company,
                'position' => $position,
                'description' => $description,
            ];
        }
    }
    if (!empty($experience_items)) {
        $experience_title = get_theme_mod('homepage_section_title_experience', 'Experience');
        echo '<section id="experience" class="homepage-experience">';
        echo '<h3>' . esc_html($experience_title) . '</h3>';
        echo '<ul class="experience-list">';
        foreach ($experience_items as $item) {
            echo '<li>';
            if ($item['years']) echo '<span class="years">' . esc_html($item['years']) . '</span>';
            if ($item['company'] || $item['position']) {
                echo '<h4>' . esc_html($item['company']) . ($item['position'] ? ' - ' . esc_html($item['position']) : '') . '</h4>';
            }
            if ($item['description']) echo '<p>' . wp_kses_post($item['description']) . '</p>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }

    // Education
    $education_items = [];
    for ($i = 1; $i <= 2; $i++) {
        $institution = get_theme_mod("homepage_education_{$i}_institution", '');
        $specialty = get_theme_mod("homepage_education_{$i}_specialty", '');
        $years = get_theme_mod("homepage_education_{$i}_years", '');
        if ($institution || $specialty || $years) {
            $education_items[] = [
                'institution' => $institution,
                'specialty' => $specialty,
                'years' => $years,
            ];
        }
    }
    if (!empty($education_items)) {
        $education_title = get_theme_mod('homepage_section_title_education', 'Education');
        echo '<section id="education" class="homepage-education">';
        echo '<h3>' . esc_html($education_title) . '</h3>';
        echo '<ul class="education-list">';
        foreach ($education_items as $item) {
            echo '<li>';
            if ($item['institution']) echo '<h4>' . esc_html($item['institution']) . '</h4>';
            if ($item['specialty']) echo '<p>' . esc_html($item['specialty']) . '</p>';
            if ($item['years']) echo '<span class="years">' . esc_html($item['years']) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }

    // Hobbies & Interests
    $hobbies_items = array();
    for ($i = 1; $i <= 6; $i++) {
        $name = get_theme_mod("homepage_hobby_{$i}_name", '');
        $icon = get_theme_mod("homepage_hobby_{$i}_icon", '');
        if ($name) {
            $hobbies_items[] = array(
                'name' => $name,
                'icon' => $icon
            );
        }
    }
    if (!empty($hobbies_items)) {
        $hobbies_title = get_theme_mod('homepage_section_title_hobbies', 'Hobbies & Interests');
        echo '<section id="hobbies" class="homepage-hobbies">';
        echo '<h3>' . esc_html($hobbies_title) . '</h3>';
        echo '<ul class="hobbies-list">';
        foreach ($hobbies_items as $item) {
            echo '<li>';
            if ($item['icon']) {
                echo '<div class="hobby-icon"><span class="hobby-emoji">' . esc_html($item['icon']) . '</span></div>';
            }
            if ($item['name']) echo '<span class="hobby-name">' . esc_html($item['name']) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }
    // Portfolio
    $portfolio_items = array();
    $max_portfolio_items = 8; // Збільшено до 8

    for ($i = 1; $i <= $max_portfolio_items; $i++) {
        $image = get_theme_mod("portfolio_item_{$i}_image", '');
        $title = get_theme_mod("portfolio_item_{$i}_title", '');
        $description = get_theme_mod("portfolio_item_{$i}_description", '');
        $url = get_theme_mod("portfolio_item_{$i}_url", '');
        
        if ($image) {
            $portfolio_items[] = array(
                'image' => $image,
                'title' => $title,
                'description' => $description,
                'url' => $url
            );
        }
    }

    if (!empty($portfolio_items)) {
        $portfolio_title = get_theme_mod('homepage_section_title_portfolio', 'Portfolio');
        echo '<section id="portfolio" class="homepage-portfolio">';
        echo '<h3>' . esc_html($portfolio_title) . '</h3>';
        echo '<div class="portfolio-gallery">';
        
        foreach ($portfolio_items as $item) {
            echo '<div class="portfolio-item">';
            
            // Для Lightbox, ми використовуємо порожнє посилання якщо URL не вказаний
            $link_open = '';
            $link_close = '';
            
            if ($item['url']) {
                $link_open = '<a href="' . esc_url($item['url']) . '" target="_blank" rel="noopener" class="portfolio-link">';
                $link_close = '</a>';
            } else {
                // Для Lightbox
                $link_open = '<a href="#" class="portfolio-lightbox-trigger">';
                $link_close = '</a>';
            }
            
            echo $link_open;
            echo '<img src="' . esc_url($item['image']) . '" alt="' . esc_attr($item['title']) . '" class="portfolio-image">';
            
            if ($item['title'] || $item['description']) {
                echo '<div class="portfolio-item-overlay">';
                if ($item['title']) echo '<h4>' . esc_html($item['title']) . '</h4>';
                if ($item['description']) echo '<p>' . esc_html($item['description']) . '</p>';
                echo '</div>';
            }
            
            echo $link_close;
            echo '</div>';
        }
        
        echo '</div>';
        echo '</section>';
    }

    // Blog - відображаємо останні записи
    $blog_count = get_theme_mod('homepage_blog_count', 4);
    $blog_title = get_theme_mod('homepage_section_title_blog', 'Blog');
    
    $recent_posts = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $blog_count,
        'ignore_sticky_posts' => 1
    ]);
    
    if ($recent_posts->have_posts()) {
        echo '<section id="blog" class="homepage-blog">';
        echo '<h3>' . esc_html($blog_title) . '</h3>';
        echo '<div class="blog-posts">';
        
        while ($recent_posts->have_posts()) {
            $recent_posts->the_post();
            
            echo '<article class="blog-post">';
            
            if (has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo get_the_post_thumbnail(null, 'medium', ['class' => 'blog-post-thumbnail']);
                echo '</a>';
            } else {
                // Якщо немає мініатюри, показуємо заглушку
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo '<img src="' . esc_url(get_template_directory_uri()) . '/img/blog-placeholder.jpg" alt="' . get_the_title() . '" class="blog-post-thumbnail">';
                echo '</a>';
            }
            
            echo '<div class="blog-post-content">';
            echo '<h4 class="blog-post-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h4>';
            echo '<div class="blog-post-excerpt">' . get_the_excerpt() . '</div>';
            
            echo '<div class="blog-post-meta">';
            echo '<span class="blog-post-date">' . get_the_date() . '</span>';
            echo '<a href="' . esc_url(get_permalink()) . '" class="read-more">Read more</a>';
            echo '</div>';
            
            echo '</div>';
            echo '</article>';
        }
        
        echo '</div>';
        echo '</section>';
        
        wp_reset_postdata();
    }

    // Contact Me
    $contact_text = get_theme_mod('homepage_contact_text', '');
    $contact_form = get_theme_mod('homepage_contact_form', '');
    if ($contact_text || $contact_form) {
        $contact_title = get_theme_mod('homepage_section_title_contact', 'Contact Me');
        echo '<section id="contact" class="homepage-contact">';
        echo '<h3>' . esc_html($contact_title) . '</h3>';
        if ($contact_text) echo '<div class="contact-text">' . wp_kses_post($contact_text) . '</div>';
        
        // Додаємо іконки соціальних мереж
        echo ucv_social_icons();
        
        if ($contact_form) echo '<div class="contact-form">' . do_shortcode($contact_form) . '</div>';
        echo '</section>';
    }
    ?>
</main>

<?php get_footer(); ?>