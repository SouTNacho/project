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
    <title>Documentos - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/index_style.css">
</head>

<body class="public-page">
    <?php require_once __DIR__ . '/../header.php'; ?>

    <main id="main" class="index-main">
        <section class="public-documents-page">

            <div class="public-documents-back">
                <a href="/index.php">
                    <i data-lucide="arrow-left"></i>Volver al inicio
                </a>
            </div>
            <div class="public-documents-header">
                <span class="public-document-label">Información pública</span>
                <h1>Documentos</h1>
                <p>Documentación disponible para consulta pública.</p>
            </div>
            <div class="search">
                <div class="search-log-container">
                    <input id="filter_search" type="search" placeholder="Buscar documento por su nombre ...">
                    <button type="button" id="search">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </div>

            <div id="public_documents_container" class="public-documents-grid"></div>
        </section>
    </main>

    <footer id="footer">
        <div class="social-media-container">
            <ul class="footer-list">
                <li class="list-items">
                    <a target="_blank" href="https://www.facebook.com/pages/Hospital-de-Cl%C3%ADnicas-Dr-Manuel-Quintela/1451600128402196">
                        <img src="/src/face_icon.png" alt="">
                    </a>
                </li>
                <li class="list-items">
                    <a target="_blank" href="https://www.instagram.com/popular/hospital-de-cl%C3%ADnicas-dr-manuel-quintela/">
                        <img src="/src/insta_icon.png" alt="">
                    </a>
                </li>
                <li class="list-items">
                    <a target="_blank" href="https://x.com/hcmquintela">
                        <img src="/src/twitt_icon.png" alt="">
                    </a>
                </li>
                <li class="list-items">
                    <a target="_blank" href="https://uy.linkedin.com/company/udelar-facultad-de-medicina-hospital-de-cl-nicas">
                        <img src="/src/link_icon.png" alt="">
                    </a>
                </li>
            </ul>
        </div>

        <div class="information-container">
            <p class="information-content">
                &copy; 2026 Hospital de Clínicas. Todos los derechos reservados. Desarrollado por BYP.
            </p>
        </div>
    </footer>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/other_document/manage_public_document.js"></script>
</body>
</html>