<?php

class Signup extends DbConn {
  private $pdo;
  private $username;
  private $email;
  private $pwd;
  public $errors = [];

  public function __construct($pdo, $username, $email, $pwd){
    $this->pdo = $pdo;
    $this->username = $username;
    $this->email = $email;
    $this->pwd = $pwd;
  }

  public function validate($pdo, $username, $email, $pwd){
    $inputEmptyMsg = $this->isInputEmpty($username, $email, $pwd);

    if($inputEmptyMsg){
      $this->errors['input_empty'] = $inputEmptyMsg;
      return;
    }
    if($this->isEmailInvalid($email)){
      $this->errors['email_invalid'] = 'Whoops! your email is invalid.';
      return;
    }
    if($this->isUsernameTaken($pdo, $username)){
      $this->errors['username_taken'] = 'Uh oh! that username is already taken.';
      return;
    }
    if($this->isEmailAlreadyRegistered($pdo, $email)){
      $this->errors['email_taken'] = 'That email has already been registered.';
      return;
    }

    // validation passed, register the user
    $this->signupUser($pdo, $username, $email, $pwd);
  }

  private function isInputEmpty($username, $email, $pwd){
    if(empty($username) && empty($email) && empty($pwd)){
      return 'Please fill in all fields.';
    } elseif(empty($username)){
      return 'Username field is empty.';
    } elseif(empty($email)){
      return 'The email field is empty.';
    } elseif(empty($pwd)){
      return 'You did not fill in the password field.';
    }
  }

  private function isEmailInvalid($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      return true;
    } else{
      return false;
    }
  }

  private function isUsernameTaken($pdo, $username){
    $query = 'SELECT username FROM users WHERE username = :username;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  private function isEmailAlreadyRegistered($pdo, $email){
    $query = 'SELECT email FROM users WHERE email = :email;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  private function signupUser($pdo, $username, $email, $pwd){
    $query = 'INSERT INTO users (username, email, pwd) VALUES (:username, :email, :pwd);';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pwd', $pwd);
    $stmt->execute();
  }
}

class Login extends DbConn {
  private $pdo;
  private $username;
  private $pwd;
  public $errors = [];

  public function __construct($pdo, $username, $pwd){
    $this->pdo = $pdo;
    $this->username = $username;
    $this->pwd = $pwd;
  }

  public function validate($pdo, $username, $pwd){
    // checks if input fields are empty
    $inputEmptyMsg = $this->isInputEmpty($username, $pwd);

    if($inputEmptyMsg){
      $this->errors['input_empty'] = $inputEmptyMsg;
      return;
    }

    // if inputs are filled, cross-check if username and pwd exists in database
    if($this->doesUsernameNotExist($pdo, $username)){
      $this->errors['username_nonexistent'] = 'The username, either does not exist or wrong.';
      return;
    }
    if($this->doesPasswordNotExist($pdo, $pwd)){
      $this->errors['password_nonexistent'] = 'Oops...that password is wrong.';
      return;
    }
  }

  private function isInputEmpty($username, $pwd){
    if(empty($username) && empty($pwd)){
      return 'Please fill in all fields.';
    } elseif(empty($username)){
      return 'Username field is empty.';
    } elseif(empty($pwd)){
      return 'You did not fill in the password field.';
    }
  }

  private function doesUsernameNotExist($pdo, $username){
    $query = 'SELECT username FROM users WHERE username = :username;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$result){
      return true;
    }
  }

  private function doesPasswordNotExist($pdo, $pwd){
    $query = 'SELECT pwd FROM users WHERE pwd = :pwd;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pwd', $pwd);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$result){
      return true;
    }
  }

  public function getUserId($pdo, $username){
    $query = 'SELECT user_id FROM users WHERE username = :username;';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && isset($result['user_id'])) {
        return $result['user_id'];
    } else {
        return null;
    }
  }
}