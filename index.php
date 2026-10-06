<?php
session_start();

$is_logged = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
$user_name = $_SESSION['username'] ?? '';
$user_type = $_SESSION['user_type'] ?? '';

$user_type_names = [
    'administrativo' => 'Administrativo',
    'conductor' => 'Conductor',
    'copiloto' => 'Copiloto',
    'super_user' => 'Super Usuario'
];

$user_type_label = $user_type_names[$user_type] ?? $user_type;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYP - Hospital de Clínicas</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/index_style.css">
</head>
<body id="top" class="public-page">

    <?php require_once __DIR__ . '/php/pages/header.php'; ?>

    <main id="main" class="index-main">

        <section class="index-hero">
            <div class="index-hero-content">
                <?php if ($is_logged): ?>
                    <span class="index-badge">Sesión iniciada</span>
                    <h2>Bienvenido, <?= htmlspecialchars($user_name) ?></h2>
                    <p>
                        Has iniciado sesión como <strong><?= htmlspecialchars($user_type_label) ?></strong>.
                        Desde aquí podés acceder nuevamente a tu panel.
                    </p>

                    <div class="index-actions">
                        <a class="index-button primary" href="<?php
                            if ($user_type === 'super_user') {
                                echo '/php/pages/super_user_pages/super_user_panel.php';
                            } else {
                                echo '/php/pages/administrative_pages/administrative_panel.php';
                            }
                        ?>">
                            <span class="material-symbols-outlined">dashboard</span>
                            Ir a mi panel
                        </a>
                    </div>
                <?php else: ?>
                    <span class="index-badge">Build Your Program</span>
                    <h2>Hospital de Clínicas Dr. Manuel Quintela</h2>
                    <p>
                        Bienvenido al sistema BYP, una solución orientada a la gestión documental
                        y a la trazabilidad de los traslados dentro del área de salud.
                    </p>

                    <div class="index-actions">
                        <a class="index-button primary" href="/php/pages/document/public_documents.php">
                            <span class="material-symbols-outlined">description</span>
                            Ver documentos
                        </a>
                        <a class="index-button secondary" href="#contacto">
                            <span class="material-symbols-outlined">mail</span>
                            Contacto
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="documents-section" id="documentos">
            <div class="documents-section-header">
                <div>
                    <span class="section-label">Información pública</span>
                    <h2>Documentos</h2>
                    <p>Consultá los documentos generales disponibles del Hospital de Clínicas.</p>
                </div>
                <a class="documents-view-all" href="/php/pages/document/public_documents.php">
                    <span class="material-symbols-outlined">description</span>
                    Ver documentos
                </a>
            </div>
        </section>

        <section class="index-cards" id="contacto">
            <article class="index-card">
                <span class="material-symbols-outlined">folder_managed</span>
                <h3>Gestión documental</h3>
                <p>
                    Organización y acceso a documentos relacionados con la atención y los servicios de salud.
                </p>
            </article>

            <article class="index-card">
                <span class="material-symbols-outlined">route</span>
                <h3>Trazabilidad</h3>
                <p>
                    Seguimiento de la información asociada a los traslados y sus diferentes etapas.
                </p>
            </article>

            <article class="index-card">
                <span class="material-symbols-outlined">contact_mail</span>
                <h3>Contacto</h3>
                <p>
                    Para consultas sobre el sistema o los documentos, comunicate con el área correspondiente del Hospital de Clínicas.
                </p>
            </article>
        </section>

    </main>

    <?php require_once __DIR__ . '/php/pages/footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
