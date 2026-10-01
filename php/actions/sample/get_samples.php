<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/sample_model.php";
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
                    $samples = findActiveSamples($mysqli);
                    break;
                case 2:
                    $samples = findInactiveSamples($mysqli);
                    break;
                case 3:
                    $samples = findDeletedSamples($mysqli);
                    break;
                default:
                    $samples = findAllSamples($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $samples = findActiveSamplesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $samples = findInactiveSamplesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $samples = findDeletedSamplesWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $samples = findAllSamplesWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$samples) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron muestras.', 
                'item' => $samples]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $samples]
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
