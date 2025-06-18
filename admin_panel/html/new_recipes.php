<?php
session_start();

require_once __DIR__ . '/../../connect/connect.php';

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // 1. Сохраняем основную инфу о рецепте
//     $name = mysqli_real_escape_string($connect, $_POST['name']);
//     $cooking_time = mysqli_real_escape_string($connect, $_POST['cooking_time']);
//     $calorie = mysqli_real_escape_string($connect, $_POST['calorie']);
//     $portions = mysqli_real_escape_string($connect, $_POST['portions']);
//     $caregories = mysqli_real_escape_string($connect, $_POST['caregories']);

//     // Обработка главного изображения
//     $maun_image = '';
//     if (isset($_FILES['maun_image']) && $_FILES['maun_image']['error'] == UPLOAD_ERR_OK) {
//         $upload_dir = '/image/recipe/main/';
//         if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $upload_dir)) {
//             mkdir($_SERVER['DOCUMENT_ROOT'] . $upload_dir, 0777, true);
//         }

//         $file_ext = pathinfo($_FILES['maun_image']['name'], PATHINFO_EXTENSION);
//         $new_filename = uniqid() . '.' . $file_ext;
//         $maun_image = $upload_dir . $new_filename;
//         $upload_path = $_SERVER['DOCUMENT_ROOT'] . $maun_image;

//         if (!move_uploaded_file($_FILES['maun_image']['tmp_name'], $upload_path)) {
//             $_SESSION['error'] = "Ошибка при загрузке главного изображения";
//             header("Location: " . $_SERVER['PHP_SELF']);
//             exit();
//         }
//     }

//     $description = mysqli_real_escape_string($connect, $_POST['description']);
//     $ingredients = mysqli_real_escape_string($connect, $_POST['ingredients']);

//     $created_at = date('Y-m-d H:i:s');

//     $sql_recipe = "INSERT INTO recipes (id, name, cooking_time, calorie, portions, caregories, maun_image, description, ingredients, created_at) 
//     VALUES (null, '$name', '$cooking_time', '$calorie', '$portions', '$caregories', '$maun_image', '$description', '$ingredients', '$created_at')";


//     // Перед выполнением запроса добавьте:
//     error_log("SQL запрос: " . $sql_recipe);
//     error_log("Данные: " . print_r($_POST, true));
//     error_log("Файлы: " . print_r($_FILES, true));

//     if (!mysqli_query($connect, $sql_recipe)) {
//         error_log("Ошибка MySQL: " . mysqli_error($connect));
//         $_SESSION['error'] = "Ошибка при сохранении рецепта: " . mysqli_error($connect);
//         header("Location: " . $_SERVER['PHP_SELF']);
//         exit();
//     }



//     if (!mysqli_query($connect, $sql_recipe)) {
//         $_SESSION['error'] = "Ошибка при сохранении рецепта: " . mysqli_error($connect);
//         header("Location: " . $_SERVER['PHP_SELF']);
//         exit();
//     }
//     $recipe_id = mysqli_insert_id($connect);

// // 2. Сохраняем шаги рецепта
// if (isset($_POST['step_description']) && is_array($_POST['step_description'])) {
//     foreach ($_POST['step_description'] as $i => $step_desc) {
//         $step_number = $i + 1;
//         $step_description = mysqli_real_escape_string($connect, $step_desc);

//         $image_name = '';
//         if (isset($_FILES['step_image']['name'][$i]) && $_FILES['step_image']['error'][$i] == UPLOAD_ERR_OK) {
//             $upload_dir = '/image/recipe/steps/';
//             if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $upload_dir)) {
//                 mkdir($_SERVER['DOCUMENT_ROOT'] . $upload_dir, 0777, true);
//             }

//             $file_ext = pathinfo($_FILES['step_image']['name'][$i], PATHINFO_EXTENSION);
//             $new_filename = uniqid() . '.' . $file_ext;
//             $image_name = $upload_dir . $new_filename;
//             $upload_path = $_SERVER['DOCUMENT_ROOT'] . $image_name;

//             if (!move_uploaded_file($_FILES['step_image']['tmp_name'][$i], $upload_path)) {
//                 $_SESSION['error'] = "Ошибка при загрузке изображения для шага $step_number";
//                 continue;
//             }
//         }

//         $sql_step = "INSERT INTO recipe_steps (recipe_id, step_number, description, image_path) 
//                     VALUES ('$recipe_id', '$step_number', '$step_description', '$image_name')";
//         if (!mysqli_query($connect, $sql_step)) {
//             $_SESSION['error'] = "Ошибка при сохранении шага $step_number: " . mysqli_error($connect);
//         }
//     }
// }

//     $_SESSION['success'] = "Рецепт успешно сохранен!";
//     header("Location: recipes.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/admin_panel/css/new_recipes.css" />
    <title>Овсяное печенье</title>
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
                <a href="recipes.php" class="sidebar_nav_link recipes"
                    style="text-decoration-line: underline;">Рецепты</a>
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
                <!--  -->
                <form class="modal_form" method="POST" action="more_create.php" enctype="multipart/form-data">
                    <table class="table_users">
                        <tr class="table_row">
                            <th class="table_column_1">Название</th>
                            <th><input type="text" class="table_inp" name="name"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Время при-ия</th>
                            <th><input type="text" class="table_inp" name="cooking_time"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Калор-ость</th>
                            <th><input type="text" class="table_inp" name="calorie"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Кол-во порций</th>
                            <th><input type="text" class="table_inp" name="portions"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Категория</th>
                            <th class="table_column_2">
                                <select name="caregories" id="">
                                    <option value="Торты">Торты</option>
                                    <option value="Печенья">Печенья</option>
                                    <option value="Пироги">Пироги</option>
                                    <option value="Кексы">Кексы</option>
                                    <option value="Конфеты">Конфеты</option>
                                    <option value="Хлеб">Хлеб</option>
                                </select>
                            </th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Главная фотография</th>
                            <th class="table_column_2"><input type="file" name="maun_image"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Описание</th>
                            <th><input type="text" class="table_inp" name="description"></th>
                        </tr>
                        <tr class="table_row">
                            <th class="table_column_1">Ингридиенты</th>
                            <th>
                                <p style="color: brown">Пометка: прописывать ингридиенты нужно через точку. Пример:
                                    мука. молоко 30мл. сахар.</p>
                                <input type="text" class="table_inp" name="ingredients">
                            </th>
                        <tr class="table_row">
                            <th class="table_column_1">Инструкция</th>
                            <th class="table_column_2">

                                <!-- <div class="step-container">
                                    <div class="step-item">
                                        <p>Фото 1: <input type="file" name="step_image[]"></p>
                                        <p>Описание шага 1: <textarea class="table_inp" name="step_description[]"
                                                required></textarea></p>
                                    </div>
                                </div> -->

                                <!-- <p>Фото 1: <input type="file" name="step_image" required></p>
                                <p>Описание шага 1: <input type="text" class="table_inp" name="step_description"
                                        required></p> -->
                            </th>
                        </tr>
                        <tr class="table_row">
                            <th>

                            </th>
                            <th>
                                <button class="btn" id="moreButton">Добавить ещё</button>
                            </th>
                        </tr>
                        <!-- <tr class="table_row">
                        <th class="table_column_1">Кол-во отзывов</th>
                        <th><input type="text" class="table_inp"></th>
                    </tr> -->
                    </table>

                    <p class="error"></p>
                    <div class="container_review_buttons">
                        <button class="btn"><a href="recipes.html">Вернуться</a></button>
                        <button class="btn" id="saveButton">Сохранить</button>
                        <button class="btn" id="editButton" style="display: none;">Изменить</button>
                    </div>
                </form>
            </section>
        </section>
    </div>


    <script>
        const moreButton = document.querySelector('#moreButton');
        const saveButton = document.querySelector('#saveButton');
        const editButton = document.querySelector('#editButton');
        const inputs = document.querySelectorAll('.table_inp');
        let counter = 2;

        moreButton.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.className = 'table_row';

            const newTh = document.createElement('th');
            newTh.className = 'table_column_2';

            newTh.innerHTML = `
            <p>Фото ${counter} : <input type="file" name="step_image[]" required></p>
            <p>Описание шага ${counter} : <input type="text" class="table_inp" name="step_description[]" required></p>`;

            newRow.appendChild(document.createElement('th')); // Пустая ячейка слева
            newRow.appendChild(newTh);
            moreButton.closest('tr').before(newRow);

            counter++;
        });

        saveButton.addEventListener('click', (e) => {
            e.preventDefault(); // Предотвращаем стандартное поведение формы

            // Проверяем, все ли обязательные поля заполнены
            let allFilled = true;
            const requiredInputs = document.querySelectorAll('.table_inp, input[type="file"]');

            requiredInputs.forEach(input => {
                if (input.type === 'file') {
                    if (!input.files || input.files.length === 0) {
                        allFilled = false;
                        input.style.border = '1px solid red'; // Подсветка ошибки
                    }
                } else if (!input.value.trim()) {
                    allFilled = false;
                    input.style.border = '1px solid red'; // Подсветка ошибки
                } else {
                    input.style.border = ''; // Убираем подсветку, если поле заполнено
                }
            });

            if (allFilled) {
                // Если все поля заполнены, отправляем форму
                document.querySelector('.modal_form').submit();
            } else {
                // Показываем сообщение об ошибке
                let error = document.querySelector('.error');
                if (!error) {
                    error = document.createElement('div');
                    error.className = 'error';
                    document.querySelector('.container_review').prepend(error);
                }
                error.textContent = "Не все обязательные поля заполнены!";
                error.style.color = 'red';
                error.style.marginBottom = '20px';
            }
        });

        editButton.addEventListener('click', function () {
            editableCells.forEach(cell => {
                const content = cell.innerHTML;

                // Извлекаем только текст без HTML-тегов
                const textContent = extractTextFromHTML(content);

                if (content.includes('<p>') || content.includes('<br>') || textContent.length > 50) {
                    const textarea = document.createElement('textarea');
                    textarea.className = 'editable editable-textarea';
                    textarea.value = textContent;
                    cell.innerHTML = '';
                    cell.appendChild(textarea);
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

            saveButton.style.display = "block";
            editButton.style.display = "none";
        })


        editButton.addEventListener('click', function () {
            // Находим все сохраненные значения
            const savedValues = document.querySelectorAll('.saved-value, .saved-file');

            savedValues.forEach(savedElement => {
                // Определяем тип поля (текст или файл)
                const isFile = savedElement.classList.contains('saved-file');

                // Создаем соответствующий input
                let input;
                if (isFile) {
                    input = document.createElement('input');
                    input.type = 'file';
                    // Для файловых полей нельзя установить предыдущее значение
                } else {
                    input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'table_inp';
                    input.value = savedElement.textContent;
                }

                // Заменяем <p> на input
                savedElement.replaceWith(input);
            });

            // Переключаем кнопки
            saveButton.style.display = 'block';
            editButton.style.display = 'none';
        });



        // это доп обработчик на форму для двойной проверки на ошибки
        document.querySelector('.modal_form').addEventListener('submit', function (e) {
            let allFilled = true;
            const requiredInputs = document.querySelectorAll('.table_inp, input[type="file"]');

            requiredInputs.forEach(input => {
                if (input.type === 'file' && (!input.files || input.files.length === 0)) {
                    allFilled = false;
                } else if (!input.value.trim()) {
                    allFilled = false;
                }
            });

            if (!allFilled) {
                e.preventDefault();

            }
        });

    </script>
</body>

</html>