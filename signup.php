<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require_once __DIR__ . '/includes/init.php';

  // block access when already logged in
  if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
    header('Location: ./index.php');
    die();
  }

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    
    try{
      $dbconn = new DbConn();
      $pdo = $dbconn->getPdo();
      
      $signup = new Signup($pdo, $username, $email, $pwd);
      $signup->validate($pdo, $username, $email, $pwd); // run validation
      $errors = $signup->errors;

      // user registered, back to index/login page
      if(empty($errors)){
        header('Location: ./index.php');
        die();
      }
      
    } catch(PDOException $e){
      die("Query failed: {$e->getMessage()}");
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>Sign up</title>
  <link rel="stylesheet" href="https://unpkg.com/7.css">
</head>
<body style="display: flex; justify-content: center; align-items: center;">
  <div class="window active" style="margin: 32px; width: fit-content;">
    <div class="title-bar">
      <div class="title-bar-title">ToDo App - Sign up</div>
    </div>

    <main class="window-body has-space">
      <p class="instruction instruction-primary">You are now in the sign up page.</p>
      <p class="instruction">Some recommendation on creating an account:</p>
      <ul class="instruction">
        <li>Choose a password that is atleast 12 characters long.</li>
        <li>Use a strong password. Combination of uppercase, lowercase, numbers, and special characters.</li>
      </ul>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
        <fieldset class="field-row">
          <legend>Create an account</legend>
          <input type="text" name="username" placeholder="username">
          <input type="text" name="email" placeholder="email">
          <input type="password" name="pwd" placeholder="password">
          <button type="submit">Register</button>
        </fieldset>   
      </form>

      <?php if(!empty($errors)): ?>
        <div>
          <?php foreach($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <p>Already have an account? <a href="./login.php">Log in</a></p>
    </main>
    
  </div>
</body>
</html>