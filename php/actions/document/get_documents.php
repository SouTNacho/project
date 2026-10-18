<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/document_model.php";
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
                    $documents = findActiveDocuments($mysqli);
                    break;
                case 2:
                    $documents = findInactiveDocuments($mysqli);
                    break;
                case 3:
                    $documents = findDeletedDocuments($mysqli);
                    break;
                default:
                    $documents = findAllDocuments($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $documents = findActiveDocumentsWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $documents = findInactiveDocumentsWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $documents = findDeletedDocumentsWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $documents = findAllDocumentsWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$documents) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron documentos.', 
                'item' => $documents]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $documents]
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
