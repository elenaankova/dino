<?php

session_start();

require_once __DIR__ . '/../../connect/connect.php';



$sql_count_recipes = "SELECT COUNT(*) as total_recipes FROM recipes";
$result = mysqli_query($connect, $sql_count_recipes);
$row = mysqli_fetch_assoc($result);
$total_recipes = $row['total_recipes']; // Получаем общее количество пользователей


$sql_month_recipes = "SELECT COUNT(*) as month_recipes FROM recipes WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$result_month = mysqli_query($connect, $sql_month_recipes);
$row_month = mysqli_fetch_assoc($result_month);
$month_recipes = $row_month['month_recipes'];


$sql = "SELECT 
           ROW_NUMBER() OVER (ORDER BY id) AS row_num,
           id,
           name,
           cooking_time,
           calorie,
           portions,
           caregories,
           maun_image,
           created_at
        FROM recipes";
$result = mysqli_query($connect, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/recipes.css" />
  <title>Рецепты</title>
</head>

<body>
  <div class="container">
    <section class="sidebar">
      <a class="header_logo">
        <img src="/image/лого.svg" class="header_logo_img" />
      </a>

      <div class="sidebar_nav">
        <a href="users.php" class="sidebar_nav_link users">Пользователи</a>
        <a href="reviews.php" class="sidebar_nav_link reviews">Отзывы</a>
        <a href="recipes.php" class="sidebar_nav_link" style="text-decoration-line: underline;">Рецепты</a>
        <a href="blog.php" class="sidebar_nav_link">Блоги</a>

        <buttom class="nav_btn"><a href="new_recipes.php">Создать рецепт</a></buttom>
      </div>
    </section>

    <section class="contant">
      <section class="search">
        <div class="find">
          <input class="search_inp" type="text" placeholder="Поиск.." />
          <button class="search_btn">Поиск</button>
        </div>
      </section>

      <section class="container_review">
        <div class="container_reviews_info">
          <h1 class="container_title">Рецепты</h1>
          <div class="review">
            <p class="all_review">Общее кол-во: <?= $total_recipes ?></p>
            <p class="for_month_review">Кол-во добавленных за посл-ий месяц: <?= $month_recipes ?></p>

            <button class="filter-mobile-toggle">Фильтры</button>
            <div class="catalog_filter">
              <div class="catalog_filter_content">
                <button class="close-filters">×</button>
                <h1 class="catalog_filter_title">Фильтр</h1>

                <div class="catalog_filter_column"> <!-- ˅ -->
                  <p class="calalog_fil_col_title">⮟ Категории</p>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox1" id="wr10" name="wr">
                    <label for="wr10"></label>
                    <p class="paragraph_text">Торты</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox11" id="wr11" name="wr">
                    <label for="wr11"></label>
                    <p class="paragraph_text">Печенье</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox12" id="wr12" name="wr">
                    <label for="wr12"></label>
                    <p class="paragraph_text">Пироги</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox13" id="wr13" name="wr">
                    <label for="wr13"></label>
                    <p class="paragraph_text">Конфеты</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox14" id="wr14" name="wr">
                    <label for="wr14"></label>
                    <p class="paragraph_text">Хлеб</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox15" id="wr15" name="wr">
                    <label for="wr15"></label>
                    <p class="paragraph_text">Кексы</p>
                  </div>
                </div>

                <div class="catalog_filter_column">
                  <p class="calalog_fil_col_title">⮟ По дате</p>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox16" id="wr16" name="wr">
                    <label for="wr16"></label>
                    <p class="paragraph_text">Недавние</p>
                  </div>
                  <div class="paragraph">
                    <input type="checkbox" class="wr-checkbox17" id="wr17" name="wr">
                    <label for="wr17"></label>
                    <p class="paragraph_text">Старые</p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <table class="table_users">
          <tr class="table_row_titles">
            <th>№</th>
            <th>Название</th>
            <th>Время при-ия</th>
            <th>Калор-ость</th>
            <th>Кол-во порций</th>
            <th>Категория</th>
            <th>Фотография</th>
            <th> </th>
          </tr>

          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row" date-time="<?= $row['created_at'] ?>">

              <td class="table_cell"><?= $row['row_num'] ?></td>
              <td class="table_cell name_recipe"><?= $row['name'] ?></td>
              <td class="table_cell"><?= $row['cooking_time'] ?></td>
              <td class="table_cell"><?= $row['calorie'] ?></td>
              <td class="table_cell"><?= $row['portions'] ?></td>
              <td class="row_categories table_cell"><?= $row['caregories'] ?></td>
              <td class="table_cell"><?= basename($row['maun_image']) ?></td>
              <td class="more table_cell"><button class="more_btn"><a
                    href="more.php?id=<?= $row['id'] ?>">Подробнее</a></button></td>
            </tr>
          <?php endwhile; ?>



          <!-- <tr class="table_row">
            <td>1</td>
            <td>Овсяное печенье</td>
            <td>2:20 ч</td>
            <td>451 ккал</td>
            <td>4</td>
            <td>Печенье</td>
            <td>
              <p>image 43</p>
            </td>
            
          </tr> -->

          <!-- Другие строки таблицы -->
        </table>


      </section>
    </section>
  </div>
  <script>
//  ПОИСК 
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search_inp');
    const searchBtn = document.querySelector('.search_btn');
    const tableRows = document.querySelectorAll('.table_row:not(.table_row_titles)');
    
    // Функция нормализации текста
    function normalizeText(text) {
        return text ? text.toString().toLowerCase().trim() : '';
    }

    // Функция поиска
    function performSearch() {
        const query = normalizeText(searchInput.value);
        
        tableRows.forEach(row => {
            const nameCell = row.querySelector('.name_recipe');
            const nameText = normalizeText(nameCell?.textContent);
            
            const categoryCell = row.querySelector('.row_categories');
            const categoryText = normalizeText(categoryCell?.textContent);
            
            // Проверяем совпадение в названии или категории
            const nameMatch = nameText.includes(query);
            const categoryMatch = categoryText.includes(query);
            
            row.style.display = (nameMatch || categoryMatch) ? '' : 'none';
        });
    }

    // Функция сброса
    function resetSearch() {
        tableRows.forEach(row => {
            row.style.display = '';
        });
    }

    // Обработчики событий
    const handleSearch = () => {
        if (searchInput.value.trim() === '') {
            resetSearch();
        } else {
            performSearch();
        }
    };

    searchBtn.addEventListener('click', handleSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') handleSearch();
    });
    searchInput.addEventListener('input', handleSearch);
});










    document.addEventListener('DOMContentLoaded', function () {
      // Получаем все чекбоксы с классами, начинающимися на "wr-checkbox"
      const checkboxes = document.querySelectorAll('input[type="checkbox"][class^="wr-checkbox"]');

      // Загружаем сохраненные состояния или создаем новый объект
      const savedStates = JSON.parse(localStorage.getItem('checkboxStates')) || {};

      // Применяем сохраненные состояния
      checkboxes.forEach(checkbox => {
        // Используем комбинацию class + id как уникальный ключ
        const storageKey = `${checkbox.className}_${checkbox.id}`;

        if (savedStates[storageKey] !== undefined) {
          checkbox.checked = savedStates[storageKey];
        }

        // Добавляем обработчик изменений
        checkbox.addEventListener('change', function () {
          // Обновляем состояние для этого конкретного чекбокса
          savedStates[storageKey] = this.checked;

          // Сохраняем обновленные состояния
          localStorage.setItem('checkboxStates', JSON.stringify(savedStates));

          console.log(`Сохранено состояние для ${storageKey}:`, this.checked);
        });
      });
    });






    // открытие и закрытие фильтра
    document.querySelector('.filter-mobile-toggle').addEventListener('click', function () {
      document.querySelector('.catalog_filter_content').classList.toggle('active');
    });

    // Закрытие при клике вне фильтра (опционально)
    document.addEventListener('click', function (e) {
      const filter = document.querySelector('.catalog_filter_content');
      const toggleBtn = document.querySelector('.filter-mobile-toggle');

      if (!filter.contains(e.target) && e.target !== toggleBtn) {
        filter.classList.remove('active');
      }
    });

    // Открытие фильтров
    document.querySelector('.filter-mobile-toggle').addEventListener('click', function () {
      document.querySelector('.catalog_filter_content').classList.add('active');
      document.body.classList.add('menu-open');
    });

    // Закрытие фильтров
    function closeFilters() {
      document.querySelector('.catalog_filter_content').classList.remove('active');
      document.body.classList.remove('menu-open');
    }

    document.querySelector('.close-filters').addEventListener('click', closeFilters);

    // работа фильтра
    document.addEventListener('DOMContentLoaded', function () {
      // Элементы DOM
      const recipeRows = document.querySelectorAll('.table_row');
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      // const searchInput = document.querySelector('.search_inp');
      // const searchBtn = document.querySelector('.search_btn');

      // Функция для проверки, была ли дата в последний месяц
      function isWithinLastMonth(dateString) {
        try {
          const recipeDate = new Date(dateString);
          const now = new Date();
          const oneMonthAgo = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());
          return recipeDate >= oneMonthAgo;
        } catch (e) {
          console.error("Ошибка при обработке даты:", dateString, e);
          return false;
        }
      }

      // Функция для проверки, является ли дата старой (более месяца)
      function isOldDate(dateString) {
        return !isWithinLastMonth(dateString);
      }

      // Функция проверки активных фильтров
      function hasActiveFilters() {
        const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
        return checkedBoxes.length > 0 || searchInput.value.trim() !== '';
      }

      // Функция фильтрации по категориям
      function filterByCategory(row, selectedCategories) {
        if (selectedCategories.length === 0) return true;

        const categoryElement = row.querySelector('.row_categories');
        if (!categoryElement) return false;

        const rowCategory = categoryElement.textContent.trim().toLowerCase();
        return selectedCategories.some(cat => rowCategory.includes(cat));
      }

      // Функция фильтрации по времени (неделя/старые)
      function filterByTime(row, selectedTimes) {
        if (selectedTimes.length === 0) return true;

        const dateTime = row.getAttribute('date-time');
        if (!dateTime) return false;

        return selectedTimes.some(timeFilter => {
          if (timeFilter === 'недавние') {
            return isWithinLastMonth(dateTime); // Используем месяц вместо недели
          } else if (timeFilter === 'старые') {
            return isOldDate(dateTime);
          }
          return false;
        });
      }

      // Функция фильтрации по поисковому запросу
      function filterBySearch(row, searchText) {
        if (!searchText) return true;

        const name = row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
        return name.includes(searchText.toLowerCase());
      }

      // Основная функция фильтрации
      function applyFilters() {
        // 1. Получаем выбранные категории (в нижнем регистре)
        const selectedCategories = Array.from(
          document.querySelectorAll('.catalog_filter_column:nth-of-type(1) input[type="checkbox"]:checked')
        ).map(checkbox => {
          return checkbox.nextElementSibling.nextElementSibling.textContent.trim().toLowerCase();
        });

        // 2. Получаем выбранное время
        const selectedTimes = Array.from(
          document.querySelectorAll('.catalog_filter_column:nth-of-type(2) input[type="checkbox"]:checked')
        ).map(checkbox => {
          return checkbox.nextElementSibling.nextElementSibling.textContent.trim().toLowerCase();
        });

        // // 3. Получаем поисковый запрос
        // const searchText = searchInput.value.trim();

        // Фильтруем строки таблицы
        recipeRows.forEach(row => {
          const matchesCategory = filterByCategory(row, selectedCategories);
          const matchesTime = filterByTime(row, selectedTimes);
          // const matchesSearch = filterBySearch(row, searchText);

          if (matchesCategory && matchesTime) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }

      // Обработчики событий
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
      });

      // searchBtn.addEventListener('click', applyFilters);
      // searchInput.addEventListener('keyup', function (e) {
      //   if (e.key === 'Enter') {
      //     applyFilters();
      //   }
      // });

      // Инициализация
      applyFilters();
    });


  </script>

</body>

</html>