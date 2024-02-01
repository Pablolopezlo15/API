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
    <link rel="stylesheet" href="<?=BASE_URL?>public/css/styles.css">
</head>
<body>

    <header>
        <nav>
            <div class="navbar">
                <div class="nav-container">
                    <div>
                    </div>
                    <input class="checkbox" type="checkbox" name="" id="checkbox" />
                    <div class="hamburger-lines">
                        <span class="line line1"></span>
                        <span class="line line2"></span>
                        <span class="line line3"></span>
                    </div>  

                    <div class="nav-container login">
                        <?php if (!isset($_SESSION['login']) OR $_SESSION['login']=='failed'):?>
                            <a href="<?=BASE_URL?>usuario/login/">Identificarse</a>
                            <a href="<?=BASE_URL?>usuario/registro/">Registrarse</a>
                        <?php else:?>
                            <p><?=$_SESSION['login']->nombre?> <?=$_SESSION['login']->apellidos?></p>
                            <a href="<?=BASE_URL?>auth/nuevoToken/">Nuevo Token</a>
                            <a href="<?=BASE_URL?>usuario/logout/">Cerrar Sesión</a>
                        <?php endif;?>
                    </div>
                    <div class="logo">
                    </div>
                    <div class="menu-items" id="categoria">
                        <li><a href="<?= BASE_URL ?>peticiones">Pruebas</a></li>
                        
                    </div>
                </div>
            </div>
        </nav>
    </header>