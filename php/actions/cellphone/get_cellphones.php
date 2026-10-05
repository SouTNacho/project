<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/cellphone_model.php";
    require_once __DIR__ . "/../../conection.php";

    $phrase = trim($_GET['phrase'] ?? '');
    $mysqli = connection_db();

    try {

        if ($phrase !== '') {

            if (ctype_digit($phrase) && strlen($phrase) === 8) {

                $complete_phrase = '%' . $phrase . '%';
                $cellphones = findEmployeeCellphones($mysqli, $complete_phrase);
            } else {

                $complete_phrase = '%' . $phrase . '%';
                $cellphones = findAllCellphonesWithPhrase($mysqli, $complete_phrase);
            }
        } else {

            $cellphones = findAllCellphones($mysqli);
        }

        if (!$cellphones) {
            echo json_encode(
                ['success' => true,
                'message' => 'No se encontraron teléfonos.', 
                'item' => $cellphones]
            );
            $mysqli->close();
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Solicitud exitosa.',
            'item' => $cellphones]
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
        exit;
    }

?>
