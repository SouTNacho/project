<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('administrative');

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/transfer_model.php";
    require_once __DIR__ . "/../../conection.php";

    $end = null;
    $duration = (int) ($_GET['duration'] ?? 0);
    $time_start = $_GET['time_start'] ?? '';

    if ($duration <= 0 || validateEmptyData($time_start)) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    try {

        $start = new DateTime($time_start);
        $start->setTimezone(new DateTimeZone('America/Montevideo'));

        $end = clone $start;
        $end->modify("+$duration minutes");

        $start->modify('-15 minutes');
        $end->modify('+15 minutes');

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'message' => 'Error, el formato de la fecha no es válido.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        $dateTimeStart = $start->format('Y-m-d H:i:s');
        $dateTimeEnd = $end->format('Y-m-d H:i:s');

        $ambulances = findAmbulancesForTransfer($mysqli, $dateTimeStart, $dateTimeEnd);

        if (!$ambulances) {
            echo json_encode(
                ['success' => false,
                'message' => 'No se encontraron ambulancias.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $ambulances]
        );
        $mysqli->close();
        exit;

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
    }

?>
