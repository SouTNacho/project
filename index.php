<?php

    session_start();

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
</head>
<body id="top">

    <?php

        if (!isset($_SESSION['type_user'])) {

            require_once __DIR__ . '/php/pages/header_nav_user.php';
        }

    ?>

    <main class="main">

        <section class="main_section">
            <div>
                <h2>Dr. Manuel Quintela</h2>
                <h3>Dr. Manuel Quintela</h3>
                <p>Bienvenido...</p>
            </div>
        </section>

        <a href="#top" class="main_up_button" aria-label="Volver al inicio">
            <span class="material-symbols-outlined">stat_1</span>
        </a>

    </main>

    <footer id="footer">
    <div class="actions-container">
        <div class="list-container">
            <ul class="footer-list">
                <li class="list-items">
                    <a href="#">Políticas</a>
                </li>
                <li class="list-items">
                    <a href="#">Derechos</a>
                </li>
                <li class="list-items">
                    <a href="#">Contacto</a>
                </li>
                <li class="list-items">
                    <a href="#">Políticas</a>
                </li>
                <li class="list-items">
                    <a href="#">Derechos</a>
                </li>
                <li class="list-items">
                    <a href="#">Contacto</a>
                </li>
                <li class="list-items">
                    <a href="#">Políticas</a>
                </li>
                <li class="list-items">
                    <a href="#">Derechos</a>
                </li>
            </ul>
        </div>
        <div class="social-media-container">
            <ul class="footer-list">
                <li class="list-items">
                    <a target="_blank" href="https://www.facebook.com/pages/Hospital-de-Cl%C3%ADnicas-Dr-Manuel-Quintela/1451600128402196">
                        <img src="/src/face_icon.png" alt="">
                    </a>
                </li>
                <li class="list-items">
                    <a target="_blank" href="https://www.instagram.com/popular/hospital-de-cl%C3%ADnicas-dr-manuel-quintela/">
                        <img src="/src/insta_icon.png" alt="">
                    </a>
                </li>
                <li class="list-items">
                    <a target="_blank" href="https://x.com/hcmquintela">
                        <img src="/src/twitt_icon.png" alt="">
                    </a>
                </li>
                <li class="list-items">
                    <a target="_blank" href="https://uy.linkedin.com/company/udelar-facultad-de-medicina-hospital-de-cl-nicas">
                        <img src="/src/link_icon.png" alt="">
                    </a>
                </li>
            </ul>
        </div>
        <div class="form-container">
            <form method="post" id="contact_form">
                <div>
                    <div>
                        <input type="text" name="contact_name"
                        id="contact_name" placeholder="Jhon Doe">
                        <span id="contact_name_msg"></span>
                    </div>
                    <div>
                        <input type="email" name="contanct_email"
                        id="contanct_email" placeholder="exaple@gmail.com">
                        <span id="contanct_email_msg"></span>
                    </div>
                </div>
                <div>
                    <textarea name="contanct_message" id="contanct_message" 
                    placeholder="Escriba su mensaje"></textarea>
                    <span id="contanct_message_msg"></span>
                </div>
                <div>
                    <input type="submit" value="Enviar" id="element_btn">
                    <span id="element_btn_msg"></span>
                </div>
            </form>
        </div>
    </div>
    <div class="information-container">
        <p class="information-content">
            &copy; 2026 Hospital de Clínicas. Todos los derechos reservados. Desarrollado por BYP.
        </p>
    </div>
</footer>

</body>
</html>