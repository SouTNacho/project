<?php
session_start();
$is_logged = isset($_SESSION["logged"]) && $_SESSION["logged"] === true;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contacto - BYP</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/index_style.css">
    <link rel="stylesheet" href="/styles/contact_style.css">
</head>

<body class="public-page">

    <?php require_once __DIR__ . '/../header.php'; ?>

    <main id="main" class="index-main">
        <section class="contact-section">
            <div class="contact-back">
                <a href="/index.php">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Volver al inicio
                </a>
            </div>

            <div class="contact-header">
                <span class="public-document-label">Contacto</span>
                <h1>Contáctenos</h1>
                <p>Complete el siguiente formulario para enviar su consulta.</p>
            </div>

            <div class="form-container">
                <form action="/php/actions/contact/send_message.php" method="post" id="contact_form" novalidate>
                    <div>
                        <div>
                            <label for="contact_name">Nombre completo</label>
                            <input type="text" name="contact_name" id="contact_name" placeholder="John Doe" maxlength="100" autocomplete="name" required>
                            <span id="contact_name_msg" class="field-message"></span>
                        </div>

                        <div>
                            <label for="contact_email">Correo electrónico</label>
                            <input type="email" name="contact_email" id="contact_email" placeholder="example@gmail.com" maxlength="254" autocomplete="email" required>
                            <span id="contact_email_msg" class="field-message"></span>
                        </div>
                    </div>

                    <div>
                        <label for="contact_message">Mensaje</label>
                        <textarea name="contact_message" id="contact_message" placeholder="Escriba su mensaje" minlength="10" maxlength="2000" required></textarea>
                        <span id="contact_message_msg" class="field-message"></span>
                    </div>

                    <div class="contact-consent">
                        <label class="contact-terms-label" for="contact_consent">
                            <input type="checkbox" name="contact_consent" id="contact_consent" value="1" required>
                            <span>Acepto los <button type="button" id="show_contact_terms" class="terms-link" aria-haspopup="dialog">términos y condiciones</button>.</span>
                        </label>
                        <span id="contact_consent_msg" class="field-message" aria-live="polite"></span>
                    </div>
                    <div>
                        <button type="submit" id="contact_btn">Enviar mensaje</button>
                    </div>

                    <p id="contact_status" class="contact-status" role="status" aria-live="polite"></p>
                </form>
            </div>
        </section>
    </main>

    <dialog id="contact_terms_dialog" class="contact-terms-dialog" aria-labelledby="contact_terms_title" aria-describedby="contact_terms_description">
        <div class="contact-terms-content">
            <h2 id="contact_terms_title">Términos y condiciones del formulario de contacto</h2>
            <div id="contact_terms_description">
                <p>Al aceptar y enviar este formulario, autorizás el uso de tu nombre, correo electrónico y mensaje para recibir, registrar y responder tu consulta.</p>
                <p>Los datos proporcionados se almacenan en la base de datos del proyecto BYP. No incluyas contraseñas, datos clínicos ni otra información sensible en el mensaje.</p>
                <p>El formulario es un medio de contacto y no está destinado a solicitar atención médica o asistencia urgente.</p>
                <p>Si no aceptás estas condiciones, no se enviará la consulta. Podés cerrar esta ventana y revisar el texto antes de decidir.</p>
            </div>
            <button type="button" id="close_contact_terms" class="terms-close">Cerrar</button>
        </div>
    </dialog>

    <?php require_once __DIR__ . '/../footer.php'; ?>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script type="module" src="/js/contact/contact.js"></script>
</body>
</html>
