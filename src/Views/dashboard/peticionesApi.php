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
    <button id="getPonentes" class="btn">Obtener ponentes</button>
    <h4>Obtener todos los ponentes</h4>
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

</body>
</html>
