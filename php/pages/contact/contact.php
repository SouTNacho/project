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

                    <div class="contact-privacy">
                        <div class="contact-privacy-control">
                            <input type="checkbox" name="contact_privacy" id="contact_privacy" value="1" aria-describedby="contact_privacy_msg" required>
                            <label class="contact-privacy-label" for="contact_privacy">Declaro que soy consciente del manejo, la protección y las condiciones de uso de mis datos personales.</label>
                            <a class="contact-privacy-link" id="open_privacy_dialog" href="#contact_privacy_dialog">Leer el detalle</a>
                        </div>
                        <span id="contact_privacy_msg" class="field-message"></span>
                    </div>

                    <div>
                        <button type="submit" id="contact_btn">Enviar mensaje</button>
                    </div>

                    <p id="contact_status" class="contact-status" role="status" aria-live="polite"></p>
                </form>
            </div>

            <dialog class="contact-privacy-dialog" id="contact_privacy_dialog" aria-labelledby="contact_privacy_title">
                <div class="contact-privacy-dialog-content">
                    <h2 id="contact_privacy_title">Manejo y uso de tus datos personales</h2>
                    <section>
                        <h3>Manejo y protección</h3>
                        <p>Los datos que ingreses en este formulario (nombre, correo electrónico y mensaje) se utilizarán para recibir, gestionar y responder tu consulta. Evitá incluir información clínica o datos sensibles que no sean necesarios para esa finalidad.</p>
                        <p>El acceso a la información debe limitarse al personal autorizado y protegerse frente a accesos, modificaciones o divulgaciones no autorizados, de acuerdo con las medidas y políticas aplicables de la institución.</p>
                    </section>
                    <section>
                        <h3>Condiciones de uso</h3>
                        <p>Al aceptar, declarás que la información que proporcionás es pertinente para tu consulta y autorizás su uso para atenderla y realizar el seguimiento necesario. No se debe utilizar para finalidades ajenas a la gestión de la consulta, salvo obligación legal o autorización correspondiente.</p>
                        <p>La información debe conservarse solo durante el tiempo necesario para gestionar la consulta y cumplir las obligaciones aplicables. Para solicitar información sobre tus datos, su corrección o ejercer otros derechos, contactá a la institución por sus canales oficiales.</p>
                    </section>
                    <button type="button" class="contact-privacy-close" id="close_privacy_dialog">Cerrar</button>
                </div>
            </dialog>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script src="/js/contact/privacy-modal.js" defer></script>
    <script type="module" src="/js/contact/contact.js"></script>
</body>
</html>
