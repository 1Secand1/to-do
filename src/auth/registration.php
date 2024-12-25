<?php
require './Auth.service.php';

if (Auth::is_authorized()) {
    header("Location: /");
    exit;
}

$result = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = html_entity_decode($_POST['username']);
    $password = html_entity_decode($_POST['password']);

    $result =  Auth::registration($username, $password);

    if ($result === true) {
        header("Location: /auth/login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Регистрация</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f0f0f0;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-wrapper {
      background: #fff;
      padding: 20px 30px;
      border-radius: 5px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      max-width: 300px;
      width: 100%;
    }

    .form-wrapper h2 {
      margin-top: 0;
      margin-bottom: 15px;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    form input {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      font-size: 14px;
    }

    form button {
      cursor: pointer;
      padding: 10px;
      background: #28a745;
      border: none;
      border-radius: 3px;
      font-weight: bold;
      color: #fff;
      transition: background-color 0.2s ease;
    }

    form button:hover {
      background: #218838;
    }

    .error {
      color: red;
      font-size: 0.9em;
      min-height: 20px;
      margin: 5px 0;
      text-align: center;
    }

    p {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }

    p a {
      color: #007bff;
      text-decoration: none;
    }

    p a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-wrapper">
    <h2>Регистрация</h2>
    <form method="POST" action="./registration.php">
      <input type="text" name="username" required placeholder="Имя пользователя">
      <input type="password" name="password" required placeholder="Пароль">
      <button type="submit">Зарегистрироваться</button>
      <p class="error">
        <?= $_SERVER['REQUEST_METHOD'] == 'POST' && $result !== false ? "Ошибка, пользователь с таким именем уже существует" : "" ?>
      </p>
      <p>
        У вас уже есть аккаунт?
        <a href="/auth/login.php">Войти</a>
      </p>
    </form>
  </div>
</div>

</body>
</html>
