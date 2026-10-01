<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Ambulancias - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../administrative_navbar.php';
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
        <form action="" method="post" id="ambulance_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="ambulance_registration">Matrícula:</label>
                <input type="text" name="ambulance_registration"
                id="ambulance_registration" placeholder="IAD1234">
                <span id="ambulance_registration_msg"></span>
            </div>
            <div>
                <label for="ambulance_brand">Marca:</label>
                <input type="text" name="ambulance_brand"
                id="ambulance_brand" placeholder="Mercedes Benz">
                <span id="ambulance_brand_msg"></span>
            </div>
            <div>
                <label for="ambulance_model">Modelo:</label>
                <input type="text" name="ambulance_model"
                id="ambulance_model" placeholder="Spark">
                <span id="ambulance_model_msg"></span>
            </div>
            <div>
                <label for="ambulance_year">Año:</label>
                <input type="text" name="ambulance_year"
                id="ambulance_year" placeholder="2025">
                <span id="ambulance_year_msg"></span>
            </div>
            <div>
                <a href="/php/pages/ambulance/manage_ambulances.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="ambulance_btn" class="form-btn"></button>
                <span id="ambulance_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/ambulance/form_ambulance.js"></script>
</body>

</html>