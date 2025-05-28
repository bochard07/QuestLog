<?php

require_once __DIR__ . '/../Classes/DbConn.php';
require_once __DIR__ . '/../Classes/Auth.php';

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_name('todoapp_session');

session_set_cookie_params([
  'lifetime' => 1800,
  'domain' => '',
  'path' => '/',
  'secure' => false, // change later
  'httponly' => true
]);

if(session_status() === PHP_SESSION_NONE){
  session_start();
}