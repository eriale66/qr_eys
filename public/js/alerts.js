// Archivo: public/js/alerts.js

function showAlert(type, message, redirect = null) {
  Swal.fire({
    icon: type,
    title: message,
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#0d6efd",
  }).then(() => {
    if (redirect) {
      window.location.href = redirect;
    }
  });
}

function confirmarEliminacion(nombre) {
  return Swal.fire({
    title: `¿Eliminar a ${nombre}?`,
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => result.isConfirmed);
}

// Muestra alertas a partir de parámetros en la URL: ?type=success|error|info&msg=Texto
document.addEventListener("DOMContentLoaded", () => {
  try {
    const params = new URLSearchParams(window.location.search);
    const type = params.get("type");
    const msg = params.get("msg");
    if (type && msg && typeof Swal !== "undefined") {
      showAlert(type, decodeURIComponent(msg));
      // Limpiar params del URL sin recargar
      const url = new URL(window.location.href);
      url.searchParams.delete("type");
      url.searchParams.delete("msg");
      window.history.replaceState({}, document.title, url.toString());
    }
  } catch (_) {
    // Silenciar errores de parsing para no romper la página
  }
});

