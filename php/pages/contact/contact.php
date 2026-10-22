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
    <title>Contacto - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <i data-lucide="arrow-left"></i>Volver
                </a>
            </div>

            <div class="contact-header">
                <span class="public-document-label">Contacto</span>
                <h1>Contáctenos</h1>
                <p>Complete el siguiente formulario para enviar su consulta.</p>
            </div>

            <div class="form-container">
                <form method="post" id="contact_form">
                    <div>
                        <div>
                            <input type="text" name="contact_name"
                            id="contact_name" placeholder="John Doe">
                            <span id="contact_name_msg"></span>
                        </div>
                        <div>
                            <input type="email" name="contact_email"
                            id="contact_email" placeholder="example@gmail.com">
                            <span id="contact_email_msg"></span>
                        </div>
                    </div>
                    <div>
                        <textarea name="contact_message" id="contact_message"
                        placeholder="Escriba su mensaje"></textarea>
                        <span id="contact_message_msg"></span>
                    </div>
                    <div class="politics-checkbox-contact">
                        <input type="checkbox" id="contact_status">
                        <p>He leido y acepto las <span>
                                <button type="button" id="checkbox_btn">Políticas</button>
                            </span>
                        </p>
                    </div>
                    <div>
                        <input type="submit" value="Enviar" id="contact_btn">
                        <span id="contact_btn_msg"></span>
                    </div>
                </form>
            </div>

        </section>
        <dialog id="contact_terms_dialog" class="contact-terms-dialog">
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
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/contact/form_contact.js"></script>
</body>

</html>