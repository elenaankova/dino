// меня в хедере при маленьком размере экрана
    document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.header_nav_btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const closeBtn = document.querySelector('.mobile-menu-close');
    
    // Открытие меню
    menuBtn.addEventListener('click', function() {
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    
    // Закрытие меню
    function closeMenu() {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    closeBtn.addEventListener('click', closeMenu);
    mobileMenuOverlay.addEventListener('click', closeMenu);
    
    // Закрытие при нажатии на Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });
    
    // Переключение темы в мобильном меню
    const themeBtn = document.querySelector('.mobile-menu-theme');
    if (themeBtn) {
        themeBtn.addEventListener('click', function() {
            // Ваш код для переключения темы
            console.log('Смена темы из мобильного меню');
        });
    }
});