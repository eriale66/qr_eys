// Animaciones para las tarjetas de estadísticas
document.addEventListener("DOMContentLoaded", () => {
  // Animar valores de las tarjetas
  const statValues = document.querySelectorAll(".stat-value");
  statValues.forEach((stat) => {
    const finalValue = parseInt(stat.textContent);
    if (!isNaN(finalValue)) {
      animateValue(stat, 0, finalValue, 1200);
    }
  });

  // Animar aparición de tarjetas
  const statCards = document.querySelectorAll(".stat-card");
  statCards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    setTimeout(() => {
      card.style.transition = "all 0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 100);
  });

  // Animar aparición de gráficas
  const charts = document.querySelectorAll(".chart-container");
  charts.forEach((chart, index) => {
    chart.style.opacity = "0";
    chart.style.transform = "translateY(30px)";
    setTimeout(() => {
      chart.style.transition = "all 0.8s ease";
      chart.style.opacity = "1";
      chart.style.transform = "translateY(0)";
    }, 300 + index * 150);
  });

  // Animar filas de la tabla
  const tableRows = document.querySelectorAll(".data-table tbody tr");
  tableRows.forEach((row, index) => {
    if (index < 10) {
      // Solo animar las primeras 10 filas
      row.style.opacity = "0";
      row.style.transform = "translateX(-20px)";
      setTimeout(() => {
        row.style.transition = "all 0.4s ease";
        row.style.opacity = "1";
        row.style.transform = "translateX(0)";
      }, 800 + index * 50);
    }
  });
});

// Función para animar números
function animateValue(element, start, end, duration) {
  const range = end - start;
  const increment = range / (duration / 16);
  let current = start;

  const timer = setInterval(() => {
    current += increment;
    if (
      (increment > 0 && current >= end) ||
      (increment < 0 && current <= end)
    ) {
      element.textContent = end;
      clearInterval(timer);
    } else {
      element.textContent = Math.floor(current);
    }
  }, 16);
}

// Efecto hover en las tarjetas
const statCards = document.querySelectorAll(".stat-card");
statCards.forEach((card) => {
  card.addEventListener("mouseenter", function () {
    this.style.transform = "translateY(-6px) scale(1.02)";
  });

  card.addEventListener("mouseleave", function () {
    this.style.transform = "translateY(0) scale(1)";
  });
});

// Efecto de scroll para elementos
function revealOnScroll() {
  const elements = document.querySelectorAll(
    ".chart-container, .table-container"
  );

  elements.forEach((element) => {
    const elementTop = element.getBoundingClientRect().top;
    const elementVisible = 150;

    if (elementTop < window.innerHeight - elementVisible) {
      element.style.opacity = "1";
      element.style.transform = "translateY(0)";
    }
  });
}

window.addEventListener("scroll", revealOnScroll);

// Agregar efecto de carga a los botones de exportación
const exportButtons = document.querySelectorAll(".btn-export");
exportButtons.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    const originalText = this.innerHTML;
    this.style.pointerEvents = "none";
    this.innerHTML = "<span>⏳</span> Generando...";

    setTimeout(() => {
      this.innerHTML = originalText;
      this.style.pointerEvents = "auto";
    }, 2000);
  });
});

// Tooltip para badges
const badges = document.querySelectorAll(".badge, .badge-mov");
badges.forEach((badge) => {
  badge.addEventListener("mouseenter", function () {
    this.style.transform = "scale(1.05)";
  });

  badge.addEventListener("mouseleave", function () {
    this.style.transform = "scale(1)";
  });
});
