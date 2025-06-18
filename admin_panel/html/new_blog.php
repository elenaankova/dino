<?php
session_start();

require_once __DIR__ . '/../../connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Сохраняем основной блог
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $number_image = isset($_FILES['image']) ? count($_FILES['image']['name']) : 0;
    $created_at = date('Y-m-d H:i:s'); // Добавляем текущую дату и время
    
    $sql_blog = "INSERT INTO blogs (name, number_image, created_at) VALUES ('$name', '$number_image', '$created_at')";
    if (!mysqli_query($connect, $sql_blog)) {
        $_SESSION['error'] = "Ошибка при сохранении блога: " . mysqli_error($connect);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    $blog_id = mysqli_insert_id($connect);
    
    // 2. Сохраняем шаги блога
    if (isset($_POST['title']) && is_array($_POST['title'])) {
        for ($i = 0; $i < count($_POST['title']); $i++) {
            $title = mysqli_real_escape_string($connect, $_POST['title'][$i]);
            $description = mysqli_real_escape_string($connect, $_POST['description'][$i]);
            $step_number = $i + 1;
            
            $image_name = '';
            if (!empty($_FILES['image']['name'][$i])) {
                $upload_dir = '/image/blog/';
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $upload_dir)) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $upload_dir, 0777, true);
                }
                
                $file_ext = pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '.' . $file_ext;
                $image_name = $upload_dir . $new_filename;
                $upload_path = $_SERVER['DOCUMENT_ROOT'] . $image_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $upload_path)) {
                    // Файл успешно загружен
                } else {
                    $_SESSION['error'] = "Ошибка при загрузке изображения для шага $step_number";
                    continue;
                }
            }
            
            $sql_step = "INSERT INTO blog_steps (blog_id, step_number, title, image, description) 
                        VALUES ('$blog_id', '$step_number', '$title', '$image_name', '$description')";
            if (!mysqli_query($connect, $sql_step)) {
                $_SESSION['error'] = "Ошибка при сохранении шага $step_number: " . mysqli_error($connect);
            }
        }
    }
    
    $_SESSION['success'] = "Блог успешно сохранен!";
    header("Location: blog.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/admin_panel/css/new_recipes.css" />
    <title>Создание блога</title>
</head>
<body>
    <div class="container">
        <section class="sidebar">
            <a class="header_logo">
                <img src="/image/лого.svg" class="header_logo_img" />
            </a>
            <div class="sidebar_nav">
                <a href="users.html" class="sidebar_nav_link users">Пользователи</a>
                <a href="reviews.php" class="sidebar_nav_link">Отзывы</a>
                <a href="recipes.php" class="sidebar_nav_link">Рецепты</a>
                <a href="blog.php" class="sidebar_nav_link" style="text-decoration-line: underline;">Блоги</a>
            </div>
        </section>

        <section class="contant">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <section class="search">
                <div class="find">
                    <input class="search_inp" type="text" placeholder="Поиск.." />
                    <button class="search_btn">Поиск</button>
                </div>
            </section>

            <section class="container_review">
                <form class="modal_form" method="POST" enctype="multipart/form-data" id="blogForm">
                    <table class="table_users">
                        <tr class="table_row">
                            <th class="table_column_1">Название</th>
                            <th><input type="text" class="table_inp" name="name" required></th>
                        </tr>
                        
                        <tbody id="stepsContainer">
                            <tr class="table_row">
                                <th class="table_column_1" style="vertical-align: top;">Шаг 1</th>
                                <th class="table_column_2">
                                    <p>Заголовок: <input type="text" class="table_inp" name="title[]" required></p>
                                    <p>Фото: <input type="file" name="image[]" required></p>
                                    <p>Описание: <textarea class="table_inp" name="description[]" required></textarea></p>      
                                </th>
                            </tr>
                        </tbody>
                        
                        <tr class="table_row">
                            <th></th>
                            <th>
                                <button type="button" class="btn" id="moreButton">Добавить ещё шаг</button>
                            </th>
                        </tr>
                    </table>
                    
                    <div class="container_review_buttons">
                        <a href="blog.php" class="btn">Вернуться</a>
                        <button type="submit" class="btn" id="saveButton">Сохранить</button>
                    </div>
                </form>
            </section>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('blogForm');
            const moreButton = document.getElementById('moreButton');
            const stepsContainer = document.getElementById('stepsContainer');
            let stepCounter = 2;

            // Добавление нового шага
            moreButton.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.className = 'table_row';
                newRow.innerHTML = `
                    <th class="table_column_1" style="vertical-align: top;">Шаг ${stepCounter}</th>
                    <th class="table_column_2">
                        <p>Заголовок: <input type="text" class="table_inp" name="title[]" required></p>
                        <p>Фото: <input type="file" name="image[]" required></p>
                        <p>Описание: <textarea class="table_inp" name="description[]" required></textarea></p>
                    </th>
                `;
                stepsContainer.appendChild(newRow);
                stepCounter++;
            });

            // Валидация формы перед отправкой
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredInputs = form.querySelectorAll('input[required]');
                
                requiredInputs.forEach(input => {
                    if (!input.value.trim() && input.type !== 'file') {
                        input.style.border = '1px solid red';
                        isValid = false;
                    } else if (input.type === 'file' && !input.files[0]) {
                        input.style.border = '1px solid red';
                        isValid = false;
                    } else {
                        input.style.border = '';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Пожалуйста, заполните все обязательные поля!');
                }
            });
        });
    </script>
</body>
</html>