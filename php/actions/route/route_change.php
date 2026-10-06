<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/route_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) ($_POST['state_id']?? 0);
    $route_id = (int) ($_POST['route_id']?? 0);

    if ($state_id <= 0 || $route_id <= 0) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $route = findRouteWithId($mysqli, $route_id);

        if (!$route) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: ruta no encontrada.']
            );
            $mysqli->close();
            exit;
        }

        if ((int)$route['id_estado_ruta'] === 3) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar un registro eliminado.'
            ]);
            $mysqli->close();
            exit;
        }

        changeStateRoute($mysqli, $route_id, $state_id);
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
