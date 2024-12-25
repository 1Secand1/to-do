<?php
require_once './Auth.service.php';

Auth::logout();
header("Location: /auth/login.php");
