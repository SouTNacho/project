<?php

    session_start();
    // Falta validar rol

    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../conection.php";

    // filter_var devuelve false si no es un entero puro
    $type_id = filter_var($_GET['type_id'] ?? '', FILTER_VALIDATE_INT);

    if ($type_id === false || $type_id <= 0) {

        http_response_code(400);

        echo json_encode(
            [
                'success' => false,
                'message' => 'El ID del tipo es incorrecto.'
            ],
            JSON_UNESCAPED_UNICODE //para que se escriba bien el tilde
        );

        exit();
    }

    $mysqli = connection_db();

    try {

        $type = findTypeWithId($mysqli, $type_id);

        if (!$type) {

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'No se encontró el tipo.'
                ],
                JSON_UNESCAPED_UNICODE
            );

        } else {

            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Solicitud exitosa.',
                    'item'    => $type
                ],
                JSON_UNESCAPED_UNICODE
            );
        }

    } catch (Throwable $e) {

        error_log('get_type_by_id.php: ' . $e->getMessage());

        http_response_code(500);

        echo json_encode(
            [
                'success' => false,
                'message' => 'Ha ocurrido un error.'
            ],
            JSON_UNESCAPED_UNICODE
        );

    } finally {

        $mysqli->close();
    }