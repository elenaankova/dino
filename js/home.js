document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.levels_btn');
  const infoContainer = document.querySelector('.levels_info');
  
  // Данные для каждого уровня (можно вынести в отдельный файл)
  const levelsData = {
    'Новичок': {
      title: 'Уровень новичок',
      text: 'Сможете рассказать о себе и поговорить на бытовые темы, где живете, работаете, рассказать о хобби, будете чувствовать себя уверенно в другой стране.',
      list: [
        '1. Как начать разговор на английском.',
        '2. Строим диалоги при знакомстве.',
        '3. To be or Not to be. Быть или не быть?',
        '4. Я, ты, он, она. Считаем на английском.',
        '5. О путешествиях, странах и национальностях.'
      ]
    },
    'Начинающий': {
      title: 'Уровень начинающий',
      text: 'Научитесь вести более сложные диалоги, понимать основные грамматические конструкции и расширите словарный запас.',
      list: [
        '1. Present Simple vs Present Continuous',
        '2. Описание повседневной деятельности',
        '3. Модальные глаголы: can, should, must',
        '4. Чтение и понимание простых текстов',
        '5. Заказ еды в ресторане'
      ]
    },
    'Продвинутый': {
      title: 'Уровень продвинутый',
      text: 'Сможете свободно участвовать в дискуссиях на профессиональные темы, понимать нюансы языка и использовать сложные грамматические конструкции.',
      list: [
        '1. Perfect Tenses: тонкости использования',
        '2. Условные предложения всех типов (Conditionals)',
        '3. Идиомы и фразовые глаголы в бизнес-среде',
        '4. Анализ новостных статей и подкастов',
        '5. Написание формальных писем и отчётов'
      ]
    },
    'Профессионал': {
      title: 'Уровень профессионал',
      text: 'Свободно ведёте деловое и повседневное общение, понимаете тонкости стиля и можете аргументированно выражать свою точку зрения.',
      list: [
         '1. Сложноподчинённые предложения',
         '2. Разговорная и письменная речь в бизнесе',
         '3. Лексика и выражения для переговоров',
         '4. Анализ и обсуждение специализированных текстов',
         '5. Подготовка и проведение деловых встреч'
      ]
    }
  };

  // Обработчик клика для кнопок
  buttons.forEach(button => {
    button.addEventListener('click', function() {
      // Удаляем активный класс у всех кнопок
      buttons.forEach(btn => btn.classList.remove('active'));
      
      // Добавляем активный класс текущей кнопке
      this.classList.add('active');
      
      // Получаем уровень из текста кнопки
      const level = this.textContent;
      
      // Обновляем контент
      updateLevelInfo(level);
    });
  });

  // Функция обновления информации
  function updateLevelInfo(level) {
    const data = levelsData[level];
    if (!data) return;

    // Плавное исчезновение
    infoContainer.style.opacity = '0';
    
    // После завершения анимации - меняем контент и показываем
    setTimeout(() => {
      document.querySelector('.levels_info_title').textContent = data.title;
      document.querySelector('.levels_info_text').textContent = data.text;
      document.querySelector('.levels_info_dop').innerHTML = data.list.map(item => `<p>${item}</p>`).join('');
      
      infoContainer.style.opacity = '1';
    }, 300);
  }

  // Инициализация - показываем данные для активной кнопки
  const activeButton = document.querySelector('.levels_btn.active');
  if (activeButton) {
    updateLevelInfo(activeButton.textContent);
  }
});