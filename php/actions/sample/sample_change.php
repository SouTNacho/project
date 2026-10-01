<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) ($_POST['state_id']?? 0);
    $sample_id = (int) ($_POST['sample_id']?? 0);


    if ($state_id <= 0 || $sample_id <= 0) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $sample = findSampleWithId($mysqli, $sample_id);

        if (!$sample) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: muestra no encontrada.']
            );
            $mysqli->close();
            exit;
        }

        if ((int)$sample['id_estado_muestra'] === 3 || (int)$sample['id_estado_muestra'] === 4) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar un registro eliminado o descartado.'
            ]);
            $mysqli->close();
            exit;
        }

        changeStateSample($mysqli, $sample_id, $state_id);
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
