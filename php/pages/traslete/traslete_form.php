<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    $is_logged = validateSession('administrative');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Traslado - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>

<body>

    <?php
        require_once __DIR__ . '/../header.php';
        require_once __DIR__ . '/../administrative_pages/administrative_navbar.php';
    ?>

    <main id="main" class="form-main">

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

        <form method="post" id="traslate_form" class="form">
            <div>
                <h2>Registrar traslado</h2>
            </div>
            <div>
                <label for="traslate_start_time">Hora de inicio:</label>
                <input type="datetime-local" id="traslate_start_time" name="traslate_start_time">
                <span id="traslate_start_time_msg"></span>
            </div>
            <div>
                <label for="traslate_route">Ruta:</label>
                <select name="traslate_route" id="traslate_route">
                    <option value="">Seleccione una ruta</option>
                </select>
                <span id="traslate_route_msg"></span>
            </div>
            <div class="datetime-route-confirm">
                <button type="button" id="confirm_route_datetime_btn">
                    <i data-lucide="check"></i> Confirmar ruta y horario
                </button>
                <span id="confirm_route_datetime_btn_msg"></span>
            </div>
            <div>
                <label for="traslate_patient">Paciente:</label>
                <select name="traslate_patient" id="traslate_patient">
                    <option value="">Seleccione un paciente</option>
                </select>
                <span id="traslate_patient_msg"></span>
            </div>

            <div id="traslate_samples_container">
                <label>Muestras:</label>
                <div id="traslate_samples_list"></div>
                <button type="button" id="add_sample_btn">
                    <i data-lucide="plus"></i> Agregar muestra
                </button>
                <span id="traslate_samples_msg"></span>
            </div>

            <div id="traslate_elements_container">
                <label>Elementos a transportar:</label>
                <div id="traslate_elements_list"></div>
                <button type="button" id="add_element_btn">
                    <i data-lucide="plus"></i> Agregar elemento
                </button>
                <span id="traslate_elements_msg"></span>
            </div>

            <div>
                <label for="traslate_driver">Conductor:</label>
                <select name="traslate_driver" id="traslate_driver">
                    <option value="">Seleccione un conductor</option>
                </select>
                <span id="traslate_driver_msg"></span>
            </div>
            <div>
                <label for="traslate_copilot">Copiloto:</label>
                <select name="traslate_copilot" id="traslate_copilot">
                    <option value="">Seleccione un copiloto</option>
                </select>
                <span id="traslate_copilot_msg"></span>
            </div>
            <div>
                <label for="traslate_companion">Acompañante:</label>
                <select name="traslate_companion" id="traslate_companion">
                    <option value="">Seleccione un acompañante</option>
                </select>
                <span id="traslate_companion_msg"></span>
            </div>
            <div>
                <label for="traslate_ambulance">Ambulancia:</label>
                <select name="traslate_ambulance" id="traslate_ambulance">
                    <option value="">Seleccione una ambulancia</option>
                </select>
                <span id="traslate_ambulance_msg"></span>
            </div>

            <div id="map"></div>

            <div>
                <p>Distancia: <span id="traslate_distance">—</span></p>
                <p>Tiempo estimado: <span id="traslate_time">—</span></p>
            </div>
            <div>
                <a href="/php/pages/traslete/manage_trasletes.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="traslate_btn"
                    class="form-btn">Registrar traslado</button>
                <span id="traslate_btn_msg" class="form-btn-msg"></span>
            </div>

        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/traslete/form_traslete.js"></script>
</body>
</html>