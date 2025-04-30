jQuery(document).ready(function($) {
    // Отримуємо необхідні елементи
    var navCheck = document.getElementById('navcheck');
    var burgerButton = document.querySelector('label[for="navcheck"]');
    
    // Додаємо індикатори для пунктів з підменю перед текстом
    $('.nav-menu li.menu-item-has-children > a').each(function() {
        $(this).before('<span class="submenu-toggle"></span>');
    });
    
    // Обробка кліку на індикатор підменю
    $(document).on('click', '.submenu-toggle', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var parentLi = $(this).parent();
        
        // Перемикаємо стан підменю
        if (parentLi.hasClass('active')) {
            parentLi.removeClass('active');
            parentLi.find('.menu-item-has-children').removeClass('active');
        } else {
            parentLi.siblings('.menu-item-has-children.active').removeClass('active');
            parentLi.addClass('active');
        }
    });
    
    // Обробка кліку на пункт меню з підменю
    $(document).on('click', '.nav-menu li.menu-item-has-children > a', function(e) {
        // Якщо це "Резюме", переходимо на головну
        var isResumeItem = isResumeMenuItem($(this).parent('li'));
        if (isResumeItem && !isFrontPage()) {
            window.location.href = '/';
            return;
        }
        
        if ($(this).siblings('ul').length > 0) {
            e.preventDefault();
            
            var parentLi = $(this).parent();
            
            if (parentLi.hasClass('active')) {
                parentLi.removeClass('active');
                parentLi.find('.menu-item-has-children').removeClass('active');
            } else {
                parentLi.siblings('.menu-item-has-children.active').removeClass('active');
                parentLi.addClass('active');
            }
        }
    });
    
    // Обробка кліку на пункт меню без підменю
    $(document).on('click', '.nav-menu li:not(.menu-item-has-children) > a', function(e) {
        var targetId = this.getAttribute('href');
        
        // Обробка якорів
        if (targetId && targetId.startsWith('#') && targetId !== '#') {
            e.preventDefault();
            
            var targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 20,
                    behavior: 'smooth'
                });
                
                // Закриваємо меню на мобільних
                if (window.innerWidth <= 768) {
                    navCheck.checked = false;
                }
            }
        } else {
            // Закриваємо меню на мобільних для звичайних посилань
            if (window.innerWidth <= 768) {
                navCheck.checked = false;
            }
        }
    });
    
    // Виділяємо активний пункт меню при прокручуванні
    $(window).on('scroll', function() {
        if (isFrontPage()) {
            var currentPosition = $(window).scrollTop();
            
            // Перебираємо всі секції
            $('section[id]').each(function() {
                var target = $(this);
                var targetTop = target.offset().top - 100;
                var targetBottom = targetTop + target.outerHeight();
                var targetId = '#' + target.attr('id');
                
                if (currentPosition >= targetTop && currentPosition <= targetBottom) {
                    $('.nav-menu li').removeClass('current-menu-item');
                    
                    var activeMenuItem = $('.nav-menu a[href="' + targetId + '"]').parent('li');
                    activeMenuItem.addClass('current-menu-item');
                    
                    // Відкриваємо батьківське підменю
                    if (activeMenuItem.parent('ul').parent('li').length) {
                        var parentMenuItem = activeMenuItem.parent('ul').parent('li');
                        parentMenuItem.addClass('active');
                    }
                }
            });
        }
    });
    
    // Додаємо ефект затримки при наведенні
    $('.nav-menu a').hover(
        function() {
            $(this).css('transition-delay', '0.1s');
        },
        function() {
            $(this).css('transition-delay', '0s');
        }
    );
    
    // Відкриваємо пункт "Резюме" при натисканні на бургер
    $('#navcheck').change(function() {
        if ($(this).is(':checked')) {
            // Закриваємо всі підменю
            $('.nav-menu li.menu-item-has-children').removeClass('active');
            
            // Відкриваємо "Резюме" на головній сторінці
            if (isFrontPage()) {
                var homeItem = findResumeMenuItem();
                if (homeItem.length && homeItem.hasClass('menu-item-has-children')) {
                    homeItem.addClass('active');
                }
            }
        } else {
            // Закриваємо всі підменю при закритті меню
            $('.nav-menu li.menu-item-has-children').removeClass('active');
        }
    });
    
    // Перевіряємо, чи це головна сторінка
    function isFrontPage() {
        var isFrontPage = $('body').data('frontpage') === true || 
                         window.location.pathname === '/' || 
                         window.location.pathname === '/index.php';
        return isFrontPage;
    }
    
    // Знаходимо пункт меню "Резюме" або "Home"
    function findResumeMenuItem() {
        var homeItem = $('.nav-menu li').filter(function() {
            var link = $(this).find('a').first();
            var href = link.attr('href');
            var text = link.text().toLowerCase();
            
            // Шукаємо пункт за URL або текстом
            return (href === window.location.pathname || 
                   href === '/' || 
                   href === '' || 
                   href === '#' || 
                   text.includes('resume') || 
                   text.includes('home') ||
                   text.includes('резюме'));
        });
        
        return homeItem;
    }
    
    // Перевіряємо, чи є пункт меню "Резюме"
    function isResumeMenuItem(menuItem) {
        var link = menuItem.find('a').first();
        var text = link.text().toLowerCase();
        
        return text.includes('resume') || text.includes('home') || text.includes('резюме');
    }
    
    // Ініціалізація активного меню
    function initActiveMenu() {
        var activePage = window.location.pathname;
        
        // Знаходимо пункт "Резюме"
        var homeItem = findResumeMenuItem();
        
        if (homeItem.length && homeItem.hasClass('menu-item-has-children')) {
            // Активуємо пункт "Резюме" за замовчуванням на головній сторінці
            if (isFrontPage()) {
                homeItem.addClass('active');
                homeItem.addClass('current-menu-item');
            }
            
            // Обробка хеша в URL
            if (window.location.hash) {
                var activeHash = window.location.hash;
                var activeMenuItem = $('.nav-menu a[href="' + activeHash + '"]').parent('li');
                
                if (activeMenuItem.length) {
                    $('.nav-menu li').removeClass('current-menu-item');
                    activeMenuItem.addClass('current-menu-item');
                    
                    if (activeMenuItem.parent('ul').parent('li').length) {
                        $('.nav-menu li.menu-item-has-children.active').removeClass('active');
                        activeMenuItem.parent('ul').parent('li').addClass('active');
                    }
                }
            }
        } else {
            // Для інших сторінок виділяємо активний пункт
            $('.nav-menu a').each(function() {
                var menuItemHref = $(this).attr('href');
                if (menuItemHref && menuItemHref !== '#' && activePage.indexOf(menuItemHref) !== -1) {
                    $(this).parent('li').addClass('current-menu-item');
                }
            });
        }
    }
    
    // Анімація соціальних іконок
    function animateSocialIcons() {
        $('.social-icon').each(function(index) {
            $(this).css({
                'opacity': 0,
                'transform': 'translateY(20px)'
            });
            
            setTimeout(function(el) {
                $(el).css({
                    'transition': 'all 0.5s ease',
                    'opacity': 1,
                    'transform': 'translateY(0)'
                });
            }, 100 * index, this);
        });
    }
    
    // Запускаємо анімацію при завантаженні сторінки
    animateSocialIcons();
    
    // Запускаємо анімацію при прокручуванні до секції контактів
    $(window).on('scroll', function() {
        var contactSection = $('#contact');
        
        if (contactSection.length) {
            var scrollPosition = $(window).scrollTop() + $(window).height() * 0.8;
            var contactTop = contactSection.offset().top;
            
            if (scrollPosition > contactTop && !contactSection.hasClass('animated')) {
                contactSection.addClass('animated');
                animateSocialIcons();
            }
        }
    });
    
    // Викликаємо ініціалізацію
    initActiveMenu();
    
    // Відкриваємо пункт "Резюме" при завантаженні, якщо це головна сторінка
    if (isFrontPage()) {
        var homeItem = findResumeMenuItem();
        if (homeItem.length && homeItem.hasClass('menu-item-has-children')) {
            homeItem.addClass('active');
        }
    }
    
    // Оновлення при зміні хеша
    $(window).on('hashchange', function() {
        if (isFrontPage()) {
            initActiveMenu();
        }
    });
    
    // Окрема обробка для Portfolio, якщо виникають проблеми з навігацією
$('.nav-menu a[href="#portfolio"]').on('click', function(e) {
    e.preventDefault();
    
    if (isFrontPage()) {
        var portfolioSection = document.getElementById('portfolio');
        if (portfolioSection) {
            window.scrollTo({
                top: portfolioSection.offsetTop - 20,
                behavior: 'smooth'
            });
            
            // Закриваємо меню на мобільних
            if (window.innerWidth <= 768) {
                document.getElementById('navcheck').checked = false;
            }
            
            // Активуємо пункт меню
            $('.nav-menu li').removeClass('current-menu-item');
            $(this).parent('li').addClass('current-menu-item');
        }
    } else {
        // Якщо ми не на головній, перенаправляємо на головну з хешем portfolio
        window.location.href = '/#portfolio';
    }
});
});