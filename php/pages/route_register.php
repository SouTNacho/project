<!--</*?php

    session_start();
    
    if (isset($_SESSION["success"])) {
        echo $_SESSION["success"];
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["errors"])) {
        echo $_SESSION["errors"];
        unset($_SESSION["errors"]);
    }
    */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Registrar Funcionario</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <main>
        <form action="actions/employee_register.php" method="post" id="employee_register_form">
            <div class="header-form">
                <a href="/php/super_user_panel.php">
                    <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                </a>
                <h1 class="form-title">Registrar Funcionario</h1>
            </div>
            <div>
                <label for="employee_first_name">Nombre</label>
                <input type="text" name="employee_first_name"
                id="employee_first_name" placeholder="John">
                <span id="employee_first_name_msg"></span>
            </div>
            <div>
                <label for="employee_last_name">Apellido</label>
                <input type="text" name="employee_last_name"
                id="employee_last_name" placeholder="Doe">
                <span id="employee_last_name_msg"></span>
            </div>
            <div>
                <label for="employee_document">Documento</label>
                <input type="text" name="employee_document"
                id="employee_document" placeholder="54321092"
                inputmode="numeric">
                <span id="employee_document_msg"></span>
            </div>
            <div>
                <label for="employee_nationality">Nacionalidad</label>
                <select name="employee_nationality" id="employee_nationality">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="employee_nationality_msg"></span>
            </div>
            <div>
                <label for="employee_birthdate">Fecha de Nacimiento</label>
                <input type="date" name="employee_birthdate" id="employee_birthdate">
                <span id="employee_birthdate_msg"></span>
            </div>
            <div>
                <label for="employee_department">Departamento</label>
                <select name="employee_department" id="employee_department">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="employee_department_msg"></span>
            </div>
            <div>
                <label for="employee_locality">Localidad</label>
                <select name="employee_locality" id="employee_locality">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="employee_locality_msg"></span>
            </div>
            <div class="hidden" id="other_locality_container">
                <label for="other_locality">Otra Localidad</label>
                <input type="text" name="other_locality"
                id="other_locality">
                <span id="other_locality_msg"></span>
            </div>
            <div>
                <label for="employee_address">Dirección</label>
                <input type="text" name="employee_address"
                id="employee_address" placeholder="Sarandí esq. Leandro Gómez">
                <span id="employee_address_msg"></span>
            </div>
            <div>
                <label for="employee_address_number">Número de Puerta</label>
                <input type="text" name="employee_address_number"
                id="employee_address_number" placeholder="1489 Bis">
                <span id="employee_address_number_msg"></span>
            </div>
            <div>
                <label for="employee_email">Correo Electrónico</label>
                <input type="email" name="employee_email"
                id="employee_email" placeholder="ejemplo@correo.com">
                <span id="employee_email_msg"></span>
            </div>
            <div class="phone_container">
                <label for="employee_cellphone_number">Número de Celular</label>
                <div>
                <select name="employee_cellphone_code" id="employee_cellphone_code">
                    <option value="">Seleccione una opción</option>
                </select>
                </div>
                <div>
                <input type="text" name="employee_cellphone_number"
                id="employee_cellphone_number" placeholder="99789876"
                inputmode="numeric">
                </div>
                <span id="employee_cellphone_number_msg"></span>
            </div>
            <div>
                <label for="employee_position">Cargo</label>
                <select name="employee_position" id="employee_position">
                    <option value="">Seleccione una opción</option>
                    <option value="FA">Administrativo</option>
                    <option value="DR">Conductor</option>
                    <option value="CO">Copiloto</option>
                </select>
                <span id="employee_position_msg"></span>
            </div>
            <div id="employee_extra_information"></div>
            <div>
                <label for="employee_entry_date">Fecha de Ingreso</label>
                <input type="date" name="employee_entry_date" id="employee_entry_date">
                <span id="employee_entry_date_msg"></span>
            </div>
            <div>
                <label for="employee_password">Contraseña</label>
                <input type="password" name="employee_password" id="employee_password">
                <span id="employee_password_msg"></span>
            </div>
            <div>
                <label for="employee_confirm_password">Confirmar Contraseña</label>
                <input type="password" name="employee_confirm_password" id="employee_confirm_password">
                <span id="employee_confirm_password_msg"></span>
            </div>
            <div>
                <input type="submit" value="REGISTRAR" id="employee_register_btn">
                <span id="employee_register_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/register_employee.js"></script>
</body>
</html>-->