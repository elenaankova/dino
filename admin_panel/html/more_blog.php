<?php

session_start();

require_once __DIR__ . '/../../connect/connect.php';
// Get blog ID from URL
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id <= 0) {
    die("Неверный ID блога");
}

// Query to get main blog info
$sql_blog = "SELECT 
                b.id,
                b.name,
                b.number_image,
                DATE_FORMAT(b.created_at, '%d.%m.%Y') as created_at,
                GROUP_CONCAT(bs.step_number ORDER BY bs.step_number) as step_numbers,
                COUNT(bs.id) as steps_count
             FROM blogs b
             LEFT JOIN blog_steps bs ON b.id = bs.blog_id
             WHERE b.id = ?
             GROUP BY b.id";

$stmt_blog = mysqli_prepare($connect, $sql_blog);
mysqli_stmt_bind_param($stmt_blog, "i", $blog_id);
mysqli_stmt_execute($stmt_blog);
$result_blog = mysqli_stmt_get_result($stmt_blog);
$blog = mysqli_fetch_assoc($result_blog);

if (!$blog) {
    die("Блог не найден");
}

// Query to get blog steps
$sql_steps = "SELECT 
                step_number,
                title,
                image,
                description
              FROM blog_steps
              WHERE blog_id = ?
              ORDER BY step_number";

$stmt_steps = mysqli_prepare($connect, $sql_steps);
mysqli_stmt_bind_param($stmt_steps, "i", $blog_id);
mysqli_stmt_execute($stmt_steps);
$result_steps = mysqli_stmt_get_result($stmt_steps);
$steps = mysqli_fetch_all($result_steps, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/admin_panel/css/new_recipes.css" />
    <title><?= htmlspecialchars($blog['name']) ?></title>
    <style>
        .editable-textarea-step {
            width: 100%;
            min-height: 100px;
            padding: 5px;
            margin: 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            text-align: left;
            line-height: 1.4;
            font-family: inherit;
            box-sizing: border-box;
            white-space: pre-wrap; /* Preserve line breaks */
        }

        .step-container {
            margin-bottom: 20px;
        }

        .step-preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }

        .file-input-wrapper {
            margin-top: 10px;
        }
        
        .description-content {
            white-space: pre-line; /* This will show line breaks */
            text-align: left;
            padding: 5px;
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
                <a href="users.php" class="sidebar_nav_link users">Пользователи</a>
                <a href="reviews.php" class="sidebar_nav_link reviews">Отзывы</a>
                <a href="recipes.php" class="sidebar_nav_link">Рецепты</a>
                <a href="blog.php" class="sidebar_nav_link" style="text-decoration-line: underline;">Блоги</a>
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
                    <h1 class="container_title"><?= htmlspecialchars($blog['name']) ?></h1>
                </div>

                <table class="table_users">
                    <tr class="table_row">
                        <th class="table_column_1">№</th>
                        <th class="table_column_2" data-field="id"><?= htmlspecialchars($blog['id']) ?></th>
                    </tr>
                    <tr class="table_row">
                        <th class="table_column_1">Название</th>
                        <th class="table_column_2" data-field="name"><?= htmlspecialchars($blog['name']) ?></th>
                    </tr>
                    <tr class="table_row">
                        <th class="table_column_1">Кол-во фотографий</th>
                        <th class="table_column_2" data-field="number_image">
                            <?= htmlspecialchars($blog['number_image']) ?></th>
                    </tr>
                    <tr class="table_row">
                        <th class="table_column_1">Дата публикации</th>
                        <th class="table_column_2" data-field="created_at"><?= htmlspecialchars($blog['created_at']) ?>
                        </th>
                    </tr>

                    <?php foreach ($steps as $step): ?>
                        <tr class="table_row">
                            <th class="table_column_1" style="vertical-align: top;">Шаг
                                <?= htmlspecialchars($step['step_number']) ?></th>
                            <th class="table_column_2">
                                <div class="step-container" data-step="<?= $step['step_number'] ?>">
                                    <?php if (!empty($step['title'])): ?>
                                        <div data-field="step_title_<?= $step['step_number'] ?>">
                                            <p><strong>Заголовок:</strong> <?= htmlspecialchars($step['title']) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($step['image'])): ?>
                                        <div data-field="step_image_<?= $step['step_number'] ?>">
                                            <p><strong>Фото:</strong></p>
                                            <img src="<?= htmlspecialchars($step['image']) ?>" class="step-preview-image">
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($step['description'])): ?>
                                        <div data-field="step_description_<?= $step['step_number'] ?>">
                                            <p><strong>Описание:</strong></p>
                                            <div class="description-content"><?= htmlspecialchars($step['description']) ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <div class="container_review_buttons">
                    <button class="btn"><a
                            href="more_blog.php?id=<?= htmlspecialchars($blog['id']) ?>">Вернуться</a></button>
                    <button class="btn" id="editButton">Изменить</button>
                    <button class="btn" id="saveButton" style="display: none;">Сохранить</button>
                    <button class="btn" id="deleteButton">Удалить</button>
                </div>
            </section>
        </section>
    </div>
    <script src="/js/catalog.js"></script>
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
            // Handle main fields
            editableCells.forEach(cell => {
                const fieldName = cell.dataset.field;
                if (!fieldName) return;

                const content = cell.innerHTML;
                const textContent = extractTextFromHTML(content);

                if (fieldName === 'id' || fieldName === 'created_at') {
                    return; // Skip non-editable fields
                }

                if (content.includes('<p>') || content.includes('<br>') || textContent.length > 50) {
                    const textarea = document.createElement('textarea');
                    textarea.className = 'editable editable-textarea';
                    textarea.value = textContent;
                    textarea.dataset.field = fieldName;
                    cell.innerHTML = '';
                    cell.appendChild(textarea);
                } else {
                    const input = document.createElement('input');
                    input.className = 'editable';
                    input.type = 'text';
                    input.value = textContent;
                    input.dataset.field = fieldName;
                    cell.innerHTML = '';
                    cell.appendChild(input);
                }
            });

            // Handle blog steps
            document.querySelectorAll('.step-container').forEach(container => {
                const stepNumber = container.dataset.step;

                // Handle step title
                const titleContainer = container.querySelector(`[data-field="step_title_${stepNumber}"]`);
                if (titleContainer) {
                    const textContent = extractTextFromHTML(titleContainer.innerHTML.replace(/<strong>Заголовок:<\/strong>/, ''));
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'editable-step';
                    input.value = textContent.trim();
                    input.dataset.field = `step_title_${stepNumber}`;
                    titleContainer.innerHTML = '<strong>Заголовок:</strong> ';
                    titleContainer.appendChild(input);
                }

                // Handle step image
                document.querySelectorAll('.step-preview-image').forEach(img => {
                    const container = img.closest('[data-field^="step_image"]');
                    if (!container) return;

                    const stepNumber = container.dataset.field.split('_')[2];
                    const currentSrc = img.src;

                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = `step_image_${stepNumber}`;
                    fileInput.className = 'editable-file-step';
                    fileInput.accept = 'image/*';

                    const previewWrapper = document.createElement('div');
                    previewWrapper.className = 'image-upload-wrapper';

                    // Show current image
                    const currentImage = document.createElement('img');
                    currentImage.src = currentSrc;
                    currentImage.className = 'current-step-image';
                    currentImage.style.maxWidth = '200px';

                    // Container for new preview
                    const newPreview = document.createElement('img');
                    newPreview.className = 'new-step-preview';
                    newPreview.style.display = 'none';
                    newPreview.style.maxWidth = '200px';

                    fileInput.addEventListener('change', function (e) {
                        if (e.target.files && e.target.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                newPreview.src = event.target.result;
                                newPreview.style.display = 'block';
                            };
                            reader.readAsDataURL(e.target.files[0]);
                        }
                    });

                    container.innerHTML = '';
                    container.appendChild(previewWrapper);
                    previewWrapper.appendChild(currentImage);
                    previewWrapper.appendChild(document.createElement('br'));
                    previewWrapper.appendChild(fileInput);
                    previewWrapper.appendChild(newPreview);
                });

                // Handle step description
                const descContainer = container.querySelector(`[data-field="step_description_${stepNumber}"]`);
                if (descContainer) {
                    const descriptionContent = descContainer.querySelector('.description-content');
                    const textContent = descriptionContent ? descriptionContent.textContent : '';
                    
                    const textarea = document.createElement('textarea');
                    textarea.className = 'editable-textarea-step';
                    textarea.value = textContent.trim();
                    textarea.dataset.field = `step_description_${stepNumber}`;
                    
                    descContainer.innerHTML = '<strong>Описание:</strong>';
                    descContainer.appendChild(textarea);
                }
            });

            saveButton.style.display = "block";
            editButton.style.display = "none";
        });

        saveButton.addEventListener('click', function () {
            const formData = new FormData();
            formData.append('id', <?= $blog_id ?>);

            // Collect main fields data
            document.querySelectorAll('.editable').forEach(input => {
                const fieldName = input.dataset.field;
                if (fieldName) {
                    formData.append(fieldName, input.value);
                }
            });

            // Collect steps data
            document.querySelectorAll('.editable-step').forEach(input => {
                formData.append(input.dataset.field, input.value);
            });

            document.querySelectorAll('.editable-textarea-step').forEach(textarea => {
                formData.append(textarea.dataset.field, textarea.value);
            });

            // Collect step images
            document.querySelectorAll('.editable-file-step').forEach(input => {
                if (input.files[0]) {
                    formData.append(input.name, input.files[0]);
                }
            });

            // Send data
            fetch('more_blog_edit.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка сети');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Изменения сохранены!');
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

        deleteButton.addEventListener('click', function() {
            if (confirm('Вы точно хотите удалить этот блог? Это действие нельзя отменить.')) {
                fetch('more_blog_delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${<?= $blog_id ?>}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка сервера: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Блог успешно удален!');
                        window.location.href = 'blog.php'; // Redirect to blogs list
                    } else {
                        alert('Ошибка: ' + (data.message || 'Не удалось удалить блог'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ошибка соединения: ' + error.message);
                });
            }
        });
    </script>
</body>
</html>