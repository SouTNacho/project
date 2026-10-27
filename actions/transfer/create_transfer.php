<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    validateActionSession('administrative');
    
    require_once __DIR__ . "/../../models/transfer_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        echo json_encode(
            ['success' => false,
            'message' => $message]
        );
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        echo json_encode(
            ['success' => false,
            'message' => $message]
        );
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $driver = trim($_POST['transfer_driver'] ?? '');
    $copilot = trim($_POST['transfer_copilot'] ?? '');
    $start_time = trim($_POST['transfer_start_time'] ?? '');
    $administrative = trim($_SESSION['employee_code'] ?? '');

    $route = (int) ($_POST['transfer_route'] ?? 0);
    $patient = (int) ($_POST['transfer_patient'] ?? 0);
    $duration = (int) ($_POST['transfer_duration'] ?? 0);
    $ambulance = (int) ($_POST['transfer_ambulance'] ?? 0);
    $companion = (int) ($_POST['transfer_companion'] ?? 0);


    if (validateEmptyData($start_time) || validateEmptyData($administrative) ||
    validateEmptyData($driver) || $route <= 0 || $duration <= 0 || $ambulance <= 0) {

        redirectionForError("Faltan datos obligatorios o hay valores no válidos.");
    }

    if (!validateEmployeeCode($administrative)) {
        redirectionForError("El código del administrativo no es válido.");
    }

    if (!validateEmployeeCode($driver)) {
        redirectionForError("El conductor seleccionado no es válido.");
    }

    if (!validateDate($copilot)) {
        if (!validateEmployeeCode($copilot)) {

            redirectionForError("El copiloto seleccionado no es válido.");
        }
    }

        $mysqli = connection_db();

        try {

            $start = new DateTime($start_time);
            $start->setTimezone(new DateTimeZone('America/Montevideo'));

            $start->modify('-15 minutes');
            $duration = $duration + 30;

            $id = crateTrasnfer($mysqli, $start, $duration, $administrative,
                $copilot, $driver, $ambulance, $route, $patient, $companion);

            $mysqli->close();

            echo json_encode(
                ['success' => true,
                'message' => 'Traslado creado.',
                'item' => $id]
            );
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            echo json_encode(
                ['success' => false,
                'message' => 'Ha ocurrido un error al crear el traslado.']
            );
            exit();
        }
    }
?>