<?php
if (!isset($_SESSION['login'])) {
    header('Location: ' . BASE_URL . 'login');
}
?>
<div class="content">
    <button id="getPonentes">Obtener ponentes</button>
    <div id="ponentes"></div>
</div>


<script>
document.getElementById('getPonentes').addEventListener('click', function() {
    fetch('http://localhost/ApiRestFul/ponente')
        .then(response => response.json())
        .then(data => {
            document.getElementById('ponentes').innerText = JSON.stringify(data, null, 2);
        })
        .catch(error => console.error('Error:', error));
});
</script>

<form id="buscarPonente" method="GET" action="<?= BASE_URL ?>peticiones">
    <label for="id">ID del ponente:</label>
    <input type="text" id="id" name="id">
    <input type="submit" value="Buscar ponente">
</form>
<div id="ponente"></div>

<script>
document.getElementById('buscarPonente').addEventListener('submit', function(event) {
    event.preventDefault();
    var id = document.getElementById('id').value;
    fetch('http://localhost/ApiRestFul/ponente/' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('ponente').innerText = JSON.stringify(data, null, 2);
        })
        .catch(error => console.error('Error:', error));
});
</script>