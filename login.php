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
  <link href="./dist/rpgui.css" rel="stylesheet" type="text/css" >
  <script src="./dist/rpgui.js"></script>
</head>
<body class="rpgui-content" style="display: flex; justify-content: center;">
  <div class="rpgui-container framed" style="margin: 32px 0; width: 95%; height: fit-content; max-width: 600px;">

    <main>
      <h1 style="text-align: center;">Log in</h1>
      <p>Fill in the information below to access your account.</p>
      <div class="rpgui-container framed-grey" style="width: 90%; margin: auto;">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
          <input type="text" name="username" placeholder="username">
          <input type="password" name="pwd" placeholder="password">
          <div style="text-align: right;">
            <button class="rpgui-button" type="submit">Log In</button>
          </div>
        </form>
      </div>

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