<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Funcionarios - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <form action="" method="post" id="employee_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="employee_first_name">Nombre:</label>
                <input type="text" name="employee_first_name"
                id="employee_first_name" placeholder="John">
                <span id="employee_first_name_msg"></span>
            </div>
            <div>
                <label for="employee_last_name">Apellido:</label>
                <input type="text" name="employee_last_name"
                id="employee_last_name" placeholder="Doe">
                <span id="employee_last_name_msg"></span>
            </div>
            <div>
                <label for="employee_document">Documento:</label>
                <input type="text" name="employee_document"
                id="employee_document" placeholder="54321092"
                inputmode="numeric">
                <span id="employee_document_msg"></span>
            </div>
            <div>
                <label for="employee_nationality">Nacionalidad:</label>
                <select name="employee_nationality" id="employee_nationality">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="employee_nationality_msg"></span>
            </div>
            <div>
                <label for="employee_birthdate">Fecha de nacimiento:</label>
                <input type="date" name="employee_birthdate" id="employee_birthdate">
                <span id="employee_birthdate_msg"></span>
            </div>
            <div>
                <label for="employee_department">Departamento:</label>
                <select name="employee_department" id="employee_department">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="employee_department_msg"></span>
            </div>
            <div>
                <label for="employee_locality">Localidad:</label>
                <select name="employee_locality" id="employee_locality">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="employee_locality_msg"></span>
            </div>
            <div class="hidden" id="other_locality_container">
                <label for="other_locality">Otra localidad:</label>
                <input type="text" name="other_locality"
                id="other_locality">
                <span id="other_locality_msg"></span>
            </div>
            <div>
                <label for="employee_address">Dirección:</label>
                <input type="text" name="employee_address"
                id="employee_address" placeholder="Sarandí esq. Leandro Gómez">
                <span id="employee_address_msg"></span>
            </div>
            <div>
                <label for="employee_address_number">Número de puerta:</label>
                <input type="text" name="employee_address_number"
                id="employee_address_number" placeholder="1489 Bis">
                <span id="employee_address_number_msg"></span>
            </div>
            <div>
                <label for="employee_email">Correo electrónico:</label>
                <input type="email" name="employee_email"
                id="employee_email" placeholder="ejemplo@correo.com">
                <span id="employee_email_msg"></span>
            </div>
            <div>
                <label for="employee_entry_date">Fecha de ingreso:</label>
                <input type="date" name="employee_entry_date" id="employee_entry_date">
                <span id="employee_entry_date_msg"></span>
            </div>
            <div>
                <a href="/php/pages/employee/manage_employees.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="employee_btn" class="form-btn"></button>
                <span id="employee_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/employee/form_employee.js"></script>
</body>

</html>