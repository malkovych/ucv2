(function($) {
    // Colors
    wp.customize('sidebar_bg_color', function(value) {
        value.bind(function(newval) {
            $('.sidebar').css('background-color', newval);
        });
    });
    wp.customize('content_bg_color', function(value) {
        value.bind(function(newval) {
            $('.main-content, .homepage').css('background-color', newval);
        });
    });

    // Sidebar Background Text
    wp.customize('sidebar_background_text', function(value) {
        value.bind(function(newval) {
            $('.sidebar-background-text').text(newval);
        });
    });
    
    // Колір та прозорість фонового тексту
    wp.customize('ucv_sidebar_bg_text_color', function(value) {
        value.bind(function(newval) {
            updateSidebarTextColor();
        });
    });
    
    wp.customize('sidebar_background_text_opacity', function(value) {
        value.bind(function(newval) {
            updateSidebarTextColor();
        });
    });
    
    // Функція для оновлення кольору фонового тексту з прозорістю
    function updateSidebarTextColor() {
        var color = wp.customize('ucv_sidebar_bg_text_color').get();
        var opacity = wp.customize('sidebar_background_text_opacity').get();
        
        // Конвертуємо HEX в RGB
        var r = parseInt(color.substr(1,2), 16);
        var g = parseInt(color.substr(3,2), 16);
        var b = parseInt(color.substr(5,2), 16);
        
        // Створюємо RGBA колір
        var rgba = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
        $('.sidebar-background-text').css('color', rgba);
    }
    
    // Налаштування тіні для фонового тексту
    wp.customize('sidebar_bg_text_shadow_h', function(value) {
        value.bind(function(newval) {
            updateSidebarTextShadow();
        });
    });
    
    wp.customize('sidebar_bg_text_shadow_v', function(value) {
        value.bind(function(newval) {
            updateSidebarTextShadow();
        });
    });
    
    wp.customize('sidebar_bg_text_shadow_blur', function(value) {
        value.bind(function(newval) {
            updateSidebarTextShadow();
        });
    });
    
    wp.customize('sidebar_bg_text_shadow_color', function(value) {
        value.bind(function(newval) {
            updateSidebarTextShadow();
        });
    });
    
    // Функція для оновлення тіні фонового тексту
    function updateSidebarTextShadow() {
        var h_offset = wp.customize('sidebar_bg_text_shadow_h').get();
        var v_offset = wp.customize('sidebar_bg_text_shadow_v').get();
        var blur = wp.customize('sidebar_bg_text_shadow_blur').get();
        var color = wp.customize('sidebar_bg_text_shadow_color').get();
        
        var shadowValue = h_offset + 'px ' + v_offset + 'px ' + blur + 'px ' + color;
        $('.sidebar-background-text').css('text-shadow', shadowValue);
    }
    
    wp.customize('ucv_sidebar_bg_text_font', function(value) {
        value.bind(function(newval) {
            $('.sidebar-background-text').css('font-family', newval);
        });
    });
    
    wp.customize('ucv_sidebar_bg_text_size', function(value) {
        value.bind(function(newval) {
            $('.sidebar-background-text').css('font-size', newval + 'px');
        });
    });

    // Typography
    var typographyElements = [
        'site_title',
        'subtitle',
        'menu_items',
        'heading1',
        'heading2',
        'heading3',
        'heading4',
        'paragraph',
        'quote',
        'link'
    ];

    typographyElements.forEach(function(key) {
        wp.customize('ucv_' + key + '_font', function(value) {
            value.bind(function(newval) {
                var selector = (key === 'site_title') ? '.site-title a' :
                              (key === 'subtitle') ? '.site-description' :
                              (key === 'menu_items') ? '.nav-menu a' :
                              (key === 'link') ? 'a' : key.replace('heading', 'h');
                $(selector).css('font-family', newval);
            });
        });
        wp.customize('ucv_' + key + '_size', function(value) {
            value.bind(function(newval) {
                var selector = (key === 'site_title') ? '.site-title a' :
                              (key === 'subtitle') ? '.site-description' :
                              (key === 'menu_items') ? '.nav-menu a' :
                              (key === 'link') ? 'a' : key.replace('heading', 'h');
                $(selector).css('font-size', newval + 'px');
            });
        });
        wp.customize('ucv_' + key + '_color', function(value) {
            value.bind(function(newval) {
                var selector = (key === 'site_title') ? '.site-title a' :
                              (key === 'subtitle') ? '.site-description' :
                              (key === 'menu_items') ? '.nav-menu a' :
                              (key === 'link') ? 'a' : key.replace('heading', 'h');
                $(selector).css('color', newval);
            });
        });
    });
    
    // Section titles
    var sections = ['about_me', 'skills', 'experience', 'education', 'portfolio', 'blog', 'contact'];
    
    sections.forEach(function(section) {
        wp.customize('homepage_section_title_' + section, function(value) {
            value.bind(function(newval) {
                $('#' + section + ' h3').text(newval);
            });
        });
    });
    
    // CV Button
    wp.customize('cv_button_color', function(value) {
        value.bind(function(newval) {
            $('.download-cv').css({
                'color': newval,
                'border-color': newval
            });
            var style = $('<style>.download-cv:hover { background-color: ' + newval + '; }</style>');
            $('style.cv-button-style').remove();
            style.addClass('cv-button-style');
            $('head').append(style);
        });
    });
    
})(jQuery);