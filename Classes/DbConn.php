<?php

require_once __DIR__ . '/../vendor/autoload.php';

class DbConn {
  private $host;
  private $dbname;
  private $dbusername;
  private $dbpassword;
  private $pdo;

  public function __construct(){
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $this->host = $_ENV['DB_HOST'];
    $this->dbname = $_ENV['DB_NAME'];
    $this->dbusername = $_ENV['DB_USERNAME'];
    $this->dbpassword = $_ENV['DB_PASSWORD'];

    $this->connect();
  }
  
  protected function connect(){
    try{
      $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->dbusername, $this->dbpassword);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->pdo;
    } catch(PDOException $e){
      die("Connection failed: {$e->getMessage()}");
    }
  }

  public function getPdo(){
    return $this->pdo;
  }
}
