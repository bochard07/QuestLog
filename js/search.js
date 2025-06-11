function searchFilter(query){
  const taskItems = document.querySelectorAll('.task-item');

  taskItems.forEach(function(taskItem){
    const label = taskItem.querySelector('.task-label');
    const text = label ? label.textContent.toLowerCase() : '';
    taskItem.style.display = text.includes(query) ? 'block' : 'none';
  });
}

document.getElementById('search').addEventListener('input', function(){
  query = this.value.trim().toLowerCase();
  searchFilter(query);
});