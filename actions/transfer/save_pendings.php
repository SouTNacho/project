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

        $start_time = $_POST['transfer_start_time'] ?? '';
        $driver = trim($_POST['transfer_driver'] ?? '');
        $copilot = trim($_POST['transfer_copilot'] ?? '');

        $id = (int) ($_POST['transfer_id']);
        $patient = (int) ($_POST['transfer_patient'] ?? 0);
        $duration = (int) ($_POST['transfer_duration'] ?? 0);
        $ambulance = (int) ($_POST['transfer_ambulance'] ?? 0);
        $companion = (int) ($_POST['transfer_companion'] ?? 0);

        if ($duration <= 0 || $ambulance <= 0 || $id <= 0 ||
            validateEmptyData($start_time) || validateEmptyData($driver)) {

            redirectionForError("Los datos recibidos no son válidos.");
        }

        if (!validateEmployeeCode($driver)) {
            redirectionForError("El conductor seleccionado no es válido.");
        }

        if (!validateEmptyData($copilot)) {
            if (!validateEmployeeCode($copilot)) {

                redirectionForError("El copiloto seleccionado no es válido.");
            }
        }

        $mysqli = connection_db();

        try {

            $start = new DateTime($start_time);
            $start->setTimezone(new DateTimeZone('America/Montevideo'));

            $end = clone $start;
            $end->modify("+$duration minutes");

            $start->modify('-15 minutes');
            $end->modify('+15 minutes');

            saveTrasnfer($mysqli, $start, $end, $id,
                $copilot, $driver, $ambulance, $patient, $companion);

            $mysqli->close();

            echo json_encode(
                ['success' => true,
                'message' => 'La reserva se ha creado con exito.']
            );
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            echo json_encode(
                ['success' => false,
                'message' => 'Ha ocurrido un error al reservar los elementos para el traslado.']
            );
            exit();
        }

    }
    
?>