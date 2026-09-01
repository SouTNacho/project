<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/document_model.php";
    require_once __DIR__ . "/../../conection.php";

    // Falta despues con la session sacar el id
    $id_administrativo = "FA00000001";
    $document_token = $_GET['token'];
    $action = "";

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

        if (!$document) {
            
            $mysqli->close();
            echo json_encode([
                'succes' => false,
                'message' => 'Error, el documento seleccionado no existe.'
            ]);
            exit;
        }

        $mysqli->begin_transaction();

        if ($document['id_estado_documento'] === 1) {

            documentChangeState($mysqli, 2, $document["id_documento"]);
            documentAction($mysqli, 3, $id_administrativo, $document["id_documento"]);

            $mysqli->commit();
            $mysqli->close();

            echo json_encode([
                'succes' => true,
                'message' => 'El estado documento ha sido desactivado exitosamente.'
            ]);

            exit;
        }

        documentChangeState($mysqli, 1, $document["id_documento"]);
        documentAction($mysqli, 2, $id_administrativo, $document["id_documento"]);

        $mysqli->commit();
        $mysqli->close();

        echo json_encode([
            'succes' => true,
            'message' => 'El estado documento ha sido activado exitosamente.'
        ]);

        exit;

    } catch (mysqli_sql_exception $e) {

        $mysqli->rollback();
        $mysqli->close();

        echo json_encode([
            'succes' => false,
            'message' => 'Ha ocurrido un error al modificar el documento.'
        ]);
        exit;
    }

?>