<?php

class Tasks extends DbConn {
  private $pdo;
  private $userId;
  public $error = [];

  public function __construct($pdo, $userId){
    $this->pdo = $pdo;
    $this->userId = $userId;
  }

  public function isInputEmpty($task){
    if(empty($task)){
      $this->error['failed'] = 'input tag is empty';
    }
  }

  public function fetchTasks($pdo, $userId){
    $query = 'SELECT users.username, tasks.task, tasks.created_datetime, tasks.task_id FROM users JOIN tasks ON users.user_id = tasks.user_id WHERE users.user_id = :userId;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function addTask($pdo, $userId, $task){
    $query = 'INSERT INTO tasks (task, user_id) VALUES (:task, :userId);';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':task', $task);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
  }

  public function deleteTask($pdo, $taskId){
    $query = 'DELETE FROM tasks WHERE task_id = :taskId;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':taskId', $taskId);
    $stmt->execute();
  }
}