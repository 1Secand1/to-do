<?php
require "./auth/Auth.service.php";
require_once __DIR__ . '/tasks/TaskManager.php';

if (!Auth::is_authorized()) {
    header("Location: /auth/login.php");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Список задач</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Roboto', Arial, sans-serif;
      display: flex;
      height: 100vh;
      background-color: #f4f6f8;
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: #007bff;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 10;
    }
    header a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      transition: opacity 0.3s ease;
    }
    header a:hover {
      opacity: 0.8;
    }
    .container {
      display: flex;
      flex: 1;
      margin-top: 70px;
      overflow: hidden;
      width: 100%;
    }
    .task-list {
      flex: 2;
      padding: 20px;
      overflow-y: auto;
      background: #fff;
      border-right: 1px solid #ddd;
    }
    .task-list h1 {
      margin-bottom: 20px;
      color: #333;
      font-size: 24px;
      text-align: center;
    }
    .task {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      margin-bottom: 10px;
      background: #f9fafc;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .task strong {
      font-size: 18px;
      color: #007bff;
    }
    .task p {
      flex: 1;
      margin: 0 15px;
      font-size: 16px;
      color: #555;
    }
    .task .actions {
      display: flex;
      gap: 10px;
    }
    .task button {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
    }
    .task .btn-complete {
      background: #28a745;
      color: #fff;
    }
    .task .btn-complete:hover {
      background: #218838;
    }
    .task .btn-delete {
      background: #dc3545;
      color: #fff;
    }
    .task .btn-delete:hover {
      background: #c82333;
    }
    .task a {
      text-decoration: none;
      color: #007bff;
      font-size: 14px;
      transition: opacity 0.3s ease;
    }
    .task a:hover {
      opacity: 0.8;
    }
    .task-form-panel {
      flex: 1;
      background: #f4f6f8;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .task-form-panel h2 {
      font-size: 20px;
      margin-bottom: 20px;
    }
    .task-form {
      width: 100%;
      max-width: 300px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .task-form input,
    .task-form textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }
    .task-form textarea {
      resize: none;
      height: 80px;
    }
    .task-form button {
      padding: 12px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .task-form button:hover {
      background: #0056b3;
    }
    .completed {
      color: #28a745;
      font-weight: bold;
    }
  </style>
</head>
<body>
<header>
  <span>Список задач</span>
  <a href="./auth/logout.php">Выйти</a>
</header>
<div class="container">
  <div class="task-form-panel">
    <h2>Добавить задачу</h2>
    <form method="POST" action="./tasks/api/post.php" class="task-form">
      <input type="text" name="title" required placeholder="Название задачи">
      <textarea name="description" required placeholder="Описание задачи"></textarea>
      <button type="submit">Добавить</button>
    </form>
</div>
  <div class="task-list">
    <h1>Ваши задачи</h1>
    <?php foreach ($task_manager->get_tasks() as &$task): ?>
      <div class="task">
        <strong>#<?php echo $task['id']; ?></strong>
        <?php if ($task['status'] === $STATUS_FINISH): ?>
          <span class="completed">Выполнена</span>
        <?php else: ?>
          <form method="POST" action="./tasks/api/finish.php" class="actions">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <button class="btn-complete">Выполнить</button>
          </form>
        <?php endif; ?>
        <p><?php echo htmlspecialchars($task['title']); ?>: <?php echo htmlspecialchars($task['description']); ?></p>
        <div class="actions">
          <a href="./tasks/api/edit.php?task_id=<?= $task['id'] ?>">Редактировать</a>
          <form method="POST" action="./tasks/api/delete.php">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <button class="btn-delete">Удалить</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
</body>
</html>
