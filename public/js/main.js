document.addEventListener('DOMContentLoaded', () => {
  const links = document.querySelectorAll('.navbar a');
  const path = window.location.pathname;

  links.forEach(link => {
    if (path.includes(link.getAttribute('href'))) {
      link.classList.add('active');
    }
  });
});
