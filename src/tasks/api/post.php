<?php
require __DIR__ . '/../task.php';
require __DIR__ . '/../../auth/Auth.service.php';

$title = html_entity_decode($_POST['title']);
$description = html_entity_decode($_POST['description']);

Task::create($title, $description, $_SESSION['user_id']);

header("Location: /index.php");