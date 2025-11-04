document.addEventListener('DOMContentLoaded', () => {
  
  // ===== ANIMACIÓN DE CONTADORES =====
  const animateCounter = (element) => {
    const target = parseInt(element.getAttribute('data-target'));
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;
    
    const updateCounter = () => {
      current += increment;
      if (current < target) {
        element.textContent = Math.floor(current);
        requestAnimationFrame(updateCounter);
      } else {
        element.textContent = target;
      }
    };
    
    updateCounter();
  };
  
  const cardValues = document.querySelectorAll('.card-value[data-target]');
  cardValues.forEach(value => {
    setTimeout(() => animateCounter(value), 300);
  });

  // ===== ANIMACIÓN DE CARDS =====
  const cards = document.querySelectorAll('.card-enhanced');
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    setTimeout(() => {
      card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 100);
  });

  // ===== GRÁFICA DE ASISTENCIA CON CANVAS =====
  const canvas = document.getElementById('attendanceChart');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;
    
    // Configurar tamaño del canvas
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    
    // Obtener datos
    const presentBar = document.querySelector('.bar.present');
    const absentBar = document.querySelector('.bar.absent');
    
    const presentHeight = presentBar ? parseInt(presentBar.style.height) || 80 : 80;
    const absentHeight = absentBar ? parseInt(absentBar.style.height) || 40 : 40;
    
    // Configuración de la gráfica
    const barWidth = 80;
    const gap = 60;
    const maxHeight = rect.height - 60;
    const startX = (rect.width - (barWidth * 2 + gap)) / 2;
    
    let animationProgress = 0;
    const animationDuration = 1500;
    const startTime = Date.now();
    
    // Función de animación
    const drawChart = () => {
      const elapsed = Date.now() - startTime;
      animationProgress = Math.min(elapsed / animationDuration, 1);
      
      // Función de easing
      const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);
      const progress = easeOutCubic(animationProgress);
      
      // Limpiar canvas
      ctx.clearRect(0, 0, rect.width, rect.height);
      
      // Calcular alturas animadas
      const currentPresentHeight = (presentHeight / 100) * maxHeight * progress;
      const currentAbsentHeight = (absentHeight / 100) * maxHeight * progress;
      
      // Dibujar barra de Presente
      const presentGradient = ctx.createLinearGradient(0, rect.height - currentPresentHeight, 0, rect.height);
      presentGradient.addColorStop(0, '#4cc9f0');
      presentGradient.addColorStop(1, '#0d6efd');
      
      ctx.fillStyle = presentGradient;
      ctx.shadowColor = 'rgba(76, 201, 240, 0.5)';
      ctx.shadowBlur = 20;
      roundRect(ctx, startX, rect.height - 40 - currentPresentHeight, barWidth, currentPresentHeight, 10);
      ctx.fill();
      
      // Dibujar barra de Ausente
      const absentGradient = ctx.createLinearGradient(0, rect.height - currentAbsentHeight, 0, rect.height);
      absentGradient.addColorStop(0, '#dc143c');
      absentGradient.addColorStop(1, '#8b0000');
      
      ctx.fillStyle = absentGradient;
      ctx.shadowColor = 'rgba(220, 20, 60, 0.5)';
      ctx.shadowBlur = 20;
      roundRect(ctx, startX + barWidth + gap, rect.height - 40 - currentAbsentHeight, barWidth, currentAbsentHeight, 10);
      ctx.fill();
      
      // Resetear sombra
      ctx.shadowBlur = 0;
      
      // Dibujar etiquetas
      ctx.fillStyle = '#a9b3c1';
      ctx.font = '14px Poppins';
      ctx.textAlign = 'center';
      ctx.fillText('Presente', startX + barWidth / 2, rect.height - 15);
      ctx.fillText('Ausente', startX + barWidth + gap + barWidth / 2, rect.height - 15);
      
      // Continuar animación
      if (animationProgress < 1) {
        requestAnimationFrame(drawChart);
      }
    };
    
    // Función para dibujar rectángulos redondeados
    function roundRect(ctx, x, y, width, height, radius) {
      ctx.beginPath();
      ctx.moveTo(x + radius, y);
      ctx.lineTo(x + width - radius, y);
      ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
      ctx.lineTo(x + width, y + height - radius);
      ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
      ctx.lineTo(x + radius, y + height);
      ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
      ctx.lineTo(x, y + radius);
      ctx.quadraticCurveTo(x, y, x + radius, y);
      ctx.closePath();
    }
    
    drawChart();
    
    // Redimensionar canvas al cambiar tamaño de ventana
    window.addEventListener('resize', () => {
      const rect = canvas.getBoundingClientRect();
      canvas.width = rect.width * dpr;
      canvas.height = rect.height * dpr;
      ctx.scale(dpr, dpr);
      animationProgress = 1;
      drawChart();
    });
  }

  // ===== FILTROS DE TABLA =====
  const filterBtns = document.querySelectorAll('.filter-btn');
  const tableRows = document.querySelectorAll('tbody tr[data-movement]');
  
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter');
      
      // Actualizar botón activo
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      
      // Filtrar filas
      tableRows.forEach(row => {
        const movement = row.getAttribute('data-movement');
        if (filter === 'all' || movement === filter) {
          row.style.display = '';
          setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
          }, 10);
        } else {
          row.style.opacity = '0';
          row.style.transform = 'translateX(-20px)';
          setTimeout(() => {
            row.style.display = 'none';
          }, 300);
        }
      });
    });
  });

  // ===== ANIMACIÓN DE BARRA DE PROGRESO =====
  const progressFill = document.querySelector('.progress-fill');
  if (progressFill) {
    const percentage = progressFill.getAttribute('data-percentage');
    progressFill.style.width = '0%';
    
    setTimeout(() => {
      progressFill.style.width = percentage + '%';
    }, 500);
  }

  // ===== ANIMACIÓN DE ENTRADA DE SECCIONES =====
  const sections = document.querySelectorAll('.stats-enhanced, .records-enhanced, .progress-enhanced');
  
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '0';
        entry.target.style.transform = 'translateY(30px)';
        entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }, 100);
        
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);
  
  sections.forEach(section => observer.observe(section));

  // ===== EFECTO PARALLAX EN CARDS =====
  cards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      
      const rotateX = (y - centerY) / 20;
      const rotateY = (centerX - x) / 20;
      
      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
    });
    
    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
    });
  });

});