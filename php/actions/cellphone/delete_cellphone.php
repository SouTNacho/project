<?php

    session_start();
    // Falta validar rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/cellphone_model.php";
    require_once __DIR__ . "/../../conection.php";

    $id = (int) trim($_GET['id'] ?? 0);

    if ($id <= 0) {

        echo json_encode(
            ['success' => false,
            'message' => 'Error, los datos enviados son incorrectos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $cellphone = findCellphoneWithId($mysqli, $id);

        if (!$cellphone) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error, no se encontró el teléfono.']
            );
            $mysqli->close();
            exit;
        }

        deleteCellphone($mysqli, $id);

        echo json_encode(
            ['success' => true,
            'message' => 'Eliminación exitosa.']
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
