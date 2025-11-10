<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link rel="stylesheet" href="../assets/CSS/cadastro.css">
</head>
<body>
  <button id="cadastro_toggleDarkMode">🌙 Alterar Modo</button>

  <div class="cadastro_container">
    <div class="cadastro_box">

      <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem-<?= $_SESSION['mensagem_tipo'] ?>">
          <?= $_SESSION['mensagem']; ?>
        </div>
        <?php unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']); ?>
      <?php endif; ?>

      <h2 class="cadastro_title">Registre-se</h2>
      <form action="processa_cadastro.php" method="POST" onsubmit="return validarCadastro()">
        <label for="nome">Digite seu nome completo:</label>
        <input type="text" name="nome" required>

        <label for="email">Digite seu e-mail:</label>
        <input type="email" name="email" required>

        <label for="cpf">Digite seu CPF:</label>
        <input type="text" name="cpf" id="cpf" required maxlength="14" placeholder="000.000.000-00">

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <label for="confirma_senha">Confirme sua senha:</label>
        <input type="password" name="confirma_senha" id="confirma_senha" required>

        <label for="data_nascimento">Data de nascimento:</label>
        <input type="date" name="data_nascimento" required>

        <label for="cep">CEP:</label>
        <input type="text" name="cep" id="cep" maxlength="9" placeholder="00000-000" required>

        <label for="logradouro">Rua:</label>
        <input type="text" name="logradouro" id="logradouro" required>

        <label for="bairro">Bairro:</label>
        <input type="text" name="bairro" id="bairro" required>

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" id="cidade" required>

        <label for="estado">Estado:</label>
        <input type="text" name="estado" id="estado" required>

        <button type="submit">Registrar</button>
      </form>

      <div class="cadastro_loginLink">
        <p class="cadastro_loginText">Já possui uma conta?</p>
        <a href="paginainicial.php" class="cadastro_loginButton">🔐 Entrar na minha conta</a>
      </div>
    </div>
  </div>

  <script>
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

      document.getElementById('cep').addEventListener('blur', function () {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) {
          alert('CEP inválido!');
          return;
        }

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
          .then(response => response.json())
          .then(data => {
            if (data.erro) {
              alert('CEP não encontrado!');
              return;
            }

            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.localidade || '';
            document.getElementById('estado').value = data.uf || '';
          })
          .catch(() => {
            alert('Erro ao buscar o CEP!');
          });
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
  </script>
  <?php include '../footer.php'; ?>
</body>
</html>
