<?php

    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Teléfonos - BYP</title>
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
        <form action="/php/actions/employee/employee_manage_cellphone.php" method="post" id="employee_manage_cellphone_form">
            <div>
                <h2>Gestionar Teléfonos</h2>
            </div>
            <div>
                <label for="employee_id">Código de Funcionario:</label>
                <input type="text" name="employee_id"
                id="employee_id" placeholder="FA123456789">
                <span id="employee_id_msg"></span>
            </div>
            <div>
                <label for="employee_cellphone_action">Seleccionar acción:</label>
                <select name="employee_cellphone_action" id="employee_cellphone_action">
                    <option value="">Seleccione una opción</option>
                    <option value="cr">Agregar Teléfono</option>
                    <option value="rm">Eliminar Teléfono</option>
                    <option value="up">Actualizar Teléfono</option>
                </select>
                <span id="employee_cellphone_action_msg"></span>
            </div>
            <div id="employee_cellphone_action_container"></div>
            <div>
                <input type="submit" value="Gestionar" id="employee_manage_cellphone_btn">
                <span id="employee_manage_cellphone_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/employee/cellphone_employee.js"></script>
</body>
</html>