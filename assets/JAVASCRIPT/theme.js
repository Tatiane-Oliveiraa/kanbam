/**
 * Sistema de Dark Mode com Persistência em localStorage
 * Salva preferência do usuário e aplica automaticamente ao carregar página
 */

// Aplicar tema ao carregar a página (antes de exibir qualquer conteúdo)
(function () {
  const theme = localStorage.getItem("theme") || "light";
  if (theme === "dark") {
    document.documentElement.classList.add("dark-mode");
  }
})();

// Função para alternar tema
function toggleDarkMode() {
  const html = document.documentElement;
  const isDark = html.classList.contains("dark-mode");

  if (isDark) {
    html.classList.remove("dark-mode");
    localStorage.setItem("theme", "light");
    updateThemeButton("light");
  } else {
    html.classList.add("dark-mode");
    localStorage.setItem("theme", "dark");
    updateThemeButton("dark");
  }
}

// Atualizar texto/ícone do botão conforme o tema
function updateThemeButton(theme) {
  const button = document.getElementById("themeToggleBtn");
  if (button) {
    button.textContent = theme === "dark" ? "☀️ Claro" : "🌙 Escuro";
  }
}

// Inicializar botão quando página carregar
document.addEventListener("DOMContentLoaded", function () {
  const theme = localStorage.getItem("theme") || "light";
  updateThemeButton(theme);
});
