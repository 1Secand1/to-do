<?php
require_once __DIR__ . '/../db.php';

$STATUS_IN_PROCESS = "in_progress";
$STATUS_FINISH = "completed";

class Task
{
  public int $id;
  public string $status;
  public string $title;
  public string $description;
  public int $user_id;

  public function __construct(int $id, string $title, string $description, string $status, int $user_id)
  {
    $this->id = $id;
    $this->title = $title;
    $this->status = $status;
    $this->description = $description;
    $this->user_id = $user_id;
  }

  public static function find(int $id): ?Task
  {
    try {
      $query = $GLOBALS['data_base']->get_data_base()->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
      $query->bind_param("ii", $id, $_SESSION['user_id']);
      $query->execute();
      $result = $query->get_result();

      $task = $result->fetch_assoc();

      if ($task === null) {
        return null;
      }

      $query->close();

      return $task ? new Task($task['id'], $task['title'], $task['description'], $task['status'], $task['user_id']) : null;
    } catch (mysqli_sql_exception $e) {
      echo 'error', $e->getMessage();
    }
  }

  public static function create(string $title, string $description, int $user_id): Task
  {
    $query = $GLOBALS['data_base']->get_data_base()->prepare("INSERT INTO tasks (title, description, user_id) VALUES (?, ?, ?)");
    $query->bind_param("ssi", $title, $description, $user_id);
    $query->execute();

    $taskId = $query->insert_id;

    $query->close();
    return new Task($taskId, $title, $description,  $GLOBALS['STATUS_IN_PROCESS'], $user_id);
  }

  public static function editTask(int $task_id, string $title, string $description, int $user_id): void
  {
    $query = $GLOBALS['data_base']->get_data_base()->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
    $query->bind_param("ssii", $title, $description, $task_id, $user_id);
    $query->execute();
    $query->close();
  }

  public static function deleteTask(int $task_id, int $user_id): void
  {
    $query = $GLOBALS['data_base']->get_data_base()->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $query->bind_param("ii", $task_id, $user_id);
    $query->execute();
    $query->close();
  }

  public function edit(string $title, string $description)
  {
    $query = $GLOBALS['data_base']->get_data_base()->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
    $query->bind_param("ssii", $title, $description, $this->id, $this->user_id);
    $query->execute();
    $query->close();

    $this->title = $title;
    $this->description = $description;
  }

  public function delete()
  {
    $query = $GLOBALS['data_base']->get_data_base()->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $query->bind_param("ii", $this->id, $this->user_id);
    $query->execute();
    $query->close();
  }

  public function finish()
  {
    try {
      $query = $GLOBALS['data_base']->get_data_base()->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
      $query->bind_param("sii", $GLOBALS['STATUS_FINISH'], $this->id, $this->user_id);

      if (!$query->execute()) {
        echo "Error executing statement: " . $query->error;
      }

      $query->close();
    } catch (Exception $e) {
      echo "Task finish error: " . $e->getMessage();
    }
  }
}
