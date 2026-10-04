
<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Super Usuario - BYP</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
<link rel="stylesheet" href="/styles/general_style.css">
<link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../super_user_navbar.php';
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


            <form action="/php/actions/super_user/super_user_register.php" method="post" id="super_user_register_form" class="form">
            <div>
                <h2 class="form-title">Registrar Super Usuario</h2>
            </div>
            <div>
                <label for="super_user_name">Nombre:</label>
                <input type="text" name="super_user_name" id="super_user_name" placeholder="Admin 001">
                <span id="super_user_name_msg"></span>
            </div>
            <div>
                <label for="super_user_permissions">Permisos:</label>
                <select name="super_user_permissions" id="super_user_permissions">
                    <option value="">Seleccione una opción</option>
                    <option value="Low">Bajos</option>
                    <option value="Mid">Medios</option>
                    <option value="High">Altos</option>
                </select>
                <span id="super_user_permissions_msg"></span>
            </div>
            <div>
                <label for="super_user_password">Contraseña:</label>
                <input type="password" name="super_user_password" id="super_user_password">
                <span id="super_user_password_msg"></span>
            </div>
            <div>
                <label for="super_user_confirm_password">Confirmar Contraseña:</label>
                <input type="password" name="super_user_confirm_password" id="super_user_confirm_password">
                <span id="super_user_confirm_password_msg"></span>
            </div>
            <div>
                <a href="/php/pages/super_user/manage_super_user.php">
                    <i data-lucide="arrow-left"></i>
                    Volver
                </a>

                <input type="submit" value="REGISTRAR" id="super_user_register_btn" class="form-btn">
                <span id="super_user_register_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/super_user/register_super_user.js"></script>

    <?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>
</body>
</html>
