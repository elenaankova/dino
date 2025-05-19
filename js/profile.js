document.addEventListener('DOMContentLoaded', function() {
  // Получаем элементы DOM
  const settingsBtn = document.querySelector('.profile_settings');
  const modal = document.querySelector('.modal_profile_settings');
  const backBtn = document.querySelector('.modal_back');
  const changeBtn = document.querySelector('.modal_change');

  // Функция открытия модального окна
  function openModal() {
    modal.style.display = 'block';
  }

  // Функция закрытия модального окна
  function closeModal() {
    modal.style.display = 'none';
  }

  // Обработчик клика по кнопке настроек
  settingsBtn.addEventListener('click', openModal);

  // Обработчик клика по кнопке "Назад"
  backBtn.addEventListener('click', closeModal);

  // Закрытие при клике вне модального окна
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Закрытие при нажатии Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'block') {
      closeModal();
    }
  });

  // Обработчик кнопки "Изменить" (можно добавить функционал)
  changeBtn.addEventListener('click', function() {
    // Здесь будет код для сохранения изменений
    alert('Изменения сохранены!');
    closeModal();
  });
});