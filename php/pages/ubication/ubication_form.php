<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('administrative');

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
                <label for="ubication_department">Departamento:</label>
                <select name="ubication_department" id="ubication_department">
                    <option value="">Seleccionar Opción</option>
                    <option value="Artigas">Artigas</option>
                    <option value="Canelones">Canelones</option>
                    <option value="Cerro Largo">Cerro Largo</option>
                    <option value="Colonia">Colonia</option>
                    <option value="Durazno">Durazno</option>
                    <option value="Flores">Flores</option>
                    <option value="Florida">Florida</option>
                    <option value="Lavalleja">Lavalleja</option>
                    <option value="Maldonado">Maldonado</option>
                    <option value="Montevideo">Montevideo</option>
                    <option value="Paysandú">Paysandú</option>
                    <option value="Río Negro">Río Negro</option>
                    <option value="Rivera">Rivera</option>
                    <option value="Rocha">Rocha</option>
                    <option value="Salto">Salto</option>
                    <option value="San José">San José</option>
                    <option value="Soriano">Soriano</option>
                    <option value="Tacuarembó">Tacuarembó</option>
                    <option value="Treinta y Tres">Treinta y Tres</option>
                </select>
                <span id="ubication_department_msg"></span>
            </div>
            <div>
                <label for="ubication_address">Dirección:</label>
                <input type="text" name="ubication_address"
                    id="ubication_address" placeholder="Av. Italia 1234">
                <span id="ubication_address_msg"></span>
            </div>
            <div class="hidden">
                <input type="text" name="ubication_lat"id="ubication_lat">
                <input type="text" name="ubication_long" id="ubication_long">
            </div>
            <div class="ubication-confirm-btn">
                <button type="button" id="confirm_address">
                    <i data-lucide="check"></i>Confirmar direccion
                </button>
            </div>
            <div id="map"></div>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/ubication/form_ubication.js"></script>
</body>

</html>