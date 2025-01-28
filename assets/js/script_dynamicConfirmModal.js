// Función para configurar y mostrar el modal dinámico
function showDynamicConfirmModal(options) {
    // Obtener los elementos del modal
    const modalHeader = document.querySelector('#dynamicConfirmModal .modal-header');
    const modalTitle = document.getElementById('dynamicConfirmModalLabel');
    const modalBody = document.querySelector('#dynamicConfirmModal .modal-body');
    const confirmBtn = document.getElementById('confirmBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const modalFooter = document.querySelector('#dynamicConfirmModal .modal-footer');
    
    // Establecer el estylo del header
    modalHeader.className += ` ${options.confirmHeaderClass || 'bg-danger bg-gradient'}`;
    // Establecer el título del modal
    modalTitle.textContent = options.title || 'Confirmar acción';

    // Establecer el estylo del body
    modalBody.className += ` ${options.confirmBodyClass || 'bg-light bg-gradient'}`;
    // Establecer el contenido del cuerpo del modal
    modalBody.innerHTML = options.body || '¿Estás seguro de que deseas realizar esta acción?';

    // Establecer el estylo del footer
    modalFooter.className += ` ${options.confirmFooterClass || 'bg-light'}`;

    // Establecer el texto del botón de confirmar
    confirmBtn.textContent = options.confirmText || 'Confirmar';
    confirmBtn.className = `btn ${options.confirmBtnClass || 'btn-primary'}`;

    // Establecer el texto del botón de cancelar
    cancelBtn.textContent = options.cancelText || 'Cancelar';
    cancelBtn.className = `btn ${options.cancelBtnClass || 'btn-secondary'}`;

    // Remover cualquier evento anterior en el botón de confirmar
    confirmBtn.replaceWith(confirmBtn.cloneNode(true)); // Remover listeners previos
    const newConfirmBtn = document.getElementById('confirmBtn');

    // Asignar el evento de confirmación
    newConfirmBtn.addEventListener('click', function() {
        if (options.onConfirm && typeof options.onConfirm === 'function') {
            options.onConfirm();  // Ejecutar la función de confirmación pasada por el usuario
        }
        // Ocultar el modal después de confirmar
        bootstrap.Modal.getInstance(document.getElementById('dynamicConfirmModal')).hide();
    });

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('dynamicConfirmModal'));
    modal.show();
}