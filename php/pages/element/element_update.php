<?php

    session_start();
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Actualizar Elemento</title>
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
        <form action="actions/element_update.php" method="post" id="element_update_form">
            <div class="header-form">
                <a href="#">
                    <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                </a>
                <h1 class="form-title">Actualizar Elemento</h1>
            </div>
            <section class="form-section">
                <label for="element_current_cod">Código Actual</label>
                <input type="text" name="element_current_cod"
                id="element_current_cod" placeholder="M0001" class="form-input">
                <span id="element_current_cod_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_name">Nombre</label>
                <input type="text" name="element_name"
                id="element_name" placeholder="Alcohol Rectificado" class="form-input">
                <span id="element_name_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_new_cod">Nuevo Código</label>
                <input type="text" name="element_new_cod"
                id="element_new_cod" placeholder="M0001" class="form-input">
                <span id="element_new_cod_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_type">Tipo</label>
                <select name="element_type" id="element_type">
                    <option value="">Seleccione una opción</option>
                    <option value="Biológico">Biológico</option>
                    <option value="No Biológico">No Biológico</option>
                </select>
                <span id="element_type_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_subtype">Subtipo</label>
                <select name="element_subtype" id="element_subtype">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="element_subtype_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_description">Descripción</label>
                <textarea
                    name="element_description"
                    id="element_description"
                    placeholder="Escriba una breve descripción"
                    class="form-input"></textarea>
                <span id="element_description_msg"></span>
            </section>
            <div>
                <input type="submit" value="ACTUALIZAR" id="element_update_btn" class="form-button">
                <span id="element_update_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/update_element.js"></script>
</body>
</html>