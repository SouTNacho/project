<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/ambulance_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) $_POST['state_id'];
    $ambulance_id = (int) $_POST['ambulance_id'];

    if (validateEmptyData($state_id) || validateEmptyData($ambulance_id)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del ambulancia: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $ambulance = findAmbulanceWithId($mysqli, $ambulance_id);

        if (!$ambulance) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado de la Ambulancia: Ambulancia no encontrada.']
            );
            exit;
        }

        changeStateAmbulance($mysqli, $ambulance_id, $state_id);
        $ambulance = findAmbulanceWithId($mysqli, $ambulance_id);

        if ((int) $ambulance['id_estado_ambulancia'] !== $state_id) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado de la Ambulancia: No se pudo actualizar el estado.']
            );
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Estado de la ambulancia actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado de la ambulancia: ' . $e->getMessage()]
        );
    }

?>
