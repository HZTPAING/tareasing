document.addEventListener('DOMContentLoaded', function() {
    // alert('sdfsdfs123133');
})

// Función para manejar la eliminación de una tarea
function eliminarTarea(event) {
    event.preventDefault(); // Evita el comportamiento predeterminado del enlace

    // Referencia al contenedor de los mensajes
    const mensajeStatus = document.getElementById('mensajeStatus');
    
    // Obtener datos de la tarea
    const botonEliminar = event.target;
    const rowId = botonEliminar.dataset.rowId;
    const nombre = botonEliminar.dataset.nombre;

    // Confirmacion del Borrar la tarea   
    showDynamicConfirmModal({
        confirmHeaderClass: 'bg-danger bg-gradient',
        title: 'Confirmación de eliminación',
        body: `¿Estás seguro de que deseas borrar la Tarea:<br><snap style="color: blue; font-weight: bold"> "${nombre}"</snap><br>Esta acción no se puede deshacer.`,
        confirmText: 'Borrar',
        cancelText: 'Cancelar',
        confirmBtnClass: 'btn btn-danger',
        cancelBtnClass: 'btn btn-secondary',
        confirmHeaderClass: 'bg-danger',
        confirmBodyClass: 'bg-danger-light',
        confirmFooterClass: 'bg-danger-light',

        onConfirm: function() {
            alert(`${BASE_URL}/controller/controller.php`);
            // La implementacion de la logica de borrar la tarea
            // El principio de la solicitud Fetch API al backend (controller.php)
            fetch(`${BASE_URL}/controller/tareas_controller.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'views': 'tareas_listado',
                    'action': 'DELETE_TAREA_AJAX',
                    'rowId': rowId,                               // La clave de la tarea
                    'numero': nombre                              // El nombre de la tarea
                })
            })
            .then(response => response.json()) // Esperar una respuesta JSON
            .then(data => {
                alert("Tareas: " + JSON.stringify(data));
                if (data.success) {
                    // Buscar la Card de la Tarea a la que estamos eliminando
                    confirmModal.hide();
                    const tareaCard = document.querySelector(`[data-rowid="${rowId}"]`).closest('.tarea-card');
                    if (tareaCard) {
                        // Eliminar la Card de la Tarea
                        tareaCard.remove();
                    }

                    // Mostrar mensaje de éxito si se borroó correctamente
                    const mensajeSuccess = `
                        <strong>Éxito: </strong> 
                        ${data.msg01} 
                        <strong> "${data.msg02}" </strong>
                        ${data.msg03}
                    `;
                    mensajeStatus.innerHTML = htmlMessageZone('success', mensajeSuccess);

                } else {
                    // Mostrar mensaje de error si no se pudo borra la tarea
                    const mensajeError = `
                        <strong>Error: </strong> 
                        ${data.msg01} 
                        <strong> "${data.msg01}" </strong>
                        ${data.msg03}
                    `;
                    mensajeStatus.innerHTML = htmlMessageZone('danger', mensajeError);
                }
            })
            .catch(error => {
                // Mostrar mensaje de error si no se pudo completar la solicitud AJAX
                description = 'Error al intentar borrar la tarea ';
                const mensajeError = `
                    <strong>Error: </strong> 
                    ${description}
                    <strong> "${nameDepartamento}" </strong>
                    intente nuevamente más tarde.
                `;
                mensajeStatus.innerHTML = htmlMessageZone('danger', mensajeError);
            });
            // El final de la solicitud Fetch API al backend (controller.php)
        }
    });
}