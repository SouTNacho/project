<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/patient_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) ($_POST['state_id']?? 0);
    $patient_id = (int) ($_POST['patient_id']?? 0);

    if ($state_id <= 0 || $patient_id <= 0) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $patient = findPatientWithId($mysqli, $patient_id);

        if (!$patient) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: paciente no encontrado.']
            );
            $mysqli->close();
            exit;
        }

        if ((int) $patient['id_estado_paciente'] === 3 || (int) $patient['id_estado_paciente'] === 4) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar un registro eliminado o fallecido.'
            ]);
            $mysqli->close();
            exit;
        }

        changeStatePatient($mysqli, $patient_id, $state_id);
        $mysqli->close();

        echo json_encode(
            ['success' => true,
            'message' => 'Estado actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado: ' . $e->getMessage()]
        );
    }

?>
