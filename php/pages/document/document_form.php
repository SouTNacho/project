<?php

    session_start();
    // Falta implementar el rol

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Documentos - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../administrative_pages/administrative_navbar.php';
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
        <form action="" method="post" id="document_form" class="form" enctype="multipart/form-data">
            <div>
                <h2></h2>
            </div>
            <div>
                <label for="document_name">Nombre:</label>
                <input type="text" name="document_name"
                id="document_name" placeholder="Análisis de sangre">
                <span id="document_name_msg"></span>
            </div>
            <div>
                <label for="document">Documento:</label>
                <input type="file" name="document"
                id="document" accept=".pdf">
                <span id="document_msg"></span>
            </div>
            <div>
                <label for="document_category">Categoria:</label>
                <select name="document_category" id="document_category">
                    <option value="">Seleccione una opción</option>
                </select>
                <span id="document_category_msg"></span>
            </div>
            <div>
                <a href="/php/pages/document/manage_documents.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="document_btn" class="form-btn"></button>
                <span id="document_btn_msg" class="form-btn-msg"></span>
            </div>
        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/document/form_document.js"></script>
</body>

</html>