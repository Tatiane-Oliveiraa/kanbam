document.addEventListener("DOMContentLoaded", () => {
  const tasks = document.querySelectorAll(".task");
  const columns = document.querySelectorAll(".kanban-column");

  let draggedTaskId = null;
  let draggedTaskElement = null;

  tasks.forEach((task) => {
    task.addEventListener("dragstart", () => {
      draggedTaskId = task.dataset.id;        // pega o ID da tarefa
      draggedTaskElement = task;              // guarda o elemento
      setTimeout(() => {
        task.classList.add("dragging");
      }, 0);
    });

    task.addEventListener("dragend", () => {
      task.classList.remove("dragging");
    });
  });

  columns.forEach((column) => {
    column.addEventListener("dragover", (e) => {
      e.preventDefault();
    });

    column.addEventListener("drop", (e) => {
      e.preventDefault();
      const newStatus = column.id; // pega o status da coluna (todo, in-progress, done)

      // Move o elemento visualmente
      column.appendChild(draggedTaskElement);

      // Atualiza no servidor
      fetch("./actions/update_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: draggedTaskId,   // ID da tarefa arrastada
          status: newStatus    // status da coluna (todo, in-progress, done)
        })
      })
      .then(res => res.json())
      .then(data => {
        console.log("🔍 Resposta do servidor:", data);

        if (data.success) {
          showToast("✅ Tarefa atualizada com sucesso!");
        } else {
          showToast("❌ Erro ao atualizar tarefa.");
          // Mostra o erro detalhado no console
          console.error("Erro detalhado:", data.error || data.debug);
        }
      })
      .catch(error => {
        showToast("⚠️ Erro de rede.");
        console.error("Erro de rede:", error);
      });

    });
  });

  function showToast(message) {
    const toast = document.getElementById("toast");
    if (!toast) return;

    toast.textContent = message;
    toast.classList.remove("hidden");
    toast.classList.add("visible");

    setTimeout(() => {
      toast.classList.remove("visible");
      toast.classList.add("hidden");
    }, 3000);
  }
});
