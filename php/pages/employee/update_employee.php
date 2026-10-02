
<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Funcionario - BYP</title>
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
        <form action="/php/actions/employee/employee_update.php" method="post" id="employee_update_form">
            <div>
                <h2>Actualizar Funcionario</h2>
            </div>
            <div>
                <label for="employee_id">CÃ³digo de Funcionario:</label>
                <input type="text" name="employee_id"
                id="employee_id" placeholder="FA123456789">
                <span id="employee_id_msg"></span>
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
                    <option value="">Seleccione una opciÃ³n</option>
                </select>
                <span id="employee_nationality_msg"></span>
            </div>
            <div>
                <label for="employee_birthdate">Fecha de Nacimiento:</label>
                <input type="date" name="employee_birthdate" id="employee_birthdate">
                <span id="employee_birthdate_msg"></span>
            </div>
            <div>
                <label for="employee_department">Departamento:</label>
                <select name="employee_department" id="employee_department">
                    <option value="">Seleccione una opciÃ³n</option>
                </select>
                <span id="employee_department_msg"></span>
            </div>
            <div>
                <label for="employee_locality">Localidad:</label>
                <select name="employee_locality" id="employee_locality">
                    <option value="">Seleccione una opciÃ³n</option>
                </select>
                <span id="employee_locality_msg"></span>
            </div>
            <div class="hidden" id="other_locality_container">
                <label for="other_locality">Otra Localidad:</label>
                <input type="text" name="other_locality"
                id="other_locality">
                <span id="other_locality_msg"></span>
            </div>
            <div>
                <label for="employee_address">DirecciÃ³n:</label>
                <input type="text" name="employee_address"
                id="employee_address" placeholder="SarandÃ esq. Leandro GÃ³mez">
                <span id="employee_address_msg"></span>
            </div>
            <div>
                <label for="employee_address_number">NÃºmero de Puerta:</label>
                <input type="text" name="employee_address_number"
                id="employee_address_number" placeholder="1489 Bis">
                <span id="employee_address_number_msg"></span>
            </div>
            <div>
                <label for="employee_email">Correo ElectrÃ³nico:</label>
                <input type="email" name="employee_email"
                id="employee_email" placeholder="ejemplo@email.com">
                <span id="employee_email_msg"></span>
            </div>
            <div>
                <label for="employee_position">Cargo:</label>
                <select name="employee_position" id="employee_position">
                    <option value="">Seleccione una opciÃ³n</option>
                    <option value="FA">Administrativo</option>
                    <option value="DR">Conductor</option>
                    <option value="CO">Copiloto</option>
                </select>
                <span id="employee_position_msg"></span>
            </div>
            <div id="employee_extra_information"></div>
            <div>
                <label for="employee_entry_date">Fecha de Ingreso:</label>
                <input type="date" name="employee_entry_date" id="employee_entry_date">
                <span id="employee_entry_date_msg"></span>
            </div>
            <div>
                <input type="submit" value="ACTUALIZAR" id="employee_update_btn">
                <span id="employee_update_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/employee/update_employee.js"></script>
</body>
</html>
