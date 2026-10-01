<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Elementos - BYP</title>
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
        <form action="" method="post" id="element_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="element_code">Código:</label>
                <input type="text" name="element_code"
                id="element_code" placeholder="M0001">
                <span id="element_code_msg"></span>
            </div>
            <div>
                <label for="element_name">Nombre:</label>
                <input type="text" name="element_name"
                id="element_name" placeholder="Alcohol Rectificado">
                <span id="element_name_msg"></span>
            </div>
            <div>
                <label for="element_type">Tipo:</label>
                <select name="element_type" id="element_type">
                    <option value="">Seleccione una opción</option>
                    <option value="Biológico">Biológico</option>
                    <option value="No Biológico">No Biológico</option>
                </select>
                <span id="element_type_msg"></span>
            </div>
            <div>
                <label for="element_subtype">Subtipo:</label>
                <select name="element_subtype" id="element_subtype">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="element_subtype_msg"></span>
            </div>
            <div>
                <label for="element_description">Descripción:</label>
                <textarea name="element_description" id="element_description" 
                placeholder="Escriba una breve descripción"></textarea>
                <span id="element_description_msg"></span>
            </div>
            <div>
                <a href="/php/pages/element/manage_elements.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="element_btn" class="form-btn"></button>
                <span id="element_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/element/form_element.js"></script>
</body>

</html>