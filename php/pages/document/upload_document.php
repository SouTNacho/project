<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Cargar documentos</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body >
    <header class="header flex-center-column">
        <div class="header_logo">
            <a href="administrative_panel.php">
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
                <a href="administrative_panel.php">Inicio</a>
            </li>
            <li class="navbar_list_item">
                <a href="">Visualizar Documentos</a>
            </li>
            <li class="navbar_list_item">
                <a href="">Gestionar Traslados</a>
            </li>
        </ul>
    </nav>
    <main class="main flex-center-column">
        <form id="document_upload_form">
            <div class="header-form">
                <h1 class="form-title">Registrar Documentos</h1>
            </div>
            <div>
                <label for="document_name">Nombre</label>
                <input type="text" name="document_name"
                id="document_name" placeholder="Análisis de sangre">
                <span id="document_name_msg"></span>
            </div>
            <div>
                <label for="document">Documento</label>
                <input type="file" name="document"
                id="document" accept=".pdf">
                <span id="document_msg"></span>
            </div>
            <div>
                <label for="document_category">Cargo</label>
                <select name="document_category" id="document_category">
                    <option value="">Seleccione una opción</option>
                    <?php

                        require_once __DIR__ . "/../../functions/documents_functions.php";
                        require_once __DIR__ . "/../../models/document_model.php";
                        require_once __DIR__ . "/../../conection.php";

                        $mysqli = connection_db();
                        $categories = findCategories($mysqli);

                        loadCategories($categories);

                    ?>
                </select>
                <span id="document_category_msg"></span>
            </div>
            <div>
                <input type="submit" value="Cargar Documento" id="document_upload">
                <span id="document_upload_msg"></span>
            </div>
        </form>
    </main>
    <footer class="footer">
        <div class="footer_logo flex-center-column">
            <a href="/php/super_user_panel.html">
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
    <script type="module" src="/js/document/upload_document.js"></script>
</body>
</html>