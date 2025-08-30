// Seleciona todas as tarefas com a classe .task
document.querySelectorAll('.task').forEach(task => {
  
  // Evento disparado quando o usuário começa a arrastar uma tarefa
  task.addEventListener('dragstart', e => {
    // Armazena o ID da tarefa no objeto dataTransfer para uso posterior no drop
    e.dataTransfer.setData('text/plain', task.dataset.id);

    // Adiciona uma classe visual para indicar que a tarefa está sendo arrastada
    task.classList.add('dragging');
  });

  // Evento disparado quando o usuário termina de arrastar a tarefa
  task.addEventListener('dragend', () => {
    // Remove a classe visual de arraste
    task.classList.remove('dragging');
  });
});
// Seleciona todas as colunas do Kanban com a classe .kanban-column
document.querySelectorAll('.kanban-column').forEach(column => {
  
  // Evento que permite que elementos sejam soltos nesta coluna
  column.addEventListener('dragover', e => {
    e.preventDefault(); // Necessário para permitir o drop
  });

  // Evento disparado quando uma tarefa é solta dentro da coluna
  column.addEventListener('drop', async e => {
    e.preventDefault(); // Evita comportamento padrão do navegador

    // Recupera o ID da tarefa que foi arrastada
    const taskId = e.dataTransfer.getData('text/plain');

    // O ID da coluna representa o novo status da tarefa (ex: 'todo', 'in-progress', 'done')
    const newStatus = column.id;

    // Seleciona a tarefa pelo seu data-id e move visualmente para a nova coluna
    const task = document.querySelector(`.task[data-id='${taskId}']`);
    column.appendChild(task); // Reposiciona o elemento DOM

    // Log para depuração no console
    console.log("Movendo tarefa com ID:", taskId);

    // Envia uma requisição AJAX para atualizar o status da tarefa no banco de dados
    const response = await fetch('./actions/update.php', {
      method: 'POST', // Método HTTP
      headers: { 'Content-Type': 'application/json' }, // Define o tipo de conteúdo como JSON
      body: JSON.stringify({ id: taskId, status: newStatus }) // Envia os dados como JSON
    });

    // Verifica se a resposta foi bem-sucedida
    if (!response.ok) {
      const errorText = await response.text(); // Captura o erro retornado
      console.error("Erro ao atualizar:", errorText); // Exibe no console
      showToast("Erro ao atualizar tarefa"); // Mostra uma notificação visual
    }
  });
});
// Função para exibir uma notificação temporária (toast)
function showToast(message) {
  const toast = document.getElementById('toast'); // Seleciona o elemento de toast
  toast.textContent = message; // Define o texto da mensagem
  toast.classList.remove('hidden'); // Torna o toast visível

  // Após 3 segundos, esconde o toast novamente
  setTimeout(() => {
    toast.classList.add('hidden');
  }, 3000);
}
