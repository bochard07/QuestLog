const addTaskForm = document.getElementById('add-task-form');


// functions
function loadTasks(){ // fetching tasks on database
  fetch('./includes/fetch_tasks.php')
  .then(function(response){
    return response.json();
  })
  .then(function(data){
    const tbody = document.getElementById('task-body');
    tbody.innerHTML = '';

    if(Array.isArray(data) && data.length > 0){
      data.forEach(function(task){
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align: center;">
            <input type="checkbox" id="checkbox_${task.task_id}">
            <label for="checkbox_${task.task_id}"></label>
          </td>
          <td>${task.task}</td>
          <td>${task.created_datetime}</td>
        `;

        tbody.appendChild(tr);
      });
    } else{
      const tr = document.createElement('tr');
      tr.innerHTML = '<td colspan="3" style="color: red; text-align: center;">No tasks found.</td>';
      tbody.appendChild(tr);
    }
  })
  .catch(function(error){
    console.error(`Error fetching tasks: ${error}`);
  });
}

function addTask(){ // adding task on database
  const taskInput = document.getElementById('task-name');
  const formData = new FormData(addTaskForm);
  const taskName = formData.get('task-name').trim();

  // replace placeholder text when no input
  if(!taskName){
    taskInput.placeholder = 'Please fill this input...';
    return;
  }
  
  // when value is true, continue
  fetch('./includes/add_task.php', {
    method: 'POST',
    body: formData
  })
  .then(function(response){
    return response.json();
  })
  .then(function(data){
    console.log('Response from server:', data);
    console.log('Status:', data.status);
    console.log('Message:', data.message);
  })
  .catch(function(err){
    console.error(err);
  });
    
  setTimeout(function(){
    loadTasks();
  }, 500);
  
  popupNewTaskClose();
}

function resetTaskInputPlaceholder(){
  const taskInput = document.getElementById('task-name');
  setTimeout(function(){
    taskInput.placeholder = 'New task...';
  }, 200);
}

function popupNewTaskOpen(){
  popupWindow = document.getElementById('new-task-popup-window');
  popupWindow.style.visibility = 'visible';
  popupWindow.style.opacity = 1;
}

function popupNewTaskClose(){
  popupWindow = document.getElementById('new-task-popup-window');
  setTimeout(function(){
    addTaskForm.reset();
  }, 200);
  popupWindow.style.visibility = 'hidden';
}


// event listeners
document.getElementById('new-task-popup-open').addEventListener('click', function(){
  popupNewTaskOpen();
});

document.getElementById('new-task-popup-close').addEventListener('click', function(){
  resetTaskInputPlaceholder();
  popupNewTaskClose();
});

document.getElementById('task-name').addEventListener('input', resetTaskInputPlaceholder);

document.getElementById('add-task-form').addEventListener('submit', function(e){
  e.preventDefault();
  addTask();
});


// calls
loadTasks();