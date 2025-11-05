// ===== CARGAR TEMA ANTES DE QUE SE RENDERICE LA PÁGINA =====
(function() {
  const savedTheme = localStorage.getItem('theme') || 'dark';
  document.documentElement.setAttribute('data-theme', savedTheme);
})();

document.addEventListener('DOMContentLoaded', () => {
  // ===== TEMA CLARO/OSCURO =====
  const themeToggle = document.getElementById('themeToggle');
  const themeText = document.getElementById('themeText');
  const themeIcon = document.getElementById('themeIcon');
  const htmlElement = document.documentElement;

  // Actualizar UI según el tema cargado
  const currentTheme = htmlElement.getAttribute('data-theme') || 'dark';
  updateThemeUI(currentTheme);

  // Manejar click en el botón
  if (themeToggle) {
    themeToggle.addEventListener('click', (e) => {
      e.preventDefault();
      const currentTheme = htmlElement.getAttribute('data-theme') || 'dark';
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

      console.log('Cambiando tema de', currentTheme, 'a', newTheme); // Debug

      htmlElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateThemeUI(newTheme);
    });
  }

  function updateThemeUI(theme) {
    if (!themeText || !themeIcon) return;

    if (theme === 'light') {
      themeText.textContent = 'Modo Oscuro';
      themeIcon.className = 'fa-solid fa-moon';
    } else {
      themeText.textContent = 'Modo Claro';
      themeIcon.className = 'fa-solid fa-sun';
    }
  }

  // Animar barras
  const progress = document.querySelector('.progress');
  if (progress) {
    progress.style.width = '0%';
    setTimeout(() => {
      progress.style.transition = 'width 1s ease';
      progress.style.width = progress.getAttribute('style').match(/width:\s*(\d+)%/)[1] + '%';
    }, 200);
  }

  // Animar las barras de asistencia
  const bars = document.querySelectorAll('.bar');
  bars.forEach((bar, index) => {
    bar.style.height = '0%';
    setTimeout(() => {
      bar.style.transition = 'height 1.2s ease';
      bar.style.height = index === 0 ? '80%' : '40%';
    }, 400);
  });
});
