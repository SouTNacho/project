<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Pacientes - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../administrative_navbar.php';
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
        <form action="" method="post" id="patient_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="patient_first_name">Nombre:</label>
                <input type="text" name="patient_first_name"
                id="patient_first_name" placeholder="John">
                <span id="patient_first_name_msg"></span>
            </div>
            <div>
                <label for="patient_last_name">Apellido:</label>
                <input type="text" name="patient_last_name"
                id="patient_last_name" placeholder="Doe">
                <span id="patient_last_name_msg"></span>
            </div>
            <div>
                <label for="patient_document">Documento:</label>
                <input type="text" name="patient_document"
                id="patient_document" placeholder="54321092"
                inputmode="numeric">
                <span id="patient_document_msg"></span>
            </div>
            <div>
                <label for="patient_birthdate">Fecha de Nacimiento:</label>
                <input type="date" name="patient_birthdate" id="patient_birthdate">
                <span id="patient_birthdate_msg"></span>
            </div>
            <div>
                <label for="patient_address">Dirección:</label>
                <input type="text" name="patient_address"
                id="patient_address" placeholder="18 de Julio 512">
                <span id="patient_address_msg"></span>
            </div>
            <div>
                <label for="patient_email">Correo Electrónico:</label>
                <input type="email" name="patient_email"
                id="patient_email" placeholder="ejemplo@correo.com">
                <span id="patient_email_msg"></span>
            </div>
            <div class="phone_container">
                <label for="patient_cellphone_number">Número de Celular:</label>
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
                <a href="/php/pages/patient/manage_patients.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="patient_btn" class="form-btn"></button>
                <span id="patient_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/patient/form_patient.js"></script>
</body>

</html>