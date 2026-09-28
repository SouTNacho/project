<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/super_user_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) $_POST['state_id'] ?? 0;
    $super_user_id = (int) $_POST['super_user_id'] ?? 0;

    if (validateEmptyData($state_id) || validateEmptyData($super_user_id)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del Administrador: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $super_user = findSuperUserWithId($mysqli, $super_user_id);

        if (!$super_user) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del Administrador: Administrador no encontrado.']
            );
            exit;
        }

        changeStateSuperUser($mysqli, $super_user_id, $state_id);
        $super_user = findSuperUserWithId($mysqli, $super_user_id);

        if ((int) $super_user['id_estado_super_usuario'] !== $state_id) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del Administrador: No se pudo actualizar el estado.']
            );
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Estado del Administrador actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del Administrador: ' . $e->getMessage()]
        );
    }

?>
