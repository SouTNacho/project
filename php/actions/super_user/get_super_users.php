<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/super_user_model.php";
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
                    $super_users = findActiveSuperUsers($mysqli);
                    break;
                case 2:
                    $super_users = findInactiveSuperUsers($mysqli);
                    break;
                case 3:
                    $super_users = findDeletedSuperUsers($mysqli);
                    break;
                default:
                    $super_users = findAllSuperUsers($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $super_users = findActiveSuperUsersWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $super_users = findInactiveSuperUsersWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $super_users = findDeletedSuperUsersWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $super_users = findAllSuperUsersWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$super_users) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron Super Usuarios.', 
                'item' => $super_users]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $super_users]
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
