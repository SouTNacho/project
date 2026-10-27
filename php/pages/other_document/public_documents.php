<?php

    session_start();

    $is_logged = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
    $user_type = $_SESSION['type_user'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="../../../styles/notifications.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../js/notifications.js"></script>

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

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/other_document/manage_public_document.js"></script>
</body>
</html>