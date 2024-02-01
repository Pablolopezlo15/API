<?php
if (!isset($_SESSION['login'])) {
    header('Location: ' . BASE_URL . 'usuario/login');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API PABLO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
</head>
<body>

<div class="container">
    <h2>Obtener Ponentes</h2>
    <button id="getPonentes" class="btn">Obtener ponentes</button>
    <p><strong class="success">GET</strong>/ponente</p>
    <div id="ponentes" class="result-container"></div>
</div>

<script>
document.getElementById('getPonentes').addEventListener('click', function() {
    fetch('http://localhost/API/ponente')
        .then(response => response.json())
        .then(data => {
            document.getElementById('ponentes').innerText = JSON.stringify(data, null, 2);
        })
        .catch(error => console.error('Error:', error));
});
</script>
<div class="container">
    <h2>Buscar Ponente</h2>
    <form id="buscarPonente" method="GET" action="<?= BASE_URL ?>peticiones" class="search-form">
        <label for="id" class="label">ID del ponente:</label>
        <input type="text" id="id" name="id" class="input">
        <input type="submit" value="Buscar ponente" class="btn">
    </form>
    <p><strong class="success">GET</strong>/ponente/{id}</p>
    <div id="ponente" class="result-container"></div>
</div>


<script>
document.getElementById('buscarPonente').addEventListener('submit', function(event) {
    event.preventDefault();
    var id = document.getElementById('id').value;
    fetch('http://localhost/API/ponente/' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('ponente').innerText = JSON.stringify(data, null, 2);
        })
        .catch(error => console.error('Error:', error));
});
</script>

<div class="container">
    <h2>Crear Ponente</h2>
    <form id="crearPonente">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre">
        <label for="apellido">Apellidos:</label>
        <input type="text" id="apellidos" name="apellido">
        <label for="imagen">Imagen</label>
        <input type="text" id="imagen" name="imagen">
        <label for="tags">Tags</label>
        <input type="text" id="tags" name="tags">
        <label for="redes">Redes</label>
        <input type="text" id="redes" name="redes">

        <input type="submit" value="Crear">
    </form>
    <p><strong class="success">POST</strong>/ponente</p>
    <div id="resultadoCrear" class="result-container"></div>
</div>

<script>
    document.getElementById('crearPonente').addEventListener('submit', function(event) {
    event.preventDefault();
    var nombre = document.getElementById('nombre').value;
    var apellido = document.getElementById('apellidos').value;
    var imagen = document.getElementById('imagen').value;
    var tags = document.getElementById('tags').value;
    var redes = document.getElementById('redes').value;

    var ponente = {nombre: nombre, apellidos: apellidos, imagen: imagen, tags: tags, redes: redes};
    fetch('http://localhost/API/ponente', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(ponente),
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultadoCrear').innerText = JSON.stringify(data, null, 2);
    })
    .catch(error => console.error('Error:', error));
});
</script>


<div class="container">
    <h2>Borrar Ponente</h2>
    <form id="borrarPonente" method="DELETE">
        <label for="idBorrar">ID del Ponente:</label>
        <input type="text" id="idBorrar" name="idBorrar">
        <input type="submit" value="Borrar">
    </form>
    <p><strong class="error">DELETE</strong>/ponente/{id}</p>
    <div id="resultadoBorrar" class="result-container"></div>
</div>

<script>
document.getElementById('borrarPonente').addEventListener('submit', function(event) {
    event.preventDefault();
    var id = document.getElementById('idBorrar').value;
    fetch('http://localhost/API/ponente/' + id, {method: 'DELETE'})
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultadoBorrar').innerText = JSON.stringify(data, null, 2);
    })
    .catch(error => console.error('Error:', error));
});
</script>

</body>
</html>
