<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../conection.php";

    $id = (int) ($_GET['id'] ?? 0);

    if (validateEmptyData($id)) {

        echo json_encode(
            ['success' => false,
            'message' => 'Error, no se recibieron datos.']
        );
        exit;
    }

    if ($id <= 0) {

        echo json_encode(
            ['success' => false,
            'message' => 'Error, los datos enviados son incorrectos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $element = findElementWithId($mysqli, $id);

        if (!$element) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error, no se encontró el elemento.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $element]
        );
        $mysqli->close();
        exit;

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
    }

?>
