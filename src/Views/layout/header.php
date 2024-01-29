<?php
    use Controllers\CategoriaController;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tienda Pablo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.7.0/remixicon.css"></link>
    <!-- <link rel="stylesheet" href="<?=BASE_URL?>/src/css/styles.css"> -->
    <link rel="stylesheet" href="<?=BASE_URL?>public/css/normalize.css">
    <link rel="stylesheet" href="<?=BASE_URL?>public/css/styles2.css">
</head>
<body>

    <header>
        <h2>API PABLO</h2>
        <nav>
            <ul>
                <?php if(!isset($_SESSION['login'])): ?>
                <li><a href="<?=BASE_URL?>usuario/login">Login</a></li>
                <?php else: ?>
                <li><a href="<?=BASE_URL?>usuario/logout">Cerrar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>