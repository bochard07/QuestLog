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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://unpkg.com/7.css">
<style>
  #new-task-popup-window {
    width: 300px;
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
  .table-container {
    overflow: hidden;
    overflow-y: scroll;
    height: 200px;
  }
  thead {
    top: 0;
    z-index: 2;
    position: sticky;
  }
  table {
    width: 290px;
  }
</style>
</head>
<body style="display: flex; justify-content: center; align-items: center;">
  <div class="window active" style="margin: 32px; width: fit-content;">
    <div class="title-bar">
      <div class="title-bar-title">ToDo App - Dashboard</div>
        <form action="../includes/logout.php" method="POST" class="title-bar-controls">
          <button type="submit" aria-label="Close"></button>
        </form>
    </div>
    
    <main class="window-body has-space">
      <h1 class="instruction instruction-primary" style="text-align: center;">My ToDo List</h1>
      <p class="instruction" style="text-align: center;">"I must finish all of these!"</p>
      <div class="field-row">
        <input type="search" placeholder="Search task...">
        <select name="sort" id="sort">
          <option value="all" selected>All</option>
          <option value="incomplete">Incomplete</option>
          <option value="complete">Complete</option>
        </select>
        <button type="button" id="new-task-popup-open">Add task</button>
      </div>

      <div class="field-row-stacked" style="float: right; margin-left: 7px;">
        <button type="button" id="edit-task" disabled>Edit</button>
        <button type="button" id="delete-task" disabled>Delete</button>
      </div>

      <div class="field-row-stacked table-container">
        <table class="has-shadow">
          <thead>
            <tr>
              <th style="width: 15%;"></th>
              <th style="width: 85%; text-align: center;">Task</th>
            </tr>
          </thead>
          <tbody id="task-body">
            <!-- ajax will insert the fetched task data here... -->
          </tbody>
        </table>
      </div>
      
      <!-- popup -->
      <div class="window active is-bright" id="new-task-popup-window" aria-labelledby="dialog-title">
        <div class="title-bar">
          <div class="title-bar-text" id="dialog-title">New Task</div>
          <div class="title-bar-controls">
            <button type="button" aria-label="Close" id="new-task-popup-close"></button>
          </div>
        </div>
        <div class="window-body has-space">
          <p class="instruction instruction-primary">Create new task...</p>
          <p class="instruction">Add the task you need to do.</p>
          <form id="add-task-form" class="field-row-stacked">
            <input type="text" id='task-name' name="task-name" placeholder="New task...">
            <div style="text-align: right;">
              <button type="submit">Add</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script src="./js/tasks.js"></script>
</body>
</html>