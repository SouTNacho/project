<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/ubication_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = $_GET['phrase'] ?? 'null';
    $state_id = (int) ($_GET['id_state'] ?? 0);

    if ($state_id < 0 || validateEmptyData($phrase)) {

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
                    $ubications = findActiveUbications($mysqli);
                    break;
                case 2:
                    $ubications = findInactiveUbications($mysqli);
                    break;
                case 3:
                    $ubications = findDeletedUbications($mysqli);
                    break;
                default:
                    $ubications = findAllUbications($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $ubications = findActiveUbicationsWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $ubications = findInactiveUbicationsWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $ubications = findDeletedUbicationsWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $ubications = findAllUbicationsWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$ubications) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron ubicaciones.', 
                'item' => $ubications]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $ubications]
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
