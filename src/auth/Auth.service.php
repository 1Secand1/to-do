<?php
require_once __DIR__ . '/../db.php';

session_start();

class Auth
{
  public static function registration(string $username, string $hash_password): bool
  {
    $hash_password = Auth::hash_password($hash_password);
    $stmt = $GLOBALS['data_base']->get_data_base()->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash_password);
    return $stmt->execute();
  }

  public static function login(string $username, string $password): bool
  {
    $stmt = $GLOBALS['data_base']->get_data_base()->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user === null) {
      return false;
    }

    $verify_password = password_verify($password, $user['password']);

    if ($verify_password === true) {
      $_SESSION['user_id'] = $user['id'];
    }

    return $verify_password;
  }

  public static function logout()
  {
    // session_reset();
    // session_write_close();
    session_destroy();
  }

  public static function is_authorized(): bool
  {
    if (!isset($_SESSION['user_id'])) {
      return false;
    }

    $stmt = $GLOBALS['data_base']->get_data_base()->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    return $stmt->execute();
  }

  private static function hash_password(string $password): string
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }
}
