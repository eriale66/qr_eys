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

  // ===== GRÁFICA DE ASISTENCIA CON CHART.JS =====
  const canvas = document.getElementById('attendanceChart');
  let attendanceChart = null;

  // Función para obtener colores según el tema
  const getChartColors = () => {
    const theme = document.documentElement.getAttribute('data-theme') || 'dark';
    const isDark = theme === 'dark';

    return {
      present: {
        gradient: isDark ? ['#4cc9f0', '#0d6efd'] : ['#06b6d4', '#0891b2'],
        shadow: isDark ? 'rgba(76, 201, 240, 0.4)' : 'rgba(6, 182, 212, 0.3)'
      },
      absent: {
        gradient: isDark ? ['#dc143c', '#8b0000'] : ['#ef4444', '#dc2626'],
        shadow: isDark ? 'rgba(220, 20, 60, 0.4)' : 'rgba(239, 68, 68, 0.3)'
      },
      text: isDark ? '#a9b3c1' : '#6b7280',
      grid: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
    };
  };

  // Función para crear la gráfica
  const createAttendanceChart = () => {
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const colors = getChartColors();

    // Obtener datos del DOM
    const presentLegend = document.querySelector('.legend-item:first-child span:last-child');
    const absentLegend = document.querySelector('.legend-item:last-child span:last-child');

    let presentValue = 0;
    let absentValue = 0;

    if (presentLegend) {
      const match = presentLegend.textContent.match(/\((\d+)\)/);
      presentValue = match ? parseInt(match[1]) : 0;
    }

    if (absentLegend) {
      const match = absentLegend.textContent.match(/\((\d+)\)/);
      absentValue = match ? parseInt(match[1]) : 0;
    }

    // Crear gradientes
    const presentGradient = ctx.createLinearGradient(0, 0, 0, 250);
    presentGradient.addColorStop(0, colors.present.gradient[0]);
    presentGradient.addColorStop(1, colors.present.gradient[1]);

    const absentGradient = ctx.createLinearGradient(0, 0, 0, 250);
    absentGradient.addColorStop(0, colors.absent.gradient[0]);
    absentGradient.addColorStop(1, colors.absent.gradient[1]);

    // Destruir gráfica anterior si existe
    if (attendanceChart) {
      attendanceChart.destroy();
    }

    // Crear nueva gráfica
    attendanceChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Presente', 'Ausente'],
        datasets: [{
          label: 'Personal',
          data: [presentValue, absentValue],
          backgroundColor: [presentGradient, absentGradient],
          borderColor: [colors.present.gradient[0], colors.absent.gradient[0]],
          borderWidth: 2,
          borderRadius: 12,
          borderSkipped: false,
          barThickness: 80,
          shadowOffsetX: 0,
          shadowOffsetY: 4,
          shadowBlur: 20,
          shadowColor: [colors.present.shadow, colors.absent.shadow]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: 'easeOutCubic'
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            borderColor: colors.present.gradient[0],
            borderWidth: 1,
            displayColors: false,
            callbacks: {
              label: function(context) {
                return context.parsed.y + ' empleado' + (context.parsed.y !== 1 ? 's' : '');
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: colors.text,
              font: {
                family: 'Poppins',
                size: 12
              },
              stepSize: 1
            },
            grid: {
              color: colors.grid,
              drawBorder: false
            }
          },
          x: {
            ticks: {
              color: colors.text,
              font: {
                family: 'Poppins',
                size: 13,
                weight: '500'
              }
            },
            grid: {
              display: false
            }
          }
        },
        layout: {
          padding: {
            top: 10,
            bottom: 5
          }
        }
      }
    });
  };

  // Crear la gráfica al cargar
  if (canvas) {
    setTimeout(createAttendanceChart, 500);
  }

  // Escuchar cambios de tema para actualizar la gráfica
  const themeToggleBtn = document.getElementById('themeToggle');
  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
      setTimeout(() => {
        createAttendanceChart();
      }, 100);
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