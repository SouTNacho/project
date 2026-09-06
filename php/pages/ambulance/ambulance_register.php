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
</head>
<body>
    <main>
        <form action="/php/actions/ambulance/ambulance_register.php" method="post" id="ambulance_register_form">
            <div class="header-form">
                <a href="#">
                    <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                </a>
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