<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth/Auth.service.php';
require_once __DIR__ . '/task.php';

if (!Auth::is_authorized()) {
  header("Location: /auth/login.php");
}

class TaskManager
{
  private $tasks = [];

  public function __destruct()
  {
    unset($this->tasks);
  }

  public function get_tasks()
  {
    $query = $GLOBALS['data_base']->get_data_base()->prepare("SELECT * FROM tasks WHERE user_id = ?");
    $query->bind_param("i", $_SESSION['user_id']);
    $query->execute();
    $result = $query->get_result();

    $tasks = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($tasks as &$task) {
      $this->tasks[] = new Task($task['id'], $task['title'], $task['description'], $task['status'], $task['user_id']);
    }

    $query->close();
    return $tasks;
  }

  public function create_task(string $title, string $description)
  {
    if (!isset($_SESSION['user_id'])) {
      header("Location: /auth/login.php");
      exit;
    }

    $task = Task::create($title, $description, $_SESSION['user_id']);
    $this->tasks = array_merge($this->tasks, [$task]);
  }

  public function find_task(int $id): ?Task
  {
    foreach ($this->tasks as &$task) {

      if ($task->id === $id) {
        return $task;
      }
    }

    return null;
  }
}

$task_manager = new TaskManager();
