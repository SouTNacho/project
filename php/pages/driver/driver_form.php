<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Conductores - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../super_user_pages/super_user_navbar.php';
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
        <form action="" method="post" id="driver_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="driver_document">Cédula:</label>
                <input type="text" name="driver_document"
                id="driver_document" placeholder="52019851">
                <span id="driver_document_msg"></span>
            </div>
            <div>
                <label for="driver_license_expiration">Vencimiento de Libreta:</label>
                <input type="date" name="driver_license_expiration" id="driver_license_expiration">
                <span id="driver_license_expiration_msg"></span>
            </div>
            <div>
                <label for="driver_license_category">Categoria de Libreta:</label>
                <select name="driver_license_category" id="driver_license_category">
                    <option value="">Seleccione una opción</option>
                    <option value="B">Categoría B</option>
                    <option value="C">Categoría C</option>
                    <option value="D">Categoría D</option>
                    <option value="E">Categoría E</option>
                    <option value="F">Categoría F</option>
                </select>
                <span id="driver_license_category_msg"></span>
            </div>
            <div>
                <label for="driver_password">Contraseña:</label>
                <input type="password" name="driver_password"
                id="driver_password">
                <span id="driver_password_msg"></span>
            </div>
            <div>
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" name="confirm_password"
                id="confirm_password">
                <span id="confirm_password_msg"></span>
            </div>
            <div>
                <a href="/php/pages/driver/manage_drivers.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="driver_btn" class="form-btn"></button>
                <span id="driver_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/driver/form_driver.js"></script>
</body>

</html>