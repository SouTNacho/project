<?php
session_start();
require_once __DIR__ . '/../../functions/session.php';
validateSession('driver');
if (empty($_SESSION['driver_tracking_csrf'])) $_SESSION['driver_tracking_csrf'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traslado del conductor - BYP</title>
    <link rel="shortcut icon" href="/src/logo_small.png">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/driver_tracking.css">
</head>
<body>
    <?php require_once __DIR__ . '/../header.php'; ?>
    <main id="main">
        <section class="tracking-card" aria-labelledby="tracking-title">
            <h2 id="tracking-title">Mi traslado</h2>
            <p>Seguimiento de ubicación del conductor</p>
            <p id="transfer-state">Consultando traslado…</p>
            <form id="tracking-form">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['driver_tracking_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="tracking-buttons">
                    <button id="start-transfer" type="button" disabled>Iniciar traslado</button>
                    <button id="pause-transfer" type="button" hidden>Pausar traslado</button>
                    <button id="finish-transfer" type="button" hidden>Finalizar traslado</button>
                </div>
            </form>
            <p id="tracking-message" role="status" aria-live="polite"></p>
            <dl>
                <dt>Latitud</dt><dd id="latitude">—</dd>
                <dt>Longitud</dt><dd id="longitude">—</dd>
                <dt>Última actualización guardada</dt><dd id="last-update">—</dd>
            </dl>
            <p class="tracking-note">Mantené esta página abierta y permití el acceso a tu ubicación. El envío se intenta cada 5 segundos; puede demorar según el GPS y la conexión.</p>
            <p class="tracking-note">Pausar detiene el envío. Cerrar la página también lo detiene, pero no finaliza el traslado.</p>
        </section>
    </main>
    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="/js/driver/tracking.js" defer></script>
</body>
</html>
