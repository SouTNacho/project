<?php

    session_start();
    // Falta validar rol

    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../conection.php";

    // filter_var devuelve false si no es un entero puro
    $subtype_id = filter_var($_GET['subtype_id'] ?? '', FILTER_VALIDATE_INT);

    if ($subtype_id === false || $subtype_id <= 0) {

        http_response_code(400);

        echo json_encode(
            [
                'success' => false,
                'message' => 'El ID del subtipo es incorrecto.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit();
    }

    $mysqli = connection_db();

    try {

        $subtype = findSubtypeWithId($mysqli, $subtype_id);

        if (!$subtype) {

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'No se encontró el subtipo.'
                ],
                JSON_UNESCAPED_UNICODE
            );

        } else {

            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Solicitud exitosa.',
                    'item'    => $subtype
                ],
                JSON_UNESCAPED_UNICODE
            );
        }

    } catch (Throwable $e) {

        error_log('get_element_subtype_by_id.php: ' . $e->getMessage());

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