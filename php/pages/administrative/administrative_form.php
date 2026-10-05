<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Administrativos - BYP</title>
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
        <form action="" method="post" id="administrative_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="administrative_document">Cédula:</label>
                <input type="text" name="administrative_document"
                id="administrative_document" placeholder="52019851">
                <span id="administrative_document_msg"></span>
            </div>
            <div>
                <label for="administrative_permissions">Permisos:</label>
                <select name="administrative_permissions" id="administrative_permissions">
                    <option value="">Seleccione una opción</option>
                    <option value="low">Pasante</option>
                    <option value="mid">Principiante</option>
                    <option value="high">Titular</option>
                </select>
                <span id="administrative_permissions_msg"></span>
            </div>
            <div>
                <label for="administrative_password">Contraseña:</label>
                <input type="password" name="administrative_password"
                id="administrative_password">
                <span id="administrative_password_msg"></span>
            </div>
            <div>
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" name="confirm_password"
                id="confirm_password">
                <span id="confirm_password_msg"></span>
            </div>
            <div>
                <a href="/php/pages/administrative/manage_administratives.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="administrative_btn" class="form-btn"></button>
                <span id="administrative_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/administrative/form_administrative.js"></script>
</body>

</html>