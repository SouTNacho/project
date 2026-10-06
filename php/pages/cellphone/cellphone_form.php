<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Teléfono - BYP</title>
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
        <form action="" method="post" id="cellphone_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="employee_document">Cédula del Funcionario:</label>
                <input type="text" name="employee_document"
                id="employee_document" placeholder="54037896">
                <span id="employee_document_msg"></span>
            </div>
            <div class="phone_container">
                <label for="cellphone_number">Número de Celular:</label>
                <select name="cellphone_code" id="cellphone_code">
                    <option value="">Seleccione una opción</option>
                </select>
                <input type="text" name="cellphone_number"
                id="cellphone_number" placeholder="99789876" inputmode="numeric">
                <span id="cellphone_msg"></span>
            </div>
            <div>
                <a href="/php/pages/cellphone/manage_cellphones.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="cellphone_btn" class="form-btn"></button>
                <span id="cellphone_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/cellphone/form_cellphone.js"></script>
</body>

</html>