document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById('cadastro_toggleDarkMode');
  const body = document.body;

  if (localStorage.getItem('modo') === 'escuro') {
    body.classList.add('dark-mode');
    toggle.textContent = '☀️ Alterar Modo';
  }

  toggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    const modoAtual = body.classList.contains('dark-mode') ? 'escuro' : 'claro';
    localStorage.setItem('modo', modoAtual);
    toggle.textContent = modoAtual === 'escuro' ? '☀️ Alterar Modo' : '🌙 Alterar Modo';
  });

  document.getElementById('cpf').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);

    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    e.target.value = value;
  });
});

function validarCPF(cpf) {
  cpf = cpf.replace(/[^\d]+/g, '');
  if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

  let soma = 0;
  for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
  let resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf.charAt(9))) return false;

  soma = 0;
  for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf.charAt(10))) return false;

  return true;
}

function validarCadastro() {
  const senha = document.getElementById("senha").value;
  const confirma = document.getElementById("confirma_senha").value;
  const cpf = document.getElementById("cpf").value;

  if (senha !== confirma) {
    alert("As senhas não coincidem!");
    return false;
  }

  if (!validarCPF(cpf)) {
    alert("CPF inválido!");
    return false;
  }

  return true;
}

/*modo escuro*/

document.addEventListener("DOMContentLoaded", () => {
  const toggleButton = document.getElementById("theme-toggle");
  const icon = document.getElementById("theme-icon");

  // verifica se o usuario já tem algum modo salvo
  if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
    icon.classList.remove("icon-sun");
    icon.innerHTML = `<path d="M21.64 13A9 9 0 1111 2.36 7 7 0 0021.64 13z"/>`;
  }

  toggleButton.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    icon.classList.add("rotate");

    setTimeout(() => {
      icon.classList.remove("rotate");
    }, 600);

    if (document.body.classList.contains("dark-mode")) {
      icon.innerHTML = `<path d="M21.64 13A9 9 0 1111 2.36 7 7 0 0021.64 13z"/>`;
      localStorage.setItem("theme", "dark");
    } else {
      icon.innerHTML = `
        <circle cx="12" cy="12" r="5"/>
        <line x1="12" y1="1" x2="12" y2="3"/>
        <line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/>
        <line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>`;
      localStorage.setItem("theme", "light");
    }
  });
});