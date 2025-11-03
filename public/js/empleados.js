document.addEventListener('DOMContentLoaded', () => {
  // ===== BUSCADOR MEJORADO =====
  const buscador = document.getElementById('buscador');
  const filas = document.querySelectorAll('.tabla-moderna tbody tr');
  let busquedaTimeout;

  if (buscador) {
    buscador.addEventListener('input', () => {
      clearTimeout(busquedaTimeout);
      
      busquedaTimeout = setTimeout(() => {
        const texto = buscador.value.toLowerCase().trim();
        let contadorVisible = 0;

        filas.forEach((fila, index) => {
          const contenidoFila = fila.textContent.toLowerCase();
          const coincide = contenidoFila.includes(texto);
          
          if (coincide) {
            fila.style.display = '';
            fila.style.animation = `fadeIn 0.4s ease ${index * 0.05}s forwards`;
            contadorVisible++;
          } else {
            fila.style.display = 'none';
          }
        });

        // Actualizar contador si existe
        actualizarContador(contadorVisible, filas.length);
      }, 300);
    });
  }

  // ===== ANIMACIONES DE ENTRADA =====
  animarFilasTabla();

  // ===== TOOLTIPS EN BOTONES =====
  agregarTooltips();

  // ===== CONTADOR DE RESULTADOS =====
  function actualizarContador(visible, total) {
    let contador = document.querySelector('.search-counter');
    
    if (!contador) {
      contador = document.createElement('div');
      contador.className = 'search-counter';
      buscador.parentElement.appendChild(contador);
    }

    if (buscador.value.trim() !== '') {
      contador.textContent = `${visible} de ${total} resultados`;
      contador.style.display = 'block';
    } else {
      contador.style.display = 'none';
    }
  }

  // ===== ANIMACIÓN DE FILAS =====
  function animarFilasTabla() {
    filas.forEach((fila, index) => {
      if (index < 15) {
        fila.style.opacity = '0';
        fila.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
          fila.style.transition = 'all 0.5s ease';
          fila.style.opacity = '1';
          fila.style.transform = 'translateX(0)';
        }, 100 + (index * 50));
      }
    });
  }

  // ===== TOOLTIPS =====
  function agregarTooltips() {
    const botones = document.querySelectorAll('[title]');
    
    botones.forEach(boton => {
      boton.addEventListener('mouseenter', function(e) {
        const tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = this.getAttribute('title');
        document.body.appendChild(tooltip);
        
        const rect = this.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
        
        this.setAttribute('data-tooltip-id', Date.now());
        tooltip.setAttribute('data-tooltip-id', this.getAttribute('data-tooltip-id'));
      });
      
      boton.addEventListener('mouseleave', function() {
        const tooltips = document.querySelectorAll('.custom-tooltip');
        tooltips.forEach(t => t.remove());
      });
    });
  }

  // ===== ANIMACIÓN EN HOVER DE QR =====
  const qrImages = document.querySelectorAll('.qr-image');
  qrImages.forEach(qr => {
    qr.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.1) rotate(3deg)';
    });
    
    qr.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1) rotate(0deg)';
    });
  });

  // ===== EFECTO EN BOTONES DE ACCIÓN =====
  const actionButtons = document.querySelectorAll('.btn-icon-action');
  actionButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      // Efecto ripple
      const ripple = document.createElement('span');
      ripple.className = 'ripple-effect';
      this.appendChild(ripple);
      
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = e.clientX - rect.left - size / 2 + 'px';
      ripple.style.top = e.clientY - rect.top - size / 2 + 'px';
      
      setTimeout(() => ripple.remove(), 600);
    });
  });

  // ===== HIGHLIGHT EN BÚSQUEDA =====
  function highlightText(text, search) {
    if (!search) return text;
    const regex = new RegExp(`(${search})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }

  // ===== SCROLL SUAVE A ELEMENTOS =====
  const tableWrapper = document.querySelector('.table-wrapper');
  if (tableWrapper) {
    let isScrolling;
    
    tableWrapper.addEventListener('scroll', function() {
      window.clearTimeout(isScrolling);
      
      tableWrapper.classList.add('is-scrolling');
      
      isScrolling = setTimeout(function() {
        tableWrapper.classList.remove('is-scrolling');
      }, 150);
    });
  }

  // ===== CONTADOR ANIMADO EN HEADER =====
  const statValue = document.querySelector('.stat-badge strong');
  if (statValue) {
    const finalValue = parseInt(statValue.textContent);
    if (!isNaN(finalValue)) {
      animateValue(statValue, 0, finalValue, 1500);
    }
  }

  function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
      current += increment;
      if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
        element.textContent = end;
        clearInterval(timer);
      } else {
        element.textContent = Math.floor(current);
      }
    }, 16);
  }

  // ===== EFECTO HOVER EN FILAS =====
  filas.forEach(fila => {
    fila.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.008)';
    });
    
    fila.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });
});

// ===== ESTILOS DINÁMICOS =====
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .custom-tooltip {
    position: fixed;
    background: rgba(27, 31, 37, 0.95);
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    pointer-events: none;
    z-index: 10000;
    border: 1px solid rgba(76, 201, 240, 0.3);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    animation: tooltipFade 0.2s ease;
  }

  @keyframes tooltipFade {
    from {
      opacity: 0;
      transform: translateY(5px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .search-counter {
    position: absolute;
    right: 14px;
    top: -24px;
    font-size: 0.8rem;
    color: var(--accent);
    font-weight: 600;
    display: none;
  }

  .search-box {
    position: relative;
  }

  .ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: scale(0);
    animation: ripple 0.6s ease-out;
    pointer-events: none;
  }

  @keyframes ripple {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }

  .table-wrapper.is-scrolling {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
  }

  mark {
    background: rgba(76, 201, 240, 0.3);
    color: var(--accent);
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
  }
`;
document.head.appendChild(style);