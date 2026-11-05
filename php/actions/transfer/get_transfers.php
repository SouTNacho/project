<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession(['administrative', 'super_user']);

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/transfer_model.php";
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

                case 2:
                    $transfers = findActiveTransfers($mysqli);
                    break;
                case 1:
                    $transfers = findPendingTransfers($mysqli);
                    break;
                case 3:
                    $transfers = findStoppedTransfers($mysqli);
                    break;
                case 4:
                    $transfers = findFinishTransfers($mysqli);
                    break;
                default:
                    $transfers = findAllTransfers($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 2:
                    $transfers = findActiveTransfersWithPhrase($mysqli, $complete_phrase);
                    break;
                case 1:
                    $transfers = findPendingTransfersWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $transfers = findStoppedTransfersWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $transfers = findFinishTransfersWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $transfers = findAllTransfersWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$transfers) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron traslados.', 
                'item' => $transfers]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $transfers]
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
