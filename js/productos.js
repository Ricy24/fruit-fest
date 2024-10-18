function agregarAlCarrito(productoId) {
    $.ajax({
        url: 'productos.php',
        method: 'POST',
        data: {
            action: 'add',
            id: productoId
        },
        dataType: 'json',
        success: function (response) {
            mostrarNotificacion(response.message, response.status === 'success');
        }
    });
}


function agregarMercadoListo() {
    $.ajax({
        url: 'productos.php',
        method: 'POST',
        data: {
            action: 'mercadoListo'
        },
        dataType: 'json',
        success: function (response) {
            mostrarNotificacion(response.message, response.status === 'success');
        }
    });
}

function mostrarNotificacion(mensaje, esExitoso) {
    var notification = $('#notification');
    notification.removeClass('error').addClass(esExitoso ? 'success' : 'error');
    notification.find('.notification-message').text(mensaje);
    notification.addClass('show');
    setTimeout(function () {
        notification.removeClass('show');
    }, 3000);
}
