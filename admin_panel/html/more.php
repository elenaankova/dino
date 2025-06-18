<?php

session_start();

require_once __DIR__ . '/../../connect/connect.php';
// Получаем ID рецепта из URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Защита от SQL-инъекций
if ($recipe_id <= 0) {
  die("Неверный ID рецепта");
}


$sql = "SELECT r.*, rs.step_number, rs.description as step_description, rs.image_path 
        FROM recipes r
        LEFT JOIN recipe_steps rs ON r.id = rs.recipe_id
        WHERE r.id = ?
        ORDER BY rs.step_number";




$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $recipe_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);




// Получаем данные
$recipe = mysqli_fetch_assoc($result);
$steps = [];

if ($recipe) {
  do {
    if (!empty($recipe['step_number'])) {
      $steps[] = [
        'number' => $recipe['step_number'],
        'description' => $recipe['step_description'],
        'image_path' => $recipe['image_path']
      ];
    }
  } while ($recipe = mysqli_fetch_assoc($result));

  // Возвращаем указатель на первую строку
  mysqli_data_seek($result, 0);
  $recipe = mysqli_fetch_assoc($result);
}

if (!$recipe) {
  die("Рецепт не найден");
}


// Получаем возможные значения ENUM для caregories
$enum_sql = "SHOW COLUMNS FROM recipes LIKE 'caregories'";
$enum_result = mysqli_query($connect, $enum_sql);
$enum_row = mysqli_fetch_assoc($enum_result);
preg_match("/^enum\(\'(.*)\'\)$/", $enum_row['Type'], $matches);
$enum_values = explode("','", $matches[1]);

// var_dump($steps);
// var_dump($recipe);
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/new_recipes.css" />
  <title><?= htmlspecialchars($recipe['name']) ?></title>
  <style>

.step-container-description {
    width: 500px; 
    height: 290px;
    margin-bottom: 40px;
    word-wrap: break-word; 
    white-space: normal;
    line-height: 1.4;
}
.editable-textarea-step {
    width: 100%;
  min-height: 100px;
  padding: 5; /* Убираем внутренние отступы */
  margin: 0; /* Убираем внешние отступы */
  border: 1px solid #ddd;
  border-radius: 4px;
  resize: vertical;
  text-align: left; /* Выравнивание текста слева */
  line-height: 1.4; /* Межстрочный интервал */
  font-family: inherit; /* Наследуем шрифт */
  box-sizing: border-box; /* Чтобы padding не влиял на ширину */
}
  </style>
</head>

<body>
  <div class="container">
    <section class="sidebar">
      <a class="header_logo">
        <img src="/image/лого.svg" class="header_logo_img" />
      </a>

      <div class="sidebar_nav">
        <a href="users.html" class="sidebar_nav_link users">Пользователи</a>
        <a href="reviews.php" class="sidebar_nav_link reviews">Отзывы</a>
        <a href="recipes.php" class="sidebar_nav_link" style="text-decoration-line: underline;">Рецепты</a>
        <a href="blog.php" class="sidebar_nav_link">Блоги</a>
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
          <h1 class="container_title"><?= htmlspecialchars($recipe['name']) ?></h1>
        </div>

        <table class="table_users">
          <tr class="table_row">
            <th class="table_column_1">id</th>
            <th class="table_column_1"><?= htmlspecialchars($recipe['id']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Название</th>
            <th class="table_column_2" data-field="name"><?= htmlspecialchars($recipe['name']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Время при-ия</th>
            <th class="table_column_2" data-field="cooking_time"><?= htmlspecialchars($recipe['cooking_time']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Калор-ость</th>
            <th class="table_column_2" data-field="calorie"><?= htmlspecialchars($recipe['calorie']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Кол-во порций</th>
            <th class="table_column_2" data-field="portions"><?= htmlspecialchars($recipe['portions']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Категория</th>
            <th class="table_column_2" data-field="caregories"><?= htmlspecialchars($recipe['caregories']) ?></th>
          </tr>

          <tr class="table_row">
            <th class="table_column_1">Главная фотография</th>
            <th class="table_column_2" data-field="maun_image"><img src="<?= htmlspecialchars($recipe['maun_image']) ?>"></th>
          </tr>

          <tr class="table_row">
            <th class="table_column_1">Описание</th>
            <th class="table_column_2" data-field="description"><?= htmlspecialchars($recipe['description']) ?></th>
          </tr>
          <tr class="table_row">
            <th class="table_column_1">Ингридиенты</th>
            <th class="table_column_2" data-field="ingredients"><?= htmlspecialchars($recipe['ingredients']) ?></th>
          </tr>




<?php if (!empty($steps)): ?>
    <tr class="table_row">
        <th class="table_column_1" style="vertical-align: top;">Инструкция</th>
        
        <!-- Столбец для изображений шагов -->
        <th class="table_column_3" data-field="steps_images">
            <?php foreach ($steps as $step): ?>
                <div class="step-container" data-step="<?= $step['number'] ?>">
                    <div class="step-image-container" data-field="step_image_<?= $step['number'] ?>">
                        <?php if ($step['image_path']): ?>
                            <div class="current-image-wrapper">
                                <p class="current-image-info">
                                    <strong>Фото для шага <?= htmlspecialchars($step['number']) ?>:</strong><br>
                                    <img src="/<?= htmlspecialchars($step['image_path']) ?>" class="step-preview-image" 
                                         onerror="this.style.display='none'" style="width:400px">
                                    
                                </p>
                            </div>
                        <?php else: ?>
                            <p class="current-image-info">Нет фото для шага <?= htmlspecialchars($step['number']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </th>
        
<!-- Столбец для описаний шагов -->
<th class="table_column_4" data-field="steps_descriptions">
    <?php foreach ($steps as $step): ?>
        <div class="step-container-description" data-step="<?= $step['number'] ?>">
            <!-- Перенесём описание в начало контейнера -->
            <div class="step-description-container" data-field="step_description_<?= $step['number'] ?>">
                <?= htmlspecialchars($step['description']) ?>
            </div>
            
            <!-- Если у вас есть другие элементы шага (например, изображение),
                 они будут идти после описания -->
            <!-- <?php if (!empty($step['image'])): ?>
                <div class="step-image-container">
                    <img src="<?= $step['image'] ?>" alt="Шаг <?= $step['number'] ?>">
                </div>
            <?php endif; ?> -->
        </div>
    <?php endforeach; ?>
</th>
    </tr>
<?php endif; ?>




          
          <tr class="table_row">
            <th class="table_column_1">Кол-во отзывов</th>
            <th class="table_column_1"><?= htmlspecialchars($recipe['number_review']) ?></th>
          </tr>

          <tr class="table_row">
            <th class="table_column_1">Дата создания</th>
            <th class="table_column_1" data-field="created_at"><?= htmlspecialchars($recipe['created_at']) ?></th>
          </tr>
        </table>

        <div class="container_review_buttons">
          <button class="btn"><a href="recipes.php">Вернуться</a></button>
          <button class="btn" id="editButton">Изменить</button>
          <button class="btn" id="saveButton" style="display: none;">Сохранить</button>
          <button class="btn" id="deleteButton">Удалить</button>
        </div>
      </section>
    </section>
  </div>

  <script>
    const editButton = document.querySelector('#editButton');
    const saveButton = document.querySelector('#saveButton');
    const deleteButton = document.querySelector('#deleteButton');
    const editableCells = document.querySelectorAll('.table_column_2');


    function extractTextFromHTML(html) {
      const temp = document.createElement('div');
      temp.innerHTML = html;
      return temp.textContent || temp.innerText || '';
    }


    editButton.addEventListener('click', function () {
      editableCells.forEach(cell => {
        const content = cell.innerHTML;
        const textContent = extractTextFromHTML(content);
        const fieldName = cell.dataset.field;

        // Пропускаем обработку, если это контейнер для шагов
        if (fieldName === 'steps_images' || fieldName === 'steps_descriptions') {
            return;
        }

        // Для поля "Категория" создаем select с ENUM значениями
        if (fieldName === 'caregories') {
          const select = document.createElement('select');
          select.className = 'editable editable-select';

          // Получаем текущее значение
          const currentValue = textContent.trim();

          // Добавляем все ENUM значения в select
          function addOption(select, value, currentValue) {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            if (value === currentValue) {
              option.selected = true;
            }
            select.appendChild(option);
          }

          // Для поля "главное фото" создаем input с типом file
          if (fieldName === 'maun_image') {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.className = 'editable editable-file';
            fileInput.accept = 'image/*'; // Разрешаем только изображения

            // Сохраняем оригинальное значение для возможного отката
            fileInput.dataset.originalValue = textContent;


            const container = document.createElement('div');
            container.appendChild(fileInput);
            container.appendChild(fileNameDisplay);

            // Обновляем отображение при выборе файла
            fileInput.addEventListener('change', function () {
              fileNameDisplay.textContent = this.files[0]?.name || fileInput.dataset.originalValue;
            });

            cell.innerHTML = '';
            cell.appendChild(container);
          }

          <?php foreach ($enum_values as $value): ?>
            addOption(select, "<?= htmlspecialchars($value) ?>", currentValue);
          <?php endforeach; ?>

          cell.innerHTML = '';
          cell.appendChild(select);
        }
        else if (content.includes('<p>') || content.includes('<br>') || textContent.length > 50) {
          const textarea = document.createElement('textarea');
          textarea.className = 'editable editable-textarea';
          textarea.value = textContent;
          cell.innerHTML = '';
          cell.appendChild(textarea);
        }
        else if (fieldName === 'maun_image') {
          const fileInput = document.createElement('input');
          fileInput.type = 'file';
          fileInput.name = 'maun_image'; // Важно: должно совпадать с именем в PHP
          fileInput.className = 'editable editable-file';
          fileInput.accept = 'image/*';

          // Показываем текущее имя файла
          const currentFile = document.createElement('div');
          currentFile.textContent = 'Текущий файл: ' + (textContent || 'нет файла');
          currentFile.style.marginTop = '10px';

          const container = document.createElement('div');
          container.appendChild(fileInput);
          container.appendChild(currentFile);

          cell.innerHTML = '';
          cell.appendChild(container);
        }
        else {
          const input = document.createElement('input');
          input.className = 'editable';
          input.type = 'text';
          input.value = textContent;
          cell.innerHTML = '';
          cell.appendChild(input);
        }

      });

 editableCells.forEach(cell => {
        const content = cell.innerHTML;
        const textContent = extractTextFromHTML(content);
        const fieldName = cell.dataset.field;

        // Пропускаем обработку, если это контейнер для шагов
        if (fieldName === 'steps_images' || fieldName === 'steps_descriptions') {
            return;
        }

        // Остальная логика обработки обычных полей...
    });

     // Обработка изображений шагов
    document.querySelectorAll('.step-image-container').forEach(container => {
        const stepNumber = container.dataset.field.replace('step_image_', '');
        
        // Создаем контейнер для загрузки нового файла
        const fileWrapper = document.createElement('div');
        fileWrapper.className = 'file-input-wrapper';
        
        const label = document.createElement('label');
        label.textContent = 'Загрузить новое фото:';
        
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.name = `step_image_${stepNumber}`;
        fileInput.className = 'editable-file-step';
        fileInput.accept = 'image/*';
        
        // Добавляем превью нового файла
        const newPreview = document.createElement('div');
        newPreview.className = 'new-file-preview';
        newPreview.style.display = 'none';
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    newPreview.innerHTML = `
                        <p><strong>Новое фото:</strong></p>
                        <img src="${e.target.result}" class="step-preview-image">
                        <p class="image-path">${this.files[0].name}</p>
                    `;
                    newPreview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                newPreview.style.display = 'none';
            }
        });
        
        fileWrapper.appendChild(label);
        fileWrapper.appendChild(fileInput);
        fileWrapper.appendChild(newPreview);
        
        // Добавляем в контейнер
        container.appendChild(fileWrapper);
    });



    // Добавляем обработку описаний шагов
    document.querySelectorAll('.step-description-container').forEach(container => {
        const textContent = extractTextFromHTML(container.innerHTML);
        const textarea = document.createElement('textarea');
        textarea.style.textAlign = 'left'; // Явное выравнивание
        textarea.style.direction = 'ltr'; // Направление текста слева-направо
        textarea.className = 'editable editable-textarea-step';
        textarea.value = textContent;
        
        // Сохраняем оригинальное значение для возможного отката
        textarea.dataset.originalValue = textContent;
        textarea.dataset.field = container.dataset.field;
        
        container.innerHTML = '';
        container.appendChild(textarea);
    });

    saveButton.style.display = "block";
    editButton.style.display = "none";
});

saveButton.addEventListener('click', function() {
    const formData = new FormData();
    formData.append('id', <?= $recipe_id ?>);

    // Добавляем основные поля рецепта
    document.querySelectorAll('.editable').forEach(input => {
        if (input.classList.contains('editable-textarea') || 
            input.classList.contains('editable-select') ||
            input.classList.contains('editable')) {
            const fieldName = input.closest('[data-field]').dataset.field;
            formData.append(fieldName, input.value);
        }
    });

    // Добавляем описания шагов
    document.querySelectorAll('.editable-textarea-step').forEach(textarea => {
        const fieldName = textarea.dataset.field;
        formData.append(fieldName, textarea.value);
    });

    // Добавляем главное изображение, если выбрано
    const mainImageInput = document.querySelector('input[name="maun_image"]');
    if (mainImageInput && mainImageInput.files[0]) {
        formData.append('maun_image', mainImageInput.files[0]);
    }

    // Добавляем изображения шагов
    document.querySelectorAll('.editable-file-step').forEach(input => {
        if (input.files[0]) {
            formData.append(input.name, input.files[0]);
        }
    });

    // Отправка данных
    fetch('more_edit.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Изменения сохранены успешно!');
            location.reload();
        } else {
            alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ошибка соединения: ' + error.message);
    });
});


    deleteButton.addEventListener('click', function () {
      if (confirm('Вы точно хотите удалить этот рецепт? Это действие нельзя отменить.')) {
        fetch('more_delete.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            id: <?= $recipe_id ?>
          })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Рецепт успешно удален!');
              window.location.href = 'recipes.php'; // Перенаправляем на список рецептов
            } else {
              alert('Ошибка: ' + (data.message || 'Не удалось удалить рецепт'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Ошибка соединения');
          });
      }
    });


    // При загрузке страницы отмечаем ячейки, которые содержат абзацы
    editableCells.forEach(cell => {
      if (cell.innerHTML.includes('<p>')) {
        cell.dataset.hasParagraphs = 'true';
      }
    });
  </script>
</body>

</html>