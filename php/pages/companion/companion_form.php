<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Acompañantes - BYP</title>
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
        <form action="" method="post" id="companion_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="companion_document">Cédula:</label>
                <input type="text" name="companion_document"
                id="companion_document" placeholder="52019851">
                <span id="companion_document_msg"></span>
            </div>
            <div>
                <label for="companion_first_name">Nombre:</label>
                <input type="text" name="companion_first_name"
                id="companion_first_name" placeholder="John">
                <span id="companion_first_name_msg"></span>
            </div>
            <div>
                <label for="companion_last_name">Apellido:</label>
                <input type="text" name="companion_last_name"
                id="companion_last_name" placeholder="Doe">
                <span id="companion_last_name_msg"></span>
            </div>
            <div>
                <a href="/php/pages/companion/manage_companions.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="companion_btn" class="form-btn"></button>
                <span id="companion_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/companion/form_companion.js"></script>
</body>

</html>