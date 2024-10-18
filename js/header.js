$(document).ready(function() {
    // Mostrar el formulario de dirección al hacer clic en el contenedor
    $('#openAddressForm').click(function() {
        $('#addressForm').fadeIn();

        // Prefill the form with current address
        var currentAddress = $('#addressText').text().replace('Se enviará a ', '');
        var addressParts = currentAddress.split(', ');
        if (addressParts.length === 3) {
            $('#ciudad').val(addressParts[0]);
            $('#direccion').val(addressParts[1]);
            $('#barrio').val(addressParts[2]);
        }
    });

    // Ocultar el formulario de dirección
    $('#closeAddressForm').click(function() {
        $('#addressForm').fadeOut();
    });

    // Enviar el formulario de dirección
    $('#addressSubmitForm').submit(function(event) {
        event.preventDefault();

        var ciudad = $('#ciudad').val();
        var direccion = $('#direccion').val();
        var barrio = $('#barrio').val();

        $.ajax({
            url: 'guardar_direccion.php',
            method: 'POST',
            data: {
                ciudad: ciudad,
                direccion: direccion,
                barrio: barrio
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#addressText').text('Se enviará a ' + ciudad + ', ' + direccion + ', ' + barrio);
                    $('#addressForm').fadeOut();
                } else {
                    alert('Error al guardar la dirección.');
                }
            }
        });
    });

    // Función para actualizar el número de productos en el carrito
    function actualizarCarrito() {
        $.ajax({
            url: 'actualizar_carrito.php',
            method: 'GET',
            success: function(response) {
                $('#cartCount').text('(' + response + ')');
            }
        });
    }

    // Llama a la función para actualizar el carrito cada cierto tiempo
    setInterval(actualizarCarrito, 2000); // Actualiza cada 2 segundos

    // Función para mostrar el menú desplegable de usuario
    $('.user-profile').click(function(event) {
        event.stopPropagation(); // Evita que el clic en el menú cierre el menú
        $(this).toggleClass('active');
    });

    // Cierra el menú si se hace clic fuera de él
    $(document).click(function() {
        $('.user-profile').removeClass('active');
    });
});
document.addEventListener('DOMContentLoaded', function() {
const searchForm = document.querySelector('.search-form');
const searchInput = searchForm.querySelector('input[type="search"]');
const searchButton = searchForm.querySelector('button.btn-search');



// Ocultar la barra de búsqueda si se hace clic fuera de ella
document.addEventListener('click', function(event) {
    if (!searchForm.contains(event.target)) {
        searchForm.classList.remove('active');
    }
});
});