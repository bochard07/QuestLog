<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // Prevent caching of this page
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  
  require_once __DIR__ . '/includes/init.php';

  if(isset($_SESSION['logged_in'])){
    // load dashboard if logged in
    include_once './includes/dashboard.php';
    die();
  }

  // redirect to login page if not yet logged in
  header('Location: ./login.php');
  die();
?>