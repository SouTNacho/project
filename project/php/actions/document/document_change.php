<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    // Falta despues con la session sacar el id
    $administrative_code = "FA00000001";
    $state_id = (int) ($_POST['state_id']?? 0);
    $document_id = (int) ($_POST['document_id']?? 0);

    if ($state_id <= 0 || $document_id <= 0) {
        
        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $action_id = 0;
    switch ($state_id) {
        case 1:
            $action_id = 1;
            break;
        case 2:
            $action_id = 2;
            break;
        case 3:
            $action_id = 5;
            break;
        default:
            echo json_encode(
                ['success' => false,
                'message' => 'Error, el estado recibido no es válido.']
            );
            exit;
    }

    $mysqli = connection_db();

    try {

        $document = findDocumentWithId($mysqli, $document_id);

        if (!$document) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: Documento no encontrado.']
            );
            $mysqli->close();
            exit;
        }

        if ((int)$document['id_estado_documento'] === 3) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar un registro eliminado.'
            ]);
            $mysqli->close();
            exit;
        }

        if ($state_id === 3) {
            if (!file_exists(__DIR__ . "/../../../" . $document['archivo'])) {
                $mysqli->close();
                echo json_encode([
                    'success' => false,
                    'message' => 'Error, el documento seleccionado no existe.'
                ]);
                exit;
            }

            $mysqli->begin_transaction();

            if (!unlink(__DIR__ . "/../../../" . $document['archivo'])) {

                $mysqli->rollback();
                $mysqli->close();
                
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el archivo, intente nuevamente.'
                ]);
                exit;
            }

            updateDocumentFile($mysqli, 'Archivo Eliminado', $document['id_documento']);
            changeStateDocument($mysqli, $document_id, 3);
            documentAction($mysqli, $action_id, $administrative_code, $document["id_documento"]);

            $mysqli->commit();

        } else {
            
            $mysqli->begin_transaction();

            changeStateDocument($mysqli, $document_id, $state_id);
            documentAction($mysqli, $action_id, $administrative_code, $document["id_documento"]);

            $mysqli->commit();
        }

        $mysqli->close();
        echo json_encode(
            ['success' => true,
            'message' => 'Estado actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado: ' . $e->getMessage()]
        );
        exit;
    }

?>
