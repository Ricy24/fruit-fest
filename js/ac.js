   // Inicializa el mapa de Leaflet
   document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([4.58834, -74.14153], 15); // Coordenadas aproximadas de Kr 71-58.50 sur

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([4.58834, -74.14153]).addTo(map)
        .bindPopup('Kr 71-58-50 sur')
        .openPopup();
});

// Manejo del formulario de contacto
document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var form = this;

    fetch(form.action, {
        method: 'POST',
        body: new FormData(form)
    }).then(response => response.text())
    .then(data => {
        alert('Tu mensaje ha sido enviado con éxito.');
        window.location.href = 'index.php';
    }).catch(error => {
        console.error('Error:', error);
    });
});