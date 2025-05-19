document.addEventListener('DOMContentLoaded', function() {
  const inputField = document.querySelector('.main_answer_inp');
  const submitButton = document.querySelector('.main_btn');

  // Проверка при загрузке страницы
  checkInput();

  // Проверка при каждом вводе
  inputField.addEventListener('input', checkInput);

  function checkInput() {
    // Активируем кнопку только если есть текст
    submitButton.disabled = inputField.value.trim() === '';
  }
});



document.addEventListener('DOMContentLoaded', function() {
  const settingsBtn = document.querySelector('.main_progress_back');
  const modal = document.querySelector('.modal_back');
  const backBtn = document.querySelector('.modal_change_btn');
  const changeBtn = document.querySelector('.modal_back_btn');

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

//   // Обработчик кнопки "Изменить" (можно добавить функционал)
//   changeBtn.addEventListener('click', function() {
//     // Здесь будет код для сохранения изменений
//     alert('Изменения сохранены!');
//     closeModal();
//   });
});