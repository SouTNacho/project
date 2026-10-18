
<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Subtipo - BYP</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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


            <form action="/php/actions/element/subtype_register.php" method="post" id="subtype_form" class="form">         
           <div>
                <h2 class="form-title" id="subtype_title">Registrar Subtipo</h2>
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
                <label for="subtype">Subtipo:</label>
                <input type="text" name="subtype" id="subtype" placeholder="Subtipo">
                <span id="subtype_msg"></span>
            </div>
            
            <div>
             <a href="/php/pages/element_subtype/management_element_subtype.php">
                    <i data-lucide="arrow-left"></i>
                    Volver
                </a>

            
                <input type="submit" value="REGISTRAR" id="subtype_btn" class="form-btn">
                <span id="subtype_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/element_subtype/subtype_register.js"></script>

    <?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>
