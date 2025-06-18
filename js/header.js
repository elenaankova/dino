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



// Функция для загрузки и управления модальным окном
function setupAuthModal() {
    // Проверяем, не загружено ли уже модальное окно
    if (!document.getElementById('authModal')) {
        fetch('../components/modal_auto.html')
            .then(response => response.text())
            .then(html => {
                document.body.insertAdjacentHTML('beforeend', html);
                initAuthModal();
            })
            .catch(error => console.error('Error loading modal:', error));
    } else {
        initAuthModal();
    }
}

function initAuthModal() {
    const modal = document.getElementById('authModal');
    const closeBtn = document.querySelector('.close-modal');
    
    // Глобальная функция для открытия модалки
    window.openAuthModal = function() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    };
    
    // Закрытие по кнопке
    closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    // Закрытие по клику вне модалки
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Обработка формы
    document.getElementById('authForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        // Ваша логика авторизации
        console.log('Форма отправлена');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Для кнопки входа в хедере
    document.querySelector('.header_nav_exit')?.addEventListener('click', function(e) {
        e.preventDefault();
        setupAuthModal();
        openAuthModal();
    });
    
    // Для кнопки входа в мобильном меню
    document.querySelector('.mobile-menu-link[href="../components/modal_auto.html"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        setupAuthModal();
        openAuthModal();
    });
});