document.addEventListener('DOMContentLoaded', () => {
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
