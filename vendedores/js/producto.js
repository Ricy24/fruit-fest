
    document.getElementById('productForm').addEventListener('submit', function (e) {
        e.preventDefault(); 

        var formData = new FormData(this);

        fetch('procesar_producto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('resultMessage');
            var notification = document.getElementById('successNotification');

            if (data.status === 'success') {
                messageDiv.innerHTML = ''; // Limpiar mensajes anteriores

                // Mostrar la notificación de éxito
                notification.classList.add('show');
                setTimeout(function() {
                    notification.classList.add('hide');
                    setTimeout(function() {
                        notification.classList.remove('show', 'hide');

                        // Actualizar la página después de que la notificación desaparezca
                        location.reload(); 
                    }, 400);
                }, 2000);

                document.getElementById('productForm').reset(); // Limpiar el formulario
                document.getElementById('image-preview').style.display = 'none'; // Ocultar vista previa
            } else {
                messageDiv.innerHTML = '<p style="color:red;">' + data.message + '</p>';
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function previewImage(event, previewElementId, containerId) {
        const imagePreview = document.getElementById(previewElementId);
        const imagePreviewContainer = document.getElementById(containerId);
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function () {
            imagePreview.src = reader.result;
            imagePreviewContainer.style.display = 'block'; // Mostrar la imagen
        }

        if (file) {
            reader.readAsDataURL(file); // Leer la imagen seleccionada
        } else {
            imagePreview.src = '#'; // Restablecer la vista previa
            imagePreviewContainer.style.display = 'none'; // Ocultar la imagen
        }
    }

    // Asociar la función de previsualización de imagen al cambio del input
    document.getElementById('imagen').addEventListener('change', function(event) {
        previewImage(event, 'image-preview', 'image-preview-container');
    });

    function openEditModal(productId) {
        // Llamada AJAX para obtener los detalles del producto
        fetch(`obtener_producto.php?id=${productId}`)
            .then(response => response.json())
            .then(product => {
                if (product) {
                    document.getElementById('productId').value = product.id;
                    document.getElementById('productName').value = product.nombre;
                    document.getElementById('productPrice').value = product.precio;
                    document.getElementById('productDescription').value = product.descripcion; // Añade este campo
                    document.getElementById('productStock').value = product.stock; // Añade este campo

                    // Previsualizar la imagen actual si existe
                    if (product.imagen) {
                        document.getElementById('imagePreview').src = product.imagen;
                        document.getElementById('imagePreviewContainer').style.display = 'block';
                    } else {
                        document.getElementById('imagePreviewContainer').style.display = 'none';
                    }

                    // Mostrar modal
                    document.getElementById('editModal').style.display = 'block'; 
                } else {
                    alert('Error al cargar el producto.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function submitEditForm(event) {
        event.preventDefault(); // Evitar el envío del formulario por defecto

        const formData = new FormData(document.getElementById('editForm'));

        fetch('guardar_producto.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message); // Notificación de éxito
                location.reload(); // Recargar la página para ver los cambios
            } else {
                alert(data.message); // Notificación de error
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    //ELIMINAR//
    function confirmDelete(productId) {
        if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
            fetch('eliminar_producto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `productId=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message); // Notificación de éxito
                    location.reload(); // Recarga la página para reflejar cambios
                } else {
                    alert(data.message); // Notificación de error
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el producto.');
            });
        }
    }

    // CUADROS DE ARRIBA 
    function openTab(event, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).classList.add("active");
        event.currentTarget.classList.add("active");
    }

    // Búsqueda con debounce
    const searchInput = document.getElementById('search-input');
    let debounceTimer;

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer); // Limpiar el temporizador anterior
        debounceTimer = setTimeout(() => {
            this.form.submit(); // Enviar el formulario automáticamente después de un retraso
        }, 300); // Esperar 300 ms antes de enviar el formulario
    });




    document.addEventListener('DOMContentLoaded', function () {
    // Manejador de envío del formulario para agregar productos
    document.getElementById('productForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Evitar el envío normal del formulario

        var formData = new FormData(this); // Crear un nuevo objeto FormData

        fetch('procesar_producto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('resultMessage');
            var notification = document.getElementById('successNotification');

            if (data.status === 'success') {
                messageDiv.innerHTML = ''; // Limpiar mensajes anteriores

                // Mostrar la notificación de éxito
                notification.classList.add('show');
                setTimeout(function() {
                    notification.classList.add('hide');
                    setTimeout(function() {
                        notification.classList.remove('show', 'hide');

                        // Actualizar la página después de que la notificación desaparezca
                        location.reload(); 
                    }, 400);
                }, 2000);

                document.getElementById('productForm').reset(); // Limpiar el formulario
                document.getElementById('image-preview').style.display = 'none'; // Ocultar vista previa
            } else {
                messageDiv.innerHTML = '<p style="color:red;">' + data.message + '</p>';
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Función para previsualizar la imagen
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function () {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block'; // Mostrar la imagen
        }

        if (file) {
            reader.readAsDataURL(file); // Leer la imagen seleccionada
        } else {
            imagePreview.src = '#'; // Restablecer la vista previa
            imagePreview.style.display = 'none'; // Ocultar la imagen
        }
    }

    // Escuchar cambios en el input de la imagen
    document.getElementById('image').addEventListener('change', previewImage);
});

