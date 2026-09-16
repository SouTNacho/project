<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Panel de Administración</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/document_style.css">

</head>
<body id="top">
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="/php/pages/administrative_panel.php">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <div class="header_login">
            <a href="actions/logout.php">Logout</a>
        </div>
    </header>
    <nav class="navbar flex-center-column" aria-label="Navegación principal">
        <ul class="navbar_list">
            <li class="navbar_list_item">
                <a href="/php/pages/administrative_panel.php">Inicio</a>
            </li>
            <li class="navbar_list_item">
                <a href="">Visualizar Documentos</a>
            </li>
            <li class="navbar_list_item">
                <a href="">Gestionar Traslados</a>
            </li>
        </ul>
    </nav>
    <main>
        <div class="form-container">
        <?php

        require_once __DIR__ . "/../../functions/patient_functions.php";
        require_once __DIR__ . "/../../models/patient_model.php";
        require_once __DIR__ . "/../../conection.php";

        $mysqli = connection_db();
        $patients = findAllPatients($mysqli);

        if ($patients) {

            echo "<div class='documents-container'>";
            echo "<h2>Modificar Estado de Pacientes</h2>";
            echo "<ul>";
            createPatientList($patients, $mysqli);
            echo "</ul>";
            echo "</div>";

        } else {
            echo "<div class='documents-container'>";
            echo "<p>No se encontraron documentos.</p>";
            echo "</div>";
        }
        ?>
</div>
    </main>
    <footer class="footer">
        <div class="footer_logo flex-center-column">
            <a href="/php/administrative_panel.html">
                <img src="/src/logo_small.png" alt="Logotipo del Hospital de Clínicas">
            </a>
        </div>
        <ul class="footer_list flex-center-column">
            <li class="footer_list_item">
                <a href="#">Políticas</a>
            </li>
            <li class="footer_list_item">
                <a href="#">Derechos</a>
            </li>
            <li class="footer_list_item">
                <a href="#">Contacto</a>
            </li>
        </ul>
        <p class="footer_content flex-center-column">
            &copy; 2026 Hospital de Clínicas. Todos los derechos reservados. Desarrollado por BYP.
        </p>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script type="module" src="/js/manage_patient.js"></script>
</body>
</html>