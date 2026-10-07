<?php

    session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Acción de Documento - BYP</title>
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>
<body>
<?php
    require_once __DIR__ . '/../header.php';
    require_once __DIR__ . '/../super_user_navbar.php';
?>
<main id="main" class="form-main">
    <?php if (isset($_SESSION['errors'])): ?>
        <p class="error-message"><?= htmlspecialchars($_SESSION['errors'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    <form action="/php/actions/document_action/document_action_register.php" method="post" class="form">
        <div>
            <h2 class="form-title">Registrar acción de documento</h2>
        </div>
        <div>
            <label for="action_name">Nombre:</label>
            <input type="text" name="action_name" id="action_name" maxlength="50" required>
        </div>
        <div>
            <a href="/php/pages/document_action/manage_document_actions.php">
                <i data-lucide="arrow-left"></i>
                Volver
            </a>
            <button type="submit" class="form-btn">REGISTRAR</button>
        </div>
    </form>
</main>
<?php require_once __DIR__ . '/../footer.php'; ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
</body>
</html>
