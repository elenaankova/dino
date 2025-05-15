const languageSelect = document.querySelector('.language_select');

// Восстановление выбранного значения при загрузке страницы
window.addEventListener('DOMContentLoaded', () => {
  const savedLanguage = localStorage.getItem('selectedLanguage');
  if (savedLanguage) {
    languageSelect.value = savedLanguage;
  }
});

// Сохранение выбранного значения при изменении
languageSelect.addEventListener('change', () => {
  localStorage.setItem('selectedLanguage', languageSelect.value);
});



function initSwitcher(containerSelector, itemSelector, leftBtnSelector, rightBtnSelector, storageKey) {
  const container = document.querySelector(containerSelector);
  const items = container.querySelectorAll(itemSelector);
  const leftBtn = container.querySelector(leftBtnSelector).parentElement;
  const rightBtn = container.querySelector(rightBtnSelector).parentElement;

  let currentIndex = localStorage.getItem(storageKey);
  currentIndex = currentIndex === null ? 0 : +currentIndex;

  function updateActive(index) {
    items.forEach((el, i) => {
      el.classList.toggle('active', i === index);
    });
    localStorage.setItem(storageKey, index);
  }

  updateActive(currentIndex);

  leftBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    updateActive(currentIndex);
  });

  rightBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % items.length;
    updateActive(currentIndex);
  });
}

// Инициализация переключателей для уровня и этапов
initSwitcher(
  '.your_level_content',
  '.your_levels_shape',
  '.level-triangle-left',
  '.level-triangle-right',
  'activeLevelIndex'
);

initSwitcher(
  '.your_stage_content',
  '.your_stages_shape',
  '.stage-triangle-left',
  '.stage-triangle-right',
  'activeStageIndex'
);
