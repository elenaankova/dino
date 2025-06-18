<?php
session_start();
require_once __DIR__ . '/../../connect/connect.php';

// Проверка соединения
if (!$connect) {
  die("Ошибка подключения: " . mysqli_connect_error());
}

// Запросы для статистики
$sql_count_reviews = "SELECT COUNT(*) as total_reviews FROM reviews";
$result = mysqli_query($connect, $sql_count_reviews);
$row = mysqli_fetch_assoc($result);
$total_reviews = $row['total_reviews'];

$sql_month_reviews = "SELECT COUNT(*) as month_reviews FROM reviews WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$result_month = mysqli_query($connect, $sql_month_reviews);
$row_month = mysqli_fetch_assoc($result_month);
$month_reviews = $row_month['month_reviews'];

$sql_pending_reviews = "SELECT COUNT(*) as pending_reviews FROM reviews WHERE status = 1";
$result_pending = mysqli_query($connect, $sql_pending_reviews);
$row_pending = mysqli_fetch_assoc($result_pending);
$pending_reviews = $row_pending['pending_reviews'];





$sql = "SELECT 
       ROW_NUMBER() OVER (ORDER BY r.status ASC, r.id) AS row_num,
       r.id,
       r.user_id,
       u.name AS user_name,
       r.recipe_id,
       IFNULL(rec.name, 'Рецепт удалён') AS recipe_name,
       r.text,
       r.created_at,
       r.reason_deletion,
       r.deletion_time,
       CASE 
         WHEN r.status = 1 THEN 'pending'
         WHEN r.status = 2 THEN 'approved' 
         WHEN r.status = 3 THEN 'rejected'
       END as status
    FROM reviews r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN recipes rec ON r.recipe_id = rec.id
    ORDER BY r.status ASC, r.id";

$result = mysqli_query($connect, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/admin_panel/css/reviews.css" />
  <title>Отзывы</title>

  <style>
    select.loading {
      background-color: #f8f8f8;
      opacity: 0.8;
      cursor: wait;
    }

    select:disabled {
      opacity: 1;
      /* Сохраняем видимость при блокировке */
    }

    .deletion-info {
      font-size: 13px;
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
          <h1 class="container_title">Отзывы</h1>
          <div class="review">
            <p class="all_review">Общее кол-во: <?= $total_reviews ?></p>
            <p class="for_month_review">Кол-во за последний месяц: <?= $month_reviews ?></p>
            <p class="review_time">Кол-во отзывов в ожидании: <?= $pending_reviews ?></p>
          </div>
        </div>

        <table class="table_users">


          <tr class="table_row_titles">
            <th>№</th>
            <th>Имя пользователя</th>
            <th>Название рецепта</th>
            <th>Текст отзыва</th>
            <th>Дата создания</th>
            <th>Статус</th>
          </tr>



          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">
              <td class="table_cell"><?= $row['row_num'] ?></td>
              <td class="table_cell name_user"><?= htmlspecialchars($row['user_name']) ?></td>
              <td class="table_cell name_recipe"><?= htmlspecialchars($row['recipe_name']) ?></td>
              <td class="review-text table_cell"><?= htmlspecialchars($row['text']) ?></td>
              <td  class="table_cell"><?= date('d.m.Y', strtotime($row['created_at'])) ?></td>
              <td class="select_status table_cell">
                <select name="status" class="status-select" data-review-id="<?= $row['id'] ?>"
                  <?= $row['status'] == 'approved' || $row['status'] == 'rejected' ? 'disabled' : '' ?>>
                  <option value="1" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>В ожидании</option>
                  <option value="2" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Одобрено</option>
                  <option value="3" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Удалить</option>
                </select>
                <?php if ($row['status'] == 'rejected'): ?>
                  <!-- <div class="deletion-info">
                    Удаление через: <span class="deletion-timer" data-deletion-time="<?= $row['deletion_time'] ?>">
                      <?= date('H:i:s', strtotime($row['deletion_time']) - time()) ?>
                    </span>
                  </div> -->
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>










          <!-- <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">
              <td><?= $row['row_num'] ?></td>
              <td><?= htmlspecialchars($row['user_name']) ?></td>
              <td><?= htmlspecialchars($row['recipe_name']) ?></td>
              <td class="review-text">
                <?= htmlspecialchars($row['text']) ?>
              </td>
              <td><?= date('d.m.Y', strtotime($row['created_at'])) ?></td>
              <td class="select_status">
                <select name="status" class="status-select" data-review-id="<?= $row['id'] ?>"
                  <?= $row['status'] == 'approved' ? 'disabled' : '' ?>>
                  <option value="1" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>В ожидании</option>
                  <option value="2" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Одобрено</option>
                  <option value="3" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Удалить</option>
                </select>
                <?php if ($row['status'] == 'approved'): ?>
                  <input type="hidden" name="status" value="2">
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?> -->


          <!-- <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="table_row">
              <td><?= $row['row_num'] ?></td>
              <td><?= htmlspecialchars($row['user_name']) ?></td>
              <td><?= htmlspecialchars($row['recipe_name']) ?></td>
              <td class="review-text">
                <?= htmlspecialchars($row['text']) ?>
              </td>
              <td><?= date('d.m.Y', strtotime($row['created_at'])) ?></td>
              <td class="select_status">
                <select name="status" class="status-select" data-review-id="<?= $row['id'] ?>">
                    <option value="1" <?= $row['status'] == 1 ? 'selected' : '' ?>>В ожидании</option>
                    <option value="2" <?= $row['status'] == 2 ? 'selected' : '' ?>>Одобрено</option>
                    <option value="3" <?= $row['status'] == 3 ? 'selected' : '' ?>>Удалить</option>
                </select>
              </td>
            </tr>
          <?php endwhile; ?> -->



          <!-- <tr class="table_row">
            <td>5</td>
            <td>Эдвин Рюдинг</td>
            <td>Печеньки</td>
            <td class="review-text">
              Я пробую этот рецепт первый раз, и знаете, я думаю что это очень
              вкусно. Тесто получилось нежным и воздушным, хотя я добавил
              немного меньше сахара, чем указано в рецепте. Мои дети были в
              восторге!
            </td>
            <td>12.04.2025</td>
            <td class="select_status">
              <select name="status">
                <option value="1">В ожидании</option>
                <option value="2">Одобрено</option>
                <option value="3">Удалить</option>
              </select>
            </td>
          </tr> -->

        </table>

        <div class="modal_overlay" id="deleteModal" style="display:none;">
          <div class="content">
            <h3 class="modal_title">Вы действительно хотите удалить этот отзыв?</h3>
            <p>Выберите причину отказа:</p>
            <div class="modal_items">
              <div class="item">
                <input type="radio" id="reason1" name="reason" value="Ненормативная лексика" required>
                <label for="reason1">Ненормативная лексика</label>
              </div>
              <div class="item">
                <input type="radio" id="reason2" name="reason" value="Спам" required>
                <label for="reason2">Спам</label>
              </div>
              <div class="item">
                <input type="radio" id="reason3" name="reason" value="Клевета" required>
                <label for="reason3">Клевета</label>
              </div>
            </div>
            <p class="error-message" style="font-size: 15px; margin: 5px; display: none;"></p>
            <div class="modal_buttons">
              <button type="button" class="modal_btn modal-btn-no" id="cancelDelete">Нет</button>
              <button type="button" class="modal_btn modal-btn-yes" id="confirmDelete">Да</button>
            </div>
          </div>
        </div>



        <!-- <div class="modal_overlay" id="deleteModal">
          <div class="content">
            <h3 class="modal_title">
              Вы действительно хотите удалить этот отзыв?
            </h3>
            <p>Выберите причину отказа:</p>

            <div class="modal_items">
              <div class="item">
                <input type="radio" id="reason1" name="reason" value="Ненормативная лексика" />
                <label for="reason1">Ненормативная лексика</label>
              </div>
              <div class="item">
                <input type="radio" id="reason2" name="reason" value="Спам" />
                <label for="reason2">Спам</label>
              </div>
              <div class="item">
                <input type="radio" id="reason3" name="reason" value="Клевета" />
                <label for="reason3">Клевета</label>
              </div>
            </div>

            <p class="answer" style="font-size: 14px">
              Пожалуйста, выберите причину удаления
            </p>

            <div class="modal_buttons">
              <button class="modal_btn modal-btn-no" id="cancelDelete">
                Нет
              </button>
              <button class="modal_btn modal-btn-yes" id="confirmDelete">
                Да
              </button>
            </div>
          </div>
        </div> -->


      </section>
    </section>
  </div>
  <script>
    //  ПОИСК 
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.querySelector('.search_inp');
      const searchBtn = document.querySelector('.search_btn');
      const tableRows = document.querySelectorAll('.table_row:not(.table_row_titles)');
      const originalDisplay = [];

      // Сохраняем оригинальное состояние строк
      tableRows.forEach(row => {
        originalDisplay.push(row.style.display);
      });

      // Функция поиска
      function performSearch() {
        const query = searchInput.value.trim().toLowerCase();

        tableRows.forEach(row => {
          const nameCell = row.querySelector('.name_user');
          const name = nameCell.textContent.toLowerCase();

          const name_recipeCell = row.querySelector('.name_recipe');
          const name_recipe = name_recipeCell.textContent.toLowerCase();

          if (name.includes(query) || name_recipe.includes(query)) {
            row.style.display = ''; // Возвращаем оригинальное значение
          } else {
            row.style.display = 'none';
          }
        });
      }

      // Функция сброса поиска
      function resetSearch() {
        searchInput.value = '';
        tableRows.forEach((row, index) => {
          row.style.display = originalDisplay[index] || '';
        });
      }

      // Обработчики событий
      searchBtn.addEventListener('click', performSearch);

      searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          performSearch();
        }
      });

      searchInput.addEventListener('input', function () {
        if (searchInput.value.trim() === '') {
          resetSearch();
        }
      });
    });










    document.addEventListener('DOMContentLoaded', function () {

      // Функция обновления счетчика ожидающих отзывов
      function updatePendingCount() {
        fetch('get_pending_count.php')
          .then(response => {
            // Сначала проверяем статус ответа
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            // Проверяем Content-Type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              throw new Error("Ожидался JSON, но получен " + contentType);
            }
            return response.json();
          })
          .then(data => {
            // Усиленная проверка структуры ответа
            if (!data || typeof data !== 'object') {
              throw new Error("Некорректный формат данных");
            }
            if (data.success && typeof data.count !== 'undefined') {
              const counterElement = document.querySelector('.review_time');
              if (counterElement) {
                counterElement.textContent = `Кол-во отзывов в ожидании: ${data.count}`;
              } else {
                console.error('Элемент .review_time не найден');
              }
            } else {
              throw new Error(data.message || "Неизвестная ошибка сервера");
            }
          })
          .catch(error => {
            console.error('Ошибка обновления счетчика:', error);
            // Можно добавить отображение ошибки пользователю
            const counterElement = document.querySelector('.review_time');
            if (counterElement) {
              counterElement.textContent = 'Ошибка загрузки данных';
              counterElement.style.color = 'red';
            }
          });
      }
      // Вызываем функцию при загрузке страницы
      document.addEventListener('DOMContentLoaded', updatePendingCount);
      // И обновляем каждые 30 секунд
      setInterval(updatePendingCount, 30000);




      // // 4. Функция показа модального окна для удаления
      // function showDeleteModal(reviewId, selectElement) {
      //   const modal = document.getElementById('deleteModal');
      //   modal.style.display = 'flex';

      //   // Сбрасываем выбор причины
      //   document.querySelectorAll('input[name="reason"]').forEach(radio => {
      //     radio.checked = false;
      //   });
      //   document.querySelector('.answer').style.display = 'none';

      //   // Обработка отмены
      //   document.getElementById('cancelDelete').onclick = function () {
      //     modal.style.display = 'none';
      //     selectElement.value = selectElement.getAttribute('data-previous-value');
      //   };

      //   // Обработка подтверждения
      //   document.getElementById('confirmDelete').onclick = function () {
      //     const reason = document.querySelector('input[name="reason"]:checked');
      //     if (!reason) {
      //       document.querySelector('.answer').style.display = 'block';
      //       return;
      //     }

      //     updateReviewStatus(reviewId, 3, selectElement);
      //     modal.style.display = 'none';
      //   };
      // }

      // 6. Функция показа уведомлений
      function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
          toast.style.opacity = '0';
          setTimeout(() => toast.remove(), 300);
        }, 3000);
      }
    });







    document.querySelectorAll('.status-select').forEach(select => {
      select.addEventListener('change', async function () {
        const reviewId = this.dataset.reviewId;
        const newStatus = this.value; // 1, 2 или 3
        const originalValue = this.dataset.originalStatus || '1';

        // Показываем состояние загрузки
        this.classList.add('loading');

        const formData = new FormData();
        formData.append('review_id', reviewId);
        formData.append('status', newStatus);

        try {
          const response = await fetch('review_status.php', {
            method: 'POST',
            body: formData
          });

          const text = await response.text();
          console.log("Server response:", text);

          if (text.trim() === "success") {
            if (newStatus === '2') { // Если статус "approved"
              this.disabled = true;
              this.dataset.originalStatus = newStatus;
              alert("Статус успешно обновлен!"); // Сообщение ТОЛЬКО для статуса 2
            }
            // Для статуса 3 и других - ничего не делаем (нет alert)
          } else {
            throw new Error(text.startsWith("error:") ?
              text.substring(6).trim() :
              "Сервер вернул неожиданный ответ");
          }
        } catch (error) {
          console.error("Update failed:", error);
          // alert("Ошибка: " + error.message);
          this.value = originalValue;
        } finally {
          this.classList.remove('loading');
        }
      });
    });








    document.addEventListener('DOMContentLoaded', function () {
      // Обработчик изменения статуса
      document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
          const reviewId = this.dataset.reviewId;
          const newStatus = this.value;

          // Для статуса "Удалить" показываем модальное окно
          if (newStatus === '3') {
            showDeleteModal(reviewId, this);
            this.value = this.querySelector('option[selected]').value;
            return;
          }

          // Для других статусов отправляем сразу
          const formData = new FormData();
          formData.append('review_id', reviewId);
          formData.append('status', newStatus);

          sendStatusUpdate(formData, this);
        });
      });

      // Инициализация таймеров удаления
      initDeletionTimers();
    });

    function showDeleteModal(reviewId, selectElement) {
      const modal = document.getElementById('deleteModal');
      const confirmBtn = document.getElementById('confirmDelete');
      const cancelBtn = document.getElementById('cancelDelete');
      const errorMessage = modal.querySelector('.error-message');

      modal.style.display = 'flex';
      errorMessage.style.display = 'none';

      // Обработчик подтверждения удаления
      confirmBtn.onclick = function () {
        const reason = modal.querySelector('input[name="reason"]:checked');

        if (!reason) {
          errorMessage.textContent = 'Пожалуйста, выберите причину удаления';
          errorMessage.style.display = 'block';
          return;
        }

        const formData = new FormData();
        formData.append('review_id', reviewId);
        formData.append('status', '3');
        formData.append('reason', reason.value);

        sendStatusUpdate(formData, selectElement, function (success) {
          if (success) {
            modal.style.display = 'none';

            // Обновляем интерфейс
            selectElement.value = '3';
            selectElement.disabled = true;

            // Добавляем таймер удаления
            const deletionTime = new Date();
            deletionTime.setHours(deletionTime.getHours() + 24);
            alert(`Удаление отзыва через: ${formatTimeRemaining(deletionTime)}`);
            const deletionInfo = document.createElement('div');
            deletionInfo.className = 'deletion-info';
            
            // deletionInfo.innerHTML = `
            //         Удаление через: <span class="deletion-timer" 
            //         data-deletion-time="${deletionTime.toISOString()}">
            //             ${formatTimeRemaining(deletionTime)}
            //         </span>
            //     `;

            selectElement.parentNode.appendChild(deletionInfo);
            startDeletionTimer(deletionTime, deletionInfo.querySelector('.deletion-timer'));
          }
        });
      };

      // Обработчик отмены
      cancelBtn.onclick = function () {
        modal.style.display = 'none';
      };

      // Закрытие при клике вне модального окна
      modal.addEventListener('click', function (e) {
        if (e.target === modal) {
          modal.style.display = 'none';
        }
      });
    }

    function sendStatusUpdate(formData, selectElement, callback) {
      fetch('review_status.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(result => {
          if (result === 'success') {
            if (formData.get('status') === '2') {
              selectElement.disabled = true;
            }
            if (typeof callback === 'function') callback(true);
          } else if (result === 'reason_required') {
            showDeleteModal(formData.get('review_id'), selectElement);
            if (typeof callback === 'function') callback(false);
          } else {
            throw new Error(result || 'Неизвестная ошибка сервера');
          }
        })
        .catch(error => {
          console.error('Ошибка:', error);
          selectElement.value = selectElement.querySelector('option[selected]').value;
          alert('Ошибка при обновлении статуса: ' + error.message);
          if (typeof callback === 'function') callback(false);
        });
    }

    function formatTimeRemaining(endTime) {
      const now = new Date();
      const diff = Math.floor((endTime - now) / 1000);

      if (diff <= 0) return '00:00:00';

      const hours = Math.floor(diff / 3600);
      const minutes = Math.floor((diff % 3600) / 60);
      const seconds = diff % 60;

      return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    function startDeletionTimer(endTime, timerElement) {
      const timer = setInterval(() => {
        const now = new Date();
        const diff = Math.floor((endTime - now) / 1000);

        if (diff <= 0) {
          clearInterval(timer);
          timerElement.textContent = '00:00:00';
          // Здесь можно обновить страницу или удалить строку через AJAX
          location.reload();
          return;
        }

        timerElement.textContent = formatTimeRemaining(endTime);
      }, 1000);
    }

    function initDeletionTimers() {
      document.querySelectorAll('.deletion-timer').forEach(timer => {
        const deletionTime = new Date(timer.dataset.deletionTime);
        startDeletionTimer(deletionTime, timer);
      });
    }





  </script>
</body>

</html>