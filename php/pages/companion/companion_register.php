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
    <title>BYP - Registrar Acompañante</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <main>
        <form action="/php/actions/companion/companion_register.php" method="post" id="companion_register_form">
            <div class="header-form">
                <a href="/php/super_user_panel.php">
                    <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                </a>
                <h1 class="form-title">Registrar Acompañante</h1>
            </div>
            <div class="form-section">
                <label for="companion_first_name">Nombre</label>
                <input type="text" name="companion_first_name"
                id="companion_first_name" placeholder="John" class="form-input">
                <span id="companion_first_name_msg"></span>
            </div>
            <div class="form-section">
                <label for="companion_last_name">Apellido</label>
                <input type="text" name="companion_last_name"
                id="companion_last_name" placeholder="Doe" class="form-input">
                <span id="companion_last_name_msg"></span>
            </div>
            <div class="form-section">
                <label for="companion_document">Documento</label>
                <input type="text" name="companion_document"
                id="companion_document" placeholder="54321092"
                inputmode="numeric" class="form-input">
                <span id="companion_document_msg"></span>
            </div>
            <div class="form-section">
                <input type="submit" value="REGISTRAR" id="companion_register_btn" class="form-button">
                <span id="companion_register_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/companion/register_companion.js"></script>
</body>
</html>