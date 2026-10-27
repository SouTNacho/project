<?php

    session_start();

    require_once __DIR__ . '/php/functions/session.php';
    $is_logged = isLoggedIn();
    $user_type = $_SESSION['type_user'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="./styles/notifications.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./js/notifications.js"></script>

    <meta charset="UTF-8">
    <title>BYP - Hospital de Clínicas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/index_style.css">
</head>

<body class="public-page">

    <?php 

        require_once __DIR__ . '/php/pages/header.php';

        if ($is_logged) {
            switch ($user_type) {
                case 'super_user':

                    require_once __DIR__ . '/php/pages/super_user_pages/super_user_navbar.php';
                    break;
                case 'administrative':

                    require_once __DIR__ . '/php/pages/administrative_pages/administrative_navbar.php';
                    break;
                case 'driver':

                    break;
                default:
                    break;
            }
        }

    ?>

    <main id="main" class="index-main">
        <section class="index-intro">
        <div class="index-intro-background">
            <div class="index-intro-content">
                <span class="index-label">Build Your Program</span>
                <h2>Hospital de Clínicas Dr. Manuel Quintela</h2>
                <p>Bienvenido al sistema BYP, una solución orientada a la gestión documental y a la trazabilidad de los traslados dentro del área de salud.</p>

                <div class="index-actions">
                    <a class="index-button primary" href="/php/pages/other_document/public_documents.php">
                        <i data-lucide="file-text"></i>Ver documentos
                    </a>
                    <a class="index-button secondary" href="/php/pages/contact/contact.php">
                        <i data-lucide="mail"></i>Contacto
                    </a>
                </div>
            </div>
        </div>
        </section>

        <section class="index-patient-info">
            <div class="index-patient-info-content">

                <span class="index-label">Información para pacientes</span>
                <h2 id="hospital-info-title">Información útil para tu atención y estadía</h2>
                <p class="index-patient-description">Información útil para pacientes y acompañantes sobre atención, consultas y vías de contacto.</p>

                <div class="index-info-grid">
                    <article class="index-info-card">
                        <i data-lucide="calendar-days"></i>
                        <div>
                            <h3>Policlínicas</h3>
                            <p>La agenda de consultas se coordina mediante el sistema de gestión de consultas del Hospital.</p>
                            <strong>Agenda telefónica: 0800 1953</strong>
                            <small>Lunes a viernes, de 8:00 a 16:00.</small>
                        </div>
                    </article>

                    <article class="index-info-card">
                        <i data-lucide="siren"></i>
                        <div>
                            <h3>Emergencia</h3>
                            <p>La atención de emergencia funciona las 24 horas, todos los días del año.</p>
                            <small>La atención y el ingreso dependen de la situación y del prestador de salud correspondiente.</small>
                        </div>
                    </article>

                    <article class="index-info-card">
                        <i data-lucide="headset"></i>
                        <div>
                            <h3>Atención al Usuario</h3>
                            <p>Para consultas, sugerencias, denuncias o reclamos relacionados con la atención.</p>
                            <strong>2487 1410 · 1953 int. 4357</strong>
                            <small>Lunes a viernes, 8:00-13:00 y 13:30-15:00. Planta baja, Ala Este.</small>
                        </div>
                    </article>

                    <article class="index-info-card">
                        <i data-lucide="map-pin"></i>
                        <div>
                            <h3>Ubicación y contacto</h3>
                            <p>Hospital Universitario de la Facultad de Medicina, Universidad de la República.</p>
                            <strong>Av. Italia 2870, Montevideo</strong>
                            <small>Central telefónica: 1953 · lunes a viernes, 8:00-15:00.</small>
                        </div>
                    </article>
                </div>

            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/php/pages/footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons() </script>

</body>
</html>