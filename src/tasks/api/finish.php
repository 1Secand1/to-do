<?php
require_once __DIR__ . '/../task.php';
require __DIR__ . '/../../auth/Auth.service.php';

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = html_entity_decode($_POST['task_id']);
    $task = Task::find(id: $task_id);
    $task->finish();

    header("Location: /index.php");
  }
} catch (Exception $e) {
  echo $e->getMessage();
}
