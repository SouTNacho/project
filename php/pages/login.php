<?php

session_start();

if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {

    switch ($_SESSION["user_type"] ?? "") {
        case "admin":
        case "copilot":
        case "driver":
            header("Location: /php/pages/administrative_pages/administrative_panel.php");
            exit();

        case "superuser":
            header("Location: /php/pages/super_user_pages/super_user_panel.php");
            exit();
    }
}

$error_message = $_SESSION["errors"] ?? null;
$success_message = $_SESSION["success"] ?? null;

unset($_SESSION["errors"], $_SESSION["success"]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BYP - Iniciar Sesión</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/login_style.css">
</head>

<body>

<main class="login-page">

    <section class="login-card">

        <!-- panel izquierdo -->
        <div class="login-info">
            <div class="login-info-content">
                <img class="login-logo" src="/src/logo-hc-white.svg" alt="Hospital de Clínicas">

                <span class="login-project">BYP</span>

                <h1>Hospital de Clínicas</h1>
                <p>
                    Sistema de gestión y trazabilidad de documentos y traslados.
                </p>
            </div>

            <div class="login-info-bottom">
                <span>Build Your Program</span>
            </div>
        </div>

        <!-- panel derecho / formulario -->
        <div class="login-form-container">

            <div class="login-heading">
                <h2>Bienvenido de nuevo</h2>
                <p>Ingresá tus credenciales para acceder al sistema.</p>
            </div>

            <?php if ($error_message !== null): ?>
                <div class="login-message error" role="alert">
                    <span class="material-symbols-outlined">error</span>
                    <span><?= htmlspecialchars($error_message, ENT_QUOTES, "UTF-8") ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success_message !== null): ?>
                <div class="login-message success" role="status">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span><?= htmlspecialchars($success_message, ENT_QUOTES, "UTF-8") ?></span>
                </div>
            <?php endif; ?>

            <form action="/php/actions/login.php" method="post" id="employee_login_form" novalidate>

                <div class="login-field">
                    <label for="employee_id">Código de funcionario</label>

                    <div class="login-input">
                        <span class="material-symbols-outlined">badge</span>

                        <input
                            type="text"
                            name="employee_id"
                            id="employee_id"
                            placeholder="FA00000001"
                            maxlength="10"
                            autocomplete="username"
                            spellcheck="false"
                        >
                    </div>

                    <span class="field-message" id="employee_id_msg"></span>
                </div>

                <div class="login-field">
                    <label for="employee_password">Contraseña</label>

                    <div class="login-input">
                        <span class="material-symbols-outlined">lock</span>

                        <input
                            type="password"
                            name="employee_password"
                            id="employee_password"
                            placeholder="Ingresá tu contraseña"
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="password_toggle"
                            aria-label="Mostrar contraseña"
                        >
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>

                    <span class="field-message" id="employee_password_msg"></span>
                </div>

                <button type="submit" class="login-submit" id="employee_login_btn">
                    <span>Ingresar</span>
                    <span class="material-symbols-outlined">login</span>
                </button>

                <span class="field-message submit-message" id="employee_login_btn_msg"></span>
            </form>

            <div class="login-footer">
                <a href="/">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Volver al inicio
                </a>
            </div>

        </div>

    </section>

</main>

<script type="module" src="/js/login.js"></script>

</body>
</html>
