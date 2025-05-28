<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../Classes/Tasks.php';

header('Content-Type: application/json');

if(isset($_SESSION['user_data']['user_id']) && is_numeric($_SESSION['user_data']['user_id'])){
  $dbconn = new DbConn();
  $pdo = $dbconn->getPdo();

  $userId = $_SESSION['user_data']['user_id'];
  $tasks = new Tasks($pdo, $userId);
  
  $fetchData = $tasks->fetchTasks($pdo, $userId);

  echo json_encode($fetchData);
} else{
  echo json_encode(['error' => 'User not logged in.']);
}