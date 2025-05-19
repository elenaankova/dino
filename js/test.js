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