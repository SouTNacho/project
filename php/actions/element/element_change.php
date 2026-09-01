<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) $_POST['state_id'];
    $element_id = (int) $_POST['element_id'];

    if (validateEmptyData($state_id) || validateEmptyData($element_id)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del elemento: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $element = findElementWithId($mysqli, $element_id);

        if (!$element) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del Elemento: Elemento no encontrado.']
            );
            exit;
        }

        changeStateElement($mysqli, $element_id, $state_id);
        $element = findElementWithId($mysqli, $element_id);

        if ((int) $element['id_estado_elemento'] !== $state_id) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del Elemento: No se pudo actualizar el estado.']
            );
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Estado del elemento actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del elemento: ' . $e->getMessage()]
        );
    }

?>
