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
    $pwd = $_POST['pwd'];

    try{
      $dbconn = new DbConn();
      $pdo = $dbconn->getPdo();

      $login = new Login($pdo, $username, $pwd);
      $login->validate($pdo, $username, $pwd);

      $errors = $login->errors;
      $userId = $login->getUserId($pdo, $username);

      if(empty($errors)){
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['user_data'] = [
          'user_id' => $userId,
          'user_username' => $username,
          'user_email' => $email
        ];
        
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
  <title>Log in</title>
  <link rel="stylesheet" href="https://unpkg.com/7.css">
</head>
<body style="display: flex; justify-content: center; align-items: center;">
  <div class="window active" style="margin: 32px; width: fit-content;">
    <div class="title-bar">
      <div class="title-bar-title">ToDo App - Log in</div>
    </div>

    <main class="window-body has-space">
      <p class="instruction instruction-primary">You are now in the log in page.</p>
      <p class="instruction">Fill in the information below to access your account.</p>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
        <fieldset class="field-row">
          <legend>Log in to your account</legend>
          <input type="text" name="username" placeholder="username">
          <input type="password" name="pwd" placeholder="password">
          <button type="submit">Log In</button>
        </fieldset>   
      </form>

      <?php if(!empty($errors)): ?>
        <div>
          <?php foreach($errors as $error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <p>No account yet? <a href="./signup.php">Sign Up</a></p>
    </main>
    
  </div>
</body>
</html>