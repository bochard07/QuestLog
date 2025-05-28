<?php
  require_once __DIR__ . '/../includes/init.php';

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }

    session_destroy();
    
    header('Location: ../index.php');
    die();
  } else{
    header('Location: ../index.php');
    die();
  }