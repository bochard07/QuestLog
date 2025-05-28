<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../Classes/Tasks.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $task = $_POST['task-name']; 

  try{
    $dbconn = new DbConn();
    $pdo = $dbconn->getPdo();

    $userId = $_SESSION['user_data']['user_id'];
    $tasks = new Tasks($pdo, $userId);
    $error = $tasks->error;

    $tasks->isInputEmpty($task);
    $tasks->addTask($pdo, $userId, $task);

    if(empty($error)){
      $response = [
        'status' => 'success',
        'message' => 'Task added successfully!',
      ];
    } else{
      $response = [
        'status' => 'failed',
        'message' => 'Failed to add task.',
      ];
    }

    echo json_encode($response);

  } catch(PDOException $e){
    die("Query failed: {$e}");
  }
}