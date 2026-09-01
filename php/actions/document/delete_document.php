<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    // Falta despues con la session sacar el id
    $id_administrativo = "FA00000001";
    $document_token = $_GET['token'];

    if (!isset($document_token)) {
        
        echo json_encode([
            'succes' => false,
            'message' => 'Error, el documento seleccionado no es valido.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        $document = findDocumentWithToken($mysqli, $document_token);

        if (!$document || !file_exists(__DIR__ . "/../../../" . $document['archivo'])) {
            
            $mysqli->close();
            echo json_encode([
                'succes' => false,
                'message' => 'Error, el documento seleccionado no existe.'
            ]);
            exit;
        }

        if (!unlink(__DIR__ . "/../../../" . $document['archivo'])) {
            
            $mysqli->close();
            echo json_encode([
                'succes' => false,
                'message' => 'Error al eliminar el documento intente nuevamente.'
            ]);
            exit;
        }

        $mysqli->begin_transaction();

        deleteDocument($mysqli, 'Archivo Eliminado', $document['id_documento']);
        documentChangeState($mysqli, 3, $document['id_documento']);
        documentAction($mysqli, 5, $id_administrativo, $document["id_documento"]);

        $mysqli->commit();
        $mysqli->close();

        echo json_encode([
            'succes' => true,
            'message' => 'El documento ha sido eliminado exitosamente.'
        ]);

    } catch (mysqli_sql_exception $e) {

        $mysqli->rollback();
        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'Ha ocurrido un error al eliminar el documento.'
        ]);
        exit;
    }

?>