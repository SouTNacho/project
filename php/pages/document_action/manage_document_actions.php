<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Acciones de Documentos - BYP</title>
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/view_style.css">
</head>
<body>
<?php
    require_once __DIR__ . '/../header.php';
    require_once __DIR__ . '/../super_user_pages/super_user_navbar.php';
?>
<main id="main">
    <section class="document-action-page">
        <?php if (isset($_SESSION['success'])): ?>
            <p class="document-action-notice"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errors'])): ?>
            <p class="document-action-error"><?= htmlspecialchars($_SESSION['errors'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        <div class="document-action-heading">
            <h2>Gestión de acciones de documentos</h2>
            <a class="document-action-register-link" href="/php/pages/document_action/document_action_register.php">
                <i data-lucide="square-plus"></i>
                Registrar acción
            </a>
        </div>
        <div class="document-action-table-wrap">
            <table class="document-action-table" id="document-action-table" hidden>
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
                </thead>
                <tbody id="document-action-list"></tbody>
            </table>
            <p class="document-action-empty" id="document-action-empty">Cargando acciones...</p>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../footer.php'; ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
<script type="module" src="/js/document_action/manage_document_actions.js"></script>
</body>
</html>
