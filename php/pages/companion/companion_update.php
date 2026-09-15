<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Acompañante - BYP</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <main>

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

        <form action="/php/actions/companion/companion_update.php" method="post" id="companion_update_form">
            <div class="header-form">
                <h2 class="form-title">Actualizar Acompañante</h2>
            </div>
            <div>
                <label for="companion_current_document">Cedula Actual:</label>
                <input type="text" name="companion_current_document"
                id="companion_current_document" placeholder="52019851">
                <span id="companion_current_document_msg"></span>
            </div>
            <div>
                <label for="companion_new_document">Nueva Cedula:</label>
                <input type="text" name="companion_new_document"
                id="companion_new_document" placeholder="52019851">
                <span id="companion_new_document_msg"></span>
            </div>
            <div>
                <label for="companion_first_name">Nombre:</label>
                <input type="text" name="companion_first_name"
                id="companion_first_name" placeholder="Jhon">
                <span id="companion_first_name_msg"></span>
            </div>
            <div>
                <label for="companion_last_name">Apellido:</label>
                <input type="text" name="companion_last_name"
                id="companion_last_name" placeholder="Doe">
                <span id="companion_last_name_msg"></span>
            </div>
            <div>
                <input type="submit" value="REGISTRAR" id="companion_update_btn">
                <span id="companion_update_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/companion/update_companion.js"></script>
</body>
</html>