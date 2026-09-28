<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) $_POST['state_id'];
    $sample_id = (int) $_POST['sample_id'];

    if (validateEmptyData($state_id) || validateEmptyData($sample_id)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado de la muestra: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $sample = findSampleWithId($mysqli, $sample_id);

        if (!$sample) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado de la muestra: Muestra no encontrado.']
            );
            exit;
        }

        changeStateSample($mysqli, $sample_id, $state_id);
        $sample = findSampleWithId($mysqli, $sample_id);

        if ((int) $sample['id_estado_muestra'] !== $state_id) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado de la muestra: No se pudo actualizar el estado.']
            );
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Estado de la muestra actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado de la muestra: ' . $e->getMessage()]
        );
    }

?>
