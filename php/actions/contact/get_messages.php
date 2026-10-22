<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-Type: application/json");
    validateActionSession('super_user');

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/contact_model.php";
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
                    $messages = findNonResponseMessages($mysqli);
                    break;
                case 2:
                    $messages = findResponseMessages($mysqli);
                    break;
                default:
                    $messages = findAllMessages($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $messages = findNonResponseMessagesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $messages = findResponseMessagesWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $messages = findAllMessagesWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$messages) {
            echo json_encode(
                ['success' => false,
                'message' => 'No se encontraron mensajes.']
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $messages]
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
