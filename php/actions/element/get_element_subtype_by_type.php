<?php

    session_start();
    // Falta implementar el rol

    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../conection.php";

    // filter_var devuelve false si no es un entero puro; solo existen los tipos 1 y 2
    $type = filter_var($_GET['type'] ?? '', FILTER_VALIDATE_INT);

    if (!in_array($type, [1, 2], true)) {

        http_response_code(400);

        echo json_encode(
            [
                'success' => false,
                'message' => 'Tipo de elemento no válido.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit();
    }

    $mysqli = connection_db();

    try {

        // Se reutiliza findSubtypes: su filtro 'bio' / 'nonbio' ya busca por tipo.
        // Una lista vacía NO es un error (puede que no queden subtipos de ese tipo).
        $subtypes = findSubtypes($mysqli, '', $type === 1 ? 'bio' : 'nonbio');

        echo json_encode(
            [
                'success' => true,
                'message' => 'Solicitud exitosa.',
                'item'    => $subtypes
            ],
            JSON_UNESCAPED_UNICODE
        );

    } catch (Throwable $e) {

        error_log("get_element_subtype_by_type.php: " . $e->getMessage());

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