function search() {
    const query = document.getElementById('search').value;
    fetch(`http://localhost/fruitfest/search.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            const searchResults = document.getElementById('search-results');
            searchResults.innerHTML = '';
            if (data.length > 0) {
                data.forEach(producto => {
                    const productoDiv = document.createElement('div');
                    productoDiv.innerHTML = `
                        <h2>${producto.nombre}</h2>
                        <p>${producto.descripcion}</p>
                        <p>Precio: $${producto.precio}</p>
                        <img src="${producto.imagen}" alt="${producto.nombre}" width="100">
                    `;
                    searchResults.appendChild(productoDiv);
                });
            } else {
                searchResults.innerHTML = '<p>No se encontraron productos.</p>';
            }
        })
        .catch(error => console.error('Error:', error));
}


