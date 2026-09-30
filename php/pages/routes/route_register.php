<?php
session_start();
// Falta autenticar
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Ruta - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>

<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../administrative_navbar.php';
?>

<main id="main" class="form-main">

    <form action="" method="post" id="register_ruta_form" class="form">

        <div>
            <h2>Registrar Ruta</h2>
        </div>

        <div>
            <label for="nombre_ruta">Nombre:</label>
            <input
                type="text"
                name="nombre_ruta"
                id="nombre_ruta"
                placeholder="Ruta Hospital - Centro"
            >
            <span id="nombre_ruta_msg"></span>
        </div>

        <div>
            <label for="origen">Origen:</label>
            <input
                type="text"
                name="origen"
                id="origen"
                placeholder="Hospital de Clínicas"
            >
            <span id="origen_msg"></span>
        </div>

        <div>
            <label for="destino">Destino:</label>
            <input
                type="text"
                name="destino"
                id="destino"
                placeholder="Hospital Maciel"
            >
            <span id="destino_msg"></span>
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea
                name="descripcion"
                id="descripcion"
                placeholder="Escriba una breve descripción"
            ></textarea>
            <span id="descripcion_msg"></span>
        </div>

        <div>
            <a href="/php/pages/routes/route_panel.php">
                <i data-lucide="arrow-left"></i>
                Volver
            </a>

            <button type="submit" id="boton_submit_ruta" class="form-btn">
                <i data-lucide="square-plus"></i>
                Registrar
            </button>

            <span id="ruta_btn_msg" class="form-btn-msg"></span>
        </div>

    </form>

</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="/js/rutes/ruta.js"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
