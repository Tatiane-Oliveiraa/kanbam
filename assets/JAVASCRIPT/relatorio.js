
function traduzirStatus(status) {
  switch (status) {
    case 'todo': return 'A Fazer';
    case 'in-progress': return 'Em Progresso';
    case 'done': return 'Concluído';
    default: return status;
  }
}

async function buscarRelatorio() {
  const status = document.getElementById("statusFiltro").value;
  try {
    const res = await fetch(`./relatorio.php?status=${encodeURIComponent(status)}`);
    const dados = await res.json();

    if (dados.erro) {
      document.getElementById("resultado").innerHTML = `<p>${dados.erro}</p>`;
      return;
    }

    if (dados.length === 0) {
      document.getElementById("resultado").innerHTML = `<p>Nenhuma tarefa encontrada.</p>`;
      return;
    }

    let html = `<table>
      <tr><th>Título</th><th>Descrição</th><th>Data</th><th>Status</th></tr>`;
    dados.forEach(t => {
      html += `<tr>
        <td>${t.titulo}</td>
        <td>${t.descricao}</td>
        <td>${t.data}</td>
        <td>${traduzirStatus(t.status)}</td>
      </tr>`;
    });
    html += `</table>`;
    document.getElementById("resultado").innerHTML = html;
  } catch (error) {
    document.getElementById("resultado").innerHTML = `<p>Erro ao buscar relatório.</p>`;
  }
}

function gerarPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  const resultado = document.getElementById("resultado");
  if (!resultado || resultado.innerHTML.trim() === "") {
    alert("Nenhum conteúdo para gerar PDF.");
    return;
  }

  const linhas = Array.from(resultado.querySelectorAll("tr"));
  const cabecalho = Array.from(linhas[0].querySelectorAll("th")).map(th => th.innerText);
  const corpo = linhas.slice(1).map(tr =>
    Array.from(tr.querySelectorAll("td")).map(td => td.innerText)
  );

  doc.autoTable({
    head: [cabecalho],
    body: corpo,
    startY: 20,
    theme: 'grid',
    styles: { fontSize: 10 }
  });

  doc.save("relatorio_tarefas.pdf");
}
