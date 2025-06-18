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







// Функция для показа модального окна
function showAuthModal(type = 'auth') {
    // Сохраняем позицию скролла перед открытием модалки
    const scrollY = window.scrollY;
    
    // Удаляем старое модальное окно, если есть
    const oldModal = document.getElementById('authModal');
    if (oldModal) oldModal.remove();
    
    // HTML для модального окна авторизации
    const authHTML = `
    <div id="authModal" class="modal">
        <div class="modal-content">
            <div class="modal-conteiner-dino">
                <img class="dino-img" src="../image/дино_веселый.png" alt="">
            </div>
            <div class="modal-conteiner-form">
                <a href="#" class="close-modal">×</a>
                <div class="reg-auto">
                    <a class="akk switch-modal" data-type="reg">Зарегистрироваться</a>
                    <a class="akk active">Войти</a>
                </div>
                <form class="modal_form" action="../connect/auto.php" method="POST">
                    <h1 class="title">С возвращением!</h1>
                    <input class="input" type="email" placeholder="Почта" name="email">
                    <input class="input" type="password" placeholder="Пароль" name="password">
                    <button class="btn">Войти</button>
                </form>
            </div>
        </div>
    </div>
    `;
    
    // HTML для модального окна регистрации
    const regHTML = `
    <div id="authModal" class="modal">
        <div class="modal-content">
            <div class="modal-conteiner-dino">
                <img class="dino-img" src="../image/дино.png" alt="">
            </div>
            <div class="modal-conteiner-form">
                <a href="#" class="close-modal">×</a>
                <div class="reg-auto">
                    <a class="akk active">Зарегистрироваться</a>
                    <a class="akk switch-modal" data-type="auth">Войти</a>
                </div>
                <form class="modal_form" action="../connect/reg.php" method="POST">
                    <h1 class="title">Заполните форму, чтобы начать обучение языков с нами</h1>
                    <input class="input" type="text" placeholder="Имя" name="name">
                    <input class="input" type="email" placeholder="Почта" name="email">
                    <input class="input" type="password" placeholder="Пароль" name="password">
                    <input class="input" type="password" placeholder="Повторите пароль" name="password_confirm">
                    <div class="approval">
                        <input type="checkbox" class="approval_inp" id="approvalCheck" required>
                        <label for="approvalCheck" class="custom-checkbox"></label>
                        <p class="approval_text">Нажимая на кнопку, вы даете согласие на обработку
                            персональных данных и
                            соглашаетесь <a href="../html/politika.html" style="text-decoration-line: underline;">c политикой конфиденциальности.</a></p>
                    </div>
                    <button class="btn">Присоединиться</button>
                </form>
            </div>
        </div>
    </div>
    `;
    
    // Добавляем выбранное модальное окно в DOM
    document.body.insertAdjacentHTML('beforeend', type === 'auth' ? authHTML : regHTML);
    
    // Инициализируем обработчики событий
    initAuthModal();
    
    // Показываем модальное окно
    const modal = document.getElementById('authModal');
    modal.style.display = 'flex';
    
    // Блокируем скролл страницы и фиксируем позицию
    // document.body.style.overflow = 'hidden';
    // document.body.style.position = 'fixed';
    // document.body.style.top = `-${scrollY}px`;
    // document.body.style.width = '100%';
}

// Функция закрытия модального окна
function closeModal() {
    const modal = document.getElementById('authModal');
    if (!modal) return;
    
    // Восстанавливаем скролл страницы
    const scrollY = Math.abs(parseInt(document.body.style.top || '0'));
    document.body.style.overflow = '';
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    
    // Скрываем модальное окно
    modal.style.display = 'none';
    
    // Восстанавливаем позицию скролла
    window.scrollTo(0, scrollY);
}

// Функция для инициализации обработчиков событий
function initAuthModal() {
    const modal = document.getElementById('authModal');
    const closeBtn = document.querySelector('.close-modal');
    
    // Закрытие по кнопке
    closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        closeModal();
    });
    
    // Закрытие по клику вне модалки
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Обработка формы авторизации
    document.querySelector('.modal_form[action="../connect/auto.php"]')?.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Форма авторизации отправлена');
        closeModal();
    });
    
    // Обработка формы регистрации
    document.querySelector('.modal_form[action="../connect/reg.php"]')?.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Форма регистрации отправлена');
        closeModal();
    });
    
    // Обработка переключения между окнами
    document.querySelectorAll('.switch-modal').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            showAuthModal(this.dataset.type);
        });
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Для кнопки входа в хедере
    document.querySelector('.header_nav_exit')?.addEventListener('click', function(e) {
        e.preventDefault();
        showAuthModal('auth');
    });
    
    // Для кнопки входа в мобильном меню
    document.querySelector('.mobile-menu-link[href="#"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        showAuthModal('auth');
    });
});