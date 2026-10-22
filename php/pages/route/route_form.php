<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('administrative');

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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
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
                <label for="route_name">Nombre:</label>
                <input type="text" name="route_name"
                id="route_name" placeholder="Hospital Maciel / Medica Uruguaya">
                <span id="route_name_msg"></span>
            </div>
            <div>
                <label for="route_origin">Origen:</label>
                <select name="route_origin" id="route_origin">
                    <option value="">Seleccione una opcion</option>
                </select>
                <span id="route_origin_msg"></span>
            </div>
            <div>
                <label for="route_intermedium">Punto intermedio:</label>
                <select name="route_intermedium" id="route_intermedium">
                    <option value="">Seleccione una opcion</option>
                </select>
                <span id="route_intermedium_msg"></span>
            </div>
            <div>
                <label for="route_destination">Destino:</label>
                <select name="route_destination" id="route_destination">
                    <option value="">Seleccione una opcion</option>
                </select>
                <span id="route_destination_msg"></span>
            </div>
            <div>
                <input type="hidden" name="origin_lat" id="origin_lat">
                <input type="hidden" name="origin_long" id="origin_long">
                <input type="hidden" name="intermedium_lat" id="intermedium_lat">
                <input type="hidden" name="intermedium_long" id="intermedium_long">
                <input type="hidden" name="finish_lat" id="finish_lat">
                <input type="hidden" name="finish_long" id="finish_long">
            </div>
            <div class="ubication-confirm-btn">
                <button type="button" id="confirm_address">
                    <i data-lucide="check"></i>Confirmar ruta
                </button>
            </div>
            <div id="map"></div>
            <div>
                <p>Distancia: <span id="route_distance">—</span></p>
                <p>Tiempo estimado: <span id="route_time">—</span></p>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/route/form_route.js"></script>
</body>

</html>