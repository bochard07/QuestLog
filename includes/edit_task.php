<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../Classes/Tasks.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $input = json_decode(file_get_contents('php://input'), true);

  $taskId = $input['taskId'] ?? null;
  $newInput = $input['newInput'] ?? null;

  try{
    $dbconn = new DbConn();
    $pdo = $dbconn->getPdo();
    
    $userId = $_SESSION['user_data']['user_id'];
    $tasks = new Tasks($pdo, $userId);

    $tasks->isInputEmpty($newInput);

    if(empty($tasks->error)){
      $tasks->editTask($pdo, $taskId, $newInput);
      $response = [
        'status' => 'success',
        'message' => 'Task deleted successfully!',
      ];
    } else {
      $response = [
        'status' => 'failed',
        'message' => 'Failed to delete task.',
      ];
    }

    echo json_encode($response);

  } catch(PDOException $e){
    die("Query failed: {$e}");
  }
}