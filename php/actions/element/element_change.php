<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) ($_POST['state_id']?? 0);
    $element_id = (int) ($_POST['element_id']?? 0);

    if (validateEmptyData($state_id) || validateEmptyData($element_id)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $element = findElementWithId($mysqli, $element_id);

        if (!$element) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: Elemento no encontrado.']
            );
            $mysqli->close();
            exit;
        }

        if ((int)$element['id_estado_elemento'] === 3) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar un registro eliminado.'
            ]);
            $mysqli->close();
            exit;
        }

        changeStateElement($mysqli, $element_id, $state_id);
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
