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
      window.location.href = "../../pages/salir.php";
    }
  });
});

function eliminarProveedor(id_proveedor) {
  Swal.fire({
    title: "¿Estás Seguro?",
    text: "Estás a punto de eliminar este proveedor",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // window.location.href = "../../procesos/eliminar_proveedores.php?id_proveedor=" + id_proveedor;
      window.location.href="../../procesos/eliminar_proveedores.php?id_proveedor=" + id_proveedor;
    }
  });
}
function eliminarCategoria(id_categoria) {
  Swal.fire({
    title: "¿Estás Seguro?",
    text: "Estás a punto de eliminar esta categoria",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href="../../procesos/eliminar_categoria.php?id_categoria=" + id_categoria;
    }
  });
  
}
