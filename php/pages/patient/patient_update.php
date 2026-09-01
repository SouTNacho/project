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
    <title>BYP - Actualizar Paciente</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
    <main>
        <form action="actions/patient_update.php" method="post" id="patient_update_form">
            <div class="header-form">
                <a href="#">
                    <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
                </a>
                <h1 class="form-title">Actualizar Paciente</h1>
            </div>
            <div>
                <label for="patient_current_document">Documento Actual</label>
                <input type="text" name="patient_current_document"
                id="patient_current_document" placeholder="54321092"
                inputmode="numeric">
                <span id="patient_current_document_msg"></span>
            </div>
            <div>
                <label for="patient_first_name">Nombre</label>
                <input type="text" name="patient_first_name"
                id="patient_first_name" placeholder="John">
                <span id="patient_first_name_msg"></span>
            </div>
            <div>
                <label for="patient_last_name">Apellido</label>
                <input type="text" name="patient_last_name"
                id="patient_last_name" placeholder="Doe">
                <span id="patient_last_name_msg"></span>
            </div>
            <div>
                <label for="patient_new_document">Nuevo Documento</label>
                <input type="text" name="patient_new_document"
                id="patient_new_document" placeholder="54321092"
                inputmode="numeric">
                <span id="patient_new_document_msg"></span>
            </div>
            <div>
                <label for="patient_birthdate">Fecha de Nacimiento</label>
                <input type="date" name="patient_birthdate" id="patient_birthdate">
                <span id="patient_birthdate_msg"></span>
            </div>
            <div>
                <label for="patient_address">Dirección</label>
                <input type="text" name="patient_address"
                id="patient_address" placeholder="18 de Julio 512">
                <span id="patient_address_msg"></span>
            </div>
            <div>
                <label for="patient_email">Correo Electrónico</label>
                <input type="email" name="patient_email"
                id="patient_email" placeholder="ejemplo@correo.com">
                <span id="patient_email_msg"></span>
            </div>
            <div class="phone_container">
                <label for="patient_cellphone_number">Número de Celular</label>
                <div>
                <select name="patient_cellphone_code" id="patient_cellphone_code">
                    <option value="">Seleccione una opción</option>
                </select>
                </div>
                <div>
                <input type="text" name="patient_cellphone_number"
                id="patient_cellphone_number" placeholder="99789876"
                inputmode="numeric">
                </div>
                <span id="patient_cellphone_number_msg"></span>
            </div>
            <div>
                <input type="submit" value="ACTUALIZAR" id="patient_update_btn">
                <span id="patient_update_btn_msg"></span>
            </div>
        </form>
    </main>
    <script type="module" src="/js/update_patient.js"></script>
</body>
</html>