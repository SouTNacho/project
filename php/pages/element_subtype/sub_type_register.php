
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


            <form action="/php/actions/employee/employee_register.php" method="post" id="employee_register_form" class="form">         
           <div>
                <h2 class="form-title">Registrar Subtipo</h2>
            </div>
            <div>
                <label for="element_type">Tipo:</label>
                <select name="element_type" id="element_type">
                    <option value="">Seleccione una opción</option>
                    <option value="FA">Biológico</option>
                    <option value="DR">No biológico</option>
                </select>
                <span id="element_type_msg"></span>
            </div>

            <div>
                <label for="element_subtype">Subtipo:</label>
                <input type="text" name="element_subtype" id="element_subtype" placeholder="Subtipo">
                <span id="element_subtype_msg"></span>
            </div>
            
            <div>
             <a href="/php/pages/element_subtype/management_element_subtype.php">
                    <i data-lucide="arrow-left"></i>
                    Volver
                </a>

            
                <input type="submit" value="REGISTRAR" id="employee_register_btn" class="form-btn">
                <span id="employee_register_btn_msg"></span>
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
