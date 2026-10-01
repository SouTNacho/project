<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/ambulance_model.php";
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
                    $ambulances = findActiveAmbulances($mysqli);
                    break;
                case 2:
                    $ambulances = findInactiveAmbulances($mysqli);
                    break;
                case 3:
                    $ambulances = findDeletedAmbulances($mysqli);
                    break;
                default:
                    $ambulances = findAllAmbulances($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $ambulances = findActiveAmbulancesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $ambulances = findInactiveAmbulancesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $ambulances = findDeletedAmbulancesWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $ambulances = findAllAmbulancesWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$ambulances) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron ambulancias.', 
                'item' => $ambulances]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $ambulances]
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
