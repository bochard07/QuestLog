const addTaskForm = document.getElementById('add-task-form');
const editTaskForm = document.getElementById('edit-task-form');
let isCheckedArray = [];

//===== LOAD TASKS =====//
function loadTasks(){
  fetch('./includes/fetch_tasks.php')
  .then(function(response){
    return response.json();
  })
  .then(function(data){
    const taskBody = document.getElementById('task-body');
    taskBody.innerHTML = '';

    if(Array.isArray(data) && data.length > 0){
      data.forEach(function(task){
        const div = document.createElement('div');
        div.innerHTML = `
          <input class="rpgui-checkbox" type="checkbox" id="${task.task_id}">
          <label for="${task.task_id}">${task.task}</label>
        `;

        taskBody.appendChild(div);

        // listen for checkboxes after loading tasks in DOM
        addCheckboxListeners();
      });
    } else{
      const div = document.createElement('div');
      div.innerHTML = '<p style="text-align: center; color: red;">No tasks found.</p>';
      taskBody.appendChild(div);
    }
  })
  .catch(function(error){
    console.error(`Error fetching tasks: ${error}`);
  });
}

function addCheckboxListeners(){
  const checkboxes = document.querySelectorAll('#task-body input[type="checkbox"]');

  checkboxes.forEach(function(checkbox){
    checkbox.addEventListener('change', evaluateCheckedCheckboxes);
  });
  
  function evaluateCheckedCheckboxes(){
    const checked = Array.from(checkboxes).filter(function(cb){
      return cb.checked;
    });

    isCheckedArray = checked.map(function(cb){
      return cb.id;
    });

    if (checked.length === 1) {
      console.log('only one ticked');
      removeDisabledAttributesOnDeleteBtn();
      removeDisabledAttributesOnEditBtn();
    } else if (checked.length > 1) {
      console.log('multiple ticked');
      removeDisabledAttributesOnDeleteBtn();
      setDisabledAttributesOnEditBtn();
    } else {
      setDisabledAttributesOnDeleteBtn();
      setDisabledAttributesOnEditBtn();
    }
  }
};

function removeDisabledAttributesOnDeleteBtn(){
  document.getElementById('delete-task').removeAttribute('disabled');
};

function setDisabledAttributesOnDeleteBtn(){
  document.getElementById('delete-task').setAttribute('disabled', true);
};

function removeDisabledAttributesOnEditBtn(){
  document.getElementById('edit-task-popup-open').removeAttribute('disabled');
};

function setDisabledAttributesOnEditBtn(){
  document.getElementById('edit-task-popup-open').setAttribute('disabled', true);
};

//===== ADD TASK =====//
function addTask(){
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
    setTimeout(function(){
    loadTasks();
    }, 500);
    setDisabledAttributesOnDeleteBtn();
    setDisabledAttributesOnEditBtn();
    popupNewTaskClose();
  })
  .catch(function(error){
    console.error(error);
  });
}

//===== EDIT TASK =====//
function editTask(){
  const taskName = document.getElementById('edit-input-task-name').value.trim();

  // replace placeholder text when no input
  if(!taskName){
    document.getElementById('edit-input-task-name').placeholder = 'Please fill this input...';
    return;
  }

  const payload = {
    taskId: isCheckedArray[0],
    newInput: taskName
  };

  fetch('./includes/edit_task.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(function(response){
    console.log(response);
    return response.json();
  })
  .then(function(data){
    console.log(data);
    setTimeout(function(){
    loadTasks();
    }, 500);
    setDisabledAttributesOnEditBtn();
    popupEditTaskClose();
  })
  .catch(function(error){
    console.error(error);
  });
}

//===== DELETE TASK =====//
function deleteTask(){
  console.log(isCheckedArray);

  fetch('./includes/delete_task.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(isCheckedArray)
  })
  .then(function(response){
    return response.json();
  })
  .then(function(data){
    console.log('Response from server:', data);
    console.log('Status:', data.status);
    console.log('Message:', data.message);
    setTimeout(function(){
    loadTasks();
    }, 500);
    setDisabledAttributesOnDeleteBtn();
  })
  .catch(function(error){
    console.error(error);
  });
}

function resetTaskInputPlaceholderForAddBtn(){
  const taskInput = document.getElementById('task-name');
  setTimeout(function(){
    taskInput.placeholder = 'New task...';
  }, 200);
}
function resetTaskInputPlaceholderForEditBtn(){
  const taskInput = document.getElementById('edit-input-task-name');
  setTimeout(function(){
    taskInput.placeholder = 'Edit task...';
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

function popupEditTaskOpen(){
  popupWindow = document.getElementById('edit-task-popup-window');
  popupWindow.style.visibility = 'visible';
  popupWindow.style.opacity = 1;
}

function popupEditTaskClose(){
  popupWindow = document.getElementById('edit-task-popup-window');
  setTimeout(function(){
    editTaskForm.reset();
  }, 200);
  popupWindow.style.visibility = 'hidden';
}


// event listeners
document.getElementById('new-task-popup-open').addEventListener('click', function(){
  popupNewTaskOpen();
});

document.getElementById('new-task-popup-close').addEventListener('click', function(){
  resetTaskInputPlaceholderForAddBtn();
  popupNewTaskClose();
});

document.getElementById('edit-task-popup-open').addEventListener('click', function(){
  popupEditTaskOpen();
});

document.getElementById('edit-task-popup-close').addEventListener('click', function(){
  resetTaskInputPlaceholderForEditBtn();
  popupEditTaskClose();
});

document.getElementById('task-name').addEventListener('input', resetTaskInputPlaceholderForAddBtn);

document.getElementById('edit-input-task-name').addEventListener('input', resetTaskInputPlaceholderForEditBtn);

document.getElementById('add-task-form').addEventListener('submit', function(e){
  e.preventDefault();
  addTask();
});

document.getElementById('edit-task-form').addEventListener('submit', function(e){
  e.preventDefault();
  editTask();
});

document.getElementById('delete-task').addEventListener('click', function(e){
  e.preventDefault();
  deleteTask();
});

// calls
loadTasks();