<?php

    // Validar que solo el funcionario pueda realizar esto

    session_start();
    
    if (isset($_SESSION["success"])) {
        echo $_SESSION["success"];
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["errors"])) {
        echo $_SESSION["errors"];
        unset($_SESSION["errors"]);
    }
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Registrar Elemento</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
    <link rel="stylesheet" href="/styles/general_style.css">
</head>
<body>
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="actions/logout.php">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/things_ambulance_panel.php">Objetos Trasladados</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/element/element_register.php">Registrar Elemento</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/element/element_update.php">Gestionar Elementos</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/element/manage_elements.php">Admin. Elementos</a>
            </li>
        </ul>
    </nav>
    <main>
        <div class="form-container">
        <form action="actions/element_register.php" method="post" id="element_register_form">
            <div class="header-form">
               
                <h1 class="form-title">Registrar Elemento</h1>
            </div>
            <section class="form-section">
                <label for="element_cod">Código de Elemento</label>
                <input type="text" name="element_cod"
                id="element_cod" placeholder="M0001" class="form-input">
                <span id="element_cod_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_name">Nombre</label>
                <input type="text" name="element_name"
                id="element_name" placeholder="Alcohol Rectificado" class="form-input">
                <span id="element_name_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_type">Tipo</label>
                <select name="element_type" id="element_type" class="form-input">
                    <option value="">Seleccione una opción</option>
                    <option value="Biológico">Biológico</option>
                    <option value="No Biológico">No Biológico</option>
                </select>
                <span id="element_type_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_subtype">Subtipo</label>
                <select name="element_subtype" id="element_subtype" class="form-input">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="element_subtype_msg"></span>
            </section>
            <section class="form-section">
                <label for="element_description">Descripción</label>
                <textarea name="element_description" id="element_description" 
                placeholder="Escriba una breve descripción" class="form-input">
                </textarea>
                <span id="element_description_msg"></span>
            </section>
            <section class="form-section">
                <input type="submit" value="REGISTRAR" id="element_register_btn" class="form-button">
                <span id="element_register_btn_msg"></span>
            </section>
        </form>
</div>
    </main>
    <script type="module" src="/js/element/register_element.js"></script>
</body>
</html>