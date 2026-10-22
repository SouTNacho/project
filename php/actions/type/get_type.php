<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession(['super_user', 'administrative']);

    require_once __DIR__ . "/../../models/type_model.php";
    require_once __DIR__ . "/../../conection.php";

    $id =  trim($_GET['id'] ?? '');

    if ($id === '') {

        echo json_encode(
            ['success' => false,
            'message' => 'Error, los datos enviados son incorrectos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $type = findTypeWithId($mysqli, $id);

        if (!$type) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error, no se encontró el tipo.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $type]
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
