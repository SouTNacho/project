<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Rutas - BYP</title>
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
        <form action="" method="post" id="route_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="route_origin">Origen:</label>
                <input type="text" name="route_origin"
                id="route_origin" placeholder="Puerta de emergencia">
                <span id="route_origin_msg"></span>
            </div>
            <div>
                <label for="route_destination">Destino:</label>
                <input type="text" name="route_destination"
                id="route_destination" placeholder="CTI">
                <span id="route_destination_msg"></span>
            </div>
            <div>
                <a href="/php/pages/route/manage_routes.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="route_btn" class="form-btn"></button>
                <span id="route_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>
    
    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/route/form_route.js"></script>
</body>

</html>