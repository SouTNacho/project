
<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Super Usuario - BYP</title>
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
        <form action="/php/actions/super_user/super_user_update.php" method="post" id="super_user_update_form">
            <div>
                <h2>Actualizar Super Usuario</h2>
            </div>
            <div>
                <label for="super_user_code">Código de Super Usuario:</label>
                <input type="text" name="super_user_code" id="super_user_code" placeholder="SU00000001">
                <span id="super_user_code_msg"></span>
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
                <label for="super_user_password">Nueva Contraseña:</label>
                <input type="password" name="super_user_password" id="super_user_password">
                <span id="super_user_password_msg"></span>
            </div>
            <div>
                <label for="super_user_confirm_password">Confirmar Contraseña:</label>
                <input type="password" name="super_user_confirm_password" id="super_user_confirm_password">
                <span id="super_user_confirm_password_msg"></span>
            </div>
            <div>
                <input type="submit" value="ACTUALIZAR" id="super_user_update_btn">
                <span id="super_user_update_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/super_user/update_super_user.js"></script>
</body>
</html>
