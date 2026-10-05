<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Ubicaciones - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../administrative_pages/administrative_navbar.php';
    ?>
    <main id="main" class="form-main">
        <?php
            if (isset($_SESSION["success"])) {
                echo '<div class="success-message">' . $_SESSION["success"] . '</div>';
                unset($_SESSION["success"]);
            }

            if (isset($_SESSION["errors"])) {
                echo '<div class="error-message">' . $_SESSION["errors"] . '</div>';
                unset($_SESSION["errors"]);
            }
        ?>
        <form action="" method="post" id="ubication_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="ubication_name">Nombre:</label>
                <input type="text" name="ubication_name"
                    id="ubication_name" placeholder="Puerta de emergencia" >
                <span id="ubication_name_msg"></span>
            </div>

            <div>
                <label for="ubication_address">Dirección:</label>
                <input type="text" name="ubication_address"
                    id="ubication_address" placeholder="Av. Italia 1234">
                <span id="ubication_address_msg"></span>
            </div>

            <div>
                <label for="ubication_latitude">Latitud:</label>
                <input type="text" name="ubication_latitude"
                    id="ubication_latitude" placeholder="-34.8912345">
                <span id="ubication_latitude_msg"></span>
            </div>

            <div>
                <label for="ubication_longitude">Longitud:</label>
                <input type="text" name="ubication_longitude"
                    id="ubication_longitude" placeholder="-56.1234567">
                <span id="ubication_longitude_msg"></span>
            </div>
            <div>
                <a href="/php/pages/ubication/manage_ubications.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="ubication_btn" class="form-btn"></button>
                <span id="ubication_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/ubication/form_ubication.js"></script>
</body>

</html>