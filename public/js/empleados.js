document.addEventListener('DOMContentLoaded', () => {
  const buscador = document.getElementById('buscador');
  const filas = document.querySelectorAll('.tabla tbody tr');

  buscador.addEventListener('input', () => {
    const texto = buscador.value.toLowerCase();
    filas.forEach(fila => {
      fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
    });
  });
});
