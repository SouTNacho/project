<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../conection.php";

    $mysqli = connection_db();

    try {

        $states = findAllEmployeesStates($mysqli);

        if (!$states) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al solicitar los estados.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $states]
        );
        $mysqli->close();
        exit;

    } catch (mysqli_sql_exception $e) {
        
        // DESPUES QUITAR EL MENSAJE
        $mysqli->close();
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
        exit;
    }

?>
