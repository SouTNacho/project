<?php
require_once __DIR__ . '/../../models/document_model.php';
require_once __DIR__ . '/../../conection.php';

$mysqli = connection_db();
$documents = findActiveDocuments($mysqli);
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos - BYP</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
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
                    <span class="material-symbols-outlined">arrow_back</span>
                    Volver al inicio
                </a>
            </div>

            <div class="public-documents-header">
                <span class="public-document-label">Información pública</span>
                <h1>Documentos</h1>
                <p>Documentación general disponible para consulta pública.</p>
            </div>

            <?php if (!$documents): ?>
                <div class="public-document-card">
                    <span class="material-symbols-outlined">description</span>
                    <h2>No hay documentos disponibles</h2>
                    <p>Actualmente no hay documentos públicos activos para consultar.</p>
                </div>
            <?php else: ?>
                <div class="public-documents-grid">
                    <?php foreach ($documents as $document): ?>
                        <?php $token = urlencode($document['token']); ?>
                        <article class="public-document-card">
                            <span class="material-symbols-outlined">description</span>
                            <h2><?= htmlspecialchars($document['nombre']) ?></h2>
                            <p>Documento disponible para consulta pública.</p>
                            <div class="public-document-actions">
                                <a class="view-document" href="/php/pages/document/screen_document.php?token=<?= $token ?>">
                                    <span class="material-symbols-outlined">visibility</span>
                                    Ver
                                </a>
                                <a class="download-document" href="/php/actions/document/download_document.php?token=<?= $token ?>">
                                    <span class="material-symbols-outlined">download</span>
                                    Descargar
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
</body>
</html>
