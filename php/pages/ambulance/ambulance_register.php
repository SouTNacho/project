<?php

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
    <title>BYP - Registrar ambulancia</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/document_style.css">
</head>
<body>

    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="/php/pages/administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="/php/actions/logout.php">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_panel.php">Panel Ambulancias</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_register.php">Registrar Ambulancia</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/ambulance_update.php">Act. Ambulancia</a>
            </li>
            <li class="navbar_list_item">
                <a href="/php/pages/ambulance/manage_ambulances.php">Gestionar Ambulancia</a>
            </li>
        </ul>
    </nav>
    <main>
        <form action="/php/actions/ambulance/ambulance_register.php" method="post" id="ambulance_register_form">
            <div class="header-form">
                
                <h1 class="form-title">Registrar ambulancia</h1>
            </div>
            <section class="form-section">
                <label for="ambulance_registration">Matricula</label>
                <input type="text" name="ambulance_registration"
                id="ambulance_registration" placeholder="IAD1234" class="form-input">
                <span id="ambulance_registration_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_brand">Marca</label>
                <input type="text" name="ambulance_brand"
                id="ambulance_brand" placeholder="Mercedez Benz" class="form-input">
                <span id="ambulance_brand_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_model">Modelo</label>
                <input type="text" name="ambulance_model"
                id="ambulance_model" placeholder="Spark" class="form-input">
                <span id="ambulance_model_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_year">Año</label>
                <input type="text" name="ambulance_year"
                id="ambulance_year" placeholder="2025" class="form-input">
                <span id="ambulance_year_msg"></span>
            </section>
            <section class="form-section">
                <label for="ambulance_description">Descripción</label>
                <textarea name="ambulance_description" id="ambulance_description" 
                placeholder="Escriba una breve descripción" class="form-input"></textarea>
                <span id="ambulance_description_msg"></span>
            </section>
            <div>
                <input type="submit" value="REGISTRAR" id="ambulance_register_btn" class="form-button">
                <span id="ambulance_register_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/ambulance/register_ambulance.js"></script>
    
</body>
</html>