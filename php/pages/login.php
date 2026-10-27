<?php

    session_start();
    require_once __DIR__ . '/../functions/session.php';
    redirectLoggedUser();
    $is_logged = false;
?>
<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="../../styles/notifications.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/notifications.js"></script>

    <meta charset="UTF-8">
    <title>Inicio de sesión - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/login_style.css">
</head>

<body>
    <main id="main" class="login-main">
        <section class="login-card">

            <div class="login-info">
                <div class="login-info-content">
                    <img class="login-logo" src="/src/logo-hc-white.svg" alt="Hospital de Clínicas">
                    <span class="login-project">BYP</span>
                    <h1>Hospital de Clínicas</h1>
                    <p>Sistema de gestión y trazabilidad de documentos y traslados.</p>
                </div>
                <div class="login-info-bottom">
                    <span>Build Your Program</span>
                </div>
            </div>

            <div class="login-form-container">

                <?php
                    if (isset($_SESSION["success"])) {
                        echo '<div class="success-message">' . $_SESSION["success"] . '</div>';
                        unset($_SESSION["success"]);
                    }

                    if (isset($_SESSION["errors"])) {
                        echo '<div class="error-message">' . $_SESSION["errors"] . '</div>';
                        unset($_SESSION["errors"]);
                    }
                ?>

            <div class="login-heading">
                <h2>Bienvenido de nuevo</h2>
                <p>Ingresá tus credenciales para acceder al sistema.</p>
                <small>Acceso exclusivo para superusuarios, administrativos y conductores autorizados.</small>
            </div>

            <form action="/php/actions/login.php" method="post" id="login_form">
                
                <div class="input-container">

                    <label for="employee_id">Código de funcionario</label>
                    <div class="login-input">
                        <i data-lucide="id-card"></i>
                        <input type="text" name="employee_id"
                            id="employee_id" placeholder="FA00000001">
                    </div>
                    <span id="employee_id_msg"></span>
                </div>

                <div class="input-container">

                    <label for="employee_password">Contraseña</label>
                    <div class="login-input">
                        <i data-lucide="lock-keyhole"></i>
                        <input type="password" name="employee_password"
                            id="employee_password" placeholder="Ingresá tu contraseña">
                        <button type="button" id="password_toggle" aria-label="Mostrar contraseña">
                            <i data-lucide="eye-off"></i>
                        </button>
                    </div>
                    <span id="employee_password_msg"></span>
                </div>

                <div>
                    <button type="submit" id="login_btn">
                        <i data-lucide="log-in"></i> Ingresar
                    </button>
                    <span id="login_btn_msg"></span>
                </div>
            </form>

            <div class="login-footer">
                <button type="button" id="back_btn">
                    <i data-lucide="house"></i> Volver al inicio
                </button>
            </div>
        </div>

    </section>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/login.js"></script>
    <script> lucide.createIcons() </script>

</body>
</html>