document.getElementById("botonCerrar").addEventListener("click", () => {
  Swal.fire({
    title: "¿Estás Seguro?",
    text: "Estás a punto de cerrar sesión",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, cerrar sesión",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "../pages/salir.php";
    }
  });
});
