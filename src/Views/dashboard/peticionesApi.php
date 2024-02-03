<?php
use Controllers\UsuarioController;
$usuarioController = new UsuarioController();
if (!isset($_SESSION['login'])) {
    header('Location: ' . BASE_URL . 'usuario/login');
}
$id = $_SESSION['login']->id;
$tokenInfo = $usuarioController->obtenerToken($id);
$token = $tokenInfo['token'];
// $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDY5MDkzNDEsImV4cCI6MTcwNjkxMTE0MSwiZGF0YSI6WzEsIlBhYmxvIiwicGxvcGV6bG96YW5vMTJAZ21haWwuY29tIiwidXNlciIsMV19.279XegKeOHwcI-QjC_Y-223wt_R4a5Ene3omeKCCBUU";


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
    <p>Token: <?php echo $token; ?></p>
</div>

<div class="container">

    <h2>Obtener Equipos</h2>
    <button id="getEquipos" class="btn">Obtener Equipos</button>
    <p><strong class="success">GET</strong>/equipo</p>
    <div id="equipos" class="result-container"></div>
</div>

<script>
document.getElementById('getEquipos').addEventListener('click', function() {
    TOKEN = "<?php echo $token; ?>";
    fetch('http://localhost/API/equipo', {
  headers: {
    'Authorization': 'Bearer ' + TOKEN,
    'Content-Type': 'application/json'

  }
})
    .then(response => {return response.json();})
    .then(data => {
        document.getElementById('equipos').innerText = JSON.stringify(data, null, 2);
    })
    .catch(error => console.error('Error:', error));
});
</script>
<div class="container">
    <h2>Buscar Equipos</h2>
    <form id="buscarEquipos" method="GET" action="<?= BASE_URL ?>peticiones" class="search-form">
        <label for="id" class="label">ID del equipo:</label>
        <input type="text" id="id" name="id" class="input">
        <input type="submit" value="Buscar equipo" class="btn">
    </form>
    <p><strong class="success">GET</strong>/equipo/{id}</p>
    <div id="equipo" class="result-container"></div>
    
</div>

<script>
document.getElementById('buscarEquipos').addEventListener('submit', function(event) {
    event.preventDefault();

    var id = document.getElementById('id').value;
    TOKEN = "<?php echo $token; ?>";

    fetch('http://localhost/API/equipo/' + id, {
      headers: {
        'Authorization': 'Bearer ' + TOKEN,
        'Content-Type': 'application/json'
      }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('equipo').innerText = JSON.stringify(data, null, 2);
        })
        .catch(error => console.error('Error:', error));
});
</script>

<div class="container">
    <h2>Crear equipo</h2>
    <form id="crearEquipo">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre">
        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad">
        <label for="division">Division</label>
        <input type="text" id="division" name="division">
        <label for="color">Color</label>
        <input type="text" id="color" name="color">
        <label for="redes">Redes</label>
        <input type="text" id="redes" name="redes">

        <input type="submit" value="Crear">
    </form>
    <p><strong class="success">POST</strong>/equipo</p>
    <div id="resultadoCrear" class="result-container"></div>
</div>

<script>
    
    document.getElementById('crearEquipo').addEventListener('submit', function(event) {
    event.preventDefault();
    var id = document.getElementById('id').value;
    TOKEN = "<?php echo $token; ?>";

    var nombre = document.getElementById('nombre').value;
    var ciudad = document.getElementById('ciudad').value;
    var division = document.getElementById('division').value;
    var color = document.getElementById('color').value;
    var redes = document.getElementById('redes').value;

    var equipo = {
        nombre: nombre,
        ciudad: ciudad,
        division: division,
        color: color,
        redes: redes
    };

    TOKEN = "<?php echo $token; ?>";

    fetch('http://localhost/API/equipo', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + TOKEN,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(equipo),
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultadoCrear').innerText = JSON.stringify(data, null, 2);
    })
    .catch(error => console.error('Error:', error));
});
</script>


<div class="container">
    <h2>Borrar equipo</h2>
    <form id="borrarEquipo" method="DELETE">
        <label for="idBorrar">ID del Equipo:</label>
        <input type="text" id="idBorrar" name="idBorrar">
        <input type="submit" value="Borrar">
    </form>
    <p><strong class="error">DELETE</strong>/equipo/{id}</p>
    <div id="resultadoBorrar" class="result-container"></div>
</div>

<script>
document.getElementById('borrarEquipo').addEventListener('submit', function(event) {
    event.preventDefault();
    var id = document.getElementById('idBorrar').value;
    TOKEN = "<?php echo $token; ?>";

    fetch('http://localhost/API/equipo/' + id, {
        method: 'DELETE',         
        headers: {
            'Authorization': 'Bearer ' + TOKEN,
            'Content-Type': 'application/json',
        },
    },
    )
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultadoBorrar').innerText = JSON.stringify(data, null, 2);
    })
    .catch(error => console.error('Error:', error));
});
</script>

</body>
</html>
