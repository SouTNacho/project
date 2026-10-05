<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Copilotos - BYP</title>
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
        <form action="" method="post" id="copilot_form" class="form">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="copilot_document">Cédula:</label>
                <input type="text" name="copilot_document"
                id="copilot_document" placeholder="52019851">
                <span id="copilot_document_msg"></span>
            </div>
            <div>
                <label for="copilot_speciality">Especialidad:</label>
                <select name="copilot_speciality" id="copilot_speciality">
                    <option value="">Selecciona una opción</option>
                    <option value="Sin especialización">Sin especialización</option>
                    <option value="Primeros Auxilios">Primeros Auxilios</option>
                    <option value="Soporte Vital Básico (SVB)">Soporte Vital Básico (SVB)</option>
                    <option value="Soporte Vital Avanzado (SVA)">Soporte Vital Avanzado (SVA)</option>
                    <option value="Trauma">Trauma</option>
                    <option value="Pediatría">Pediatría</option>
                    <option value="Neonatología">Neonatología</option>
                    <option value="Paciente Crítico">Paciente Crítico</option>
                </select>
                <span id="copilot_speciality_msg"></span>
            </div>
            <div>
                <label for="copilot_password">Contraseña:</label>
                <input type="password" name="copilot_password"
                id="copilot_password">
                <span id="copilot_password_msg"></span>
            </div>
            <div>
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" name="confirm_password"
                id="confirm_password">
                <span id="confirm_password_msg"></span>
            </div>
            <div>
                <a href="/php/pages/copilot/manage_copilots.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="copilot_btn" class="form-btn"></button>
                <span id="copilot_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/copilot/form_copilot.js"></script>
</body>

</html>