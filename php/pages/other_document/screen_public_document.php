<?php

    session_start();

    $is_logged = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
    $user_type = $_SESSION['type_user'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Visualizar Documento - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
    <link rel="stylesheet" href="/styles/public_document_viewer.css">
</head>

<body>
    <?php 
        require_once __DIR__ . '/../header.php';
    ?>
    
    <main id="main" class="public-viewer-main">
        <section class="public-viewer" aria-label="Visualización de documento">
            <div class="viewer-topbar">
                <a class="viewer-back" href="/php/pages/other_document/public_documents.php">
                    <i data-lucide="arrow-left"></i><span>Volver a documentos</span>
                </a>
                <span class="viewer-tag"><i data-lucide="file-text"></i> Consulta pública</span>
            </div>
            <div class="viewer-heading">
                <div class="viewer-file-icon"><i data-lucide="file-text"></i></div>
                <div class="viewer-heading-text">
                    <h1 id="viewer-title">Visualizar documento</h1>
                    <p id="viewer-subtitle">Preparando el documento para su consulta...</p>
                </div>
                <div class="viewer-actions" id="viewer-actions" hidden>
                    <a id="viewer-open" class="viewer-button viewer-secondary" target="_blank" rel="noopener noreferrer"><i data-lucide="external-link"></i> Abrir aparte</a>
                    <a id="viewer-download" class="viewer-button viewer-primary"><i data-lucide="download"></i> Descargar</a>
                </div>
            </div>
            <div class="viewer-panel" id="viewer-panel">
                <div class="viewer-status" id="viewer-status" role="status" aria-live="polite">
                    <div class="viewer-spinner" aria-hidden="true"></div>
                    <h2>Cargando documento...</h2>
                    <p>Esperá un momento mientras preparamos la vista previa.</p>
                </div>
                <iframe id="document_view" title="Vista previa del documento" hidden></iframe>
            </div>
            <p class="viewer-tip"><i data-lucide="info"></i> Podés consultar el archivo acá, descargarlo o abrirlo en otra pestaña para verlo más grande.</p>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php' ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/other_document/screen_public_document.js"></script>
</body>

</html>