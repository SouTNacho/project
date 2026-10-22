<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../models/category_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) ($_POST['state_id']?? 0);
    $category_id = (int) ($_POST['category_id']?? 0);

    if ($state_id <= 0 || $category_id <= 0) {
        
        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $type = findCategoryWithId($mysqli, $category_id);

        if (!$type) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: categoria no encontrada.']
            );
            $mysqli->close();
            exit;
        }

        changeStateCategory($mysqli, $category_id, $state_id);
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
        exit;
    }

?>
