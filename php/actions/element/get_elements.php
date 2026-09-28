<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = $_GET['phrase'] ?? 'null';
    $state_id = (int) ($_GET['id_state'] ?? 0);

    if (validateEmptyData($state_id) || validateEmptyData($phrase)) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, datos faltantes.']
            );
        exit;
    }

    if ($state_id < 0 || strlen($phrase) < 1) {

        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        if ($phrase === 'null') {

            switch($state_id) {

                case 1:
                    $elements = findActiveElements($mysqli);
                    break;
                case 2:
                    $elements = findInactiveElements($mysqli);
                    break;
                case 3:
                    $elements = findDeletedElements($mysqli);
                    break;
                default:
                    $elements = findAllElements($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $elements = findActiveElementsWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $elements = findInactiveElementsWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $elements = findDeletedElementsWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $elements = findAllElementsWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$elements) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron elementos.', 
                'item' => $elements]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $elements]
        );
        $mysqli->close();
        exit;

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        echo json_encode(
            ['success' => false,
            'message' => 'Ha ocurrido un error: ' . $e->getMessage()]
        );
    }

?>
