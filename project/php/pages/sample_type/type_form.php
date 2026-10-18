
<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Tipo Muestra - BYP</title>
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


            <form action="/php/actions/sample_type/type_register.php" method="post" id="sample_type_register_form" class="form">         
           <div>
                <h2 class="form-title">Registrar Tipo de Muestra</h2>
            </div>


            <div>
                <label for="sample_type">Tipo:</label>
                <input type="text" name="sample_type" id="sample_type" placeholder="Tipo de muestra">
                <span id="sample_type_msg"></span>
            </div>
            
            <div>
             <a href="/php/pages/sample_type/manage_sample_type.php">
                    <i data-lucide="arrow-left"></i>
                    Volver
                </a>

            
                <input type="submit" value="REGISTRAR" id="sample_type_register_btn" class="form-btn">
                <span id="sample_type_register_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/sample_type/type_register.js"></script>

    <?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>
