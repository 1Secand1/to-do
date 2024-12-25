<?php
require_once __DIR__ . '/../task.php';
require __DIR__ . '/../../auth/Auth.service.php';

$task_id = $_POST['task_id'];

Task::deleteTask($task_id, $_SESSION['user_id']);

header("Location: /index.php");
