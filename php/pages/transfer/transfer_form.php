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

        <form method="post" id="transfer_form" class="form">
            <div>
                <h2>Registrar traslado</h2>
            </div>
            <div>
                <label for="transfer_start_time">Hora de inicio:</label>
                <input type="datetime-local" id="transfer_start_time" name="transfer_start_time">
                <span id="transfer_start_time_msg"></span>
            </div>
            <div>
                <label for="transfer_route">Ruta:</label>
                <select name="transfer_route" id="transfer_route">
                    <option value="">Seleccione una ruta</option>
                </select>
                <span id="transfer_route_msg"></span>
            </div>
            <div class="datetime-route-confirm">
                <button type="button" id="confirm_route_datetime_btn">
                    <i data-lucide="check"></i> Confirmar ruta y horario
                </button>
                <span id="confirm_route_datetime_btn_msg"></span>
            </div>
            <div>
                <label for="transfer_patient">Paciente:</label>
                <select name="transfer_patient" id="transfer_patient">
                    <option value="">Seleccione un paciente</option>
                </select>
                <span id="transfer_patient_msg"></span>
            </div>

            <div id="transfer_samples_container">
                <label>Muestras:</label>
                <div id="transfer_samples_list"></div>
                <button type="button" id="add_sample_btn">
                    <i data-lucide="plus"></i> Agregar muestra
                </button>
                <span id="transfer_samples_msg"></span>
            </div>

            <div id="transfer_elements_container">
                <label>Elementos a transportar:</label>
                <div id="transfer_elements_list"></div>
                <button type="button" id="add_element_btn">
                    <i data-lucide="plus"></i> Agregar elemento
                </button>
                <span id="transfer_elements_msg"></span>
            </div>

            <div>
                <label for="transfer_driver">Conductor:</label>
                <select name="transfer_driver" id="transfer_driver">
                    <option value="">Seleccione un conductor</option>
                </select>
                <span id="transfer_driver_msg"></span>
            </div>
            <div>
                <label for="transfer_copilot">Copiloto:</label>
                <select name="transfer_copilot" id="transfer_copilot">
                    <option value="">Seleccione un copiloto</option>
                </select>
                <span id="transfer_copilot_msg"></span>
            </div>
            <div>
                <label for="transfer_companion">Acompañante:</label>
                <select name="transfer_companion" id="transfer_companion">
                    <option value="">Seleccione un acompañante</option>
                </select>
                <span id="transfer_companion_msg"></span>
            </div>
            <div>
                <label for="transfer_ambulance">Ambulancia:</label>
                <select name="transfer_ambulance" id="transfer_ambulance">
                    <option value="">Seleccione una ambulancia</option>
                </select>
                <span id="transfer_ambulance_msg"></span>
            </div>

            <div id="map"></div>

            <div>
                <p>Distancia: <span id="transfer_distance">—</span></p>
                <p>Tiempo estimado: <span id="transfer_time">—</span></p>
            </div>
            <div>
                <a href="/php/pages/transfer/manage_transfers.php">
                    <i data-lucide="arrow-left"></i>Volver
                </a>
                <button type="submit" id="transfer_btn"
                    class="form-btn">Registrar traslado</button>
                <span id="transfer_btn_msg" class="form-btn-msg"></span>
            </div>

        </form>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="module" src="/js/transfer/form_transfer.js"></script>
</body>
</html>