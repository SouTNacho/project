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

                    <span class="index-badge">
                        Sesión iniciada
                    </span>

                    <h2>
                        Bienvenido, <?= htmlspecialchars($user_name) ?>
                    </h2>

                    <p>
                        Has iniciado sesión como
                        <strong><?= htmlspecialchars($user_type_label) ?></strong>.
                        Desde aquí podés acceder nuevamente a tu panel.
                    </p>

                    <div class="index-actions">

                        <a class="index-button primary"
                           href="<?php
                                if ($user_type === 'super_user') {
                                    echo '/php/pages/super_user_pages/super_user_panel.php';
                                } else {
                                    echo '/php/pages/administrative_pages/administrative_panel.php';
                                }
                           ?>">

                            <span class="material-symbols-outlined">
                                dashboard
                            </span>

                            Ir a mi panel

                        </a>

                    </div>

                <?php else: ?>

                    <span class="index-badge">
                        Build Your Program
                    </span>

                    <h2>
                        Hospital de Clínicas Dr. Manuel Quintela
                    </h2>

                    <p>
                        Bienvenido al sistema BYP, una solución orientada
                        a la gestión documental y a la trazabilidad de
                        los traslados dentro del área de salud.
                    </p>

                    <div class="index-actions">

                        <a class="index-button primary"
                           href="/php/pages/document/public_documents.php">

                            <span class="material-symbols-outlined">
                                description
                            </span>

                            Ver documentos

                        </a>

                        <a class="index-button secondary"
                           href="/php/pages/contact/contact.php">

                            <span class="material-symbols-outlined">
                                mail
                            </span>

                            Contacto

                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </section>

        <section class="index-hospital-info"
                 aria-labelledby="hospital-info-title">

            <div class="index-hospital-info-inner">

                <span class="index-badge">
                    Información para pacientes
                </span>

                <h2 id="hospital-info-title">
                    Información útil para tu atención y estadía
                </h2>

                <p class="index-hospital-description">
                    Información útil para pacientes y acompañantes sobre
                    atención, consultas y vías de contacto.
                </p>


                <div class="index-hospital-grid">

                
                    <article class="index-hospital-item">

                        <i data-lucide="calendar-days"></i>

                        <div>

                            <h3>
                                Policlínicas
                            </h3>

                            <p>
                                La agenda de consultas se coordina mediante
                                el sistema de gestión de consultas del Hospital.
                            </p>

                            <strong>
                                Agenda telefónica: 0800 1953
                            </strong>

                            <small>
                                Lunes a viernes, de 8:00 a 16:00.
                            </small>

                        </div>

                    </article>


                    <article class="index-hospital-item">

                        <i data-lucide="siren"></i>

                        <div>

                            <h3>
                                Emergencia
                            </h3>

                            <p>
                                La atención de emergencia funciona las
                                24 horas, todos los días del año.
                            </p>

                            <small>
                                La atención y el ingreso dependen de la
                                situación y del prestador de salud correspondiente.
                            </small>

                        </div>

                    </article>


                    <article class="index-hospital-item">

                        <i data-lucide="headset"></i>

                        <div>

                            <h3>
                                Atención al Usuario
                            </h3>

                            <p>
                                Para consultas, sugerencias, denuncias o
                                reclamos relacionados con la atención.
                            </p>

                            <strong>
                                2487 1410 · 1953 int. 4357
                            </strong>

                            <small>
                                Lunes a viernes, 8:00-13:00 y 13:30-15:00.
                                Planta baja, Ala Este.
                            </small>

                        </div>

                    </article>


                    <article class="index-hospital-item">

                        <i data-lucide="map-pin"></i>

                        <div>

                            <h3>
                                Ubicación y contacto
                            </h3>

                            <p>
                                Hospital Universitario de la Facultad de Medicina,
                                Universidad de la República.
                            </p>

                            <strong>
                                Av. Italia 2870, Montevideo
                            </strong>

                            <small>
                                Central telefónica: 1953 · lunes a viernes,
                                8:00-15:00.
                            </small>

                        </div>

                    </article>

                </div>

            </div>

        </section>

    </main>


    <?php require_once __DIR__ . '/php/pages/footer.php'; ?>


    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>