<?php
  // redirect to index/login when user not yet logged in
  if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
      header('Location: ../index.php');
      exit;
  }

  // prevents file access
  if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
      header('Location: ../index.php');
      die();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Quests</title>
  <link href="./dist/rpgui.css" rel="stylesheet" type="text/css" >
  <script src="./dist/rpgui.js"></script>
<style>
  #new-task-popup-window,
  #edit-task-popup-window {
    width: 350px;
    position: fixed;
    z-index: 999;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    visibility: hidden;
    opacity: 0;
    transition-duration: 0.2s;
    transition-property: visibility, opacity;
  }
</style>
</head>
<body class="rpgui-content" style="display: flex; justify-content: center;">
  <div class="rpgui-container framed" style="margin: 36px 0; width: 90%; height: fit-content; max-width: 600px;">
    <div style="text-align: right;">
      <form action="../includes/logout.php" method="POST" class="title-bar-controls">
        <button class="rpgui-button" type="submit">Log Out</button>
      </form>
    </div>
    
    <main>
      <h1 style="text-align: center;">My Quests Log</h1>
      <p style="text-align: center;">"I must finish all of these!"</p>
      <hr class="golden">
      <div>
        <div>
          <input type="search" id="search" placeholder="Search task...">
          <select class="rpgui-dropdown" name="sort" id="sort">
            <option value="all" selected>All</option>
            <option value="incomplete">Incomplete</option>
            <option value="complete">Complete</option>
          </select>
        </div>
        
        <!-- action buttons for tasks -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <div>
            <button class="rpgui-button" type="button" id="new-task-popup-open">Add task</button>
          </div>
          <div>
            <button class="rpgui-button" type="button" id="edit-task-popup-open" disabled>Edit</button>
            <button class="rpgui-button" type="button" id="delete-task" disabled>Delete</button>
          </div>  
        </div>
      </div>

      <div class="rpgui-container framed-grey" style="width: 90%; margin: auto;">
        <h2 style="text-align: center;">Tasks</h2>
        <hr>
        <div style="overflow-y: auto; height: 400px;">
          <div id="task-body" style="width: 90%; margin: auto; word-break: break-all;">
            <!-- ajax will insert the fetched task data here... -->
          </div>
        </div>
      </div>
      
      <!-- popup for add task -->
      <div class="rpgui-container framed golden" id="new-task-popup-window">
        <h3>New Task</h3>
        <p>Add the task you need to do.</p>
        <form id="add-task-form">
          <input type="text" id="task-name" name="task-name" placeholder="New task...">
          <div style="display: flex; justify-content: center; align-items: center;">
            <button class="rpgui-button" type="button" id="new-task-popup-close">Close</button>
            <button class="rpgui-button" type="submit">Add</button>
          </div>
        </form>
      </div>

      <!-- popup for edit task -->
      <div class="rpgui-container framed golden" id="edit-task-popup-window">
        <h3>Edit Task</h3>
        <p>Apply a new value.</p>
        <form id="edit-task-form">
          <input type="text" id="edit-input-task-name" name="edit-input-task-name" placeholder="Add new value...">
          <div style="display: flex; justify-content: center; align-items: center;">
            <button class="rpgui-button" type="button" id="edit-task-popup-close">Close</button>
            <button class="rpgui-button" type="submit">Edit</button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <script src="./js/tasks.js"></script>
</body>
</html>