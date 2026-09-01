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
    <title>BYP - Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <main>
        <form action="actions/employee_login.php" method="post" id="employee_login_form">
            <div class="header-form">
                <a href="../index.html">
                    <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                </a>
                <h1 class="form-title">Iniciar Sesión</h1>
            </div>
            <div>
                <label for="employee_id">Código de Funcionario</label>
                <input type="text" name="employee_id" id="employee_id" placeholder="FA123456789">
                <span id="employee_id_msg"></span>
            </div>
            <div>
                <label for="employee_password">Contraseña</label>
                <input type="password" name="employee_password" id="employee_password">
                <span id="employee_password_msg"></span>
            </div>
            <div>
                <input type="submit" value="Ingresar" id="employee_login_btn">
                <span id="employee_login_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/employee/login_employee.js"></script>
</body>
</html>