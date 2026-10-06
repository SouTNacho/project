<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../models/route_model.php";
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
                    $routes = findActiveRoutes($mysqli);
                    break;
                case 2:
                    $routes = findInactiveRoutes($mysqli);
                    break;
                case 3:
                    $routes = findDeletedRoutes($mysqli);
                    break;
                default:
                    $routes = findAllRoutes($mysqli);
                    break;
            }
        } else {

            $complete_phrase = '%' . $phrase . '%';

            switch($state_id) {

                case 1:
                    $routes = findActiveRoutesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 2:
                    $routes = findInactiveRoutesWithPhrase($mysqli, $complete_phrase);
                    break;
                case 3:
                    $routes = findDeletedRoutesWithPhrase($mysqli, $complete_phrase);
                    break;
                default:
                    $routes = findAllRoutesWithPhrase($mysqli, $complete_phrase);
                    break;
            }
        }

        if (!$routes) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron rutas.', 
                'item' => $routes]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $routes]
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
