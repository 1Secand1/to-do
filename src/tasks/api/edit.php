<?php
require __DIR__ . '/../task.php';
require __DIR__ . '/../../auth/Auth.service.php';

if (!isset($_GET['task_id'])) {
  header("Location: /index.php");
}

$task_id = html_entity_decode($_GET['task_id']);

$task = Task::find(id: $_GET["task_id"]);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $task_id = html_entity_decode($_POST['task_id']);
  $title = html_entity_decode($_POST['title']);
  $description = html_entity_decode($_POST['description']);

  $task->edit($title, $description);

  header("Location: /index.php");
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Обновить задачу</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Roboto', Arial, sans-serif;
      background-color: #f4f6f8;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }
    .update-task-container {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }
    .update-task-container h1 {
      font-size: 24px;
      color: #007bff;
      margin-bottom: 20px;
      text-align: center;
    }
    .update-task-container form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .update-task-container label {
      font-size: 14px;
      color: #555;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .update-task-container input,
    .update-task-container textarea {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      width: 100%;
    }
    .update-task-container textarea {
      resize: none;
      height: 100px;
    }
    .update-task-container button {
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
    .update-task-container button:hover {
      background: #0056b3;
    }
    .update-task-container .hidden-input {
      display: none;
    }
  </style>
</head>
<body>
  <div class="update-task-container">
    <h1>Обновить задачу №<?= $task->id ?> "<?= htmlspecialchars($task->title) ?>"</h1>
    <form method="POST" action="./edit.php?task_id=<?= $task->id ?>">
      <label>
        Заголовок:
        <input
          type="text"
          name="title"
          required
          placeholder="<?= htmlspecialchars($task->title) ?>"
          value="<?= htmlspecialchars($task->title) ?>"
        />
      </label>
      <label>
        Описание:
        <textarea
          name="description"
          required
          placeholder="<?= htmlspecialchars($task->description) ?>"><?= htmlspecialchars($task->description) ?></textarea>
      </label>
      <input type="hidden" name="task_id" value="<?= $task->id ?>" class="hidden-input" />
      <button type="submit">Обновить</button>
    </form>
  </div>
</body>
</html>
