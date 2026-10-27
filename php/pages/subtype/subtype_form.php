<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('super_user');

?>
<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="../../../styles/notifications.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../js/notifications.js"></script>

    <meta charset="UTF-8">
    <title>Formulario de Subtipos - BYP</title>
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
        <form action="" method="post" id="subtypes_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="subtype_type">Tipo:</label>
                <select name="subtype_type" id="subtype_type">
                    <option value="">Seleccione una opción</option>
                    <option value="1">Biológico</option>
                    <option value="2">No biológico</option>
                </select>
                <span id="subtype_type_msg"></span>
            </div>
            <div>
                <label for="subtype_name">Nombre:</label>
                <input type="text" name="subtype_name"
                id="subtype_name" placeholder="Medicamentos">
                <span id="subtype_name_msg"></span>
            </div>
            <div>
                <a href="/php/pages/subtype/manage_subtypes.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="subtype_btn" class="form-btn"></button>
                <span id="subtype_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/subtype/form_subtype.js"></script>
</body>

</html>